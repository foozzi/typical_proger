<?php

Auth::Check_Access();

if(!isset($_POST['ADD']))
{
    header("Location: /");
}

@$Uploading = new Upload($_POST['text'], $_FILES['filename']['name'], $_FILES['filename']['size'], $_FILES['filename']['tmp_name']);

?>