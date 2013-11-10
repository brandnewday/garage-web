<?php
require_once($_SERVER['DOCUMENT_ROOT'].'/page_initialize.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/common_functions.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/db_open.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/db_functions.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/write_details_functions.php');

if($admin_mode != 1)
{
	load_homepage();
}
if(isset($_GET['CarID']))
	$car_id = $_GET['CarID'];
else if(isset($_POST['CarID']))
	$car_id = $_POST['CarID'];
else
{
	load_homepage();
}

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<link rel="stylesheet" type="text/css" href="/style.css" />
<link rel="stylesheet" type="text/css" href="/menu.css" />
<title>Walker Automotive UK</title>

<script type="text/javascript" language="JavaScript" src="/menu.js"></script>
<script type="text/javascript" language="JavaScript" src="edit_details.js"></script>

</head>
<body>

<?php
require_once($_SERVER['DOCUMENT_ROOT'].'/menu.php');
?>

<form id="frm_save" method="POST" action="edit_details_proc.php">
<?php
////////// define var //////////

$img_web_path = "/car_img/$car_id/";

////////// write //////////

write_spec();
write_desc();
write_imgs();


////////// functions //////////

function str_js_layer($div_id)
{
	return  "MM_dragLayer('".$div_id."','',0,0,0,0,true,true,-1,-1,-1,-1,false,false,0,'',false,'')";
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
	return "<input id=\"input_$object_id\" name=\"input_$object_id\" type=\"hidden\" value=\"\" />\n";
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

	// A movable object is enclosed in a div, must have an attribute called 
	// "inputID", having the value = its ObjectID in the DB.
	// When user saves the layout, JS looks at all divs, and pick out the ones 
	// of interest using this attribute.
	// A movable object also has an associated <inupt>, for submitting the 
	// layout values. The <input> has both id and name attribs, former is for
	// identifying in JS, latter is necessary to make a valid PHP POST var.
	// When JS picks out a relevant div, it will also get the associated 
	// <input> (its id is always the inputID attrib prefixed by "input_").
	// Then, it stuffed the layout values as a comma-sep list into the value
	// of the <input>. These can then be easily extracted when the POST data
	// reach the processing PHP script.

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

	$str_SQL = "SELECT ImgID, FileName, ObjectID, LeftPx, TopPx, ZIdx
		FROM CarImg dp, DetailLayout dl
		WHERE dp.CarID = dl.CarID
		AND dp.LayoutID = dl.ObjectID
		AND dp.CarID = $car_id";
	$query_result = mysql_query($str_SQL);
	check_sql_error($str_SQL);

	while($row = mysql_fetch_array($query_result, MYSQL_ASSOC))
	{
		// copy the elements relevant to layout into layout_info
		$layout_info = array_slice($row, 2);
		$object_id = $layout_info['ObjectID'];
		$div_id = "Img_".$object_id;
		print "<div id=\"$div_id\" style=\"".str_style($layout_info)."\" onMouseDown=\"".str_js_layer($div_id)."\" inputID=\"$object_id\">\n";
		print str_input_tag($object_id);
		print "<img src=\"$img_web_path".$row['FileName']."\" />\n";
		print "</div>\n";
	}
}
?>

<input name="CarID" type="hidden" value="<?php echo $car_id; ?>" />
<div class="contenttopright">
	<input name="save" type="button" onClick="savePos()" value="Save Layout" />
</div>
</form>

</body>
</html>