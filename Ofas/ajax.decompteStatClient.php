<?php
include_once "../src/class/Db.class.php";
include_once "../src/class/Mrp.class.php";
include_once('../src/functions.inc.php');

// Create the db instance
$db = new DB();
$mrp = new Mrp();

$mrp->pageAccess();


$dateRech = "AND actDebut BETWEEN '" . $_SESSION['DebutOfas'] . "' AND '" . $_SESSION['finOfas'] . "'";


$annee = substr($_SESSION['DebutOfas'], 0, 4);
// info reçu depuis le get sans OAFS
$valeure = substr($_GET['cat'], 4);

if ((isset($_GET['cat']))AND($_GET['cat']!= 'N/A')){


if (substr($_GET['cat'], 0, 4) == 'tota') {
    $pos1 = stripos($_GET['cat'], '&handi');

    if ($pos1 != false) { //si oui séparation de catégorie OFAS et type de personne
        $cat1 = substr($valeure, 0, -8);
        $typePersonne = substr($valeure, -1);
            if (($typePersonne) == 0 ){
                $conditionPersonne = 'ofasProche != 1';
            echo 'personne Proche ';
            }
            else
            {
                $conditionPersonne = 'ofasProche = 1';
                echo 'peronne Handicape';
            }
        $condition = ' conIdP != 2146190079 and actCodeOfas IN (' . $cat1 . ')AND '.$conditionPersonne . ' ' . $dateRech;

    } else {//si non catégori est égale à valeur
        $condition = 'conIdP != 2146190079 and actCodeOfas IN (' . $valeure . ')' . $dateRech;
    }


    $valu = 'total';
    $type = 'total';
    $texteSecondeColone = 'Nombre de journée participation';

    $req = "SELECT count(conIdP)as total,hanNom FROM tblActivites
  INNER JOIN tblParticipants on tblActivites.actId = tblParticipants.actId
  LEFT JOIN tblMedical on conIdP = tblMedical.medConId
  LEFT JOIN tblHandicape on tblMedical.medOfasType = hanId
WHERE  $condition 
GROUP BY hanNom";

    $reqPluri ="SELECT actCodeOfas, conIdP,sum((DATEDIFF( actFin, actDebut )+1)) as puriHandicape,hanNom FROM tblParticipants
INNER JOIN tblActivites on tblParticipants.actId = tblActivites.actId
INNER JOIN tblMedical on tblParticipants.conIdP = tblMedical.medConId
INNER JOIN tblHandicape on tblMedical.medOfasType = tblHandicape.hanId
WHERE  $condition AND medOfasReconnu in (1,2)  AND medOfasPluri = 1
GROUP BY hanNom";

    $reqCanton="SELECT actCodeOfas, conIdP,sum((DATEDIFF( actFin, actDebut )+1)) as parCanton,cantNom FROM tblParticipants
INNER JOIN tblActivites on tblParticipants.actId = tblActivites.actId
INNER JOIN tblMedical on tblParticipants.conIdP = tblMedical.medConId
INNER JOIN tblCanton on tblMedical.ofasCanton = cantId WHERE  $condition AND medOfasReconnu in (1,2)  
GROUP BY cantNom";


    $reqNouveau ="";


}//requete de totalisation des nombre de personne


$Resultat = $db->query($req);
$ResultatPluri = $db->query($reqPluri);
$ResultatCanton = $db->query($reqCanton);


?>
    <table class="tbl-display">
        <tr>
            <th><?=$mrp->getText('Type de handicape'); ?></th>
            <th><?= $texteSecondeColone ?></th>
        </tr>
        <?php $nbrHeureTot = 0;
        foreach ($Resultat as $res) { ?>
            <tr>
                <td><?= $res['hanNom'] ?></td>
                <td><?= $res[$valu] ?></td>
            </tr>

            <? $nbrHeureTot = $nbrHeureTot + $res[$valu];
        }
        ?>
        <tr>
            <th><?=$mrp->getText('Total'); ?></th>
            <th><?= $nbrHeureTot ?></th>
        </tr>

        <tr>
            <td><?=$mrp->getText('dont plurihandicapés'); ?></td>
            <td><? $puriHandicap= 0;

                foreach ($ResultatPluri as $puri){
                    $puriHandicap+=$puri['puriHandicape'];
                }


                echo $puriHandicap; ?>

            </td>
        </tr>


        <tr>
            <th colspan="2"><?=$mrp->getText('Total par Région'); ?></th>
        </tr>
        <?php $totCanton = 0;
        foreach ($ResultatCanton as $totC) {
            ?>

            <tr>
                <td> <?= $totC['cantNom'] ?></td>
                <td> <?= $totC['parCanton'] ?></td>
            </tr>
            <? $totCanton += $totC['parCanton'];

        } ?>
        <tr>
            <th><?=$mrp->getText('Total'); ?></th>
            <th><?= $totCanton ?></th>
        </tr>


    </table>
<? } ?>