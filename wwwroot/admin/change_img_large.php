<?php
require_once("../page_initialize.php");
require_once("../common_functions.php");
require_once("../db_open.php");
require_once("../db_functions.php");

if($admin_mode != 1 || !isset($_GET['CarID']) || !isset($_GET['ImgID']))
{
	load_homepage();
}

$car_id = $_GET['CarID'];
$img_id = $_GET['ImgID'];
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
	var frm = document.getElementById("frm_LargeImg");
	frm.cmd.value = "r";
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

Change / Upload Full Scale Image
<form id="frm_LargeImg" action="change_img_proc.php" method="POST" enctype="multipart/form-data">
	<input name="ImgName" type="file" />
	<input type="hidden" name="cmd" value="l" />
	<input type="hidden" name="CarID" value="<?php echo $car_id; ?>" />
	<input type="hidden" name="ImgID" value="<?php echo $img_id; ?>" />
	<input type="submit" value="Upload" />
	<input type="button" value="Back" onClick="self.location='change_img.php?CarID=<?php echo $car_id; ?>'" />
</form>
<?php
$img_web_path = "/car_img/$car_id/";

$str_SQL = "SELECT LargeImgFileName FROM CarImg
			WHERE CarID = $car_id AND ImgID = $img_id";
$query_result = mysql_query($str_SQL);
check_sql_error($str_SQL);

if(mysql_result($query_result,0,0) != null)
{
	$large_img = mysql_result($query_result,0,0);
	print "Full Scale Image: $large_img ";
	print "<a href=\"JavaScript:deleteImg($img_id)\">(Delete)</a><br />";
	print "<img src=\"$img_web_path$large_img\" />";
}
?>

</body>
</html>