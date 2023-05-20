<?php


use App\Http\Controllers\Parent\ConsultationController;
use App\Http\Controllers\Parent\CourseController;
use App\Http\Controllers\Parent\DashboardController;
use App\Http\Controllers\Parent\ProfileController;
use App\Http\Controllers\Parent\FollowController;
use App\Http\Controllers\MessagesController;
use Illuminate\Support\Facades\Route;

Route::get('dashboard', [DashboardController::class, 'dashboard'])->name('parent.dashboard');

Route::get('profile', [ProfileController::class, 'profile'])->name('parent.profile');
Route::post('save-profile/{uuid}', [ProfileController::class, 'saveProfile'])->name('save.profile')->middleware('isDemo');
Route::get('address', [ProfileController::class, 'address'])->name('parent.address');
Route::post('address-update/{uuid}', [ProfileController::class, 'address_update'])->name('parent.address.update');
Route::get('get-state-by-country/{country_id}', [ProfileController::class, 'getStateByCountry']);
Route::get('get-city-by-state/{state_id}', [ProfileController::class, 'getCityByState']);

Route::prefix('course')->group(function () {
    Route::get('/organization', [CourseController::class, 'organizationCourses'])->name('parent.course.organization');

});
Route::name('parent.')->group(function () {
    //Start:: messages
    Route::get('my-messages',[MessagesController::class,'parent_index'])->name('messages');
    Route::post('my-messages/send',[MessagesController::class,'therapsit_sends_message'])->name('send_message');
    Route::post('my-messages/stop',[MessagesController::class,'therapsit_stop_conversation'])->name('stop-conversation');
    Route::post('my-messages/resume',[MessagesController::class,'therapsit_resume_conversation'])->name('resume-conversation');

    //End:: messages
    //Start:: Consultation
    Route::group(['prefix' => 'consultation', 'as' => 'consultation.'], function () {
        Route::post('parent-availability-store-update', [ConsultationController::class, 'parentAvailabilityStoreUpdate'])->name('parentAvailabilityStoreUpdate')->middleware('isDemo');
    });

    Route::get('followers', [FollowController::class,'followers'])->name('followers');
    Route::get('followings', [FollowController::class,'followings'])->name('followings');
});
