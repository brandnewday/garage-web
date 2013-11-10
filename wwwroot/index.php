<?php
require_once('page_initialize.php');
require_once('db_open.php');
require_once('db_functions.php');
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
require_once("menu.php");
?>
<div id="content">

<div class="bigbox">
<?php
$str_SQL = "SELECT CarID, Year, Make, Model, Colour, Price FROM CarMain ORDER BY HomepageSeq";
$query_result = mysql_query($str_SQL);
check_sql_error($str_SQL);

print "<table class=\"biglist\">\n";
while($row = mysql_fetch_array($query_result, MYSQL_ASSOC))
{
	print "<tr><td>".$row['Year']."</td>";
	print "<td class=\"maincol\"><a href=\"details.php?CarID=".$row['CarID']."\">".$row['Make']." ".$row['Model'].", ".$row['Colour']."</a></td>";
	print "<td>£".$row['Price']."</td></tr>\n";
}
print "</table>\n";
?>
</div>

</div>

<?php
require_once('footer.php');
?>

</body>
</html>
