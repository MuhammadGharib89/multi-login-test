<?php

/*
Route::get('/', function () {
    return view('welcome');
});*/

//Auth::routes(['verify' => true]);
Route::get('/email/verify/{email}/{id}', 'Auth\Officers\VerificationController@show')->name('verification.verify');
Route::get('/email/resend', 'Auth\Officers\VerificationController@show')->name('verification.resend');


Route::get('/', 'HomeController@index')->name('home');

Route::prefix('officer')->group(function(){

    Route::get('/dashboard', 'OfficerController@index')->name('officer.dashboard')->middleware('verified');
    Route::get('/register', 'Auth\Officers\RegisterController@showRegistrationForm')->name('officer.register');
    Route::post('/register', 'Auth\Officers\RegisterController@register')->name('officer.register.submit');
    
    Route::get('/email/verify', 'Auth\Officers\VerificationController@show')->name('officer.verification.notice');
    
    Route::get('/', 'Auth\Officers\LoginController@showLoginForm')->name('officer');
    Route::get('/login', 'Auth\Officers\LoginController@showLoginForm')->name('officer.login');
    Route::post('/login', 'Auth\Officers\LoginController@login')->name('officer.login.submit');
    Route::post('/logout', 'Auth\Officers\LoginController@logout')->name('officer.logout.submit');

});    

/*----Musician Routes----*/
Route::prefix('musician')->group(function(){

    Route::get('/', 'MusicianController@index')->name('musician.dashboard')->middleware('verified');
    
    Route::get('register', 'Auth\RegisterController@showRegistrationForm')->name('musician.register');
    Route::post('register', 'Auth\RegisterController@register')->name('musician.register.submit');
    
    Route::get('email/verify', 'Auth\VerificationController@show')->name('musician.verification.notice');
    
    Route::get('login', 'Auth\LoginController@showLoginForm')->name('musician.login');
    Route::post('login', 'Auth\LoginController@login')->name('musician.login.submit');
    Route::post('logout', 'Auth\LoginController@logout')->name('musician.logout.submit');
});

/*----Member Routes----*/
Route::prefix('member')->group(function(){

    Route::get('/', 'MemberController@index')->name('member.dashboard')->middleware('verified');
    
    Route::get('/member/register', 'Auth\Members\RegisterController@showRegistrationForm')->name('member.register');
    Route::post('/member/register', 'Auth\Members\RegisterController@register')->name('member.register.submit');
    
    Route::get('/member/email/verify', 'Auth\Members\VerificationController@show')->name('member.verification.notice');
    Route::get('/member/email/verify/{id}', 'Auth\Members\VerificationController@verify')->name('member.verification.verify');
    Route::get('/member/email/resend', 'Auth\Members\VerificationController@verify')->name('member.verification.resend');
    
    Route::get('/member/login', 'Auth\Members\LoginController@showLoginForm')->name('member.login');
    Route::post('/member/login', 'Auth\Members\LoginController@login')->name('member.login.submit');
    Route::post('/member/logout', 'Auth\Members\LoginController@logout')->name('member.logout.submit');
});


