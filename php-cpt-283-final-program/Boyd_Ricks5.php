<?php
//FILE : Boyd_Ricks5.php
//PROG : Brian Boyd
//PURP : Program to process the check boxes from Boyd_Ricks4.php

	//Set up your root password for your installation
	define ("ROOTPW", "");
	extract ($_POST);
	$dep = $_POST['seldep'];
	$fname = $_POST['fname'];
	$lname = $_POST['lname'];
	$address = $_POST['address'];
	$city = $_POST['city'];
	$state = $_POST['state'];
	$zip = $_POST['zip'];
	$phone = $_POST['phone'];
	$cctype = $_POST['cctype'];
	$ccn = $_POST['ccn'];
	$ccxm = $_POST['ccxm'];
	$ccxd = $_POST['ccxd'];
	$ccxy = $_POST['ccxy'];

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
		<link rel="stylesheet" type="text/css" href="receipt.css" />
	</head>
	<body bgcolor=#f5f5f5>

ENDFIRST;
?>

	<form action="Boyd_Ricks1.php" method="post" id="myform">

	<div id="header"><h1>Rick's Books and Stuff Superstore</h1></div>
	<div>Your card was approved and your order will be shipped soon. You can print out this page as your receipt.<br /><br /></div>
    <div id="checkOut"><input style="float:right;" type="button" name="Print" class="printButton" value="Print" onclick="window.print();">

	<div class="cartTitle"><h5>INVOICE DETAILS</h5></div>
    <div id="orderInfo">
		<dl>
			<dt>Order #:</dt><dd>24561</dd>
			<dt>Submitted:</dt><dd><?php print(Date("l F d, Y")); ?> </dd>
		</dl>
	</div>

	<div class="orderOptions">
		<a href="javascript:printFriendlyByForm(true, false, false, false, 0);" class="btnOn">Print</a>
	</div>

	<div class="halfL">
		<fieldset class="alignHeight ship clean">
			<legend>Ship To</legend>
			<?php echo $fname;?> <?php echo $lname;?><br /><br />
			<?php echo $address;?><br />
			<?php echo $city;?>, <?php echo $state;?> <?php echo $zip;?><br /><br />
		</fieldset>
	</div>

	<div class="halfR">
		<fieldset class="alignHeight bill clean">
			<legend>Bill To</legend>
 			<?php echo $fname;?> <?php echo $lname;?><br /><br />
			<?php echo $address;?><br />
			<?php echo $city;?>, <?php echo $state;?> <?php echo $zip;?><br /><br />
			<?php echo 'Credit Card Type: '.$cctype;?><br />
			<?php echo 'Credit Card Number (Last 4 digits): '.substr($ccn, 14);?><br />
		</fieldset>
	</div>

	<fieldset class="full clean" id="summary">
		<legend>Order Summary</legend>
					
        <table class="cartSum" cellpadding="0" cellspacing="0">
	        <thead>
		        <tr>
			        <td class="optCheckbox">
			        <label for="ckbSelectAll"><span style="display:none">.</span></label><input id="ckbSelectAll" name="selectAll" type="checkbox"></td>	
			        <td>Qty</td>
			        <td>Product Description</td>
			        <td class="panelRight">Price</td>
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
							<td class="optCheckbox">&nbsp;</td>
							<td class="qty">1</td>
							<td>
								<dl class="prodDesc">
									<dd><?php echo $summary;?></dd>
									<dd>by: <?php echo $entertainerauthor;?></dd>
									<dd><?php echo $media;?></dd> 
									<dd>P/N <?php echo $ID; ?></dd>
									<dd></dd>
								</dl>
							</td>
							<td class="money"><?php echo $price;?></td>
						</tr>
					<?php
					$count = $count + 1;
				} //END FOR
			} //END WHILE
		}
		else printf ("Error with the DB result set!\n");
	}
?>

        </tr>
	        <tr class="subtotal">
		        <td colspan="2" class="priceRow">Subtotal</td>
		        <td class="panelRight"><?php echo $total;?></td>
	        </tr>
		        <tr class="noTop">
		        <td colspan="2" class="priceRow">Tax</td>
		        <td class="panelRight">$0.00</td>
	        </tr>
    	        <tr class="noTop">
		        <td colspan="2" class="priceRow">Discount</td>
		        <td class="panelRight">$0.00</td>
	        </tr>
	        <tr class="noTop">
		        <td colspan="2" class="priceRow">Order Total</td>
		        <td id="ORDER_TOTAL" class="panelRight"><?php echo $total;?></td>
	        </tr>
        </tbody>
        </table>
    </fieldset>
				
    <div id="purchasingNotesDiv"></div>	
    <div class="infoEnd"></div>
	
    </div>

    <div id="disclaimer">
    <!-- design borrowed from Newegg.com for a school assignment -->
    </div>

	<input type="hidden" name="entry" value="1">
	<input type="hidden" name="seldep" value="<?php echo $dep;?>">
	<input type="submit" value="Return to Store" name="submit">
	</form>

	</body>
	</html>

<?php
		mysql_close($link);
?>
