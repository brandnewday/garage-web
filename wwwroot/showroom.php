<?php
require_once('page_initialize.php');
require_once('db_open.php');
require_once('db_functions.php');
require_once('common_functions.php');

// default is not selected (i.e. display all)
$car_type_id = 0;

if(isset($_GET['CarTypeID']))
	$car_type_id = $_GET['CarTypeID'];

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

<?
////////// DB action //////////

$str_SQL = "SELECT CarID, Make, Model, Year, Colour, Status, Price, ShowroomImgName
			FROM CarMain";
if($car_type_id != 0) $str_SQL = $str_SQL . " WHERE CarTypeID = $car_type_id";
$str_SQL = $str_SQL . " ORDER BY ShowroomSeq";
$query_result = mysql_query($str_SQL);
check_sql_error($str_SQL);

////////// output //////////

$img_web_path = "/car_img";

$col = 0;
print "<table>\n";
while($row = mysql_fetch_array($query_result, MYSQL_ASSOC))
{
	if($col % 2 == 0) print "<tr>\n";
	print "	<td>";
	print "	<div class=\"bluetagborder\">";
	print "		<table class=\"car\"><tr>\n";
	print "			<td>";
	print "				<img src=\"".$img_web_path."/".$row['CarID']."/".$row['ShowroomImgName']."\" width=\"160\" height=\"120\" />";
	print "			</td>\n";
	print "			<td class=\"details\">\n";
	print "				<table class=\"spec\">\n";
	print "					<tr>\n";
	print "						<td class=\"key\">Make & Model</td>\n";
	print "						<td>".$row['Make']." ".$row['Model']."</td>\n";
	print "					</tr>\n";
	print "					<tr>\n";
	print "						<td class=\"key\">Year & Colour</td>\n";
	print "						<td>".$row['Year']." ".$row['Colour']."</td>\n";
	print "					</tr>\n";
	print "					<tr>\n";
	print "						<td class=\"key\">Status</td>\n";
	print "						<td>".$row['Status']."</td>\n";
	print "					</tr>\n";
	print "					<tr>\n";
	print "						<td class=\"key\">Price</td>\n";
	print "						<td>£".$row['Price']."</td>\n";
	print "					</tr>\n";
	print "				</table>\n";
	print "				<div class=\"heavy\"><a href=\"details.php?CarID=".$row['CarID']."\">View</a></div>\n";
	print "			</td>\n";
	print "		</tr></table>\n";
	print "	</div>\n";
	print "	</td>\n";
	if($col % 2 == 1) print "</tr>\n";

	++$col;
}
print "</table>\n";
?>

<?php
require_once('footer.php');
?>

</body>
</html>