<?php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Request;
use Hash;
use Socialite;
use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Spatie\Permission\Models\Role;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
// TODO : Include above things to this project.
// TODO : Apply these accounts of API to fill the content of file ,env.
class UserAuthController extends Controller
{

    //Facebook 登入 | Facebook Logging
    public function facebookSignInProcess(Request $request, $role=null)
    {
        $redirect_url = env('FB_CALLBACK_URL').'/'.$role;

        Session::forget('social.login.return.url');
        Session::put('social.login.return.url', redirect()->back()->getTargetUrl());

        Session::forget('register.return.url');
        Session::put('register.return.url', redirect()->back()->getTargetUrl());

        return Socialite::driver('facebook')
            ->redirectUrl($redirect_url)
            ->redirect();
    }

    //Facebook登入重新導向授權資料處理 | Do something after facebook logging.
    public function facebookSignInCallbackProcess(Request $request, $role=null)
    {

        if(isset($_GET['error_code']) && $_GET['error_code'] == 200){
            return redirect(url(Session::pull('social.login.return.url', route('index'))));
        }

        $current_user = Auth::user();

        $redirect_url = env('FB_CALLBACK_URL').'/'.$role;
        $is_create = false;
        $current_user_exits = false;

        $FacebookUser = Socialite::driver('facebook')
            ->fields([
                'name',
                'email',
            ])
            ->redirectUrl($redirect_url)->user();

        $facebook_email = $FacebookUser->email;
        if(is_null($facebook_email))
        {
            $return_url = Session::pull('social.login.return.url', 'no session');
            return redirect($return_url, ['error' => trans('AboutSocialMedia.messages.unverifiedUser', ['title' => 'Email'])]);
        }
        //取得 Facebook 資料 | Get facebook information.
        $facebook_id = $FacebookUser->id;
        $facebook_name = $FacebookUser->name;

        $data = ['fb_id' => $facebook_id, 'fb_email' => $facebook_email];

        if($role == null){
            foreach ($current_user->getRoleNames() as $current_role){
                if($current_role == 'teacher'){
                    $role = $current_role;
                    break;
                }elseif($current_role  == 'student'){
                    $role = $current_role;
                    break;
                }
            }
        }

        if(!$current_user){
            //目前沒有登入的使用者 | Now there is not a authed-user.
            $current_user = User::where('fb_id', $facebook_id)->orWhere('email', $facebook_email)->first();
            if($current_user){
                //如果原本就有使用者，那就幫他/她登入並更新facebook_id | If now there is a authed-user, let the user login and update facebook_id.
                User::where('id' ,$current_user->id)
                    ->update($data);
            }else{
                if($role == 'student'){
                    $status = 'activated';
                }else{
                    $status = 'unverified';
                }
                $data += [
                    'email' => $facebook_email,
                    'password' => Hash::make($facebook_id),
                    'name' => $facebook_name,
                    'register_source' => 'Facebook',
                    'status' => $status,
                    'registered_at' => now()->addHour(8),
                ];
                $current_user = User::create($data);
                $is_create = true;
            }
            Auth::loginUsingId($current_user->id);
        }else {
            //已經有使用者登入，然後判斷是否有相同的帳號資料存在。 | If there is a authed-user, and then check there is a same account as account from api.
            $check_user = User::where('fb_id', $facebook_id)->orWhere('email', $facebook_email)->first();
            if($check_user){
                return redirect(route('my-account.'.$role.'Information', ['error', 'fb']));
            }
            User::where('id' ,$current_user->id)
                ->update($data);
            $current_user_exits = true;
        }
        $current_user->assignRole($role);

        if($current_user->hasRole('teacher')){
            $role = 'teacher';
        }else{
            $role = 'student';
        }
        $current_user->update([
            'current_role' => $role ,
            'logined_at' => now()->addHour(8)]);

        $role_pause  = false;
        foreach($current_user->getRoleNames() as $single_role){
            if(Role::where('name', $single_role)->get()->toArray()[0]['activated'] != 'Y'){
                $role_pause = true;
                break;
            }
        }
        if($current_user->status == 'pause' || $role_pause){
            Auth::logout();
            return redirect(route('index', ['error' => 'status']));
        }

        if($is_create || $current_user_exits || $role == 'teacher'){
            return redirect(route('my-account.'.$role.'Information', ['source', 'fb']));
        }else{
            $return_url = Session::pull('social.login.return.url', 'no session');
            return redirect($return_url);
        }
    }

