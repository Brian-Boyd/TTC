<?php
//FILE : Boyd_Ricks2.php
//PROG : Brian Boyd
//PURP : Query the database for items from the selected department and display so the user can make selection.
//		 Calls Boyd_Ricks3.php to process the request and continue dialogue.

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
	$query = "SELECT ID, entertainerauthor, title, media, feature FROM products WHERE department = '$dep' ORDER BY entertainerauthor";

	//Execute the query
	$result_set = mysql_query ($query);

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

	<p>Select any or all items that you are interested in!</p>

	<form action="Boyd_Ricks3.php" method="post" id="myform" onSubmit="return check(this)">
	<table cellspacing="2" cellpadding="2" id="tabid" border='1'>
	<thead>
	<tr>
		<th><input type="checkbox" id="checkall" name="checkall"></th>
		<th><strong>ID Number</strong></th>
		<th><strong>Entertainer / Author</strong></th>
		<th><strong>Title</strong></th>
		<th><strong>Media</strong></th>
		<th><strong>Feature</strong></th>
	</tr>
	</thead>
	<tbody>
<?php
		//Start a while loop to process all the rows
		while ($row = mysql_fetch_assoc ($result_set))
		{
			$ID = $row['ID'];
			$entertainerauthor = $row['entertainerauthor'];
			$title =  $row['title'];
			$media =  $row['media'];
			$feature =  $row['feature'];

			for($i=0;$i<sizeof($dep);$i++)
			{
?>
	<tr>
		<td><input type="checkbox" class="checkbox" id="myc" value="<?php echo $ID; ?>" name="choices[]"></td>
		<td><?php echo $ID; ?></td>
		<td><?php echo $entertainerauthor;?></td>
		<td><?php echo $title;?></td>
		<td><?php echo $media;?></td>
		<td><?php echo $feature;?></td>
	</tr>
<?php
			} //END FOR
		} //END WHILE
?>
	</tbody>
	</table><br />
		<input type="hidden" name="seldep" value="<?php echo $dep;?>">
		<input type="submit" value="submit" name="submit">
		<input type="reset" value="Clear">
	</form>

	</body>
	</html>

<?PHP
		mysql_close ($link);
?>
