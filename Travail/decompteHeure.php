
<?php
include '../variables.php';


$bdd = new PDO($dsn, $user, $password);

$dateDebut = ($_POST['debut']);
$dateFin = ($_POST['fin']);
$id = $idUtilisateur;

$contact = $bdd->query("SELECT * FROM tblEmployer WHERE empId = $id");
$contact = $contact->fetch();

$heure = $bdd->query("SELECT  SUM(traHeureTot) FROM tblTravail WHERE tblEmployer_empId = '$id' 
AND traDate BETWEEN '$dateDebut' AND '$dateFin'  AND traCat3 <= 10 ");
$heure = $heure->fetch();

$maladie = $bdd->query("SELECT  SUM(traHeureTot) FROM tblTravail WHERE tblEmployer_empId = '$id' 
AND traDate BETWEEN '$dateDebut' AND '$dateFin' AND traCat3 = 30 ");
$maladie = $maladie->fetch();

$accident = $bdd->query("SELECT  SUM(traHeureTot) FROM tblTravail WHERE tblEmployer_empId = '$id' 
AND traDate BETWEEN '$dateDebut' AND '$dateFin' AND traCat3 = 29 ");
$accident = $accident->fetch();


$heureAbsence = $bdd->query("SELECT  SUM(traHeureTot) FROM tblTravail WHERE tblEmployer_empId = '$id' 
AND traDate BETWEEN '$dateDebut' AND '$dateFin' AND  traCat3 >= 20 AND traCat3  ");
$heureAbsence = $heureAbsence->fetch();

$heureAbsenceTOT = $bdd->query("SELECT SUM(traHeureTot) FROM tblTravail WHERE tblEmployer_empId = '$id' 
AND traDate BETWEEN '$dateDebut' AND '$dateFin' AND  traCat3 IN (20,21,22,23,31,32,33,34,40,41,42) ");
$heureAbsenceTOT = $heureAbsenceTOT->fetch();

$droitPris = $bdd->query("SELECT round(traHeureTot,2), cat3Code FROM tblTravail
LEFT JOIN tblTraCat3 on cat3Id = traCat3 WHERE cat3Id >=3 AND tblEmployer_empId= '$id'");
$droitPris = $droitPris->fetch();

$taux = $bdd->query("SELECT empTaux FROM tblEmployer WHERE empId ='$id' ");
$taux = $taux->fetch();

$apg = $bdd->query("SELECT SUM(traHeureTot)FROM tblTravail WHERE traCat3 = 31 AND  traDate BETWEEN '$dateDebut' AND '$dateFin'
 AND tblEmployer_empId= '$id'");
$apg = $apg->fetch();

$vac = $bdd->query("SELECT SUM(traHeureTot) FROM tblTravail WHERE traCat3 = 21 AND  traDate BETWEEN '$dateDebut' AND '$dateFin'
 AND tblEmployer_empId= '$id'");
$vac = $vac->fetch();

$fer = $bdd->query("SELECT SUM(traHeureTot) FROM tblTravail WHERE traCat3 = 22 AND  traDate BETWEEN '$dateDebut' AND '$dateFin'
 AND tblEmployer_empId= '$id'");
$fer = $fer->fetch();

$cho = $bdd->query("SELECT SUM(traHeureTot) FROM tblTravail WHERE traCat3 = 23 AND  traDate BETWEEN '$dateDebut' AND '$dateFin'
 AND tblEmployer_empId= '$id'");
$cho = $cho->fetch();

$mat = $bdd->query("SELECT SUM(traHeureTot) FROM tblTravail WHERE traCat3 = 32 AND  traDate BETWEEN '$dateDebut' AND '$dateFin'
 AND tblEmployer_empId= '$id'");
$mat = $mat->fetch();

$dem = $bdd->query("SELECT SUM(traHeureTot) FROM tblTravail WHERE traCat3 = 41 AND  traDate BETWEEN '$dateDebut' AND '$dateFin'
 AND tblEmployer_empId= '$id'");
$dem = $dem->fetch();

$mar = $bdd->query("SELECT SUM(traHeureTot) FROM tblTravail WHERE traCat3 = 33 AND  traDate BETWEEN '$dateDebut' AND '$dateFin'
 AND tblEmployer_empId= '$id'");
$mar = $mar->fetch();

$deu = $bdd->query("SELECT round(traHeureTot,2) FROM tblTravail WHERE traCat3 = 40 AND  traDate BETWEEN '$dateDebut' AND '$dateFin'
 AND tblEmployer_empId= '$id'");
$deu = $deu->fetch();
$annee = substr($dateDebut, 0, 4);

$droit = $bdd->query("SELECT * FROM tblDroit WHERE droAnnee = '$annee' AND tblEmplyer_empId = '$id'");
$droit = $droit->fetch();

$heureDiff = $bdd->query("SELECT droDiffe FROM tblDroit WHERE droAnnee = $annee and tblEmplyer_empId = $id");
$heureDiff = $heureDiff->fetch();
$heureDiff = $heureDiff[0] ?? '';


include '../heade.php';

?>
    <nav>
        <ul>
            <li><a href="travail.php">Retour </a></li>
        </ul>
    </nav>
    <h1>Décompte d'heures pour <? echo $contact['empNom'] . ' ' . $contact['empPrenom'] ?> </h1>
    <table>
        <tr>
            <th>Période du</th>
            <th>Heures à <br> effectuer</th>
            <th>Heures <br> consignées</th>
            <th>Absences <br> maladie/accident</th>
            <th>Congés <br>payés</th>
            <th>Total<br>justifié</th>
            <th>Différence</th>
        </tr>
        <?php
        $heureTot = JourOuvrable($_POST['debut'], $_POST['fin']);
        $heureTot = $heureTot * 8.4 / 100 * $taux[0];
        $heureTotEff = $heure[0] + $heureAbsence[0];
        $totMaladi = $maladie[0] + $accident[0];
        ?>
        <tr>
            <td><?php echo dateToUser($dateDebut) . " au " . dateToUser($dateFin); ?></td>
            <!-- si le dans la période la date de début est en janvier les solde des heures est déduit au nombre d'heures à effetuer-->
            <td><?php $mois = substr($dateDebut,5,2);
            if ($mois == 01){
                echo round($heureTot, 2)-$heureDiff;
            }
            else
            {
                echo round($heureTot, 2);
            }
            ?>

            </td>
            <td><?php echo round($heure[0], 2); ?></td>
            <td><?php echo round($totMaladi, 2); ?></td>
            <td><?php echo round($heureAbsenceTOT[0], 2); ?></td>
            <td><?php echo round($heureTotEff, 2) ?></td>
     
            <!-- si le dans la période la date de début est en janvier les solde des heures est déduit au nombre d'heures à effetuer-->
            <td>
                <?php 
                    $mois = substr($dateDebut,5,2);
                    if ($mois == 01)echo round($heureTotEff - $heureTot, 2)+$heureDiff; 
                    else echo round($heureTotEff - $heureTot, 2); 
                ?>
            </td>
        </tr>
    </table>
    <p style="margin-top: -80px; margin-left: 2px: "> La différence d'heures de l'année précédent (soit :<?php echo ' '.($droit['droDiffe'] ?? 0).' ';?> heures) est reportée au 1er janvier </p>

    <h1>Décompte des absences - année <?php echo $annee; ?>  </h1>
    <table>
        <tr>
            <td></td>
            <th>Vacances</th>
            <th>Fériés</th>
            <th>Chômés</th>
            <th>APG</th>
            <th>Maternité</th>
            <th>Maladie</th>
            <th>Accident</th>
            <th>Déménag.</th>
            <th>Deuil</th>
            <th>Mariage</th>
            <th>Solde reporté</th>
        </tr>
        <tr>
            <th>Droit</th>
            <td><?php echo $droit['droVacances'] ?? 0; ?></td>
            <td><?php echo $droit['droFerier'] ?? 0; ?></td>
            <td><?php echo $droit['droChomer'] ?? 0; ?></td>
            <td><?php echo $droit['droAPG'] ?? 0; ?></td>
            <td><?php echo $droit['droMaterniter'] ?? 0; ?></td>
            <td><?php echo $droit['droMaladie'] ?? 0; ?></td>
            <td><?php echo $droit['droAccident'] ?? 0; ?></td>
            <td><?php echo $droit['droDemenagement'] ?? 0; ?></td>
            <td><?php echo $droit['droDeuil'] ?? 0; ?></td>
            <td><?php echo $droit['droMariage'] ?? 0; ?></td>
            <td style="vertical-align: top" rowspan="3"><?php echo $droit['droDiffe'] ?? 0; ?></td>
        </tr>
        <tr>
            <th>Saisis</th>
            <td><?php echo round($vac[0] / 8.4) ?? 0; ?></td>
            <td><?php echo round($fer[0] / 8.4) ?? 0; ?></td>
            <td><?php echo round($cho[0] / 8.4) ?? 0; ?></td>
            <td><?php echo round($apg[0] / 8.4) ?? 0; ?></td>
            <td><?php echo round($mat[0] / 8.4) ?? 0; ?></td>
            <td><?php echo round($maladie[0] / 8.4) ?? 0; ?></td>
            <td><?php echo round($accident[0] / 8.4) ?? 0; ?></td>
            <td><?php echo round($dem[0] / 8.4) ?? 0; ?></td>
            <td><?php echo round(($deu[0] ?? 0) / 8.4) ?? 0; ?></td>
            <td><?php echo round($mar[0] / 8.4) ?? 0; ?></td>
        </tr>
        <tr>
            <th>Solde</th>
            <td><?php echo round(($droit['droVacances'] ?? 0) - (($vac[0] ?? 0) / 8.4)) ?? 0; ?></td>
            <td><?php echo round(($droit['droFerier'] ?? 0) - (($fer[0] ?? 0) / 8.4)) ?? 0; ?></td>
            <td><?php echo round(($droit['droChomer'] ?? 0) - (($cho[0] ?? 0) / 8.4)) ?? 0; ?></td>
            <td><?php echo round(($droit['droDeuil'] ?? 0) - (($deu[0] ?? 0) / 8.4)) ?? 0; ?></td>
            <td><?php echo round(($droit['droMaterniter'] ?? 0) - (($mat[0] ?? 0) / 8.4)) ?? 0; ?></td>
            <td><?php echo round(($droit['droMaladie'] ?? 0) - (($maladie[0] ?? 0) / 8.4)) ?? 0; ?></td>
            <td><?php echo round(($droit['droAccident'] ?? 0) - (($accident[0] ?? 0) / 8.4)) ?? 0; ?></td>
            <td><?php echo round(($droit['droDemenagement'] ?? 0) - (($dem[0] ?? 0) / 8.4)) ?? 0; ?></td>
            <td><?php echo round(($droit['droAPG'] ?? 0) - (($apg[0] ?? 0) / 8.4)) ?? 0; ?></td>
            <td><?php echo round(($droit['droMariage'] ?? 0) - (($mar[0] ?? 0) / 8.4)) ?? 0; ?></td>
        </tr>
    </table>
<?php include "../footer.php"; ?>