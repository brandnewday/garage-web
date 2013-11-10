<?php
require_once("../page_initialize.php");
require_once('../common_functions.php');
require_once("../db_open.php");
require_once("../db_functions.php");

if($admin_mode != 1 || !isset($_POST['CarID']))
{
	load_homepage();
}

$car_id = $_POST['CarID'];

////////// POST var //////////

$make = $_POST["Make"];
$year = $_POST["Year"];
$model = $_POST["Model"];
$colour = $_POST["Colour"];
$type_id = $_POST["CarType"];
$num_doors = $_POST["NumDoors"];
$gearbox = $_POST["Gearbox"];
$num_owners = $_POST["NumOwners"];
$mileage = $_POST["Mileage"];
$showroom_seq = $_POST["ShowroomSeq"];
$homepage_seq = $_POST["HomepageSeq"];
$engine_cap = $_POST["EngineCap"];
$price = $_POST["Price"];
$status = $_POST["Status"];
// save an extra copy, we need it later
$img_name = $_FILES["ShowroomImg"]["name"];
$img_tmp = $_FILES["ShowroomImg"]["tmp_name"];

////////// define var //////////

$img_path = $_SERVER['DOCUMENT_ROOT']."/car_img/$car_id";

////////// check inputs //////////

// mandatory fields

if(trim($make) == '')
	exit('The field "Make" cannot be empty. Please try again.');
else if(trim($model) == '')
	exit('The field "Model" cannot be empty. Please try again.');
else if(trim($colour) == '')
	exit('The field "Colour" cannot be empty. Please try again.');
else if(trim($year) == '')
	exit('The field "Year" cannot be empty. Please try again.');
else if(trim($engine_cap) == '')
	exit('The field "Engine Capacity" cannot be empty. Please try again.');

// mandatory numeric fields

if(!is_numeric($num_doors))
	exit('The field "Number of Doors" must be a number. Please try again.');
else if(!is_numeric($mileage))
	exit('The field "Mileage" must be a number. Please try again.');
else if(!is_numeric($price))
	exit('The field "Price" must be a number. Please try again.');

// optional numeric field

if(trim($num_owners) != '' && !is_numeric($num_owners))
	exit('The field "Number of Owners" must be a number. Please try again.');
else if(trim($showroom_seq) != '' && !is_numeric($showroom_seq))
	exit('The field "Showroom Seq No" must be a number. Please try again.');
else if(trim($homepage_seq) != '' && !is_numeric($homepage_seq))
	exit('The field "Homepage Seq No" must be a number. Please try again.');

// the only special case: if img field empty, means NOT changing img

if(trim($img_name) == '')
	$do_update_img = 0;

////////// get original info for showroom img //////////

$str_SQL = "SELECT ShowroomImgName FROM CarMain WHERE CarID = $car_id";
$query_result = mysql_query($str_SQL);
check_sql_error($str_SQL);

$orig_img_name = mysql_result($query_result,0,0);

////////// update new showroom img //////////

if($do_update_img == 1)
{
	require_once('change_img_functions.php');
	$img_dest_name = upload_no_overwrite($img_name, $img_tmp, $img_path);
}
else
{
	$img_dest_name = $orig_img_name;
}

////////// format values as SQL string //////////

if(get_magic_quotes_gpc() == 0)
{
	$make = addslashes($make);
	$model = addslashes($model);
	$colour = addslashes($colour);
	$year = addslashes($year);
	$img_dest_name = addslashes($img_dest_name);
	$gearbox = addslashes($gearbox);
	$engine_cap = addslashes($engine_cap);
	$status = addslashes($status);
}
$make = "'$make'";
$model = "'$model'";
$year = "'$year'";
$colour = "'$colour'";
$img_dest_name = "'$img_dest_name'";
$gearbox = "'$gearbox'";
$engine_cap = "'$engine_cap'";
if($showroom_seq == '') $showroom_seq = "NULL";
if($homepage_seq == '') $homepage_seq = "NULL";
if($num_owners == '') $num_owners = "NULL";
if($status == '') $status = "NULL"; else $status = "'$status'";

////////// update DB //////////

$str_SQL = "UPDATE CarMain
	SET	Make = $make,
		Model = $model,
		CarTypeID = $type_id,
		Year = $year,
		Colour = $colour,
		Price = $price,
		Status = $status,
		ShowroomSeq = $showroom_seq,
		HomepageSeq = $homepage_seq,
		ShowroomImgName = $img_dest_name,
		NumDoors = $num_doors,
		Gearbox = $gearbox,
		NumOwners = $num_owners,
		Mileage = $mileage,
		EngineCapacity = $engine_cap
	WHERE CarID = $car_id"; 
mysql_query($str_SQL);
// if SQL failed, roll back change
// (UPDATE will rollback itself, but have to manually remove uploaded img)
if(check_sql_error_r($str_SQL) != 0
	&& $do_update_img == 1)
{	
	if(unlink($img_dest_name))
		log_msg("Rollback: Removed $img_dest_name");
	else
		log_msg("Error: unlink($img_dest_name) failed.");

	exit("Error when updating database. Please retry. If failed after 2 attempts, please contact Web Admin");
}

////////// all success //////////

// remove old img
if($do_update_img == 1)
{
	chdir($img_path);
	unlink($orig_img_name);
}

load_page("/admin/edit_details.php?CarID=$car_id");
?>
