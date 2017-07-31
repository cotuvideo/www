<?php

$host = "localhost";
$user = "user";
$pass = "";
$db = "db";
$tbl = "tbl";

if(!isset($_POST{'id'}))exit(0);

$id = $_POST{'id'};

$mysqli = new mysqli($host, $user, $pass, $db);
if($mysqli->connect_errno)
{
	echo $mysqli->connect_error;
	exit();
}

$query = "SELECT * FROM $tbl WHERE id=$id";
$result = $mysqli->query($query) or die($mysqli->error);

if($row = $result->fetch_assoc())
{
	$mail = $row['mail'];
	$password = $row['password'];
	$count = $row['count']+1;
	$log = date('Y-m-d H:i:s ').sprintf(": %-15s %-32s %s\n", $_SERVER{REMOTE_ADDR}, $mail, $_SERVER{HTTP_USER_AGENT});
	file_put_contents("./log", $log, FILE_APPEND);
	echo $log."\n";

	$data = http_build_query
	(
		array
		(
			'next_url' => "",
			'mail'     => $mail,
			'password' => $password,
			'submit'   => "",
		)
	);
	$option = array
	(
		"http"=>array
		(
			'method'  => "POST",
			'header'  => "Content-Type: application/x-www-form-urlencoded\r\nAccept-language: ja\r\n",
			'content' => $data
		)
	);
	$context = stream_context_create($option);
	$url = "https://secure.nicovideo.jp/secure/login?site=niconico";
	$file = file_get_contents($url, false, $context);

	$user_session = "";
	$user_session_secure = "";
	echo "<hr>\n";
	foreach($http_response_header as $res)
	{
		echo "$res<br>\n";
		preg_match("/user_session=(user_session_[0-9a-z_]+)/i", $res, $tmp);
		if(count($tmp) >= 2)
		{
			$user_session = $tmp[1];
			echo "<font color=\"red\">$user_session</font><br>\n";
		}
		preg_match("/user_session_secure=([0-9a-z_]+)/i", $res, $tmp);
		if(count($tmp) >= 2)
		{
			$user_session_secure = $tmp[1];
			echo "<font color=\"red\">$user_session_secure</font><br>\n";
		}
	}
	echo "<hr>\n";

	$query = "UPDATE $tbl SET count=$count,user_session='$user_session',user_session_secure='$user_session_secure' WHERE id=$id";
//	$mysqli->query($query) or die($mysqli->error);
}

$result->free();
$mysqli->close();

?>
