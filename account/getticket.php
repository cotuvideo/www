<?php
if(!isset($_POST['url']) || !isset($_POST['session']))exit;
$url = $_POST['url'];
$session = $_POST['session'];

$options = array("http"=>array("method"=>"GET", "header"=>"Accept-language: ja\r\n"."Cookie: user_session=$session\r\n"));
$context = stream_context_create($options);
$data = file_get_contents($url, false, $context);
if($data !== FALSE)
{
	preg_match("/<table>(.*?)<\/table>/i", $data, $res);
	$cnt = count($res);
//	echo "cnt=$cnt<br>\n";
	if($cnt > 0)
	{
		echo "<table border=1>";
		echo $res[1];
		echo "</table>";
	}
}
?>
