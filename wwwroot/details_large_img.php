<?php
require_once($_SERVER['DOCUMENT_ROOT'].'/page_initialize.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/common_functions.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/db_open.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/db_functions.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/write_details_functions.php');

if(isset($_GET['CarID']) && isset($_GET['ImgID']))
{
	$car_id = $_GET['CarID'];
	$img_id = $_GET['ImgID'];
}
else
	load_page('/showroom.php');
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<link rel="stylesheet" type="text/css" href="/style.css" />
<title>Walker Automotive UK</title>

</head>
<body>

<div class="heavy">
<a href="JavaScript:history.back()"><< Back to car details</a>
</div>
<?php
$img_web_path = "/car_img/$car_id/";

$str_SQL = "SELECT LargeImgFileName FROM CarImg WHERE CarID = $car_id AND ImgID = $img_id";
$query_result = mysql_query($str_SQL);
check_sql_error($str_SQL);

$img_name = mysql_result($query_result,0,0);
if($img_name != null)
	print "<img src=\"$img_web_path$img_name\" />\n";
?>

</body>
</html>