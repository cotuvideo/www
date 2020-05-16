<?php

require "define.php";

date_default_timezone_set('Asia/Tokyo');

$mysqli = new mysqli($host, $user, $pass, $db, $port);
if($mysqli->connect_errno)
{
	die($mysqli->connect_error."\n");
}
$mysqli->query("SET names utf8") or die($mysqli->error."\n");

$url = "https://live.nicovideo.jp/rss";
$contents = file_get_contents($url) or die("read error $url\n");
$xml = new SimpleXMLElement($contents);
$cnt = count($xml->channel->item);
for($i = 0; $i < $cnt; $i++)
{
	$item = $xml->channel->item[$i];
	$title = htmlspecialchars($item->title, ENT_QUOTES);
	$guid = (string)$item->guid;
	$pubDate = (string)$item->pubDate;
	$open_time = (string)$item->xpath('nicolive:open_time')[0];
	$start_time = (string)$item->xpath('nicolive:start_time')[0];
	$type = (string)$item->xpath('nicolive:type')[0];

	$lv = substr($guid, 2);
	$query = "SELECT lv FROM $tb WHERE lv=$lv";
	$result = $mysqli->query($query);
	if(!($row = $result->fetch_row()))
	{
		$query = "INSERT INTO $tb(lv, create_date, open_time, start_time, title) VALUES($lv, now(), '$open_time', '$start_time', '$title')";
		echo $query."\n";
		$result = $mysqli->query($query);
		if($result === false)
		{
			$query = "INSERT INTO $tb(lv, create_date, title) VALUES($lv, now(), '$title')";
			$result = $mysqli->query($query);
		}
	}
}

$mysqli->close();

?>
