<?php
//FILE : Boyd_Ricks4.php
//PROG : Brian Boyd
//PURP : Program to process the check boxes from Boyd_Ricks3.php

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

	<p>Here are the items in your shopping cart!</p>

	<form action="Boyd_Ricks5.php" method="post" id="myform">
	<table id="tabid">

	<thead>
	<tr>
		<th><strong>Item</strong></th>
		<th align="center"><strong>Qty</strong></th>
		<th align="right"><strong>Price</strong></th>
	</tr>
	</thead>
	<tbody>

<?php
	//Now, loop through the checkbox array:  $choices
	$count = 0;
	$total = 0;

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
				$summary = $items_row['summary'];
				$entertainerauthor = $items_row['entertainerauthor'];
				$media = $items_row['media'];
				$price = $inv_row['UnitPrice'];
				$total = $total + $price;

				for($i=0;$i<sizeof($dep);$i++)
				{
					?>
				<tr>
					<td valign="top" align="left"><?php echo $summary;?><br />by: <?php echo $entertainerauthor;?><br /><?php echo $media;?><br />P/N <?php echo $ID; ?>
					<input type="hidden" name="choices[]" value="<?php echo $ID;?>">
					</td>
					<td valign="top" align="center">1</td>
					<td valign="top" align="right"><?php echo $price;?></td>
				</tr>
					<?php
					$count = $count + 1;
				} //END FOR
			} //END WHILE
		}
		else printf ("Error with the DB result set!\n");
	}
?>
	<tr>
		<td valign="top" align="right" colspan="2">Your <?php echo $count;?> item Total:</td>
		<td valign="top" align="right"><?php echo $total;?></td>
	</tr>
	</tbody>
	</table>

	<p></p>
	<table id="tabid">
	<thead>
	<tr>
		<th><strong>Payee Details</strong></th>
		<th><strong>Payment Method</strong></th>
	</tr>
	</thead>
	<tbody>

	<tr>
		<td valign="top" align="left">
	<p>
		<label class="description">Name (First, Last)</label><br />
		<input maxlength="30" size="15" name="fname" value="Bill"/>
		<input maxlength="30" size="15" name="lname" value="Murray"/>
	</p>
	<p>
		<label class="description">Street Address</label><br />
		<input maxlength="50" size="50" name="address" value="1950 Caddy Shack Lane" type="text"><br /><br />
		<label>City, State, Zip</label><br />
		<input maxlength="30" size="30" name="city" value="Sullivans Island" type="text">
		<input maxlength="2" size="2" name="state" value="SC" type="text">
		<input maxlength="10" size="10" name="zip" value="29466" type="text">
	</p>
	<p>
		<label class="description">Phone</label><br />
		<input size="12" maxlength="12" name="phone" value="843-555-1234" type="text">
	</p>
		</td>

		<td valign="top" align="left">

	<p>
		<h3>Payment Information (Credit Cards Only)</h3>
	</p>
	<p>
		<input type="radio" name="cctype" value="Visa" checked /> <label class="choice">Visa</label>
		<input type="radio" name="cctype" value="MasterCard" /> <label class="choice">MasterCard</label>
		<input type="radio" name="cctype" value="American Express" /> <label class="choice">American Express</label>
	</p>
	<p>
		<label class="description">Card Number</label><br />
		<input type="text" maxlength="19" name="ccn" value="4012 2379 5871 2387"/><br /><br />
		<label class="description">Expiration Date (Format = MM/DD/YYYY)</label><br />
		<input size="2" maxlength="2" name="ccxm" value="04" type="text"> /
		<input size="2" maxlength="2" name="ccxd" value="30" type="text"> /
		<input size="4" maxlength="4" name="ccxy" value="2012" type="text">
	</p>
	</td>
	</tr>

	</tbody>
	</table><br />

	<input type="hidden" name="seldep" value="<?php echo $dep;?>">
	<input type="submit" value="Submit" name="submit">
	</form>

	</body>
	</html>

<?php
		mysql_close($link);
?>
