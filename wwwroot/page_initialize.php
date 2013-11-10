<?php
session_start();

$car_id = -1;
$admin_mode = 0;

if(isset($_SESSION['sess_admin_mode']))
{
	$admin_mode = $_SESSION['sess_admin_mode'];
	$user_full_name = $_SESSION['sess_user_full_name'];
}
?>
