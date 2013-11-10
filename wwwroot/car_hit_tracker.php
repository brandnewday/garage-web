<?php
// pre-conditions:
// $car_id and $admin_mode set

if($admin_mode != 1)
{
	// in addition, can use HTTP_X_FORWARDED_FOR to get IP behind proxy (can't be bothered!);
	$ip = getenv("REMOTE_ADDR");

	// don't need to log my or webring-robots access!
	if(1)
	{
		$str_SQL = "SELECT CarID, Hit, LastIP, LastAccess
					FROM Stats
					WHERE CarID = $car_id";
		$query_result = mysql_query($str_SQL);
		check_sql_error($str_SQL);

		// this car has been visited before
		if(mysql_num_rows($query_result) > 0)
		{
			$row = mysql_fetch_array($query_result, MYSQL_ASSOC);

			// last access was NOT from the same IP, or IS more than 5 mins ago
			// this is to avoid counting mulitple access from same IP in a short time
			if($ip != $row['LastIP']
				|| time() - $row['LastAccess'] > 5)
			{
				$hit = $row['Hit'] + 1;
				$str_SQL = "UPDATE Stats SET Hit = $hit, LastIP = '$ip', LastAccess = ".time()." WHERE CarID = $car_id";
				mysql_query($str_SQL);
				check_sql_error($str_SQL);
			}
		}
		else
		{
			$str_SQL = "INSERT INTO Stats (CarID, Hit, LastIP, LastAccess)
						VALUES ($car_id, 1, '$ip', ".time().")";
			mysql_query($str_SQL);
			check_sql_error($str_SQL);
		}
	}
}
?>
