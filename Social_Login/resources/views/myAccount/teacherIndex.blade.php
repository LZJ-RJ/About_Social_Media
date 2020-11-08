<?php
{{--TODO : Extend layout.--}}

<h3>Hi, I am a teacher.</h3>
<div class="d-md-flex">
    <div class="bg-@if(isset($user->fb_id)){{$user->fb_id != '' ? 'primary':'secondary'}}
    @else{{'secondary'}}
    @endif py-1 px-2 text-white info-item-status">{{trans('AboutSocialMedia.myAccount.facebookBind')}}<span>@if(isset($user->fb_id)){{$user->fb_id!=''?trans('AboutSocialMedia.myAccount.binded'):trans('AboutSocialMedia.myAccount.unbind')}}
            @else {{trans('AboutSocialMedia.myAccount.unbind')}}
            @endif</span></div>
    <a class="text-@if(isset($user->fb_id)){{$user->fb_id != '' ? 'primary':'secondary'}}
    @else{{'secondary'}}
    @endif ml-md-4 sub-link fb-member-link" data-redirect="/user/auth/facebook-sign-in/@if($user->current_role == 'teacher'){{'teacher'}}@else{{'student'}}@endif">@if(isset($user->fb_id)){{$user->fb_id!=''?trans('AboutSocialMedia.myAccount.bindAgain'):trans('AboutSocialMedia.myAccount.unbind')}}
        @else {{trans('AboutSocialMedia.myAccount.bindNow')}}
        @endif</a>
</div>

<div class="d-md-flex">
    <div class="bg-@if(isset($user->google_id)){{$user->google_id != '' ? 'primary':'secondary'}}
    @else{{'secondary'}}
    @endif py-1 px-2 text-white info-item-status">{{trans('AboutSocialMedia.myAccount.googleBind')}}<span>@if(isset($user->google_id)){{$user->google_id!=''?trans('AboutSocialMedia.myAccount.binded'):trans('AboutSocialMedia.myAccount.unbind')}}
            @else {{trans('AboutSocialMedia.myAccount.unbind')}}
            @endif</span></div>
    <a class="text-@if(isset($user->google_id)){{$user->google_id != '' ? 'primary':'secondary'}}
    @else{{'secondary'}}
    @endif ml-md-4 sub-link google-member-link"  data-redirect="/user/auth/google-sign-in/@if($user->current_role == 'teacher'){{'teacher'}}@else{{'student'}}@endif">@if(isset($user->google_id)){{$user->google_id!=''?trans('AboutSocialMedia.myAccount.bindAgain'):trans('AboutSocialMedia.myAccount.unbind')}}
        @else {{trans('AboutSocialMedia.myAccount.bindNow')}}
        @endif
    </a>
</div>