<?php

/**
* display error
*/

ini_set("display_errors", "On");
error_reporting(E_ALL | E_STRICT);

/**
* default path
*/

define("ROOT", realpath(dirname(__FILE__)));

define("APPLICATION", ROOT . "/application");

/**
* include main bootstrap
*/

require_once APPLICATION . "/bootstrap.php";




