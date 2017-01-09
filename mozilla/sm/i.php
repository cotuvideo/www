<?php

$mysqli = new mysqli("localhost", "nico", "", "nico");
if($mysqli->connect_errno)
{
	echo $mysqli->connect_error."\n";
	exit();
}
$mysqli->query("set names utf8") or die($mysqli->error."\n");

$pattern = "|.+?/watch/([0-9a-z]+)(\??.*?)(',.*);|i";
$select = "SELECT video_id FROM moz_places WHERE video_id='$1'";
$insert = "INSERT INTO moz_places(video_id,title,last_visit_date) VALUES('$1$3";
$handle = @fopen("sm", "r");
if($handle)
{
	$cnt = 0;
	while(($line = fgets($handle, 4096)) !== false)
	{
		$query = preg_replace($pattern, $select, $line);
		if($query != NULL)
		{
			$result = $mysqli->query($query) or die($mysqli->error."\n");
			$param = preg_replace($pattern, "$2", $line);
			if($param != "\n")
			{
				echo $result->num_rows.":".preg_replace($pattern, "$1$2", $line);
				continue;
			}
			if($result->num_rows == 0)
			{
				$query = preg_replace($pattern, $insert, $line);
				if($query != NULL)
				{
					echo $query;
					$mysqli->query($query) or die($mysqli->error."\n");
					$cnt++;
				}
			}
			$result->free();
		}
		else
		{
			echo "**** error";
			exit();
		}		
	}
	echo "cnt=$cnt\n";
	fclose($handle);
}
$mysqli->close();
?>

