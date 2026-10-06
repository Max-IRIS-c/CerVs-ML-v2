<?php
	include ("../variables.php");
	try{		
		$contacts = json_decode($_POST['contacts'], true);

		/* Modif FC : modification de l'encodage en UTF */
		header('Content-Encoding: UTF-8');
		header('Content-type: text/csv; charset=UTF-8');
		header('Content-Disposition: attachment; filename=Customers_Export.csv');
		echo "\xEF\xBB\xBF"; // UTF-8 BOM
		/* Fin modif */

		CSV::export($contacts,"exportAvancer");
	}catch(Exception $e){
		echo $e->getMessage();
	} 
?>








