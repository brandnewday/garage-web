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

require_once('car_hit_tracker.php');
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<link rel="stylesheet" type="text/css" href="/style.css" />
<link rel="stylesheet" type="text/css" href="/menu.css" />
<title>Walker Automotive UK</title>

<script type="text/javascript" language="JavaScript" src="/menu.js"></script>

<script type="text/javascript" language="JavaScript">

function openImgWindow(carID, imgID)
{
//	window.open("details_large_img.php?CarID="+carID+"&ImgID="+imgID, "win_Img", "location=1,status=1,scrollbars=1,width=500,height=500,resizable=1");
	window.location = "details_large_img.php?CarID="+carID+"&ImgID="+imgID;
}

</script>
</head>
<body>

<?php
require_once($_SERVER['DOCUMENT_ROOT'].'/menu.php');
?>

<?php
////////// define var //////////

$img_web_path = "/car_img/$car_id/";

////////// write //////////

write_print();
write_spec();
write_desc();
write_imgs();


////////// functions //////////

function write_print()
{
	global $car_id;

	print "<div class=\"contenttopright\"><a href=\"print_view.php?CarID=$car_id\"><img src=\"print_icon.jpg\" /> PRINT</a></div>";
}

function str_js_layer($div_id)
{
	return "";
}

function str_style(&$layout_info)
{
	$str = "position:absolute; left: ".$layout_info["LeftPx"]."px; "
		."top: ".$layout_info["TopPx"]."px; padding:1em; "
		."z-index: ".$layout_info["ZIdx"]."; ";
	if(isset($layout_info["Width"])) $str = $str ."; width: ".$layout_info["Width"]."px;";
	if(isset($layout_info["Height"])) $str = $str ."; height: ".$layout_info["Height"]."px;";

	return $str;
}

function str_input_tag($object_id)
{
	return "";
}

function write_spec()
{
	global $car_id;

	$str_SQL = "SELECT SpecLayoutID, Make, Model, EngineCapacity, Gearbox, NumDoors, Colour, Mileage, NumOwners, Year, Price, Status 
		FROM CarMain WHERE CarID = $car_id";
	$query_result = mysql_query($str_SQL);
	check_sql_error($str_SQL);
	$car_info = mysql_fetch_array($query_result, MYSQL_ASSOC);

	$str_SQL = "SELECT ObjectID, LeftPx, TopPx, ZIdx 
		FROM DetailLayout dl 
		WHERE dl.CarID = $car_id 
		AND dl.ObjectID = ".$car_info['SpecLayoutID'];
	$query_result = mysql_query($str_SQL);
	check_sql_error($str_SQL);
	$layout_info = mysql_fetch_array($query_result, MYSQL_ASSOC);

	$object_id = $layout_info['ObjectID'];
	$div_id = "CarSpec_".$object_id;
	print "<div id=\"$div_id\" style=\"".str_style($layout_info)."\" onMouseDown=\"".str_js_layer($div_id)."\" inputID=\"$object_id\">\n";
	print str_input_tag($object_id);
	print "<div class=\"car_name\">".$car_info['Make']." ".$car_info['Model']."</div>\n";

	print "<div class=\"topleftborder\">\n";
	write_spec_table($car_info);
	print "</div>\n";
	print "</div>\n";
}

function write_desc()
{
	global $car_id;

	$str_SQL = "SELECT ObjectID, LeftPx, TopPx, ZIdx, Width
		FROM DetailLayout dl, CarMain cm
		WHERE cm.CarID = dl.CarID
		AND cm.DescLayoutID = dl.ObjectID
		AND cm.CarID = $car_id";
	$query_result = mysql_query($str_SQL);
	check_sql_error($str_SQL);
	$layout_info = mysql_fetch_array($query_result, MYSQL_ASSOC);

	$str_SQL = "SELECT DescText, TextStyle FROM CarDesc WHERE CarID = $car_id ORDER BY ParNum";
	$query_result = mysql_query($str_SQL);
	check_sql_error($str_SQL);

	$object_id = $layout_info['ObjectID'];
	$div_id = "CarDesc_".$object_id;
	print "<div id=\"$div_id\" style=\"".str_style($layout_info)."\" onMouseDown=\"".str_js_layer($div_id)."\" inputID=\"$object_id\">\n";
	print str_input_tag($object_id);
	write_desc_table($query_result);
	print "</div>\n";
}

function write_imgs()
{
	global $car_id;
	global $img_web_path;

	$str_SQL = "SELECT ImgID, FileName, LargeImgFileName, ObjectID, LeftPx, TopPx, ZIdx
		FROM CarImg dp, DetailLayout dl
		WHERE dp.CarID = dl.CarID
		AND dp.LayoutID = dl.ObjectID
		AND dp.CarID = $car_id";
	$query_result = mysql_query($str_SQL);
	check_sql_error($str_SQL);

	while($row = mysql_fetch_array($query_result, MYSQL_ASSOC))
	{
		// copy the elements relevant to layout into layout_info
		$layout_info = array_slice($row, 3);
		$object_id = $layout_info['ObjectID'];
		$div_id = "Img_".$object_id;
		// if full scale img exists, add the link
		if($row['LargeImgFileName'] != null)
		{
			print "<div id=\"$div_id\" style=\"".str_style($layout_info)." cursor:pointer;\" onMouseDown=\"openImgWindow($car_id,".$row['ImgID'].")\">\n";
			print "<div class=\"tooltip\">Click to enlarge</div>";
		}
		else
			print "<div id=\"$div_id\" style=\"".str_style($layout_info)."\">\n";
		print str_input_tag($object_id);
		print "<img src=\"$img_web_path".$row['FileName']."\" />\n";
		print "</div>\n";
	}
}
?>

</body>
</html>
