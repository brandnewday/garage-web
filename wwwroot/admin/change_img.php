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
<title>Walker Automotive UK</title>
<script type="text/javascript">//<![CDATA[
function deleteImg(ImgID)
{
	var frm = document.getElementById("frm_DelImg");
	frm.ImgID.value = ImgID;
	frm.submit();
}
function endEditWindow()
{
	if(window.opener && !window.opener.closed)
	{
		window.opener.location.reload(true);
		self.close();
	}
}
//]]></script>
</head>
<body>

<form id="frm_UploadImg" action="change_img_proc.php" method="POST" enctype="multipart/form-data">
	Upload <input name="ImgName" type="file" />
	<input type="hidden" name="cmd" value="n" />
	<input type="hidden" name="CarID" value="<?php echo $car_id; ?>" />
	<input type="submit" value="Upload" />
	<input type="button" value="Done" onClick="endEditWindow()" />
</form>

<form id="frm_DelImg" action="change_img_proc.php" method="POST">
	<input type="hidden" name="ImgID" />
	<input type="hidden" name="cmd" value="d" />
	<input type="hidden" name="CarID" value="<?php echo $car_id; ?>" />
<?php
////////// define var //////////

$img_web_path = "/car_img/$car_id/";


////////// DB action //////////

$str_SQL = "SELECT ImgID, FileName, LargeImgFileName
			FROM CarImg WHERE CarID = $car_id";
$query_result = mysql_query($str_SQL);
check_sql_error($str_SQL);

////////// output //////////

print "<table class=\"grid\">\n";
$col = 0;

while($row = mysql_fetch_array($query_result,MYSQL_ASSOC))
{
	// 1st col, start row
	if($col % 5 == 0)
		print "<tr>\n";

	print "<td class=\"grid\"><img width=\"160\" height=\"120\" src=\"".$img_web_path.$row['FileName']."\" />\n";
	print "<br />".$row['FileName']." ";
	print "<a href=\"JavaScript:deleteImg(".$row['ImgID'].")\">(Delete)</a>";
	print "<br />Large Image: <a href=\"change_img_large.php?CarID=$car_id&ImgID=".$row['ImgID']."\">";
	if($row['LargeImgFileName'] == null)
		print "Upload";
	else
		print "Change";
	print "</a></td>\n";

	// next col
	++$col;

	// will the next col be in next row?
	if($col % 5 == 0)
		print "</tr>\n";
}
// the final col does not complete a whole row, so close it
if($col % 5 != 0)
	print "</tr>\n";

print "</table>\n";

?>
</form>

</body>
</head>
