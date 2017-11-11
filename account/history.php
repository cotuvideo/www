<?php

$self = $_SERVER['PHP_SELF'];
if(isset($_POST['user_session']))
{
	$user_session = $_POST['user_session'];
	setcookie("user_session", $user_session);
	$link = "<a href=\"$self\" target=\"_blank\">history</a>";
}
else
{
	if(isset($_COOKIE['user_session']))
	{
		$user_session = $_COOKIE['user_session'];
		$link = "<a href=\"$self\">history</a>";
	}
}

$url = "http://www.nicovideo.jp/my/history";
$options = array("http"=>array("method"=>"GET", "header"=>"Accept-language: ja\r\n"."Cookie: user_session=$user_session\r\n"));
$context = stream_context_create($options);
$file = file_get_contents($url, false, $context);
if($file !== FALSE)
{
	echo "<!DOCTYPE HTML>\n";
	echo "<html lang=\"ja-jp\">\n";
	echo "<head>\n";
	echo "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=UTF-8\">\n";
	echo "<meta http-equiv=\"Content-Script-Type\" content=\"text/javascript\">\n";
	echo "<meta http-equiv=\"Content-Style-Type\" content=\"text/css\">\n";
	echo "</head>\n";

	echo "<body>\n";
	preg_match("/<title>\n?(.*)\n?<\/title>/i", $file, $res);
	if(count($res) >= 2)
	{
		echo $res[1]."<br>\n";
	}
	if(isset($link))
	{
		echo "$link<br>\n";
	}

	$pat  = '|<div class="outer".*?';
	$pat .= 'data-original=("http://.*?").*?';
	$pat .= '<span class="videoTime">([\d:]+).*?';
	$pat .= '<p class="posttime">(.+?:[0-9]+).*?';
	$pat .= '<span>(.+?)</span>.*?';
	$pat .= '<h5><a href="watch/(\w+?)">(.+?)</a></h5>.*?';
	$pat .= '<ul class="metadata">.*?';
	$pat .= '<li class="play">(.+?)</li>.*?';
	$pat .= '<li class="comment">(.+?)</li>.*?';
	$pat .= '<li class="mylist">.+?>([\d,]+)</a></li>.*?';
	$pat .= '<li class="posttime">(.+?)</li>.*?';
	$pat .= '</ul>|s';
	preg_match_all($pat, $file, $data);
	$n = count($data[0]);
	echo "#data[".count($data)."][$n]<br>\n";
	for($i = 0; $i < $n; $i++)
	{
		echo "<hr>\n";
		$img = $data[1][$i];
		$videoTime = $data[2][$i];
		$viewtime = $data[3][$i];
		$playtime = $data[4][$i];
		$videoId = $data[5][$i];
		$title = $data[6][$i];
		$play = $data[7][$i];
		$comment = $data[8][$i];
		$mylist = $data[9][$i];
		$posttime = $data[10][$i];

		echo "<img src=$img width=128>\n";
		echo "<br>\n";
		echo "$videoTime|\n";
		echo "$viewtime|\n";
		echo "$playtime\n";
		echo "<br>\n";
		echo "$videoId|\n";
		echo "$title\n";
		echo "<br>\n";
		echo "$play|\n";
		echo "$comment|\n";
		echo "$mylist|\n";
		echo "$posttime\n";

/*		echo "<pre>\n";
		echo "#[$i]\n";
	//	var_dump($data[0][$i]);
		for($j = 1; $j < count($data); $j++)
		{
			echo "$j (".$data[$j][$i].")\n";
		}
		echo "</pre>\n";*/
	}
	echo "</body>\n";
	echo "</html>\n";
}
?>
