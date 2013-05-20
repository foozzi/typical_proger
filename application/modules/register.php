<?php

/**
 * title page 
 */

$title = 'Регистрация';

/**
 * if $_POST['REG']...
 */

if(isset($_POST['REG']))
{

	/**
 	 * call self
 	 */

	Register::Check($_POST['NICK'], $_POST['PASSWD'], $_POST['EMAIL']);

}


?>
