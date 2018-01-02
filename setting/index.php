<?php
echo "<htmla>\n";
echo "<head>\n";
echo "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=UTF-8\">\n";
echo "<title>setting</title>\n";
echo "</head>\n";
echo "<body>\n";

if(isset($_COOKIE['cur_dir']))
{
	$cur_dir = $_COOKIE['cur_dir'];
}
else
{
	$cur_dir = '.';
	setcookie('cur_dir', $cur_dir, time()+60*60);
}

if(isset($_POST['dir']))
{
	if($_POST['dir'] == "..")
	{
		$pos = strrpos($cur_dir, '/');
		if($pos !== false)
		{
			$cur_dir = substr($cur_dir, 0, $pos);
		}
	}
	else
	{
		$cur_dir .= '/'.$_POST['dir'];
	}
	setcookie('cur_dir', $cur_dir, time()+60*60);
}

$dirs = array();
foreach(glob($cur_dir."/*", GLOB_ONLYDIR) as $dir)
{
	$dirs[] = basename($dir);
}
$dir_cnt = count($dirs);

echo "<form id=\"up\" action=\"index.php\" method=\"post\">\n";
echo "<input type=\"hidden\" name=\"dir\" value=\"..\">\n";
echo "</form>\n";
foreach($dirs as $dir)
{
	echo "<form name=\"$dir\" action=\".\" method=\"post\">\n";
	echo "<input type=\"hidden\" name=\"dir\" value=\"$dir\">\n";
	echo "</form>\n";
}

echo "$cur_dir<br>\n";
echo "<a href=\".\">[reload]</a><br>\n";
echo "<hr>\n";

if($cur_dir != ".")
{
	echo "<a href=\".\" onclick=\"document.getElementById('up').submit();return false;\">[up]</a><br>\n";
}
foreach($dirs as $dir)
{
	echo "<a href=\".\" onclick=\"document.$dir.submit();return false;\">[$dir]</a>\n";
}
echo "<hr>\n";

if($cur_dir != ".")
{
	foreach(glob($cur_dir."/*") as $filename)
	{
		if(is_file($filename))
		{
			$name = basename($filename);
			$path = $cur_dir."/$name";
			echo "<a href=\"$path\">$name</a><br>\n";
		}
	}
}

echo "</body>\n";
echo "</html>\n";
?>
