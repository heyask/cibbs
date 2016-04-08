$.fn.ajax_request = function(_url, _data, _on_success, _redirect_url = '') {
	btnObj = $(this);
	$.ajax({
	    type: 'get',
	    url: _url,
	    data: _data,
	    cache: false,
	    beforeSend: function() {
	    	btnObj.attr('disabled', 'disabled');
	    },
	    success: function (result) {
	    	data = $.parseJSON(result);
	    	
	    	alert(data.msg);
	    	
	    	if(data.success === true)
	    	{
		    	if(_on_success == 'reload')
		    		location.reload();
		    	else if(_on_success == 'redirect')
		    		location.href = _redirect_url;
		    	
		    	return true;
	    	} 
	    	else
	    	{
	    		return false;
	    	}
	    },
	    complete: function() {
	    	btnObj.removeAttr('disabled');
	    },
	    error: function(e) {
	    	console.log(e);
	    }
	});
}

$(document).ready(function() {
	
	$(document).on('click', '#btn-login' , function(e) {
		e.preventDefault();
		
		var email = $('#email').val();
		var password = $('#password').val();
		var remember = $('#remember').val();

		var array = { 
				"email": email, 
				"password": password, 
				"remember": remember 
			};
		var str = $.param(array);
		
		$(this).ajax_request('/account/login_check/', str, 'reload');
		
	});
	
	$(document).on('click', '#btn-signup' , function(e) {
		e.preventDefault();
		
		var redirect_url = $(this).parents('form').attr('data-redirect-url');
		var email = $('#email').val();
		var email_confirm = $('#email_confirm').val();
		var password = $('#password').val();
		var password_confirm = $('#password_confirm').val();
		var member_id = $('#member_id').val();
		var nickname = $('#nickname').val();

		var array = { 
				"email": email, 
				"email_confirm": email_confirm, 
				"password": password, 
				"password_confirm": password_confirm, 
				"member_id": member_id, 
				"nickname": nickname
			};
		var str = $.param(array);
		
		$(this).ajax_request('/account/signup_check/', str, 'redirect', redirect_url);
	});
	
	$(document).on('click', '#dup_check_nickname' , function(e) {
		e.preventDefault();
		
		var nickname = $('#nickname').val();

		var array = {  
				"nickname": nickname
			};
		var str = $.param(array);
		
		result = $(this).ajax_request('/account/dup_nickname_check/', str, 'nothing');
	});
	
	$(document).on('click', '#dup_check_member_id' , function(e) {
		e.preventDefault();
		
		var member_id = $('#member_id').val();

		var array = {  
				"member_id": member_id
			};
		var str = $.param(array);
		
		result = $(this).ajax_request('/account/dup_id_check/', str, 'nothing');
		
		
	});
	
	$(document).on('click', '#btn-forgot-password' , function(e) {
		e.preventDefault();
		
		var email = $('#email').val();

		var array = { 
				"email": email
			};
		var str = $.param(array);
		$(this).ajax_request('/account/send_reset_password_email/', str, 'reload');
	});
	
	$(document).on('click', '#btn-reset-password' , function(e) {
		e.preventDefault();

		var password = $('#password').val();
		var password_confirm = $('#password_confirm').val();
		var auth_key = $('#auth_key').val();

		var array = { 
				'password': password,
				'password_confirm': password_confirm,
				'auth_key': auth_key
			};
		var str = $.param(array);
		$(this).ajax_request('/account/servlet_reset_password/', str, 'reload');
	});
});