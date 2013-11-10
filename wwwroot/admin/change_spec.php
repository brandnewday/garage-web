<?php
require_once("../page_initialize.php");
require_once("../common_functions.php");
require_once("../db_open.php");
require_once("../db_functions.php");

if($admin_mode != 1 || !isset($_GET['CarID']))
{
	load_homepage();
}

$car_id = $_GET['CarID'];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<link rel="stylesheet" type="text/css" href="/style.css" />
<link rel="stylesheet" type="text/css" href="/menu.css" />
<title>Walker Automotive UK</title>
<script language="JavaScript" src="/menu.js"></script>
<script language="JavaScript">

</script>
</head>
<body>

<?php
require_once("../menu.php");
?>

<?php
$web_img_path = "/car_img/$car_id";

////////// DB action //////////

$str_SQL = "SELECT CarTypeID, CarTypeName FROM CarType ORDER BY CarTypeName";
$car_types = mysql_query($str_SQL);
check_sql_error($str_SQL);

$str_SQL = "SELECT Make, Model, CarTypeID, Year, Colour, Price, Status, ShowroomSeq, HomepageSeq, ShowroomImgName, NumDoors, Gearbox, NumOwners, Mileage, EngineCapacity
			FROM CarMain WHERE CarID = $car_id";
$query_result = mysql_query($str_SQL);
check_sql_error($str_SQL);
$car_info = mysql_fetch_array($query_result, MYSQL_ASSOC);

?>

<form action="change_spec_proc.php" method="POST" enctype="multipart/form-data">
<table>
<tr>
	<td class="key">
		Make:*
	</td>
	<td>
		<input name="Make" type="text" value="<?php echo $car_info['Make']; ?>" />
	</td>
	<td class="key">
		Year of Manufacture:*
	</td>
	<td>
		<input name="Year" type="text" value="<?php echo $car_info['Year']; ?>" />
	</td>
</tr>
<tr>
	<td class="key">
		Model:*
	</td>
	<td>
		<input name="Model" type="text" value="<?php echo $car_info['Model']; ?>" />
	</td>
	<td class="key">
		Colour:*
	</td>
	<td>
		<input name="Colour" type="text" value="<?php echo $car_info['Colour']; ?>"/>
	</td>
</tr>
<tr>
	<td class="key">
		Type:*
	</td>
	<td>
		<select name="CarType">
		<?php
		////////// output //////////

		while($row = mysql_fetch_row($car_types))
		{
			print "<option value=\"$row[0]\"";
			if($row[0] == $car_info['CarTypeID']) print " selected";
			print ">$row[1]</option>\n";
		}
		?>
		</select>
	</td>
	<td class="key">
		Number of Doors:*
	</td>
	<td>
		<input name="NumDoors" type="text" value="<?php echo $car_info['NumDoors']; ?>"/>
	</td>
</tr>
<tr>
	<td class="key">
		Transmission:*
	</td>
	<td>
		<select name="Gearbox">
			<option value="Manual" selected>Manual</option>
			<option value="Automatic">Automatic</option>
			<option value="Tiptronic">Tiptronic</option>
		</select>
	</td>
	<td class="key">
		Number of Owners:
	</td>
	<td>
		<input name="NumOwners" type="text" value="<?php echo $car_info['NumOwners']; ?>" />
	</td>
</tr>
<tr>
	<td class="key">
		Mileage:*
	</td>
	<td>
		<input name="Mileage" type="text" value="<?php echo $car_info['Mileage']; ?>" />
	</td>
	<td class="key">
		Showroom Sequence No:
	</td>
	<td>
		<input name="ShowroomSeq" type="text" value="<?php echo $car_info['ShowroomSeq']; ?>" />
		<input type="button" onClick="openEditWindow('/admin/show_seq.php')" value="?" />
	</td>
</tr>
<tr>
	<td>
		&nbsp;
	</td>
	<td>
		&nbsp;
	</td>
	<td class="key">
		Home Page Sequence No:
	</td>
	<td>
		<input name="HomepageSeq" type="text" value="<?php echo $car_info['HomepageSeq']; ?>" />
		<input type="button" onClick="openEditWindow('/admin/show_seq.php')" value="?" />
	</td>
</tr>
<tr>
	<td class="key">
		Engine Capacity:*
	</td>
	<td>
		<input name="EngineCap" type="text" value="<?php echo $car_info['EngineCapacity']; ?>" />
	</td>
	<td class="key">
		Price:*£
	</td>
	<td>
		<input name="Price" type="text" value="<?php echo $car_info['Price']; ?>" />
	</td>
</tr>
<tr>
	<td class="key">
		Status:
	</td>
	<td>
		<input name="Status" type="text" value="<?php echo $car_info['Status']; ?>" />
	</td>
	<td>
		&nbsp;
	</td>
	<td>
		&nbsp;
	</td>
</tr>
<tr>
	<td class="key">
		Showroom Image:*<br />
		(Size 360x270 only)
	</td>
	<td>
		<input name="ShowroomImg" type="file" />
	</td>
	<td colspan="2">
		<img src="<?php echo $web_img_path.'/'.$car_info['ShowroomImgName']; ?>" width="160" height="120" />
	</td>
</tr>
<tr>
	<td colspan="4">
		<input type="submit" value="Next >>" />
		<br />
		<input type="button" value="Cancel" onClick="self.location='edit_details.php?CarID=<?php echo $car_id; ?>'" />
	</td>
</tr>
</table>
<input type="hidden" name="CarID" value="<?php echo $car_id; ?>" />
</form>

<?php
require_once('../footer.php');
?>

</body>
</html>
