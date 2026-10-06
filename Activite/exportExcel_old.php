<?php

include ("../variables.php");
$bdd = new PDO($dsn, $user, $password);
$bdd->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE,PDO::FETCH_ASSOC);

$id = $_GET['Id'];
$reqPar = $bdd->prepare("SELECT DISTINCT civNom as 'Titre', conNom as Nom,conPrenom as 'Prénom', conComplement as 'complément',
       conAdresse as Adresse, conAdresse2 as Adresse2, conNpa as Npa, conLocaliter as 'Localité', conTel1 as 'Téléphone1', conTel2 as Téléphone2,
       conTel3 as 'Téléphone3' from tblParticipants
  LEFT JOIN tblActivites  on tblActivites.actId = tblParticipants.actId
  LEFT JOIN tblContact ON conIdP  = conId 
                          OR conIdA = conId 
                          or conIdD = conId
                          or tblActivites.actResponsable = conId
                          or tblActivites.actCoResponsable = conId
                          or tblActivites.actCuisiniere = conId
                          or tblActivites.actInfirmier = conId
  LEFT JOIN tblCiviliter on tblCiviliter_civId = civId
WHERE tblParticipants.actId = '$id' ORDER BY Nom");
$reqPar->execute();
$data = $reqPar->fetchAll();

	/* Modif FC : modification de l'encodage en UTF */
	header('Content-Encoding: UTF-8');
	header('Content-type: text/csv; charset=UTF-8');
	header('Content-Disposition: attachment; filename=Customers_Export.csv');
	echo "\xEF\xBB\xBF"; // UTF-8 BOM
	/* Fin modif */


CSV::export($data,"exportParticipant ")

?>









