//TODO : Do something to let this file to be included.
//TODO : Let jQuery can be used.
jQuery(function($){

	//彈跳視窗的社群登入
    $('#registerForm .social-login button').click(function (e) {
          e.preventDefault();
          e.stopPropagation();
          let role = $(this).parent().parent('#registerForm').children().children('input[name="role"]').val();
          //登入預設是學生
          if(role == undefined){
            role = 'student';
          }
          let result_href = location.origin + $(this).attr('data-redirect')+'/'+role;
          location.href = result_href;
    });

});