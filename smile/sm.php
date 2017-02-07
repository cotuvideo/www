<?php

if(count($argv) >= 2)
{
	$video_id = $argv[1];
}
if(isset($video_id) === false)
{
	exit();
}

$user_session = "";

$info = "";
$url = "http://nicovideo.jp/watch/$video_id";
$options = array('http'=>array('method'=>"HEAD", 'header'=>"Accept-language: ja\r\nCookie: user_session=$user_session\r\n"));
$context = stream_context_create($options);
$file = file_get_contents($url, false, $context);
$nicohistory = "";
foreach($http_response_header as $res)
{
	$info .= $res."\n";
	preg_match("(Set-Cookie: nicohistory=(.+?);)", $res, $tmp);
	if(count($tmp) >= 2)
	{
		$nicohistory = $tmp[1];
	//	break;
	}
}
if($nicohistory == "")
{
	echo $info;
	exit();
}
$info .= "\n";

$url = "http://flapi.nicovideo.jp/api/getflv/$video_id";
$options = array('http'=>array('method'=>"GET", 'header'=>"Accept-language: ja\r\nCookie: user_session=$user_session; nicohistory=$nicohistory\r\n"));
$context = stream_context_create($options);
$file = file_get_contents($url, false, $context);
$array = explode('&', $file);
$url = "";
foreach($array as $str)
{
	$info .= urldecode($str)."\n";
	preg_match("(url=(.+?))", $str, $tmp);
	$tmp = explode('url=', $str);
	if(count($tmp) >= 2)
	{
		$url = urldecode($tmp[1]);
	//	break;
	}
}
if($url == "")
{
	echo $info;
	exit();
}
file_put_contents("info/$video_id", $info);

$options = array('http'=>array('method'=>"GET", 'header'=>"Accept-language: ja\r\nCookie: user_session=$user_session; nicohistory=$nicohistory\r\n"));
$context = stream_context_create($options);

$fp = fopen($url, 'r', false, $context);
file_put_contents("video/$video_id.mp4", $fp);
fclose($fp);
echo "ok\n";

?>
