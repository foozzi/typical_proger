<?php

$title = 'Профиль';

Auth::Check_Access();

$u_profile = array();

$u_profile = Profile::info_user();

if(isset($_POST['prof']))
{
	Profile::edit_data($_POST['login'], $_POST['email_user']);
}

?>