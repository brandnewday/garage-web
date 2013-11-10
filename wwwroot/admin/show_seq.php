<?php
require_once("../page_initialize.php");
require_once("../common_functions.php");
require_once("../db_open.php");
require_once("../db_functions.php");

if($admin_mode != 1)
{
	load_homepage();
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<link rel="stylesheet" type="text/css" href="/style.css" />
<link rel="stylesheet" type="text/css" href="/menu.css" />
<title>Walker Automotive UK</title>

<script type="text/javascript" language="JavaScript" src="/menu.js"></script>

</head>
<body>

<?php

$str_SQL = "SELECT Make, Model, HomepageSeq FROM CarMain ORDER BY HomepageSeq";
$query_result = mysql_query($str_SQL);
check_sql_error($str_SQL);

print "<table><tr><td>\n";
print "<table class=\"grid\">\n";
print "<th></th><th>Homepage Sequence</th>\n";
while($row = mysql_fetch_array($query_result, MYSQL_ASSOC))
{
	print "<tr><td>".$row['HomepageSeq']."</td>";
	print "<td>".$row['Make']." ".$row['Model']."</td></tr>\n";
}
print "</table>\n";
print "</td>\n";

$str_SQL = "SELECT Make, Model, ShowroomSeq FROM CarMain ORDER BY ShowroomSeq";
$query_result = mysql_query($str_SQL);
check_sql_error($str_SQL);

print "<td>";
print "<table class=\"grid\">\n";
print "<th></th><th>Showroom Sequence</th>\n";
while($row = mysql_fetch_array($query_result, MYSQL_ASSOC))
{
	print "<tr><td>".$row['ShowroomSeq']."</td>";
	print "<td>".$row['Make']." ".$row['Model']."</td></tr>\n";
}
print "</table>\n";
print "</td></tr></table>\n";
?>
<input type="button" value="Close" onClick="self.close()" />


</body>
</html>