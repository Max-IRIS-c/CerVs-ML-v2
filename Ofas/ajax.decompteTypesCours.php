<?php
include_once "../src/class/Db.class.php";
include_once "../src/class/Mrp.class.php";
include_once('../src/functions.inc.php');

// Create the db instance
$db = new DB();
$mrp = new Mrp();

$mrp->pageAccess();


$dateRech = " actDebut BETWEEN '" . $_SESSION['DebutOfas'] . "' AND '" . $_SESSION['finOfas'] . "'";

$annee = substr($_SESSION['DebutOfas'], 0, 4);
// info reçu depuis le get sans OAFS
$valeure = substr($_GET['cat'], 4);

if ((isset($_GET['cat']))AND($_GET['cat']!= 'N/A')){



if (substr($_GET['cat'], 4, 5) == 'Cours') {
$texteColone = 'Nombre de cours';
$value ='nbrJour';

    $req = "SELECT cat1Nom,ofaTypNom, COUNT(tblActivites.actId) as nbrJour   FROM tblActivites
  INNER JOIN tblTraCat1 on actCodeOfas = tblTraCat1.cat1Id
  INNER JOIN tblOfasType on actOfasType = ofaTypId
  
where $dateRech
GROUP BY actCodeOfas, actOfasType";

}
elseif (substr($_GET['cat'], 4, 5) == 'Parti')
{
    $texteColone = 'Nombre de participants';
    $value ='jourParticipants';

    $req = "SELECT cat1Nom, ofaTypNom ,sum((DATEDIFF( actFin, actDebut )+1)) as jourParticipants FROM tblParticipants
  INNER JOIN tblActivites on tblParticipants.actId = tblActivites.actId
  INNER JOIN tblTraCat1 on actCodeOfas = tblTraCat1.cat1Id
  INNER JOIN tblOfasType on actOfasType = ofaTypId
    INNER JOIN tblMedical on conIdP = tblMedical.medConId

where $dateRech AND medOfasReconnu  in (1,2)
GROUP BY actCodeOfas, actOfasType";

}
elseif (substr($_GET['cat'], 4, 10) == '1&handi=1')
{
    $texteColone = 'Personnes handicapés art 74';
    $value ='jourParticipants';

    $req = "SELECT cat1Nom, ofaTypNom ,sum((DATEDIFF( actFin, actDebut )+1)) as jourParticipants FROM tblParticipants
  INNER JOIN tblActivites on tblParticipants.actId = tblActivites.actId
  INNER JOIN tblTraCat1 on actCodeOfas = tblTraCat1.cat1Id
  INNER JOIN tblOfasType on actOfasType = ofaTypId
  INNER JOIN tblMedical on conIdP = tblMedical.medConId
where $dateRech and medOfasReconnu = 1 AND ofasProche = 1
GROUP BY actCodeOfas, actOfasType";
}
elseif (substr($_GET['cat'], 4, 10) == '1&handi=0')
{
    $texteColone = 'Personnes proches art 74';
    $value ='jourParticipants';

    $req = "SELECT cat1Nom, ofaTypNom ,sum((DATEDIFF( actFin, actDebut )+1)) as jourParticipants FROM tblParticipants
  INNER JOIN tblActivites on tblParticipants.actId = tblActivites.actId
  INNER JOIN tblTraCat1 on actCodeOfas = tblTraCat1.cat1Id
  INNER JOIN tblOfasType on actOfasType = ofaTypId
  INNER JOIN tblMedical on conIdP = tblMedical.medConId
where $dateRech and medOfasReconnu = 1 AND ofasProche = 0
GROUP BY actCodeOfas, actOfasType";
}
elseif (substr($_GET['cat'], 4, 10) == '2&handi=1')
{
    $texteColone = 'Personnes handicapés art 101bis LAVS';
    $value ='jourParticipants';

    $req = "SELECT cat1Nom, ofaTypNom ,sum((DATEDIFF( actFin, actDebut )+1)) as jourParticipants FROM tblParticipants
  INNER JOIN tblActivites on tblParticipants.actId = tblActivites.actId
  INNER JOIN tblTraCat1 on actCodeOfas = tblTraCat1.cat1Id
  INNER JOIN tblOfasType on actOfasType = ofaTypId
  INNER JOIN tblMedical on conIdP = tblMedical.medConId
where $dateRech and medOfasReconnu = 2 AND ofasProche = 1
GROUP BY actCodeOfas, actOfasType";
}
elseif (substr($_GET['cat'], 4, 10) == '2&handi=0')
{
    $texteColone = 'Proches art 101bis LAVS';
    $value ='jourParticipants';

    $req = "SELECT cat1Nom, ofaTypNom ,sum((DATEDIFF( actFin, actDebut )+1)) as jourParticipants FROM tblParticipants
  INNER JOIN tblActivites on tblParticipants.actId = tblActivites.actId
  INNER JOIN tblTraCat1 on actCodeOfas = tblTraCat1.cat1Id
  INNER JOIN tblOfasType on actOfasType = ofaTypId
  INNER JOIN tblMedical on conIdP = tblMedical.medConId
where $dateRech and medOfasReconnu = 2 AND ofasProche = 1
GROUP BY actCodeOfas, actOfasType";
}
elseif (substr($_GET['cat'], 4, 5) == 'NonR')
{
    $texteColone = 'Non reconnus';
    $value ='jourParticipants';

    $req = "SELECT cat1Nom, ofaTypNom ,sum((DATEDIFF( actFin, actDebut )+1)) as jourParticipants FROM tblParticipants
  INNER JOIN tblActivites on tblParticipants.actId = tblActivites.actId
  INNER JOIN tblTraCat1 on actCodeOfas = tblTraCat1.cat1Id
  INNER JOIN tblOfasType on actOfasType = ofaTypId
  INNER JOIN tblMedical on conIdP = tblMedical.medConId
where $dateRech and (medOfasReconnu NOT IN (1,2) or medOfasReconnu is NULL ) 
GROUP BY actCodeOfas, actOfasType";
}

$resultat = $db->query($req);








?>
    <table class="tbl-display">
        <tr>
            <th>Catégories de prestation</th>
            <th>Types de cours</th>
            <th><?= $texteColone ?> </th>
        </tr>
        <?php
        $tot = 0;
        foreach ($resultat as $res){

            if (empty($oldCat)){
                $oldCat = $res['cat1Nom'];
            }
            else
            {
                if ($oldCat != $res['cat1Nom']){
                    echo'<th colspan="2">Total</th><th>'.$tot.'</th>';
                    $tot = 0;
                    $oldCat = $res['cat1Nom'];

                }
            }

            ?>

            <tr>
                <td><?= $res['cat1Nom'] ?></td>
                <td><?= $res['ofaTypNom'] ?></td>
                <td><?= $res[$value] ?></td>
            </tr>

       <?

            $tot +=$res[$value];

        }

        echo'<th colspan="2">Total</th><th>'.$tot.'</th>';?>
    </table>

<? } ?>