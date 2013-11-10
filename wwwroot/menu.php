<?php
// pre-condition:
// $car_id and $admin_mode set
?>

<div id="topcolor">
	<img src="/topcolor.gif" />
</div>

<div id="logo">
	<img src="/logo.gif" />
</div>

<div id="login">
<?php
if($admin_mode == 0)
	print "<a href=\"/login.php\">Admin Log In</a>\n";
else if($admin_mode == 1)
	print "Logged in as $user_full_name\n";
?>
</div>

<!-- top banner
<div id="wrapper">
	<div id="headerwrapper">
		<div id="header">
			
		</div>
	</div>
</div>
-->

<div id="navigation">
<ul id="nav">

	<li class="menuparent"><a href="/index.php">Home</a></li>

	<li class="menuparent"><a href="/showroom.php">Showroom</a>
		<ul>
			<li><a href="/showroom.php">Display All</a></li>
			<li class="menuparent"><a href="#">Display By Type &nbsp;&nbsp;>></a>
				<ul>
					<?php
					print_car_type_submenu();
					?>
				</ul>
			</li>
		</ul>
	</li>

	<li class="menuparent"><a href="/about.php">About</a></li>
	<li class="menuparent"><a href="/contact.php">Contact</a></li>

	<?php
	if($admin_mode == 1)
	{
	?>
		<li class="menuparent"><a href="/admin/index.php">Admin</a>
			<ul>
				<?php
					print_admin_menu();
				?>
			</ul>
		</li>
	<?php
	}
	?>
</ul>
</div>
<div id="banner_spacer"></div>

<?php
function print_car_type_submenu()
{
	$str_SQL = "SELECT ct.CarTypeID, ct.CarTypeName, Count(1) AS Num
				FROM CarMain cm, CarType ct
				WHERE cm.CarTypeID = ct.CarTypeID
				GROUP BY ct.CarTypeID, ct.CarTypeName";
	$query_result = mysql_query($str_SQL);
	check_sql_error($str_SQL);

	while($row = mysql_fetch_array($query_result, MYSQL_ASSOC))
	{
		print "<li><a href=\"/showroom.php?CarTypeID=";
		print $row['CarTypeID'];
		print "\">";
		print $row['CarTypeName']." (".$row['Num'].")</a></li>\n";
	}
}

function print_admin_menu()
{
	global $car_id;

	print "<li><a href=\"/admin/add_new.php\">Add New</a></li>\n";

	// is editing a car
	if($car_id != -1)
	{
		print "<li><a href=\"/admin/change_spec.php?CarID=$car_id\">Change Basic Info</a></li>\n";
		print "<li><a href=\"/admin/edit_details.php?CarID=$car_id\">EDIT Mode</a></li>\n";
		print "<li><a href=\"/details.php?CarID=$car_id\">NORMAL Mode</a></li>\n";
		print "<li><a href=\"JavaScript:openEditWindow('/admin/change_img.php',$car_id)\">Change Images</a></li>\n";
		print "<li><a href=\"JavaScript:openEditWindow('/admin/change_desc.php',$car_id)\">Change Description</a></li>\n";
		print "<li><a href=\"/admin/delete.php?CarID=$car_id\">Delete Car</a></li>\n";
	}
	print "<li><a href=\"/login_proc.php?cmd=o\">Log Out</a></li>\n";
}
?>
