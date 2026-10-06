<?php

include ("../variables.php");


$bdd = new PDO($dsn, $user, $password);
$bdd->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
$recherche = $_GET['Id'];

$req = $bdd->prepare("SELECT civNom as 'Titre', conNom as Nom,conPrenom as Prénom, conComplement as complément,
 conAdresse as Adresse, conAdresse2 as Adresse2, conNpa as Npa, conLocaliter as Localité, conTel1 as Téléphone1, conTel2 as Téléphone2, 
  conTel3 as Téléphone3, conMail as 'e-mail', mTypNom as 'Type' from tblContact
  LEFT JOIN tblCiviliter on tblCiviliter_civId = civId 
  LEFT JOIN tblMembreType on conTypeMembre = mTypId
  WHERE $recherche and conStatu = 1  ORDER BY conNom, conPrenom ");


$req->execute();

$data = $req->fetchAll();
	
	/* Modif FC : modification de l'encodage en UTF */
	header('Content-Encoding: UTF-8');
	header('Content-type: text/csv; charset=UTF-8');
	header('Content-Disposition: attachment; filename=Customers_Export.csv');
	echo "\xEF\xBB\xBF"; // UTF-8 BOM
	/* Fin modif */


CSV::export($data,"exportAvancer")

?>









