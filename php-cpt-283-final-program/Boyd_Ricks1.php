<?php
//FILE : Boyd_Ricks1.php
//PROG : Brian Boyd
//PUPR : Program to pull a list of departments from a database and present it to the user to choose from.
//       Calls Boyd_Ricks2.php to process the request and continue dialogue.

	//Set up your root password for your installation
	define ("ROOTPW", "");
	extract ($_POST);

	//Set up connection to cpt283db and set up list of items
	$link = mysql_connect ("localhost", "root", ROOTPW);
	if (!$link) die("Could not connect: " . mysql_error());

	//Choose the cpt283db database
	if (!mysql_select_db ("cpt283db"))
		die("Problem with the database: " . mysql_error());

	//Set up query for items to display
	$query = "SELECT DISTINCT department FROM products";

	//Execute the query
	$result_set = mysql_query ($query);

	//Send the start of the HTML form
print<<<ENDFIRST

	<!doctype html public "-//W3C//DTD HTML 4.0 //EN">
	<html>
	<head>
		<title>Welcome to Rick's Store</title>

	<script type="text/javascript">

	function check(myform)
	{
		var chk = 0;
		for (var i=0; i < myform.dep.length; i++)
		{
			if (myform.dep[i].checked)
			{
				chk = 1;
			}
		}
		if(chk==0)
		{
			alert("Please select one of the departments");
			return false;
		}
	}
	</script>

	</head>
		<body bgcolor=#f5f5f5>

ENDFIRST;
?>

	<h1>Rick's Books and Stuff Superstore</h1>
	<h2>Department Listings</h2>

	<p>Select the department you want to go to!</p>
	<form action="Boyd_Ricks2.php" method="post" id="myform" onSubmit="return check(this)">

	<fieldset><legend>Departments:</legend>
		<ul>

			<?php
			//Start a while loop to process all the rows
			while ($row = mysql_fetch_assoc ($result_set))
			{
				$dep = $row['department'];

				for($i=0;$i<sizeof($dep);$i++)
				{
					?>
						<li><input type="radio" value="<?php echo $dep; ?>" name="dep"><?php echo $dep; ?></li>
					<?php
				} //END FOR
			} //END WHILE
			?>

		</ul>
	</fieldset><br />
	<input type="hidden" name="seldep" value="<?php echo $dep;?>">
	<input type="submit" value="submit" name="submit">
	<input type="reset" value="Clear">
	</form>

	</body>
	</html>

<?PHP
	mysql_close ($link);
?>
