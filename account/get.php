<?php

if(!isset($_POST{'url'}))exit(0);
if(!isset($_POST{'session'}))exit(0);

$url = $_POST{'url'};
$session = $_POST{'session'};

$options = array('http'=>array('method'=>"GET", 'header'=>"Accept-language: ja\r\n"."Cookie: user_session=$session\r\n"));
$context = stream_context_create($options);
$file = file_get_contents($url, false, $context);
if($file !== FALSE)
{
	echo $file;
}

?>
