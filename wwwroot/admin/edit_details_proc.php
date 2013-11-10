<?php
require_once("../page_initialize.php");
require_once('../common_functions.php');
require_once("../db_open.php");
require_once("../db_functions.php");

if($admin_mode != 1)
{
	load_homepage();
}

$car_id = $_POST["CarID"];

$str_SQL = "SELECT ObjectID FROM DetailLayout WHERE CarID = $car_id";
$query_result = mysql_query($str_SQL);
check_sql_error($str_SQL);

while($row = mysql_fetch_array($query_result, MYSQL_ASSOC))
{
	$input_id = "input_".$row['ObjectID'];
	if(isset($_POST[$input_id]))
	{
		list($left, $top, $zidx) = explode(",", $_POST[$input_id]);
		// remove the "px" suffix
		$left = substr($left, 0, strlen($left)-2);
		$top = substr($top, 0, strlen($top)-2);

		$str_SQL = "UPDATE DetailLayout 
					SET LeftPx = $left,
						TopPx = $top,
						ZIdx = $zidx
					WHERE CarID = $car_id
					AND ObjectID = ".$row['ObjectID'];
		mysql_query($str_SQL);
		check_sql_error($str_SQL);
	}
}

////////// load next page //////////

$host  = $_SERVER['HTTP_HOST'];
$uri  = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
$next_page = 'edit_details.php';
header("Location: http://$host$uri/$next_page?CarID=$car_id");

?>
