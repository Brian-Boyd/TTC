<!doctype html public "-//W3C//DTD HTML 4.0 //EN">
<html>
<head>
       <title>Rick's Rentals & Repairs (Equipment Rental Report)</title>
</head>
<body>

<table border="1" cellpadding="1" cellspacing="2" align="center" bgcolor="silver">
    <tr>
        <td colspan="9" align="center">Rick's Rental and Repairs</td>
    </tr>
    <tr>
        <td width="100px" align="left">EQUIPMENT</td>
        <td width="100px" align="center">YEAR</td>
        <td width="100px" align="center">SERIAL#</td>
        <td width="100px" align="center">RENTALCHG</td>
        <td width="100px" align="center">DAYSOUT</td>
        <td width="100px" align="center">REVENUE</td>
        <td width="100px" align="center">ORIGPRICE</td>
        <td width="100px" align="center">HOURS</td>
        <td width="100px" align="center">NOTES</td>
    </tr>

<?php
	//FILE : BrianBoyd_Program6.php
	//PROG : Brian Boyd
	//PURP : To select data from an include file and display in a report.
    include 'ricks_inventory.php';
	
	$equipment['name'] = rtrim(strtok($data, " "));
	$equipment['year'] = rtrim(strtok(" "));
	$equipment['serial'] = rtrim(strtok(" "));
	$equipment['charge'] = rtrim(strtok(" "));
	$equipment['daysout'] = rtrim(strtok(" "));
	$equipment['revenue'] = rtrim(strtok(" "));
	$equipment['price'] = rtrim(strtok(" "));
	$equipment['hours'] = rtrim(strtok(" "));
	$equipment['dep'] = rtrim(strtok(" "));
	$equipment['maint'] = rtrim(strtok("\n"));
    
	$totalDays = $equipment['daysout'];
	$totalRevenue = $equipment['revenue'];
	
	while($equipment['name'])
    {
		echo "<tr>";
		$note = "";
		foreach($equipment as $key=>$value)
        {
			if($key != 'dep' && $key != 'maint')
            {
				echo "<td>{$value}</td>";
				if ($key == 'hours')
                {
					if($value > 1000)
                    {
						$note .= "H";
					}
				}
				if ($key == 'daysout')
                {
					if($value < 90)
                    {
						$note .= "L";
					}
				}
			}
		}
		echo "<td>{$note}</td>";
		echo "</tr>";
		
		$equipment['name'] = rtrim(strtok(" "));
		$equipment['year'] = rtrim(strtok(" "));
		$equipment['serial'] = rtrim(strtok(" "));
		$equipment['charge'] = rtrim(strtok(" "));
		$equipment['daysout'] = rtrim(strtok(" "));
		$equipment['revenue'] = rtrim(strtok(" "));
		$equipment['price'] = rtrim(strtok(" "));
		$equipment['hours'] = rtrim(strtok(" "));
		$equipment['dep'] = rtrim(strtok(" "));
		$equipment['maint'] = rtrim(strtok("\n"));
		
		$totalDays += $equipment['daysout'];
		$totalRevenue += $equipment['revenue'];
	}
	echo "</table>";
	echo "<center><h1>Total Days: {$totalDays}</h1>
          <h1>Total Revenue: {$totalRevenue}</h1></center>";
?>

</body>
</html>