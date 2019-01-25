<?php

function search_file($dir)
{
	global $server_url;
	global $base_dir_len;

	$cnt=0;
	foreach(glob($dir."/*.xml", GLOB_NOSORT) as $filename)
	{
		if($cnt++ > 0)sleep(1);

		$file0 = file_get_contents($filename);
		if($file0 === false)
		{
			exit("**** read error $filename ****\n");
		}
		$res = strstr($file0, "</rss>");
		if($res === false)
		{
			echo "file error $filename\n";
			continue;
		}
		try
		{
			$xml0 = new SimpleXMLElement($file0);
		}
		catch(Exception $e)
		{
			exit("**** xml error $filename ****\n");
		}

		$url = $server_url.substr($filename, $base_dir_len);
		$file1 = file_get_contents($url);
		if($file1 === false)
		{
			echo "**** read error $url ****\n";
			continue;
		}
		$res = strstr($file1, "</rss>");
		if($res === false)
		{
			echo "file error $url\n";
			continue;
		}
		try
		{
			$xml1 = new SimpleXMLElement($file1);
		}
		catch(Exception $e)
		{
			exit("**** xml error $url ****\n");
		}

		$xml0->channel->pubDate = $xml1->channel->pubDate;
		$xml0->channel->lastBuildDate = $xml1->channel->lastBuildDate;
		$n = 0;
		foreach($xml0->channel->item as $item)
		{
			$item->pubDate = $xml1->channel->item[$n++]->pubDate;
		}

		$str0 = $xml0->asXML();
		$str1 = $xml1->asXML();
		$res = strcmp($str0, $str1);
		if($res === 0)
		{
			echo "ok $filename\n";
			if(unlink($filename) === false)
			{
				exit("unlink error $filename\n");
			}
		}
		else
		{
		//	file_put_contents("./file0.xml", $str0);
		//	file_put_contents("./file1.xml", $str1);
			echo "diff $res $filename\n";
		}
	}
	echo $dir."($cnt)\n";
}

function find($cur_dir)
{
	foreach(glob($cur_dir."/*", GLOB_ONLYDIR) as $dir)
	{
		echo $dir."\n";
		search_file($dir);
		find($dir);
	}
}

$server_url = "http://127.0.0.1/nico/ranking/data";
$base_dir = "data";
$base_dir_len = strlen($base_dir);
$cur_dir = $base_dir;
find($cur_dir);

?>
