<?php
$ip=$_SERVER['REMOTE_ADDR'];
echo $ip;
date_default_timezone_set('Asia/Tokyo');
file_put_contents("./log",  date('Y-m-d H:i:s')." $ip\n", FILE_APPEND);
?>
