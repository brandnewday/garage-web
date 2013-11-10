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
$line_style = 'norm';
$text_colour = 'black';

// reloading after change_desc_proc.php, restore the previously used style
// NB this page submit style as separate elements, but for DB storage, 
//    these are put together into comm-sep list 'TextStyle'
if(isset($_GET['TextStyle']) && $_GET['TextStyle'] != '')
	list($line_style, $text_colour) = explode(',', $_GET['TextStyle']);

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<link rel="stylesheet" type="text/css" href="/style.css" />
<title>Walker Automotive UK</title>
<script type="text/javascript">//<![CDATA[
function delDesc(ParNum)
{
	var frm = document.getElementById("frm_DelDesc");
	frm.ParNum.value = ParNum;
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

<?php
// default values
$par_num = 10;
$width = 600;

$str_SQL = "SELECT MAX(ParNum) FROM CarDesc WHERE CarID = $car_id";
$query_result = mysql_query($str_SQL);
check_sql_error($str_SQL);

if(mysql_num_rows($query_result) == 0)
	;
else
	$par_num = mysql_result($query_result, 0, 0);

$str_SQL = "SELECT dl.Width FROM DetailLayout dl, CarMain cm
			WHERE cm.CarID = dl.CarID
			AND cm.DescLayoutID = dl.ObjectID
			AND cm.CarID = $car_id";
$query_result = mysql_query($str_SQL);
check_sql_error($str_SQL);

// use default width or get existing width

if(mysql_result($query_result, 0, 0) == null)
	;
else
	$width = mysql_result($query_result, 0, 0);	


$line_styles = array('norm' => 'Normal',
					'bold' => 'Bold',
					'head' => 'Heading',
					'list' => 'List Item');
$text_colours = array('black','white','red','yellow','blue','green','gray','silver');
?>

<form id="frm_AddDesc" action="change_desc_proc.php" method="POST">
	<input type="hidden" name="CarID" value="<?php echo $car_id; ?>" />
	<input type="hidden" name="cmd" value="n" />
	<table>
		<tr>
		<td class="key">Seq:</td>
		<td><input type="text" name="ParNum" size="3" value="<?php echo ($par_num + 10); ?>" /></td>
		<td class="key">Style:</td>
		<td>
			<select name="LineStyle">
			<?php
				while(list($k, $v) = each($line_styles))
				{
					print "<option value=\"$k\"";
					if($line_style == $k) print ' selected';
					print ">$v</option>\n";
				}
			?>
			</select>
			<select name="TextColour">
			<?php
				while(list($k, $v) = each($text_colours))
				{
					print "<option value=\"$v\"";
					if($text_colour == $v) print ' selected';
					print ">$v</option>\n";
				}
			?>	
			</select>
		</td>
		<td class="key">Width:</td>
		<td><input type="text" name="DescWidth" size="3" value="<?php if(!isset($width)) echo "no"; else echo $width; ?>" /></td>
		<td><input type="submit" value="Submit" /></td>
		<td><input type="button" value="Done" onClick="endEditWindow()" /></td>
	</tr>
	</table>
	<textarea name="DescText" cols="50" rows="4" wrap="virtual"></textarea>
</form>


<?php
////////// DB action //////////

$str_SQL = "SELECT ParNum, DescText, TextStyle FROM CarDesc WHERE CarID = $car_id ORDER BY ParNum";
$query_result = mysql_query($str_SQL);
check_sql_error($str_SQL);

if(mysql_num_rows($query_result) == 0)
	display_copy_from();
else
	display_desc();


////////// output //////////

function display_copy_from()
{
	global $car_id;

	print <<<BLOCK
No description is set for this car.<br />
Enter new description above or copy from an existing car:
<form action="change_desc_proc.php" method="POST">
	<input type="hidden" name="CarID" value="$car_id" />
	<input type="hidden" name="cmd" value="c" />
BLOCK;
	$str_SQL = "SELECT CarID, Make, Model FROM CarMain ORDER BY Make, Model";
	$query_result = mysql_query($str_SQL);
	check_sql_error($str_SQL);

	print "<select name=\"CopyCarID\">\n";	
	while($row = mysql_fetch_array($query_result, MYSQL_ASSOC))
		print "<option value=\"".$row['CarID']."\">".$row['Make']." ".$row['Model']."</option>\n";
	print "</select>\n";
	print <<<BLOCK
	<input type="submit" value="Copy" />
</form>
BLOCK;
}

function display_desc()
{
	global $car_id;
	global $width;
	global $query_result;

	require_once($_SERVER['DOCUMENT_ROOT'].'/write_details_functions.php');

	print <<<BLOCK
<form id="frm_DelDesc" action="change_desc_proc.php" method="POST">
	<input type="hidden" name="CarID" value="$car_id" />
	<input type="hidden" name="cmd" value="d" />
	<input type="hidden" name="ParNum" value="" />
BLOCK;
	print "<table style=\"width:".$width."px\">\n";
	while($row = mysql_fetch_array($query_result, MYSQL_ASSOC))
	{
		print "<tr>\n";
		print "<td><a href=\"JavaScript:delDesc(".$row['ParNum'].")\">".$row['ParNum']."</a></td>";
		write_desc_table_row($row);
		print "</tr>\n";
	}
	print "</table>\n";
	print "</form>\n";
}
?>
</body>
</head>
