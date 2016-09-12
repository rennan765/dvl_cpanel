$(function(){

	$('#forgotPass').frameWarp();

	$('#createUser').frameWarp({
		url : 'controlpanel_functions/createUser.php',
		cache:false
	});

	$('#changePass').frameWarp({
		url : 'controlpanel_functions/changePass.php',
		cache:false
	});

	$('#showUsers').frameWarp({
		url : 'controlpanel_functions/showUsers.php',
		cache:false
	});

});