<?php
require_once("../page_initialize.php");
require_once("../common_functions.php");
require_once("../db_open.php");
require_once("../db_functions.php");

if($admin_mode != 1 || !isset($_GET['CarID']))
	load_homepage();

$car_id = $_GET['CarID'];

////////// define var //////////

$img_path = "../car_img";

////////// DB action //////////

require_once("../db_open.php");
require_once("../db_functions.php");

$str_SQL = "DELETE FROM CarMain WHERE CarID = $car_id";
mysql_query($str_SQL);
check_sql_error($str_SQL);

$str_SQL = "DELETE FROM CarImg WHERE CarID = $car_id";
mysql_query($str_SQL);
check_sql_error($str_SQL);

$str_SQL = "DELETE FROM DetailLayout WHERE CarID = $car_id";
mysql_query($str_SQL);
check_sql_error($str_SQL);

$str_SQL = "DELETE FROM CarDesc WHERE CarID = $car_id";
mysql_query($str_SQL);
check_sql_error($str_SQL);

$str_SQL = "DELETE FROM Stats WHERE CarID = $car_id";
mysql_query($str_SQL);
check_sql_error($str_SQL);

////////// remove files //////////

if(is_dir($img_path."/$car_id"))
{
	foreach(glob($img_path."/$car_id/*") as $file_name)
	{
		unlink($file_name);
	}
	chdir($img_path);
	rmdir($car_id);
}

load_page('/admin/index.php');
?>
