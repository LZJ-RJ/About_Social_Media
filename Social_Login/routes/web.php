<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', 'HomeController@index')->name('index');

/**
 *社群登入 | Social Logging
 */
Route::group(['prefix' => 'user'], function(){

    //使用者驗證 | Verify User
    Route::group(['prefix' => 'auth'], function(){
        //Facebook登入 | Facebook Logging
        Route::get('/facebook-sign-in/{role?}', 'Auth\UserAuthController@facebookSignInProcess');
        //Facebook登入重新導向授權資料處理 | Do something after facebook logging.
        Route::get('/facebook-sign-in-callback/{role?}', 'Auth\UserAuthController@facebookSignInCallbackProcess');

        //Google登入 | Google Logging
        Route::get('/google-sign-in/{role?}', 'Auth\UserAuthController@googleSignInProcess');
        //Google登入重新導向授權資料處理 | Do something after google logging.
        Route::get('/google-sign-in-callback/{role?}', 'Auth\UserAuthController@googleSignInCallbackProcess');
    });

});


/**
 * 會員中心 | Member Center
 */
Route::Group(['prefix'=>'my-account','as'=>'my-account.'],function () {

    //社群登入 | Social Logging
    Route::get('student-information', 'UserController@studentInformation')->name('studentInformation');//學生 | Student
    Route::get('teacher-information', 'UserController@teacherInformation')->name('teacherInformation');//老師 | Teacher

});