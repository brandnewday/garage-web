<?php
require_once($_SERVER['DOCUMENT_ROOT'].'/page_initialize.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/common_functions.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/db_open.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/db_functions.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/write_details_functions.php');

if(isset($_GET['CarID']))
	$car_id = $_GET['CarID'];
else
	load_page('/showroom.php');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" style="border:0">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<link rel="stylesheet" type="text/css" href="/style.css" />
<title>Walker Automotive UK</title>

</head>
<body>

<div id="content">

<div class="print_view">

<?php
////////// define var //////////

$img_web_path = "/car_img/$car_id/";


////////// DB queries //////////

$str_SQL = "SELECT Make, Model, EngineCapacity, Gearbox, NumDoors, Colour, Mileage, NumOwners, Year, Price, Status, ShowroomImgName
			FROM CarMain WHERE CarID = $car_id";
$query_result = mysql_query($str_SQL);
check_sql_error($str_SQL);
$car_info = mysql_fetch_array($query_result, MYSQL_ASSOC);

$str_SQL = "SELECT ParNum, DescText, TextStyle FROM CarDesc WHERE CarID = $car_id";
$desc = mysql_query($str_SQL);
check_sql_error($str_SQL);

////////// write header, image, spec //////////

print "<img src=\"logo.gif\" width=\"547\" height=\"136\" />\n";
print "<div class=\"largetitle\">";
print $car_info['Make'].' '.$car_info['Model'];
print "</div>\n";
print "<div class=\"print_view_spec\">";
print "<table class=\"print_view_spec\"><tr>";
print "<td><img src=\"".$img_web_path.$car_info['ShowroomImgName']."\" /></td>\n";
print "<td>";
write_spec_table($car_info);
print "</td></tr></table>\n";
print "</div>\n";

////////// write header, image, spec //////////

print "<div class=\"print_view_desc\">";
write_desc_table($desc);
print "</div>\n";

?>
</div>

</div>

<?php
//require_once($_SERVER['DOCUMENT_ROOT'].'/print_footer.php');
?>
</body>
</html>
