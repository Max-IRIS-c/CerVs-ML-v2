<?php 
    include('./class/locationObject.php');
	include('../variables.php');
	include('../heade.php');
	include('../fonctionReservation.php');
	setlocale(LC_TIME, 'fra_fra');
	
	$selectedStart = $_GET['start'] ?? 'Y-m-d';
	$selectedEnd = $_GET['end'] ?? 'Y-m-d';

	if (!empty($_POST)) {
		$start = new DateTime($_POST['debut']);
		$end = new DateTime($_POST['fin']);
	}else{
		$start = new DateTime(Date($selectedStart));
		$end = !empty($_GET) ? new DateTime($selectedEnd)
							: new DateTime(date('Y-m-d', strtotime($start->format('Y-m-d') . " +10 weeks")));
	}	
	include('./planning-header.php');
	$allParenthese = LocationObject::getAllParenthese();
	LocationObject::displayPlanningTable($allParenthese, $start, $end);
?>
<style>
	th
	{
		padding: 7px;
		text-align: center;
	}
	.month{
		background-color: #5377b2;
	}
	td
	{
		height: 3em;
		text-align: center;
		border: 1px dotted #5083c1;
	}

	.reservation
	{
		margin-left: auto;
		margin-right: auto;
		width: 100%;
	}

	#debute
	{
		margin-right: 5em;
	}

	#boutonBusLogement
	{
		display: flex;
		flex-direction: row;
		justify-content: center;
		font-weight: bold;
	}
	.week{
		text-align: left;
	}
	.separator{
		background-color: black;
		width: 10px;
	}
	.tbl-layout{
		table-layout: fixed; 
		border-collapse: collapse;
	}
</style>