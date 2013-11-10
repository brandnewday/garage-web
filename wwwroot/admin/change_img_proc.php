<?php
require_once("../page_initialize.php");
require_once('../common_functions.php');
require_once("../db_open.php");
require_once("../db_functions.php");

if($admin_mode != 1 || !isset($_POST['CarID']) || !isset($_POST['cmd']))
{
	load_homepage();
}

////////// POST var //////////

$car_id = $_POST["CarID"];
$cmd = $_POST["cmd"];

////////// define var //////////

$img_path = $_SERVER['DOCUMENT_ROOT']."/car_img/$car_id";

////////// delegate //////////

if($cmd == 'd') // delete
{
	if(!isset($_POST['ImgID']))
		exit("Error: ImgID not avaialble. Please try again.");

	$img_id = $_POST['ImgID'];
	delete_img($img_id);
}
else if($cmd == 'n') // upload new img
{
	if(!isset($_FILES['ImgName']['name']) || trim($_FILES['ImgName']['name']) == '')
		exit("Error: Upload file not selected. Please try again.");

	$img_name = $_FILES['ImgName']['name'];
	$img_tmp = $_FILES['ImgName']['tmp_name'];
	upload_img($img_name, $img_tmp);
}
else if($cmd == 'l') // upload large img
{
	if(!isset($_POST['ImgID']))
		exit("Error: ImgID not avaialble. Please try again.");
	if(!isset($_FILES['ImgName']['name']) || trim($_FILES['ImgName']['name']) == '')
		exit("Error: Upload file not selected. Please try again.");

	$img_id = $_POST['ImgID'];
	$img_name = $_FILES['ImgName']['name'];
	$img_tmp = $_FILES['ImgName']['tmp_name'];
	upload_large_img($img_name, $img_tmp, $img_id);
}
else if($cmd == 'r') // delete large img
{
	if(!isset($_POST['ImgID']))
		exit("Error: ImgID not avaialble. Please try again.");

	$img_id = $_POST['ImgID'];
	delete_large_img($img_id);
}

////////// functions //////////

function delete_img($img_id)
{
	global $car_id;
	global $img_path;

	// get img info

	$str_SQL = "SELECT FileName, LayoutID FROM CarImg WHERE ImgID = $img_id AND CarID = $car_id";
	$query_result = mysql_query($str_SQL);
	check_sql_error($str_SQL);

	$img_info = mysql_fetch_array($query_result, MYSQL_ASSOC);

	// delete its layout info

	$str_SQL = "DELETE FROM DetailLayout WHERE CarID = $car_id AND ObjectID = ".$img_info['LayoutID'];
	$query_result = mysql_query($str_SQL);
	check_sql_error($str_SQL);

	// delete the file

	chdir($img_path);
	if(!unlink($img_info['FileName']))
		exit("Error: Could not remove file ".$img_info['FileName']." in $img_path. Please contact Web Admin");

	// delete from img db

	$str_SQL = "DELETE FROM CarImg WHERE ImgID = $img_id AND CarID = $car_id";
	$query_result = mysql_query($str_SQL);
	check_sql_error($str_SQL);
}

function upload_img($img_name, $img_tmp)
{
	global $car_id;
	global $img_path;

	require_once('change_img_functions.php');
	$img_dest_name = upload_no_overwrite($img_name, $img_tmp, $img_path);

	// get new img id and layout id for img

	$img_id = get_next_id_for_table($car_id,"ImgID","CarImg");
	$layout_id = get_next_id_for_table($car_id,"ObjectID","DetailLayout");

	// input img to db

	if(get_magic_quotes_gpc() == 0)
		$img_dest_name = addslashes($img_dest_name);
	$str_SQL = "INSERT INTO CarImg (CarID, ImgID, FileName, LayoutID)
		VALUES ($car_id, $img_id, '$img_dest_name', $layout_id)";
	mysql_query($str_SQL);
	check_sql_error($str_SQL);

	// store the layout

	$str_SQL = "INSERT INTO DetailLayout
		(CarID, ObjectID, ObjectType, LeftPx, TopPx, ZIdx)
		VALUES ($car_id, $layout_id, 'img', 150,150,10)";
	mysql_query($str_SQL);
	check_sql_error($str_SQL);
}

function upload_large_img($img_name, $img_tmp, $img_id)
{
	global $car_id;
	global $img_path;

	// get info for original img (if exists)

	$str_SQL = "SELECT LargeImgFileName FROM CarImg WHERE CarID = $car_id AND ImgID = $img_id";
	$query_result = mysql_query($str_SQL);
	check_sql_error($str_SQL);

	if(mysql_result($query_result,0,0) == null)
		$is_new = 1;
	else
	{
		$is_new = 0;
		$orig_img_name = mysql_result($query_result,0,0);
	}

	// upload img

	require_once('change_img_functions.php');
	$img_dest_name = upload_no_overwrite($img_name, $img_tmp, $img_path);

	// put img info to db

	if(get_magic_quotes_gpc() == 0)
		$img_dest_name = addslashes($img_dest_name);
	$str_SQL = "UPDATE CarImg SET LargeImgFileName = '$img_dest_name'
				WHERE CarID = $car_id AND ImgID = $img_id";
	mysql_query($str_SQL);
	check_sql_error($str_SQL);

	if($is_new)
	{
		chdir($img_path);
		unlink($orig_img_name);
	}
}

function delete_large_img($img_id)
{
	global $car_id;
	global $img_path;

	// get img info

	$str_SQL = "SELECT LargeImgFileName FROM CarImg WHERE ImgID = $img_id AND CarID = $car_id";
	$query_result = mysql_query($str_SQL);
	check_sql_error($str_SQL);

	$img_info = mysql_fetch_array($query_result, MYSQL_ASSOC);

	// delete the file

	chdir($img_path);
	if(!unlink($img_info['LargeImgFileName']))
		exit("Error: Could not remove file ".$img_info['LargeImgFileName']." in $img_path. Please contact Web Admin");

	// remove from img db

	$str_SQL = "UPDATE CarImg SET LargeImgFileName = NULL WHERE CarID = $car_id AND ImgID = $img_id";
	$query_result = mysql_query($str_SQL);
	check_sql_error($str_SQL);
}

////////// load next page //////////

load_page("/admin/change_img.php?CarID=$car_id");
?>
