<?php

require "define.php";

function get_live($p)
{
	global $co_map;
	global $mysqli, $tb;

	if($p === false)
	{
		$url = "https://live.nicovideo.jp/recent/rss";
	}
	else
	{
		$url = "https://live.nicovideo.jp/recent/rss?p=$p";
	}
	echo "# $url\n";
	$contents = file_get_contents($url);
	if($contents === false)
	{
		echo "**** read error $url\n";
		return false;
	}

	$xml = new SimpleXMLElement($contents);
	$total_count = $xml->channel->xpath('nicolive:total_count')[0];
	$cnt = count($xml->channel->item);
	echo "count=$cnt/$total_count\n";
	for($i = 0; $i < $cnt; $i++)
	{
		$item = $xml->channel->item[$i];
		$title = htmlspecialchars($item->title, ENT_QUOTES);
		$link = (string)$item->link;
		$guid = (string)$item->guid;
		$pubDate = (string)$item->pubDate;
		$community_name = (string)$item->xpath('nicolive:community_name')[0];
		$community_id = (string)$item->xpath('nicolive:community_id')[0];
		$type = (string)$item->xpath('nicolive:type')[0];

		$lv = substr($guid, 2);
	//	if(isset($co_map[$community_id]))
		{
			$query = "SELECT lv FROM $tb WHERE lv=$lv";
			$result = $mysqli->query($query);
			if(!($row = $result->fetch_row()))
			{
				$query = "INSERT INTO $tb(lv, create_date, open_time, start_time, co, title, description, name) VALUES($lv, now(), now(), now(), '$community_id', '$title', '$p:$type', '$community_name')";
				echo $query."\n";
				$result = $mysqli->query($query);
				if($result === false)
				{
					$query = "INSERT INTO $tb(lv, create_date, title, description) VALUES($lv, now(), '$title', 'error')";
					$result = $mysqli->query($query);
				}
			}
		}
	}
	if($p*30 > $total_count)
	{
		echo "$p*30 > $total_count\n";
		return false;
	}
	return true;
}

date_default_timezone_set('Asia/Tokyo');
echo date("Y-m-d H:i:s")."\n";

$mysqli = new mysqli($host, $user, $pass, $db, $port);
if($mysqli->connect_errno)
{
	die($mysqli->connect_error."\n");
}
$mysqli->query("SET names utf8") or die($mysqli->error."\n");

$co_map['co47201'  ] = true;
$co_map['co3456784'] = true;	// cotutest

get_live(false);
for($i = 0; $i < 16; $i++)
{
	sleep(1);
	if(get_live($i) === false)break;
}

$mysqli->close();

?>
