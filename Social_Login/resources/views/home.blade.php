{{--TODO : Extend layout.--}}
{{--TODO : Include bootstrap.--}}
<div id="#registerForm">
    <div class="social-login">
        <button data-redirect='/user/auth/facebook-sign-in' class="btn btn-has-icon btn-block btn-sm text-primary">
            <i class="icon-box fa fa-facebook-f"></i>{{trans('AboutSocialMedia.home.facebookLogin')}}
        </button>
        <button data-redirect='/user/auth/google-sign-in' class="btn btn-has-icon btn-block btn-sm text-primary">
            <i class="icon-box fab fa-google"></i>{{trans('AboutSocialMedia.home.googleLogin')}}
        </button>
    </div>
</div>