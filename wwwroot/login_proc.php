<?php
require_once('db_open.php');
require_once('db_functions.php');
require_once('common_functions.php');

// attempting to login
if(isset($_POST['username']) && isset($_POST['password']))
{
	$username = $_POST['username'];
	$password = $_POST['password'];

	$str_SQL = "SELECT FirstName, LastName FROM AdminUser
			WHERE UserName = '".$username."' AND Password = '".$password."'";
	$query_result = mysql_query($str_SQL);
	check_sql_error($str_SQL);

	if(mysql_num_rows($query_result) == 1)
	{
		$row = mysql_fetch_array($query_result, MYSQL_ASSOC);
		$user_full_name = $row['FirstName']." ".$row['LastName'];
		session_start();
		$_SESSION['sess_admin_mode'] = 1;
		$_SESSION['sess_user_full_name'] = $user_full_name;

		load_page('/admin/index.php');
	}
	else
	{
		load_page('/login.php');
	}
}
// command to log out
else if(isset($_GET['cmd']) && $_GET['cmd'] = 'o')
{
	// must retrieve the current session first
	session_start();
	session_unset();
	session_destroy();
	load_page('/login.php');
}
else
{
	load_page('/login.php');
}
?>