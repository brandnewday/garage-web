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
require_once("../menu.php");
?>

<div id="content">

<div class="bigbox">
<?php
$str_SQL = "SELECT cm.CarID, cm.Make, cm.Model, s.Hit 
			FROM CarMain cm
			LEFT JOIN Stats s
			ON cm.CarID = s.CarID
			ORDER BY s.Hit DESC";
$query_result = mysql_query($str_SQL);
check_sql_error($str_SQL);

print "<table class=\"biglist\">\n";
print "<th>Vehicle</th><th>Hits</th>\n";
while($row = mysql_fetch_array($query_result, MYSQL_ASSOC))
{
	print "<tr><td><a href=\"edit_details.php?CarID=".$row['CarID']."\">";
	print $row['Make']." ".$row['Model']."</a></td>";
	if($row['Hit'] == null)
		$hit = 0;
	else
		$hit = $row['Hit'];
	print "<td>".$hit."</td></tr>\n";
}
print "</table>\n";
?>
</div>

</div>

<?php
require_once('../footer.php');
?>
</body>
</html>