    //Google登入 | Google Logging
    public function googleSignInProcess(User $user, $role = null)
    {
        $redirect_url = env('GOOGLE_CALLBACK_URL').'/'.$role;

        Session::forget('social.login.return.url');
        Session::put('social.login.return.url', redirect()->back()->getTargetUrl());

        Session::forget('register.return.url');
        Session::put('register.return.url', redirect()->back()->getTargetUrl());


        return Socialite::driver('google')
            ->redirectUrl($redirect_url)
            ->redirect();
    }

    //Google登入重新導向授權資料處理 | Do something after google logging.
    public function googleSignInCallbackProcess (Request $request, $role=null)
    {
        if(isset($_GET['error_code']) && $_GET['error_code'] == 200){
            return redirect(url(Session::pull('social.login.return.url', route('index'))));
        }

        $current_user = Auth::user();

        $redirect_url = env('GOOGLE_CALLBACK_URL').'/'.$role;
        $is_create = false;
        $current_user_exits = false;

        $GoogleUser = Socialite::driver('google')
            ->redirectUrl($redirect_url)->user();

        $google_email = $GoogleUser->email;
        if(is_null($google_email))
        {
            $return_url = Session::pull('social.login.return.url', 'no session');
            return redirect($return_url, ['error' => trans('AboutSocialMedia.messages.unverifiedUser', ['title' => 'Email'])]);
        }
        //取得 Google 資料 | Get Google Information
        $google_id = $GoogleUser->id;
        $google_name = $GoogleUser->name;

        $data = ['google_id' => $google_id, 'google_email' => $google_email];

        if($role == null){
            foreach ($current_user->getRoleNames() as $current_role){
                if($current_role == 'teacher'){
                    $role = $current_role;
                    break;
                }elseif($current_role  == 'student'){
                    $role = $current_role;
                    break;
                }
            }
        }

        if(!$current_user){
            //目前沒有登入的使用者 | Now there is not a authed-user.
            $current_user = User::where('google_id', $google_id)->orWhere('email', $google_email)->first();
            if($current_user){
                //如果原本就有使用者，那就幫他/她登入並更新facebook_id
                User::where('id' ,$current_user->id)
                    ->update($data);
            }else{
                if($role == 'student'){
                    $status = 'activated';
                }else{
                    $status = 'unverified';
                }
                $data += [
                    'email' => $google_email,
                    'password' => Hash::make($google_id),
                    'name' => $google_name,
                    'register_source' => 'Google',
                    'status' => $status,
                    'registered_at' => now()->addHour(8),
                ];
                $current_user = User::create($data);
                $is_create = true;
            }
            Auth::loginUsingId($current_user->id);
        }else {
            //已經有使用者登入，然後判斷是否有相同的帳號資料存在。 | If there is a authed-user, and then check there is a same account as account from api.
            $check_user = User::where('fb_id', $google_id)->orWhere('email', $google_email)->first();
            if($check_user){
                return redirect(route('my-account.'.$role.'Information', ['error', 'google']));
            }
            User::where('id' ,$current_user->id)
                ->update($data);

            $current_user_exits = true;
        }

        $current_user->assignRole($role);
        if($current_user->hasRole('teacher')){
            $role = 'teacher';
        }else{
            $role = 'student';
        }
        $current_user->update(
            [
                'current_role' => $role,
                'logined_at' => now()->addHour(8)
            ]);

        $role_pause  = false;
        foreach($current_user->getRoleNames() as $single_role){
            if(Role::where('name', $single_role)->get()->toArray()[0]['activated'] != 'Y'){
                $role_pause = true;
                break;
            }
        }
        if($current_user->status == 'pause' || $role_pause){
            Auth::logout();
            return redirect(route('index', ['error' => 'status']));
        }

        if($is_create || $current_user_exits || $role == 'teacher'){
            return redirect(route('my-account.'.$role.'Information', ['source', 'google']));
        }else{
            $return_url = Session::pull('social.login.return.url', 'no session');
            return redirect($return_url);
        }

    }

}
