<?PHP
echo "<html>\n";

echo "<head>\n";
echo "<title>sm</title>\n";
echo "</head>\n";

echo "<body>\n";

$mysqli = new mysqli("localhost", "nico", "", "nico");
if($mysqli->connect_errno)
{
	echo $mysqli->connect_error."\n";
	exit();
}
$mysqli->query("set names utf8") or die($mysqli->error."\n");

echo "<table border=\"1\">\n";
$query = "SELECT * FROM moz_places ORDER by last_visit_date desc LIMIT 1024";
$result = $mysqli->query($query) or die($mysqli->error."\n");
while($row = $result->fetch_assoc())
{
	echo "<tr>\n";
	echo "<td>".$row["id"]."</td>\n";
	echo "<td>".$row["video_id"]."</td>\n";
	echo "<td>".$row["title"]."</td>\n";
	echo "<td>".date("y-m-d\nH:i:s", $row["last_visit_date"])."</td>\n";
	echo "</tr>\n";
}
$result->free();
$mysqli->close();
echo "</table>\n";

echo "</body>\n";

echo "</html>\n";
?>
