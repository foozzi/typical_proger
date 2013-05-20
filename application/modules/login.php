<?php

if(isset($_GET['exit']))
{
	Auth::logout();
}
else
{
	if(!isset($_POST['LOGN']))
	{
		header("Location: /main");
	}
}

// Login a user
Auth::attempt($_POST['LOGIN'], $_POST['PASSWD_LOGN'], Auth::real_ip(), Auth::get_user_agent())


?>