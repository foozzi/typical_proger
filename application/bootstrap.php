<?php
session_start();

/**
 * define for main path
 */

define("FUNC", APPLICATION . "/func.php");
    
define("MODULES", APPLICATION . "/modules/");
define("_CLASS_", ROOT . "/class/");

define("LAYOUTS", APPLICATION . "/layouts/");
define("HEADER",  LAYOUTS . "parts/header.html");
define("MENU",    LAYOUTS . "parts/nav.html");
define("FOOTER",  LAYOUTS . "parts/footer.html");

define("MEDIA", "/media/");
define("CSS", MEDIA . "css/");
define("IMG", MEDIA . "img/");
define("JS", MEDIA . "js/");

define("UPLOAD_DIR", "up/");
define("UPLOAD_DIR_THUMB", UPLOAD_DIR . "thumb/");

/**
 * DB DATA
 */

define("HOST", "localhost");
define("USER", "root");
define("PASSWD", "");
define("DB", "typical_proger");
define("CHARSET", "utf8");

/**
 * number post in post
 */

define("NUM_POSTS" , "5");

/**
 * settings for uploader
 */

define("MIN_SIZE_FILE", 70);
define("MAX_FILE_SIZE", 450);

/**
 * page navigator name
 */

define("GO", "Туда ->");
define("BACK", "<- Обратно");

/**
 * valid extensions for upload
 */

$valid_extensions = array('gif', 'jpg', 'png', 'GIF', 'JPG', 'PNG'); 

/**
 * include class/func and other
 */ 

include "class/lib.Profile.php";
include "class/lib.Mysqli.php";
include "class/lib.SimpleImage.php";
include "class/lib.Register.php";
include "class/lib.Add.php";
include "class/lib.User.php";
include "class/lib.Paginator.php";

include FUNC;


/** 
 * connect to DB
 */

db::connect(HOST, USER, PASSWD, DB);

db::setCharset(CHARSET); 

/**
 * check user 
 */

Auth::check_session();

/**
 * rewrite 
 */ 


$source = $_SERVER['REQUEST_URI'];

$_SERVER['REQUEST_URI'] = "";
//$_GET = array();

/**
 * Убираем слеши и делим запрос на 2 части, page=1
 */

$destination = rtrim(preg_replace("/(.+)\/(\?)/", "$1$2", $source), "/");
$destination = preg_replace("/((\?|&)page=1)\b$/", "", $destination);

/**
 * Если строка оказалась пуста
 */

if ($destination == "") {
    $destination = "/";
}

/**
 * Если после изменений исходная строка и результирующая не совпадают,
 * идем на результирующую.
 */

if ($destination != $source) {
    header("Location: $destination");
    exit();
}

/**
 * Режем запрос на 2 части, до знака ? и после него
 */

$qs = explode("?", $destination);
$uri = $qs[0];

/**
 * Первую часть запроса сохраняем в роутер $routeParams
 */

$routeParams = array();
$params = explode("/", $uri);
foreach ($params as $param) {

$param = trim(rawurldecode($param));
    if ($param) {
        array_push($routeParams, $param);
    }
}

/**
 * Если есть хотя бы параметр...
 */

if (isset($routeParams[0])) {

    /**
     * Пытаемся найти такой модуль
     */

    $module = MODULES . $routeParams[0] . ".php";

    /**
     * Если его нет, подключаем 404
     */

    if (!file_exists($module)) {

        show404("Router error", "Module not found");

    }

/**
 * Если есть, подключаем модуль и шаблон модуля
 */

    require_once $module;
    require_once LAYOUTS . "{$routeParams[0]}.html";

/**
 * Если параметров нет, идет на главную страницу 
 */

} else {
    header("Location: /main");
    exit();
}



