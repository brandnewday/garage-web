<?php
require_once("../page_initialize.php");
require_once('../common_functions.php');

if($admin_mode != 1)
{
	load_homepage();
}

////////// POST var //////////

$car_id = $_POST['CarID'];
$cmd = $_POST['cmd'];

////////// delegate //////////

require_once("../db_open.php");
require_once("../db_functions.php");

if($cmd == 'd')
{
	$par_num = $_POST['ParNum'];

	$str_SQL = "DELETE FROM CarDesc WHERE CarID = $car_id AND ParNum = $par_num";
	$query_result = mysql_query($str_SQL);
	check_sql_error($str_SQL);
}
else if($cmd == 'n')
{
	// assign POST vars

	$par_num = $_POST['ParNum'];
	if(get_magic_quotes_gpc() == 0) $desc_text = addslashes($_POST['DescText']);
	else $desc_text = $_POST['DescText'];
	$text_style = $_POST['LineStyle'] . ',' . $_POST['TextColour'];
	$width = $_POST['DescWidth'];

	// check if updating existing text

	$str_SQL = "SELECT 1 FROM CarDesc WHERE CarID = $car_id AND ParNum = $par_num";
	$query_result = mysql_query($str_SQL);
	check_sql_error($str_SQL);

	if(mysql_num_rows($query_result) > 0)
	{
		$str_SQL = "UPDATE CarDesc SET DescText = '$desc_text', TextStyle = '$text_style'
					WHERE CarID = $car_id AND ParNum = $par_num";
	}
	// new text
	else
	{
		$str_SQL = "INSERT INTO CarDesc (CarID, ParNum, DescText, TextStyle)
					VALUES ($car_id, $par_num, '$desc_text', '$text_style')";
	}
	$query_result = mysql_query($str_SQL);
	check_sql_error($str_SQL);

	// update layout info (width)

	$str_SQL = "SELECT DescLayoutID FROM CarMain WHERE CarID = $car_id";
	$query_result = mysql_query($str_SQL);
	check_sql_error($str_SQL);
	$layout_id = mysql_result($query_result, 0, 0);

	$str_SQL = "UPDATE DetailLayout SET Width = $width WHERE CarID = $car_id AND ObjectID = $layout_id";
	mysql_query($str_SQL);
	check_sql_error($str_SQL);
}
else if($cmd == 'c')
{
	$copy_from = $_POST['CopyCarID'];

	$str_SQL = "INSERT CarDesc
				SELECT $car_id, ParNum, DescText, TextStyle
				FROM CarDesc WHERE CarID = $copy_from";
	mysql_query($str_SQL);
	check_sql_error($str_SQL);
}

////////// go back to edit page //////////

load_page("/admin/change_desc.php?CarID=$car_id&TextStyle=$text_style");
?>

