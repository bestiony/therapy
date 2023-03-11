<?php

namespace App\Http\Controllers;

use App\Traits\General;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use File;
use ZipArchive;

class VersionUpdateController extends Controller
{
    use General;

    public function versionUpdate(Request $request)
    {
        $data['title'] = 'Version Update';

        return view('zainiklab.installer.version-update', $data);
    }

    public function processUpdate(Request $request)
    {
        $request->validate([
            'purchase_code' => 'required',
            'email' => 'bail|required|email'
        ],[
            'purchase_code.required' => 'Purchase code field is required',
            'email.required' => 'Customer email field is required',
            'email.email' => 'Customer email field is must a valid email'
        ]);

        $response = Http::acceptJson()->post('https://support.zainikthemes.com/api/745fca97c52e41daa70a99407edf44dd/active', [
            'app' => config('app.app_code'),
            'type' => 1,
            'email' => $request->email,
            'purchase_code' => $request->purchase_code,
            'version' => config('app.build_version'),
            'url' => $request->fullUrl(),
        ]);

        if($response->successful()){
            $data = $response->object();
            if($data->status === 'success'){
                Artisan::call('migrate', [
                    '--force' => true
                ]);

                $data = json_decode($data->data->data);
                Log::info($data);
                foreach($data as $d){
                    if(!Artisan::call($d)){
                        break;
                    }
                }

                $installedLogFile = storage_path('installed');
                if (file_exists($installedLogFile)) {
                    $data = json_decode(file_get_contents($installedLogFile));
                    if(!is_null($data) && isset($data->d)){
                        $data->u = date('ymdhis');
                    }
                    else{
                        $data = [
                            'i' => date('ymdhis'),
                            'u' => date('ymdhis'),
                            'd' => base64_encode(get_domain_name(request()->fullUrl())),
                        ];
                    }
    
                    file_put_contents($installedLogFile, json_encode($data));
                }

            }else{
                return Redirect::back()->withErrors(['purchase_code' => $data->message]);
            }
        }
        else{
            return Redirect::back()->withErrors(['purchase_code' => 'Something went wrong with your purchase key.']);
        }

        return redirect()->route('main.index');
    }

    public function versionFileUpdate(Request $request)
    {
        if (!auth()->user()->can('manage_version_update')) {
            abort('403');
        } // end permission checking

        $data['title'] = ' Version Update';
        $data['subNavVersionUpdateActiveClass'] = 'mm-active';


        return view('admin.version_update.create', $data);
    }
    
    
    public function versionFileUpdateStore(Request $request)
    {
       $request->validate([
            'update_file' => 'bail|required|mimes:zip'
       ]);

        $path = storage_path('app/updates.zip');

        if (file_exists($path)) {
            File::delete($path);
        }

        if($request->update_file->storeAs('/','updates.zip')){
            $response = $this->executeUpdate();
            if($response['success'] == true){
                $this->showToastrMessage('success', $response['message']);
            }
            else{
                $this->showToastrMessage('error', $response['message']);
            }
            return back();
        }
        else{
            $this->showToastrMessage('success', __('Something went wrong'));
            return back();
        }

    }

    public function executeUpdate(){
        $path = storage_path('app/updates.zip');
        $demoPath = storage_path('app/updates');
        
        $response['success'] = false;
        $response['message'] = 'File not exist on storage!';

        if(file_exists($path)){
            $zip = new ZipArchive;

            if(is_dir($demoPath)){
                File::deleteDirectory($demoPath);
            }
            else{
                File::makeDirectory($demoPath, 0777, true, true);
            }

            $res = $zip->open($path);

            if ($res === true) {
                try{
                    $res = $zip->extractTo($demoPath);
                    $versionFile = file_get_contents($demoPath.DIRECTORY_SEPARATOR.'update_note.txt');
                    $codeVersion = json_decode($versionFile)->build_version;
                    $currentVersion = getCustomerCurrentBuildVersion();

                    $apiResponse = Http::acceptJson()->post('https://support.zainikthemes.com/api/745fca97c52e41daa70a99407edf44dd/glv', [
                        'app' => config('app.app_code')
                    ]);
                    
                    if($apiResponse->successful()){
                        $data = $apiResponse->object();
                        $latestVersion = $data->data->bv;
                        if($data->status === 'success'){
                            if($latestVersion == $codeVersion && $codeVersion > $currentVersion){
                                $res = $zip->extractTo(base_path());
                                $response['success'] = true;
                                $response['message'] = 'Successfully done';
                                Log::info('Version '.$codeVersion.' update done');
                            }
                            else{
                                $response['message'] = 'Your code is not up to date';
                            }
                        }
                        else{
                            $response['message'] = $data->message;
                        }
                    }
                    else{
                        $data = $apiResponse->object();
                        $response['message'] = $data['message'];
                    }

                    File::deleteDirectory($demoPath);
                }
                catch(\Exception $e){
                    Log::info($e->getMessage());
                    $response['message'] = $e->getMessage();
                }
            }

            $zip->close();
        }
        
        return $response;
    }

}
