<?php

$url='';
if(count($argv) >= 2)
{
	$video_id = $argv[1];
}
if(isset($video_id) === false)
{
	exit();
}


$options = array('http'=>array('method'=>"GET", 'header'=>"Accept-language: ja\r\n"));
$context = stream_context_create($options);
$fp = fopen($url, 'r', false, $context);
file_put_contents("video/$video_id.mp4", $fp);
fclose($fp);
echo "ok\n";

sleep(1);
$url = "https://ext.nicovideo.jp/api/getthumbinfo/$video_id";
$file = file_get_contents($url);
if($file !== false)
{
	$xml = new SimpleXMLElement($file);
	$status = (string)$xml['status'];
	$info = $status;
	if($status === 'ok')
	{
		echo htmlspecialchars($xml->thumb->title, ENT_QUOTES)."\n";
		file_put_contents("thumbinfo/$video_id.xml", $file);
	}
	else
	{
		echo sprintf("%s:%s", $xml->error->code, $xml->error->description);
	}
}

?>
