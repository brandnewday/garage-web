<?php
require_once("../page_initialize.php");
require_once('../common_functions.php');
require_once("../db_open.php");
require_once("../db_functions.php");

if($admin_mode != 1)
{
	load_homepage();
}

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
$showroom_img_name_f = $showroom_img_name = $_FILES["ShowroomImg"]["name"];
$showroom_img_tmp = $_FILES["ShowroomImg"]["tmp_name"];

////////// define var //////////

$img_path = $_SERVER['DOCUMENT_ROOT'].'/car_img';
$car_id = -1;

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
else if(trim($showroom_img_name) == '')
	exit('The field "Showroom Image" cannot be empty. Please try again.');
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

// get new car id

$car_id = get_next_id("CarID","CarMain");

////////// create img dir //////////

// deal with upload img first, as this part often fails
// create the img dir for that car

chdir($img_path);
if(file_exists($car_id))
{
	$lmsg = "Error: Directory for new car id ($car_id) already exists.";
	$dmsg = "Error uploading file. Please retry. If failed after 2 attempts, please contact Web Admin";
	rollback_add_car($lmsg, $dmsg);
}
else
{
	if(mkdir($car_id))
		;
	else
	{
		$lmsg = "Error: Cannot make directory $car_id in $img_path\n";
		$dmsg = "Error uploading file. Please retry. If failed after 2 attempts, please contact Web Admin";
		rollback_add_car($lmsg, $dmsg);
	}
}

////////// put showroom img //////////

chdir($car_id);
if(move_uploaded_file($showroom_img_tmp, $showroom_img_name))
{
	if(file_exists($showroom_img_name))
		// this is the only SUCCESSFUL branch 
		log_msg("Debug: File moved from $showroom_img_tmp to $showroom_img_name");
	else
	{
		$lmsg = "Error: move_uploaded_file() succeeded, but $showroom_img_name does not exist.";
		$dmsg = "Error uploading file. Please retry. If failed after 2 attempts, please contact Web Admin";
		rollback_add_car($lmsg, $dmsg);
	}
}
else
{
	$lmsg = "Error: move_uploaded_file() failed, on $showroom_img_tmp to $showroom_img_name";
	$dmsg = "Error uploading file. Please retry. If failed after 2 attempts, please contact Web Admin";
	rollback_add_car($lmsg, $dmsg);
}

////////// add entry to layout //////////

$spec_left_px_def = 150;
$spec_top_px_def = 150;
$spec_z_idx_def = 10;
$desc_left_px_def = 150;
$desc_top_px_def = 200;
$desc_z_idx_def = 5;
$desc_width_def = 600;

$spec_layout_id = get_next_id_for_table($car_id,"ObjectID","DetailLayout");

$str_SQL = "INSERT INTO DetailLayout
	(CarID, ObjectID, ObjectType, LeftPx, TopPx, ZIdx)
	VALUES ($car_id, $spec_layout_id, 'spec', $spec_left_px_def, $spec_top_px_def, $spec_z_idx_def)";
mysql_query($str_SQL);
if(check_sql_error_r($str_SQL) != 0)
{
	$dmsg = "Error when updating database. Please retry. If failed after 2 attempts, please contact Web Admin";
	rollback_add_car("", $dmsg);
}

$desc_layout_id = get_next_id_for_table($car_id,"ObjectID","DetailLayout");

$str_SQL = "INSERT INTO DetailLayout
	(CarID, ObjectID, ObjectType, LeftPx, TopPx, ZIdx, Width)
	VALUES ($car_id, $desc_layout_id, 'desc', $desc_left_px_def, $desc_top_px_def, $desc_z_idx_def, $desc_width_def)";
mysql_query($str_SQL);
if(check_sql_error_r($str_SQL) != 0)
{
	$dmsg = "Error when updating database. Please retry. If failed after 2 attempts, please contact Web Admin";
	rollback_add_car("", $dmsg);
}

////////// format values for SQL string //////////

if(get_magic_quotes_gpc() == 0)
{
	$make = addslashes($make);
	$model = addslashes($model);
	$colour = addslashes($colour);
	$year = addslashes($year);
	$showroom_img_name = addslashes($showroom_img_name);
	$gearbox = addslashes($gearbox);
	$engine_cap = addslashes($engine_cap);
	$status = addslashes($status);
}
$make = "'$make'";
$model = "'$model'";
$year = "'$year'";
$colour = "'$colour'";
$showroom_img_name = "'$showroom_img_name'";
$gearbox = "'$gearbox'";
$engine_cap = "'$engine_cap'";
if($showroom_seq == '') $showroom_seq = "NULL";
if($homepage_seq == '') $homepage_seq = "NULL";
if($num_owners == '') $num_owners = "NULL";
if($status == '') $status = "NULL"; else $status = "'$status'";


////////// input new car spec //////////

$str_SQL = "INSERT INTO CarMain
	(CarID,Make,Model,CarTypeID,Year,Colour,Price,Status,ShowroomSeq,HomepageSeq,ShowroomImgName,NumDoors,Gearbox,NumOwners,Mileage,EngineCapacity,SpecLayoutID,DescLayoutID) 
	VALUES ($car_id, $make, $model, $type_id, $year, $colour, $price, $status, $showroom_seq, $homepage_seq, $showroom_img_name, $num_doors, $gearbox, $num_owners, $mileage, $engine_cap, $spec_layout_id, $desc_layout_id)";
mysql_query($str_SQL);
if(check_sql_error_r($str_SQL) != 0)
{
	$dmsg = "Error when updating database. Please retry. If failed after 2 attempts, please contact Web Admin";
	rollback_add_car("", $dmsg);
}

load_page("/admin/edit_details.php?CarID=$car_id");

////////// roll back //////////

// if any error, remove any db INSERT, any new dir, new file

function rollback_add_car($lmsg, $dmsg)
{
	global $img_path;
	global $car_id;
	global $showroom_img_name;

	log_msg($lmsg);

	// roll back db

	$str_SQL = "DELETE FROM DetailLayout WHERE CarID = $car_id";
	mysql_query($str_SQL);
	check_sql_error($str_SQL);

	$str_SQL = "DELETE FROM CarMain WHERE CarID = $car_id";
	mysql_query($str_SQL);
	check_sql_error($str_SQL);

	// del new dir and new file

	chdir($img_path);
	if(is_dir($car_id))
	{
		chdir($car_id);
		if(is_file($showroom_img_name))
		{
			if(unlink($showroom_img_name))
				log_msg("Rollback: Removed $showroom_img_name");
			else
				log_msg("Error: unlink($showroom_img_name) failed.");
		}
		else
			log_msg("Error: is_file($showroom_img_name) failed.");

		chdir("..");
		if(rmdir($car_id))
			log_msg("Rollback: Removed directory $car_id");
		else
			log_msg("Error: rmdir($car_id) failed.");
	}
	else
		log_msg("Error: chdir($img_path/$car_id) failed.");

	exit($dmsg);
}
?>
