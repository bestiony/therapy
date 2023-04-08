<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Requests\Frontend\SignUpRequest;
use App\Mail\UserEnailVerificaion;
use App\Mail\WelcomeUserToPlatformMail;
use App\Models\Country;
use App\Models\Instructor;
use App\Models\Student;
use App\Models\User;
use App\Tools\Repositories\Crud;
use App\Traits\EmailSendTrait;
use App\Traits\General;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class RegistrationController extends Controller
{
    use EmailSendTrait, General;

    protected $model;
    protected $studentModel;

    public function __construct(User $user, Student $student)
    {
        $this->model = new Crud($user);
        $this->studentModel = new Crud($student);
    }


    public function signUp()
    {
        $data['pageTitle'] = 'Sign Up';
        $data['countries'] = Country::all();
        return view('auth.sign-up', $data);
    }

    public function storeSignUp(SignUpRequest $request)
    {
        $user = new User();
        $user->name = $request->first_name . ' '. $request->last_name;
        $user->email = $request->email;
        $user->area_code = $request->area_code;
        $user->mobile_number = $request->mobile_number;
        $user->phone_number = $user->area_code.$request->mobile_number;
        $user->password = Hash::make($request->password);
        $user->remember_token = $request->_token;
        $user->email_verified_at = get_option('registration_email_verification') == 1 ? null : Carbon::now()->format("Y-m-d H:i:s");
        $user->role = 3;
        $user->save();

        $student_data = [
            'user_id' => $user->id,
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'phone_number' => $user->phone_number,
            'status' => get_option('private_mode') ? STATUS_PENDING : STATUS_ACCEPTED
        ];

        $this->studentModel->create($student_data);

        if (get_option('registration_email_verification') == 1){
            try {
                Mail::to($user->email)->send(new UserEnailVerificaion($user));
            } catch (\Exception $exception) {
                toastrMessage('error', 'Something is wrong. Try after few minutes!');
                return redirect()->back();
            }
            $this->showToastrMessage('error', __('Sent verification mail your account. Please check your email.'));
        }
        if(get_option('registration_phone_number_verification') == 1){
            try{
                createPhoneVerification($user->phone_number);
            }catch(\Exception $exception){
                // dd($exception);
                toastrMessage('error', 'Something is wrong with phone verification. Try after few minutes!');
                return redirect()->back();
            }
        }
        $email_data = [
            "email_title" => "Welcome to Our Platform",
            "user_name" => $user->name
        ];
        Mail::to($user)->send(new WelcomeUserToPlatformMail($email_data));
        $this->showToastrMessage('success', __('Your registration is successful.'));

        return redirect(route('login'));
    }
    public function change_phone_number(Request $request){
        $data = $request->validate([
            'phone_number'=>'required',
        ]);
        $response =  createPhoneVerification($data['phone_number']);
        return response(
            $response == true
        );
    }

    public function emailVerification($token)
    {
        if (User::where('remember_token', $token)->count() > 0)
        {
            $user = User::where('remember_token', $token)->first();
            $user->email_verified_at = Carbon::now()->format("Y-m-d H:i:s");
            $user->remember_token = null;
            $user->save();
            if(!$user->student || ($user->student && $user->student->status != STATUS_PENDING)){
                Auth::login($user);
            }

            $this->showToastrMessage('success', __('Congratulations! Successfully verified your email.'));
            return redirect()->route('home');

        } else {
            return redirect(route('login'));
        }

    }



}
