<?php
function load_page($page)
{
	$host  = $_SERVER['HTTP_HOST'];
	header("Location: http://$host$page");
}

function load_homepage()
{
	load_page('/index.php');
}

function log_msg($msg)
{
	$fp = fopen($_SERVER['DOCUMENT_ROOT'].'/admin/log.txt','a');
	fputs($fp, date("Y-m-d H:i:s")." ".$msg."\n");
	fclose($fp);
}

function log_error($msg)
{

}

?>
