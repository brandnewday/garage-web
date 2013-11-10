<?php
require_once("../page_initialize.php");
require_once("../common_functions.php");
require_once("../db_open.php");
require_once("../db_functions.php");

if($admin_mode != 1)
{
	load_homepage();
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<link rel="stylesheet" type="text/css" href="/style.css" />
<title>Walker Automotive UK</title>
</head>
<body>

<form method="POST">
<textarea name="sql" cols="30" rows="4"></textarea>
<input type="submit" value="Submit" />
</form>

<?php
if(isset($_POST['sql']))
{
	$sqls = explode(';',$_POST['sql']);

	while($str_SQL = array_shift($sqls))
	{
		// assume the input sql is ALREADY well-escaped
		if(get_magic_quotes_gpc() == 0)
			;
		// if system is auto-addslash, take them out (or we can't input strings!)
		else
			$str_SQL = stripslashes($str_SQL);
		print $str_SQL;

		$query_result = mysql_query($str_SQL);
		check_sql_error($str_SQL);


		if(mysql_num_rows($query_result) > 0)
		{
			$i = 0;
			print "<table class=\"grid\">\n";
			while($i < mysql_num_fields($query_result))
			{
				$field = mysql_fetch_field($query_result, $i);
				print "<th>".$field->name."</th>\n";
				++$i;
			}

			while($row = mysql_fetch_array($query_result, MYSQL_ASSOC))
			{
				print "<tr>\n";
				while(list($k,$v) = each($row))
					print "<td>".$v."</td>\n";
				print "</tr>\n";
			}
			print "</table>\n";
		}
		else
		{
			print "Affected rows: ".mysql_affected_rows($query_result)."<br />\n";
		}
	}
}

?>

</body>
</html>
