<?php
require_once("page_initialize.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<link rel="stylesheet" type="text/css" href="/style.css" />
<link rel="stylesheet" type="text/css" href="/menu.css" />
<title>Walker Automotive UK</title>

<script language="JavaScript" src="/menu.js"></script>

</head>
<body>

<?php
require_once("db_open.php");
require_once("db_functions.php");
require_once("menu.php");
?>

<form action="login_proc.php" method="POST">
<table>
<tr>
	<td class="key">User Name:</td>
	<td><input type="text" name="username" size="20" /></td>
</tr>
<tr>
	<td class="key">Password:</td>
	<td><input type="password" name="password" size="20" /></td>
</tr>
<tr colspan="2">
	<td>
		<input type="submit" value="Submit" />
	</td>
</tr>
</table>
</form>

<?php
require_once('footer.php');
?>

</body>
</html>