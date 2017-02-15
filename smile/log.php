<?php

function access_log()
{
	$data = ".";
	$uid_file = "$data/uid";
	$admin = 0;
	$admin_name = '';

	$expire = time()+60*60*24*365;

	if(isset($_GET['admin']))
	{
		if($_GET['admin'] == $admin_name)
		{
			setcookie("uid", $admin, $expire);
			echo "admin=$admin";
			exit(0);
		}
	}

	if(isset($_COOKIE{'uid'}))
	{
		$uid = $_COOKIE['uid'];
	}
	else
	{
		$tmp = file_get_contents($uid_file);
		if($tmp === false)
		{
			$uid = 0;
		}
		else
		{
			$uid = $tmp+1;
		}
		file_put_contents($uid_file, $uid);
	}
	setcookie("uid", $uid, $expire);

	if(isset($_COOKIE{'access_counter'}))
	{
		$counter = $_COOKIE['access_counter']+1;
	}
	else
	{
		$counter = 0;
	}
	setcookie("access_counter", $counter, $expire);

	if($uid != $admin)
	{
		$log = date('Y-m-d H:i:s ').sprintf(": %-15s %4d %3d %-16s %s\n", $_SERVER{REMOTE_ADDR}, $uid, $counter, $_SERVER{REQUEST_URI}, $_SERVER{HTTP_USER_AGENT});
		file_put_contents("$data/log", $log, FILE_APPEND);
	}
}

?>
