<?PHP

require './log.php';
access_log();

echo "<html>\n";

echo "<head>\n";
echo "<title>smile</title>\n";
echo "</head>\n";

echo "<body>\n";

$mysqli = new mysqli("localhost", "", "", "nico");
if($mysqli->connect_errno)
{
	die($mysqli->error);
}
$mysqli->query("SET NAMES utf8") or die($mysqli->error);

echo "<table border=\"1\">\n";
echo "<tr>\n";
echo "<td>id</td>\n";
echo "<td>タイトル</td>\n";
echo "<td>視聴日時</td>\n";
echo "</tr>\n";

$files = scandir('./video/');
foreach($files as $file)
{
	$pos = strpos($file, '.');
	if($pos !== false && $pos > 0)
	{
		$id = substr($file, 0, $pos);
		echo "<tr>\n";
		echo "<td>$id</td>\n";
		$query = "SELECT title,last_visit_date FROM moz_places WHERE video_id='$id'";
		$result = $mysqli->query($query) or die($mysqli->error."\n");
		if($row = $result->fetch_assoc())
		{
			echo "<td><a href=\"video/$file\" target=\"_blank\">".$row["title"]."</a></td>\n";
			echo "<td>".date("y-m-d\nH:i:s", $row["last_visit_date"])."</td>\n";
		}
		else
		{
			echo "<td><a href=\"video/$file\" target=\"_blank\">".$id."</a></td>\n";
			echo "<td></td>\n";
		}
		echo "</tr>\n";
	}
}
$result->free();
$mysqli->close();
echo "</table>\n";

echo "</body>\n";

echo "</html>\n";
?>
