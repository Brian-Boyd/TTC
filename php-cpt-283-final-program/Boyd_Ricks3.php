<?php
//FILE : Boyd_Ricks3.php
//PROG : Brian Boyd
//PURP : Program to process the check boxes from Boyd_Ricks2.php

	//Set up your root password for your installation
	define ("ROOTPW", "");
	extract ($_POST);
	$dep = $_POST['seldep'];

	//First, connect to the cpt283db database
	$link = mysql_connect ("localhost", "root", ROOTPW);
	if (!$link) die("Could not connect: " . mysql_error());

	//Choose the cpt283db database
	if (!mysql_select_db ("cpt283db"))
		die("Problem with the database: " . mysql_error());

	//Send the start of the HTML form
print<<<ENDFIRST

	<!doctype html public "-//W3C//DTD HTML 4.0 //EN">
	<html>
	<head>
	<title>You are currently browsing in Rick's Store</title>
	<link rel="stylesheet" type="text/css" href="tab.css" />
	<script type='text/javascript' src='http://code.jquery.com/jquery-1.4.3.min.js'></script>
	<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.5/jquery-ui.js"></script>
	<link rel="stylesheet" type="text/css" href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.5/themes/ui-lightness/jquery-ui.css">

	<script type='text/javascript'>
		$(window).load(function()
		{
			$("#checkall").change(function ()
			{
				$('#tabid input[type=checkbox]').attr("checked", this.checked);
			});
		});
	</script>

	<script type="text/javascript">
		function check(myform)
		{
			var chk1 = 0;
			var mychk = document.getElementsByName('choices[]');

			for (var i=0; i < mychk.length; i++)
			{
				if (mychk[i].checked)
				{
					chk1 = 1;
				}
			}

			if(chk1 == 0)
			{
				alert("Please select one of the items");
				return false;
			}
		}
	</script>

	</head>
	<body bgcolor=#f5f5f5>

ENDFIRST;
?>

	<h1>Rick's Books and Stuff Superstore</h1>
	<h2>Department Listings: <?php echo $dep;?></h2>

	<p>Here is additional info on the items you selected!</p>

	<form action="Boyd_Ricks4.php" method="post" id="myform" onSubmit="return check(this)">
	<table cellspacing="2" cellpadding="2" id="tabid" border='1'>
	<thead>
	<tr>
		<th><input type="checkbox" id="checkall" name="checkall"></th>
		<th><strong>ID Number</strong></th>
        <th><strong>Entertainer / Author</strong></th>
		<th><strong>Title</strong></th>
		<th align="right"><strong>Price</strong></th>
		<th align="right"><strong>In Stock</strong></th>
		<th align="right"><strong>Summary</strong></th>
	</tr>
	</thead>
	<tbody>

<?php
	//Now, loop through the checkbox array:  $choices
	$count = 0;
	foreach ($choices as $value)
	{
		//Set up the query for the first $value.  Just select all fields.
		$items_query  =  "SELECT * FROM products WHERE ID = '$value' ORDER BY entertainerauthor";

		//Execute the query
		$items_result = mysql_query ($items_query);

		//If the result set is good, then get the data to display
		if ($items_result)
		{
			$items_row = mysql_fetch_assoc($items_result);
			//Now, we have an associative array with all fields from items

			//Initiate a new query here to the other table, the product inventory table.
			$idcode = $items_row['ID'];

			//Now, here is the query for the product inventory table
			$inv_query = "SELECT UnitsInStock, UnitPrice from prodinv WHERE ID = '$idcode'";

			//Execute the query
			$inv_result = mysql_query ($inv_query);

			//Start a while loop to process all the rows
			while ($inv_row = mysql_fetch_assoc ($inv_result))
			{
				$ID = $items_row['ID'];
				$entertainerauthor = $items_row['entertainerauthor'];
				$title = $items_row['title'];
				$summary = $items_row['summary'];
				$price = $inv_row['UnitPrice'];
				$qty = $inv_row['UnitsInStock'];

				for($i=0;$i<sizeof($dep);$i++)
				{
					?>
				<tr>
					<td><input type="checkbox" class="checkbox" id="myc" value="<?php echo $ID; ?>" name="choices[]" checked="checked"></td>
					<td valign="top" align="left" ><?php echo $ID; ?></td>
					<td valign="top" align="left" ><?php echo $entertainerauthor;?></td>
					<td valign="top" align="left" ><?php echo $title;?></td>
					<td valign="top" align="right"><?php echo $price;?></td>
					<td valign="top" align="right"><?php echo $qty;?></td>
					<td valign="top" align="left"><?php echo $summary;?></td>
				</tr>
					<?php
					$count = $count + 1;
				} //END FOR
			} //END WHILE
		}
		else printf ("Error with the DB result set!\n");
	}
?>
	</tbody>
	</table>

		<h3>You have a total of <?php echo $count;?> items selected.</h3>
		
		<p>Here is your chance to uncheck any items you do not wish to purchase. When you are sure of the items you want, click the submit button below.</p>

		<br />
		<input type="hidden" name="seldep" value="<?php echo $dep;?>">
		<input type="submit" value="Submit" name="submit">
	</form>
	
	</body>
	</html>

<?php
		mysql_close($link);
?>
