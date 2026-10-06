<?php 
    include('./class/locationObject.php');
	include('../variables.php');
	include('../heade.php');
	include('../fonctionReservation.php');
	setlocale(LC_TIME, 'fra_fra');
	
	$selectedStart = $_GET['start'];
	$selectedEnd = $_GET['end'];

	if (!empty($_POST)) {
		$start = new DateTime($_POST['debut']);
		$end = new DateTime($_POST['fin']);
	}else if(!empty($_GET)){
		$start = new DateTime($selectedStart);
		$end = new DateTime($selectedEnd);
	}else{		
		$start = new DateTime(date('Y-m-d'));
		$end = new DateTime(date('Y-m-d', strtotime($start->format('Y-m-d') . " +10 weeks")));
	}
	include('./planning-header.php');
	$allBuses = LocationObject::getAllBusCerebral();
	LocationObject::displayPlanningTable($allBuses, $start, $end);
	?>
	<style>
	.tbl-layout{
		table-layout: fixed; 
		border-collapse: collapse;
	}
	th
	{
		padding: 7px;
		text-align: center;
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
	.month{
		background-color: #5377b2;
	}
	.separator{
		background-color: black;
	}
</style>