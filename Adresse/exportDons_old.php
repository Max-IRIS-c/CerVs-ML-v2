<?php
include ("../variables.php");

$bdd = new PDO($dsn, $user, $password);
$bdd->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
$debut = $_GET['DateDebut'];
$fin = $_GET['DateFin'];
$source = $_GET['Source'];

$req = $bdd->prepare("SELECT civNom as 'Civilité', conNom as Nom, conPrenom as Prenom, conNpa as Npa, conLocaliter as 'localité', cotiDate as 'Date du versement',
cotiValeur as 'Somme', gerCotiNom 'Genre', concat(tCotCode,' - ', tCotNom)as Source FROM tblCotisation
                LEFT JOIN tblContact on tblContact_conId = conId
                LEFT JOIN tblTypeCotisation on tblTypeCotisation_tCotiId = tCotId
                LEFT JOIN tblGenreCoti on tblGenreCot_GenCotId = gerCotiId
                LEFT JOIN tblCiviliter on tblCiviliter_civId = civId
WHERE cotiDate between '$debut' and '$fin'  AND tblTypeCotisation_tCotiId = '$source' ORDER BY conNom, conPrenom");


$req->execute();


$data = $req->fetchAll();

	/* Modif FC : modification de l'encodage en UTF */
	header('Content-Encoding: UTF-8');
	header('Content-type: text/csv; charset=UTF-8');
	header('Content-Disposition: attachment; filename=Customers_Export.csv');
	echo "\xEF\xBB\xBF"; // UTF-8 BOM
	/* Fin modif */

CSV::export($data,"exportDons");


?>

