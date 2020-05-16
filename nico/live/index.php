<?php

require "define.php";

$self = basename($_SERVER['PHP_SELF']);

echo "<a href=\"$self\">&#x1f320;&#x2604;☆彡</a>\n";

$mysqli = new mysqli($host, $user, $pass, $db, $port);
if($mysqli->connect_errno)
{
	die($mysqli->connect_error."\n");
}
$mysqli->query("SET names utf8") or die($mysqli->error."\n");

if(isset($_REQUEST['cmd']))
{
	$cmd = $_REQUEST['cmd'];
	if(isset($_REQUEST['id']))
	{
		$id = $_REQUEST['id'];
		switch($cmd)
		{
		case 'del':
			$query = "UPDATE $db.$tb SET hide=true WHERE id=$id";
			echo "$cmd:$query<br>\n";
			$mysqli->query($query);
			break;
		case 'dis':
			$query = "UPDATE $db.$tb SET enable=false, hide=true WHERE id=$id";
			echo "$cmd:$query<br>\n";
			$mysqli->query($query);
			break;
		}

	}
}

$query = "SELECT * FROM $db.$tb WHERE hide=FALSE AND enable=TRUE AND open_time<=now() ORDER BY id DESC";
$result = $mysqli->query($query);

echo "<table border=2 align=\"center\">\n";

while($row = $result->fetch_assoc())
{
	$id = $row['id'];
	$lv = $row['lv'];
	$co = $row['co'];
	$title = ($row['title'] != null) ? $row['title'] : $row['lv'];
	$hide = $row['hide'];
	$create_date = $row['create_date'];
	$open_time = $row['open_time'];
	$start_time = $row['start_time'];
	$description = $row['description'];
	$name = $row['name'];

	$url = "https://live.nicovideo.jp/watch/lv$lv";

	echo "<tr>\n";
	echo "<td>$id</td>\n";
	echo "<td><a href=\"index.php?cmd=dis&id=$id\">$create_date</a></td>\n";
	echo "<td>$open_time</td>\n";
	echo "<td>$start_time</td>\n";
	echo "<td>$lv</td>\n";
	if($hide)
	{
		echo "<td>$title</td>\n";
	}
	else
	{
		echo "<td><a href=\"$url\" target=\"_blank\">$title</a></td>\n";
	}
	echo "<td>$name</td>\n";

	echo "<td>\n";
	echo "<form method=\"POST\" action=\"$self\">\n";
	echo "<input type=\"hidden\" name=\"cmd\" value=\"del\">\n";
	echo "<input type=\"hidden\" name=\"id\" value=\"$id\">\n";
	echo "<input type=\"submit\" value=\"del\">\n";
	echo "</form>\n";
	echo "</td>\n";

	echo "<td>$description</td>\n";

	echo "</tr>\n";
}

echo "</table>\n";

$result->free();
$mysqli->close();

?>
