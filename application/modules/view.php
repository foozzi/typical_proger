<?php

$title = "Типичный программист";

if( isset($_GET['id']) )
{
	
	if(is_array($_GET['id']) == true)
	{
		show404("Hello!", "My server made is boo");
	}

	$OnePost = db::normalizeQuery("SELECT * FROM post WHERE id_post = '%u'", $_GET['id']);
	if( !$OnePost )
	{
		show404('404 Error', 'Post Not Found');
	}
}


