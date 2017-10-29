<?php

$host = "localhost";
$user = "user";
$pass = "";
$db = "db";
$tbl = "tbl";

echo "<html>\n";

echo "<head>\n";
echo "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=UTF-8\">\n";
echo "<title>account</title>\n";

echo "<script src=\"https://code.jquery.com/jquery-2.1.4.min.js\"></script>\n";
echo "<script src=\"https://cdn.jsdelivr.net/clipboard.js/1.5.3/clipboard.min.js\"></script>\n";
echo "<script>\n";
echo "$(function () {\n";
echo "var clipboard = new Clipboard('.btn');\n";
echo "});\n";
echo "</script>\n";

echo "</head>\n";

$mysqli = new mysqli($host, $user, $pass, $db);
if($mysqli->connect_errno)
{
	echo $mysqli->connect_error."\n";
	exit();
}

echo "<body>\n";

if(isset($_SERVER['PHP_SELF']))
{
	$self = basename($_SERVER['PHP_SELF']);
	echo "<h1><a href=\"$self\">reload</a></h1>\n";
}

if(isset($_POST{'mail'}))
{
	$mail = $_POST{'mail'};
	if(isset($_POST{'password'}))
	{
		$password = $_POST{'password'};
	}
	else
	{
		$password = "****";
	}
	echo "mail=$mail<br>";
	echo "password=$password<br>";
	$query =
	"INSERT INTO $db.$tbl("
		.  "create_date"
		.", mail"
		.", password"
	.") VALUES("
		.  "now()"
		.", '$mail'"
		.", '$password'"
	.")";
	mysql_query($query) or die(mysql_error()."<br>\n$query");
}

if(isset($self))
{
	echo "<form method=\"POST\" action=\"$self\">\n";
	echo "<input type=\"text\" name=\"mail\">\n";
	echo "<input type=\"text\" name=\"password\">\n";
	echo "<input type=\"submit\"><br>\n";
	echo "</form>\n";
}

echo "<table border=\"1\">\n";

$query = "desc $tbl";
$result = $mysqli->query($query) or die($mysqli->error);
echo "<tr>\n";
$fields = array("id", "date", "mail", "session", "session_sec", "count", "login", "top", "co", "ticket");
foreach($fields as &$field)
{
	echo "<td>$field</td>\n";
}
echo "</tr>\n";

$query = "select * from $tbl order by id";
$result = $mysqli->query($query) or die($mysqli->error);

while($row = $result->fetch_assoc())
{
	$id = $row['id'];
	$timestamp = $row['timestamp'];
//	$create_date = $row['create_date'];
	$mail = $row['mail'];
	$password = $row['password'];
	$user_session = $row['user_session'];
	$user_session_secure = $row['user_session_secure'];
	$count = $row['count'];

	echo "<tr>\n";
	echo "<td>$id</td>\n";
	echo "<td>$timestamp</td>\n";
//	echo "<td>$create_date</td>\n";
	echo "<td>$mail</td>\n";
//	echo "<td>$password</td>\n";

	echo "<td>\n";
	echo "<input type=\"text\" id=\"s$id\" value=\"$user_session\" size=\"1\">\n";
	echo "<button class=\"btn\" data-clipboard-target=\"#s$id\">copy</button>\n";
	echo "</td>\n";

	echo "<td>\n";
	echo "<input type=\"text\" id=\"ss$id\" value=\"$$user_session_secure\" size=\"1\">\n";
	echo "<button class=\"btn\" data-clipboard-target=\"#ss$id\">copy</button>\n";
	echo "</td>\n";

	echo "<td>$count</td>\n";

	echo "<td>\n";
	echo "<form method=\"POST\" action=\"login.php\">\n";
	echo "<input type=\"hidden\" name=\"id\" value=\"$id\">\n";
	echo "<input type=\"submit\" value=\"login\">\n";
	echo "</form>\n";
	echo "</td>\n";

	$url = "http://www.nicovideo.jp/my/top";
	echo "<td>\n";
	echo "<form method=\"POST\" action=\"get.php\">\n";
	echo "<input type=\"hidden\" name=\"url\" value=\"$url\">\n";
	echo "<input type=\"hidden\" name=\"session\" value=\"$user_session\">\n";
	echo "<input type=\"submit\" value=\"top\">\n";
	echo "</form>\n";
	echo "</td>\n";

	$url = "http://com.nicovideo.jp/community?page=1";
	echo "<td>\n";
	echo "<form method=\"POST\" action=\"get.php\">\n";
	echo "<input type=\"hidden\" name=\"url\" value=\"$url\">\n";
	echo "<input type=\"hidden\" name=\"session\" value=\"$user_session\">\n";
	echo "<input type=\"submit\" value=\"co\">\n";
	echo "</form>\n";
	echo "</td>\n";

	$url = "http://uad.nicovideo.jp/#ticket-detail";
	echo "<td>\n";
	echo "<form method=\"POST\" action=\"getticket.php\">\n";
	echo "<input type=\"hidden\" name=\"url\" value=\"$url\">\n";
	echo "<input type=\"hidden\" name=\"session\" value=\"$user_session\">\n";
	echo "<input type=\"submit\" value=\"ticket\">\n";
	echo "</form>\n";
	echo "</td>\n";

	echo "</tr>\n";
}
$result->free();
$mysqli->close();
echo "</table>\n";

echo "<hr>\n";

echo "</body>\n";
echo "</html>\n";

?>
