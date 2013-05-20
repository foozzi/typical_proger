<?php

function ifguest()
{
	if( !isset($_COOKIE['hash']) )
	{
		return false;
	}
	else
	{
		return true;
	}
}

function exit_user()
{
    unset($_SESSION['login'], $_SESSION['hash']);
}

/**
 * display msg with twitter bootstrap
 */

function display_msg($type, $type_div, $msg)
{
    $output = '<div class="alert alert-block '.$type_div.' fade in"> 
    <button type="button" class="close" data-dismiss="alert">&times;</button> 
    <h4 class="alert-heading">'.$type.'!</h4> 
    <p>'.$msg.'!</p>';
	return $output;
}

function display_msg_redir($type, $type_div, $msg, $place, $time)
{
    $output = '<div class="alert alert-block '.$type_div.' fade in"> 
    <button type="button" class="close" data-dismiss="alert">&times;</button> 
    <h4 class="alert-heading">'.$type.'!</h4> 
    <p>'.$msg.'!</p>
    <script language="JavaScript" type="text/javascript">
    setTimeout( \'location="'.$place.'";\', '.$time.');
    </script>';
    return $output;
}

/** 
 * clean from xss/sql inj
 */

function xss($var)
{
    if(is_array($var)) 
    {
        foreach($var as $k=>$v)
            $new[$k] = xss($v);
        return $new;
    }
    return htmlspecialchars(strip_tags($var));
}

/**
 * get info from images
 */

function get_image_info($file = NULL) 
{ 

    if(!is_file($file)) return false; 

    if(!$data = getimagesize($file) or !$filesize = filesize($file)) return false; 

    $extensions = array(1 => 'gif', 2 => 'jpg', 3 => 'png', 4 => 'bmp'); 

    return array(
        'width'	 =>	$data[0], 
        'height'	=>	$data[1], 
        'extension'	=>	$extensions[$data[2]], 
        'size'	 =>	$filesize, 
        'mime'	 =>	$data['mime']
    ); 

    //return $result; 
} 

/**
 * check and create folder for uploading
 */

function create_folder($name_folder)
{
    if(!file_exists($name_folder))
    {
        mkdir($name_folder, 0777);
    }
}

function show404($title, $message)
{
    header("HTTP/1.0 404 Not Found");
    require_once LAYOUTS . "404.html";
    die();
}

?>
