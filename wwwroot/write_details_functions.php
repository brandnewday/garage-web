<?php
function write_spec_table(&$car_info)
{
	print "	<table class=\"spec\">";
	print "		<tr><td class=\"key\">Engine</td>";
	print "		<td>".$car_info['EngineCapacity']."</td></tr>\n";
	print "		<tr><td class=\"key\">Gearbox</td>";
	print "		<td>".$car_info['Gearbox']."</td></tr>\n";
	print "		<tr><td class=\"key\">Doors</td>";
	print "		<td>".$car_info['NumDoors']."</td></tr>\n";
	print "		<tr><td class=\"key\">Colour</td>";
	print "		<td>".$car_info['Colour']."</td></tr>\n";
	print "		<tr><td class=\"key\">Mileage</td>";
	print "		<td>".$car_info['Mileage']." miles</td></tr>\n";
	print "		<tr><td class=\"key\">Owners</td>";
	print "		<td>".$car_info['NumOwners']."</td></tr>\n";
	print "		<tr><td class=\"key\">Year</td>";
	print "		<td>".$car_info['Year']."</td></tr>\n";
	print "		<tr><td class=\"key\">Price</td>";
	print "		<td>£".$car_info['Price']."</td></tr>\n";
	print "		<tr><td class=\"key\">Status</td>";
	print "		<td>".$car_info['Status']."</td></tr>\n";
	print "	</table>";
}

function write_desc_table(&$query_result)
{
	print "<table>\n";
	while($row = mysql_fetch_array($query_result, MYSQL_ASSOC))
	{
		print "<tr>\n";
		write_desc_table_row($row);
		print "</tr>\n";
	}
	print "</table>\n";
}

function write_desc_table_row(&$row)
{
	print "<td style=\"text-align:justify\">";
	$style = explode(',', $row['TextStyle']);
	if(count($style) == 1)
	{
		list($line_style) = $style;		
		$text_colour = '#000000';
	}
	else
		list($line_style, $text_colour) = $style;
	if($line_style == 'list')
	{
		print "<div class=\"bullet\"> • </div>";
		print "<div style=\"display:inline; color: $text_colour\">";
		print $row['DescText'];
		print "</div>\n";
	}
	else if($line_style == 'bold')
	{
		print "<div style=\"font-weight:bold; color: $text_colour\">";
		print $row['DescText'];
		print "</div>\n";
	}
	else if($line_style == 'head')
	{
		print "<div style=\"font-weight:bold; font-size: 12pt; color: $text_colour\">";
		print $row['DescText'];
		print "</div>\n";
	}
	else if($line_style == 'norm')
	{
		print "<div style=\"color: $text_colour\">";
		print $row['DescText'];
		print "</div>\n";
	}
	print "</td>";
}
?>
