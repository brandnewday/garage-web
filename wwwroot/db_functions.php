<?php
function check_sql_error_r($str_SQL)
{
	global $db;

	if(mysql_errno($db))
	{
		log_msg("Error: ".mysql_error($db)."<br />\nFrom query: ".$str_SQL);
		return 1;
	}
	else
		return 0;
}

function check_sql_error($str_SQL)
{
	global $db;

	if(mysql_errno($db))
	{
		exit("Error: ".mysql_error($db)."<br />\nFrom query: ".$str_SQL);
	}
}

function get_next_id($field_name, $table_name)
{
	$str_SQL = "SELECT MAX($field_name) FROM $table_name";
	$query_result = mysql_query($str_SQL);
	if(mysql_num_rows($query_result) == 1)
		$new_id = mysql_result($query_result, 0, 0) + 1;
	else
		$new_id = 1;

	return $new_id;
}

function get_next_id_for_table($car_id, $field_name, $table_name)
{
	$str_SQL = "SELECT MAX($field_name) FROM $table_name WHERE CarID = $car_id";
	$query_result = mysql_query($str_SQL);
	if(mysql_num_rows($query_result) == 1)
		$new_id = mysql_result($query_result, 0, 0) + 1;
	else
		$new_id = 1;

	return $new_id;
}

?>