<?php
include_once "../src/class/Db.class.php";
include_once "../src/class/Mrp.class.php";
include_once('../src/functions.inc.php');

// Create the db instance
$db = new DB();
$mrp = new Mrp();

$mrp->pageAccess();


$dateRech = "AND traDate BETWEEN '" . $_SESSION['DebutOfas'] . "' AND '" . $_SESSION['finOfas'] . "'";

$annee = substr($_SESSION['DebutOfas'], 0, 4);
// info reçu depuis le get sans OAFS
$valeure = substr($_GET['cat'], 4);

if ((isset($_GET['cat']))AND($_GET['cat']!= 'N/A')){

if (substr($_GET['cat'], 0, 4) == 'somm') {

    // test si il y as dans la valeure $handu
    $pos1 = stripos($_GET['cat'], '&handi');

    if ($pos1 != false) { //si oui séparation de catégorie OFAS et type de personne
        $cat1 = substr($valeure, 0, -8);
        $typerPersonne = substr($valeure, -1);
        $condition = 'AND traCat1 IN (' . $cat1 . ')AND ofasProche = ' . $typerPersonne . ' ' . $dateRech;

    } else {//si non catégori est égale à valeur
        $condition = 'AND traCat1 IN (' . $valeure . ')' . $dateRech;


    }
    $valu = 'totHeure';
    $type = 'somme';
    $texteSecondeColone = $mrp->getText('Nombre d\'heures');

    $req = "SELECT sum(traHeureTot)as totHeure,traCat3, traId,traCat1,cat1Code,regConNom ,medOfasType, medOfasPluri,medOfasReconnu,
              ofasBesoin, hanNom,
              ofasNouveau FROM tblTravail
              LEFT JOIN tblTraCat1 on traCat1 = cat1Id
              LEFT JOIN tblContact on tblContact_conId = conId
              LEFT JOIN tblRegionCon on tblContact.conRegion = regConId
              LEFT JOIN tblMedical on tblTravail.tblContact_conId = medConId
              LEFT JOIN tblHandicape on tblMedical.medOfasType = hanId
            where tblContact_conId is not null AND tblContact_conId != 2146190079 and traCat3 != 1 $condition 
            group by medOfasType;";

    $reqPluri = "SELECT sum(traHeureTot)as totHeure,traCat3, traId,traCat1,cat1Code,regConNom ,medOfasType, medOfasPluri,medOfasReconnu,
  ofasBesoin, hanNom,
  ofasNouveau FROM tblTravail
  LEFT JOIN tblTraCat1 on traCat1 = cat1Id
  LEFT JOIN tblContact on tblContact_conId = conId
  LEFT JOIN tblRegionCon on tblContact.conRegion = regConId
  LEFT JOIN tblMedical on tblTravail.tblContact_conId = medConId
  LEFT JOIN tblHandicape on tblMedical.medOfasType = hanId
where tblContact_conId is not null AND tblContact_conId != 2146190079 and traCat3 != 1 AND medOfasPluri = 1 $condition 
group by medOfasType";


    $reqArticle = "SELECT  sum(traHeureTot)as totParArticle, traCat1,cat1Code,medOfasReconnu, artNom
FROM tblTravail
  LEFT JOIN tblTraCat1 on traCat1 = cat1Id
  LEFT JOIN tblContact on tblContact_conId = conId
  LEFT JOIN tblMedical on tblTravail.tblContact_conId = medConId
  LEFT JOIN tblArt on medOfasReconnu = artId
where tblContact_conId is not null AND tblContact_conId != 2146190079 and traCat3 != 1 $condition   group by medOfasReconnu ";


    $reqCanton = "SELECT sum(traHeureTot) AS totParCanton, traCat1, cat1Code, medOfasReconnu, cantNom FROM tblTravail
  LEFT JOIN tblTraCat1 ON traCat1 = cat1Id
  LEFT JOIN tblContact ON tblContact_conId = conId
  LEFT JOIN tblMedical ON tblTravail.tblContact_conId = medConId
LEFT JOIN tblCanton on ofasCanton = cantId 
where tblContact_conId is not null AND tblContact_conId != 2146190079 and traCat3 != 1 $condition  group by ofasCanton ";

    $reqNouveau = "SELECT sum(traHeureTot) AS totParCanton, traCat1, cat1Code, medOfasReconnu, cantNom FROM tblTravail
  LEFT JOIN tblTraCat1 ON traCat1 = cat1Id
  LEFT JOIN tblContact ON tblContact_conId = conId
  LEFT JOIN tblMedical ON tblTravail.tblContact_conId = medConId
LEFT JOIN tblCanton on ofasCanton = cantId 
where tblContact_conId is not null AND tblContact_conId != 2146190079 and traCat3 != 1 $condition  AND ofasNouveau = $annee ";


}  //si c'est une somme de temps
elseif (substr($_GET['cat'], 0, 4) == 'tota') {
    $pos1 = stripos($_GET['cat'], '&handi');

    if ($pos1 != false) { //si oui séparation de catégorie OFAS et type de personne
        $cat1 = substr($valeure, 0, -8);
        $typerPersonne = substr($valeure, -1);
        $condition = ' tblContact_conId is not null AND tblContact_conId != 2146190079 and traCat3 != 1 AND traCat1 IN (' . $cat1 . ')AND ofasProche = ' . $typerPersonne . ' ' . $dateRech;

    } else {//si non catégori est égale à valeur
        $condition = 'tblContact_conId is not null AND tblContact_conId != 2146190079 and traCat3 != 1 AND traCat1 IN (' . $valeure . ')' . $dateRech;
    }
    $valu = 'TotalPers';
    $type = 'total';
    $texteSecondeColone = 'Nombre de personne';
    $req = "SELECT COUNT(DISTINCT(tblContact_conId))as TotalPers,hanNom,medOfasType FROM tblTravail
LEFT JOIN tblMedical on tblContact_conId = medConId
  LEFT JOIN tblHandicape on tblMedical.medOfasType = hanId
where  $condition GROUP BY medOfasType";

    $reqPluri = "SELECT COUNT(DISTINCT(tblContact_conId))as TotalPers,hanNom,medOfasType FROM tblTravail
LEFT JOIN tblMedical on tblContact_conId = medConId
  LEFT JOIN tblHandicape on tblMedical.medOfasType = hanId
where  medOfasPluri = 1 AND $condition GROUP BY medOfasType";

    $reqArticle = " SELECT COUNT(DISTINCT(tblContact_conId))as totParArticle, traCat1,cat1Code,medOfasReconnu, artNom
FROM tblTravail
  LEFT JOIN tblTraCat1 on traCat1 = cat1Id
  LEFT JOIN tblContact on tblContact_conId = conId
  LEFT JOIN tblMedical on tblTravail.tblContact_conId = medConId
  LEFT JOIN tblArt on medOfasReconnu = artId
where  $condition group by medOfasReconnu ";

    $reqCanton = "SELECT COUNT(DISTINCT(tblContact_conId))as totParCanton, traCat1, cat1Code, medOfasReconnu, cantNom FROM tblTravail
  LEFT JOIN tblTraCat1 ON traCat1 = cat1Id
  LEFT JOIN tblContact ON tblContact_conId = conId
  LEFT JOIN tblMedical ON tblTravail.tblContact_conId = medConId
LEFT JOIN tblCanton on ofasCanton = cantId 
where $condition group by ofasCanton ";

    $reqNouveau = "SELECT COUNT(DISTINCT(tblContact_conId))as totParAnnee, traCat1, cat1Code, medOfasReconnu, cantNom FROM tblTravail
      LEFT JOIN tblTraCat1 ON traCat1 = cat1Id
  LEFT JOIN tblContact ON tblContact_conId = conId
  LEFT JOIN tblMedical ON tblTravail.tblContact_conId = medConId
LEFT JOIN tblCanton on ofasCanton = cantId 
where $condition AND ofasNouveau = $annee ";


}//requete de totalisation des nombre de personne
elseif (substr($_GET['cat'], 0, 4) == 'inte'){
    $valu = 'TotalPers';
    $type = 'total';
    $texteSecondeColone = 'Nombre de visites/intervention';

    $pos1 = stripos($_GET['cat'], '&handi');

    if ($pos1 != false) { //si oui séparation de catégorie OFAS et type de personne
        $cat1 = substr($valeure, 0, -8);
        $typerPersonne = substr($valeure, -1);
        $condition = 'tblContact_conId is not null AND tblContact_conId != 2146190079 and traCat3 != 1 AND traCat1 IN (' . $cat1 . ')AND ofasProche = ' . $typerPersonne . ' ' . $dateRech;

    } else {//si non catégori est égale à valeur
        $condition = 'tblContact_conId is not null AND tblContact_conId != 2146190079 and traCat3 != 1 AND traCat1 IN (' . $valeure . ')' . $dateRech;
    }
    $req = "SELECT COUNT(DISTINCT(traId))as TotalPers,hanNom,medOfasType FROM tblTravail
LEFT JOIN tblMedical on tblContact_conId = medConId
  LEFT JOIN tblHandicape on tblMedical.medOfasType = hanId
where  $condition GROUP BY medOfasType";

    $reqPluri = "SELECT COUNT(DISTINCT(traId))as TotalPers,hanNom,medOfasType FROM tblTravail
LEFT JOIN tblMedical on tblContact_conId = medConId
  LEFT JOIN tblHandicape on tblMedical.medOfasType = hanId
where  medOfasPluri = 1 AND $condition GROUP BY medOfasType";

    $reqArticle = " SELECT COUNT(DISTINCT(traId))as totParArticle, traCat1,cat1Code,medOfasReconnu, artNom
FROM tblTravail
  LEFT JOIN tblTraCat1 on traCat1 = cat1Id
  LEFT JOIN tblContact on tblContact_conId = conId
  LEFT JOIN tblMedical on tblTravail.tblContact_conId = medConId
  LEFT JOIN tblArt on medOfasReconnu = artId
where  $condition group by medOfasReconnu ";

    $reqNouveau = "SELECT COUNT(DISTINCT(traId))as totParAnnee, traCat1, cat1Code, medOfasReconnu, cantNom FROM tblTravail
      LEFT JOIN tblTraCat1 ON traCat1 = cat1Id
  LEFT JOIN tblContact ON tblContact_conId = conId
  LEFT JOIN tblMedical ON tblTravail.tblContact_conId = medConId
LEFT JOIN tblCanton on ofasCanton = cantId 
where  $condition AND ofasNouveau = $annee ";

    $reqCanton = "SELECT COUNT(DISTINCT(traId))as totParCanton, traCat1, cat1Code, medOfasReconnu, cantNom FROM tblTravail
  LEFT JOIN tblTraCat1 ON traCat1 = cat1Id
  LEFT JOIN tblContact ON tblContact_conId = conId
  LEFT JOIN tblMedical ON tblTravail.tblContact_conId = medConId
LEFT JOIN tblCanton on ofasCanton = cantId 
where $condition group by ofasCanton ";

}//requete de totalisation des nombre de visites/Intervention

$Resultat = $db->query($req);
$ResultatPluri = $db->single($reqPluri);
$recoArticle = $db->query($reqArticle);
$ResultatCanton = $db->query($reqCanton);
$ResultatNouveau = $db->single($reqNouveau);

?>
<table class="tbl-display">
    <thead>
    <tr>
        <th><?=$mrp->getText('Type de handicape') ?></th>
        <th><?= $texteSecondeColone ?></th>
    </tr>
    </thead>
    <tbody>
    <?php $nbrHeureTot = 0;
    foreach ($Resultat as $res) { ?>
        <tr>
            <td><?= $mrp->getText($res['hanNom']) ?></td>
            <td><?= $res[$valu] ?></td>
        </tr>

        <? $nbrHeureTot = $nbrHeureTot + $res[$valu];
    } ?>
    <tr>
        <th><?=$mrp->getText('Total') ?></th>
        <th><?= $nbrHeureTot ?></th>
    </tr>
    <tr>
        <td><?=$mrp->getText('dont plurihandicapés') ?></td>
        <td><?= $ResultatPluri[0] ?></td>
    </tr>
    <tr>
        <th colspan="2"><?=$mrp->getText('Reconnu au sense de l\'article') ?></th>
    </tr>
    <?php $reconnu = 0;
    foreach ($recoArticle as $reco) { ?>

        <tr>
            <td><?= $reco['artNom'] ?></td>
            <td><?= $reco['totParArticle'] ?></td>
        </tr>

        <? $reconnu = $reconnu + $reco['totParArticle'];
    } ?>
    <tr>
        <th><?=$mrp->getText('Total de personne non Reconnu') ?></th>
        <th><?= $nbrHeureTot - $reconnu ?></th>
    </tr>
    <tr>
        <th colspan="2"><?=$mrp->getText('Total par Région') ?></th>
    </tr>
    <?php $totCanton = 0;
    foreach ($ResultatCanton as $totC) {
        ?>

        <tr>
            <td> <?= $totC['cantNom'] ?></td>
            <td> <?= $totC['totParCanton'] ?></td>
        </tr>
        <? $totCanton += $totC['totParCanton'];

    } ?>
    <tr>
        <th><?=$mrp->getText('Total') ?></th>
        <th><?= $totCanton ?></th>
    </tr>
    <tr>
        <td><?=$mrp->getText('Nouveau Client') ?></td>
        <td><?= $ResultatNouveau['totParAnnee'] ?></td>
    </tr>
    <tr>
        <td><?=$mrp->getText('Repris de l\'année précédente') ?></td>
        <td><?= $totCanton - $ResultatNouveau['totParAnnee'] ?></td>
    </tr>
    <tr>
        <th><?=$mrp->getText('Total') ?></th>
        <th><?= $ResultatNouveau['totParAnnee'] + ($totCanton - $ResultatNouveau['totParAnnee']) ?></th>
    </tr>
    </tbody>
</table>
<? } ?>