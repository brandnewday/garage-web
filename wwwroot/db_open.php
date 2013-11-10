<?php
$ip = getenv("REMOTE_ADDR");

// ip of dev box, or whatever box for viewing the site in dev
if($ip == '127.0.0.1')
{
	$db = mysql_connect('localhost', 'root', 'mypass');
	mysql_select_db('garage',$db);
}
else
{
	$db = mysql_connect('PROD_DB_HOST', 'PROD_USER', 'PROD_PASSWORD');
	mysql_select_db('PROD_DB_NAME',$db);
}
?>
