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
	}/*
	else if(!empty($_GET)){
		$start = new DateTime($selectedStart);
		$end = new DateTime($selectedEnd);
	}else{		
		$start = new DateTime(date('Y-m-d'));
		$end = new DateTime(date('Y-m-d', strtotime($start->format('Y-m-d') . " +10 weeks")));
	}*/
	include('./planning-header.php');
	
	$allObjects = LocationObject::getAllCerebral();
	LocationObject::displayPlanningTable($allObjects, $start, $end);
	?>
	<style>
	.separator{
		background-color: black;
		width: 10px;
	}
	th
	{
		padding: 7px;
		width: 150px;
		text-align: center;
	}
	.month{
		background-color: #5377b2;
	}
	td
	{
		width: 150px;
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
</style>