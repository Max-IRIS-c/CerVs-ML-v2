<?php
include '../variables.php';

// Initialisation des variables pour éviter les erreurs si les requêtes échouent
$contact = null;
$heure = [0];
$maladie = [0];
$accident = [0];
$heureAbsence = [0];
$heureAbsenceTOT = [0];
$droitPris = [];
$taux = [0];
$apg = [0];
$vac = [0];
$fer = [0];
$cho = [0];
$mat = [0];
$dem = [0];
$mar = [0];
$deu = [0];
$droit = [];
$heureDiff = 0;

try {
    $bdd = new PDO($dsn, $user, $password);
    $bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}

$dateDebut = $_POST['debut'] ?? date('Y-m-d');
$dateFin = $_POST['fin'] ?? date('Y-m-d');
$id = $idUtilisateur ?? 0;

// Récupération infos employé
$contactReq = $bdd->query("SELECT * FROM tblEmployer WHERE empId = $id");
$contact = $contactReq->fetch();

if (!$contact) {
    die("Employé non trouvé.");
}

// Récupération heures travaillées
$heureReq = $bdd->query("SELECT SUM(traHeureTot) FROM tblTravail WHERE tblEmployer_empId = '$id' 
AND traDate BETWEEN '$dateDebut' AND '$dateFin' AND traCat3 <= 10");
$heure = $heureReq->fetch();

// Récupération maladie
$maladieReq = $bdd->query("SELECT SUM(traHeureTot) FROM tblTravail WHERE tblEmployer_empId = '$id' 
AND traDate BETWEEN '$dateDebut' AND '$dateFin' AND traCat3 = 30");
$maladie = $maladieReq->fetch();

// Récupération accident
$accidentReq = $bdd->query("SELECT SUM(traHeureTot) FROM tblTravail WHERE tblEmployer_empId = '$id' 
AND traDate BETWEEN '$dateDebut' AND '$dateFin' AND traCat3 = 29");
$accident = $accidentReq->fetch();

// CORRECTION ICI : Requête complétée (manquait la fin de la condition)
$heureAbsenceReq = $bdd->query("SELECT SUM(traHeureTot) FROM tblTravail WHERE tblEmployer_empId = '$id' 
AND traDate BETWEEN '$dateDebut' AND '$dateFin' AND traCat3 >= 20 AND traCat3 <= 49");
$heureAbsence = $heureAbsenceReq->fetch();

$heureAbsenceTOTReq = $bdd->query("SELECT SUM(traHeureTot) FROM tblTravail WHERE tblEmployer_empId = '$id' 
AND traDate BETWEEN '$dateDebut' AND '$dateFin' AND traCat3 IN (20,21,22,23,31,32,33,34,40,41,42)");
$heureAbsenceTOT = $heureAbsenceTOTReq->fetch();

// Récupération droits (attention au nom de la table/colonne)
// CORRECTION ICI : tblEmplyer_empId corrigé en tblEmployer_empId (supposition basée sur la cohérence)
// Si votre colonne s'appelle vraiment 'tblEmplyer_empId', remettez l'ancienne orthographe.
$droitReq = $bdd->query("SELECT * FROM tblDroit WHERE droAnnee = '$annee' AND tblEmployer_empId = '$id'");
$droit = $droitReq->fetch();

$tauxReq = $bdd->query("SELECT empTaux FROM tblEmployer WHERE empId ='$id'");
$taux = $tauxReq->fetch();

// Détail des absences
$apgReq = $bdd->query("SELECT SUM(traHeureTot) FROM tblTravail WHERE traCat3 = 31 AND traDate BETWEEN '$dateDebut' AND '$dateFin' AND tblEmployer_empId= '$id'");
$apg = $apgReq->fetch();

$vacReq = $bdd->query("SELECT SUM(traHeureTot) FROM tblTravail WHERE traCat3 = 21 AND traDate BETWEEN '$dateDebut' AND '$dateFin' AND tblEmployer_empId= '$id'");
$vac = $vacReq->fetch();

$ferReq = $bdd->query("SELECT SUM(traHeureTot) FROM tblTravail WHERE traCat3 = 22 AND traDate BETWEEN '$dateDebut' AND '$dateFin' AND tblEmployer_empId= '$id'");
$fer = $ferReq->fetch();

$choReq = $bdd->query("SELECT SUM(traHeureTot) FROM tblTravail WHERE traCat3 = 23 AND traDate BETWEEN '$dateDebut' AND '$dateFin' AND tblEmployer_empId= '$id'");
$cho = $choReq->fetch();

$matReq = $bdd->query("SELECT SUM(traHeureTot) FROM tblTravail WHERE traCat3 = 32 AND traDate BETWEEN '$dateDebut' AND '$dateFin' AND tblEmployer_empId= '$id'");
$mat = $matReq->fetch();

$demReq = $bdd->query("SELECT SUM(traHeureTot) FROM tblTravail WHERE traCat3 = 41 AND traDate BETWEEN '$dateDebut' AND '$dateFin' AND tblEmployer_empId= '$id'");
$dem = $demReq->fetch();

$marReq = $bdd->query("SELECT SUM(traHeureTot) FROM tblTravail WHERE traCat3 = 33 AND traDate BETWEEN '$dateDebut' AND '$dateFin' AND tblEmployer_empId= '$id'");
$mar = $marReq->fetch();

$deuReq = $bdd->query("SELECT SUM(traHeureTot) FROM tblTravail WHERE traCat3 = 40 AND traDate BETWEEN '$dateDebut' AND '$dateFin' AND tblEmployer_empId= '$id'");
$deu = $deuReq->fetch();

$annee = substr($dateDebut, 0, 4);

// Récupération différence heures
$heureDiffReq = $bdd->query("SELECT droDiffe FROM tblDroit WHERE droAnnee = $annee AND tblEmployer_empId = $id");
$resDiff = $heureDiffReq->fetch();
$heureDiff = $resDiff[0] ?? 0;

include '../heade.php';
?>

<nav>
    <ul>
        <li><a href="travail.php">Retour</a></li>
    </ul>
</nav>

<h1>Décompte d'heures pour <?php echo htmlspecialchars($contact['empNom'] . ' ' . $contact['empPrenom']); ?></h1>

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
    // Calculs sécurisés avec opérateur ?? pour éviter les erreurs sur les valeurs NULL
    $heureTotCalc = JourOuvrable($_POST['debut'], $_POST['fin']);
    $tauxHoraire = $taux[0] ?? 0;
    $heureTot = ($heureTotCalc * 8.4 / 100 * $tauxHoraire);
    
    $hCons = $heure[0] ?? 0;
    $hAbs = $heureAbsence[0] ?? 0;
    $hMal = $maladie[0] ?? 0;
    $hAcc = $accident[0] ?? 0;
    $hAbsTot = $heureAbsenceTOT[0] ?? 0;

    $heureTotEff = $hCons + $hAbs;
    $totMaladi = $hMal + $hAcc;
    
    // Gestion du report d'heures si mois de janvier
    $mois = substr($dateDebut, 5, 2);
    $heuresAEffectuer = ($mois == '01') ? (round($heureTot, 2) - $heureDiff) : round($heureTot, 2);
    $diffFinal = ($mois == '01') ? (round($heureTotEff - $heureTot, 2) + $heureDiff) : round($heureTotEff - $heureTot, 2);
    ?>
    <tr>
        <td><?php echo dateToUser($dateDebut) . " au " . dateToUser($dateFin); ?></td>
        <td><?php echo $heuresAEffectuer; ?></td>
        <td><?php echo round($hCons, 2); ?></td>
        <td><?php echo round($totMaladi, 2); ?></td>
        <td><?php echo round($hAbsTot, 2); ?></td>
        <td><?php echo round($heureTotEff, 2); ?></td>
        <td><?php echo $diffFinal; ?></td>
    </tr>
</table>

<p style="margin-top: -80px; margin-left: 2px;">
    La différence d'heures de l'année précédente (soit : <?php echo $heureDiff; ?> heures) est reportée au 1er janvier.
</p>

<h1>Décompte des absences - année <?php echo $annee; ?></h1>
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
        <!-- Utilisation de ?? 0 si la ligne tblDroit n'existe pas -->
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
        <td style="vertical-align: top" rowspan="3"><?php echo $heureDiff; ?></td>
    </tr>
    <tr>
        <th>Saisis</th>
        <td><?php echo round(($vac[0] ?? 0) / 8.4); ?></td>
        <td><?php echo round(($fer[0] ?? 0) / 8.4); ?></td>
        <td><?php echo round(($cho[0] ?? 0) / 8.4); ?></td>
        <td><?php echo round(($apg[0] ?? 0) / 8.4); ?></td>
        <td><?php echo round(($mat[0] ?? 0) / 8.4); ?></td>
        <td><?php echo round(($maladie[0] ?? 0) / 8.4); ?></td>
        <td><?php echo round(($accident[0] ?? 0) / 8.4); ?></td>
        <td><?php echo round(($dem[0] ?? 0) / 8.4); ?></td>
        <td><?php echo round(($deu[0] ?? 0) / 8.4); ?></td>
        <td><?php echo round(($mar[0] ?? 0) / 8.4); ?></td>
    </tr>
    <tr>
        <th>Solde</th>
        <td><?php echo round(($droit['droVacances'] ?? 0) - (($vac[0] ?? 0) / 8.4)); ?></td>
        <td><?php echo round(($droit['droFerier'] ?? 0) - (($fer[0] ?? 0) / 8.4)); ?></td>
        <td><?php echo round(($droit['droChomer'] ?? 0) - (($cho[0] ?? 0) / 8.4)); ?></td>
        <!-- Correction cohérence : APG -->
        <td><?php echo round(($droit['droAPG'] ?? 0) - (($apg[0] ?? 0) / 8.4)); ?></td>
        <td><?php echo round(($droit['droMaterniter'] ?? 0) - (($mat[0] ?? 0) / 8.4)); ?></td>
        <td><?php echo round(($droit['droMaladie'] ?? 0) - (($maladie[0] ?? 0) / 8.4)); ?></td>
        <td><?php echo round(($droit['droAccident'] ?? 0) - (($accident[0] ?? 0) / 8.4)); ?></td>
        <td><?php echo round(($droit['droDemenagement'] ?? 0) - (($dem[0] ?? 0) / 8.4)); ?></td>
        <!-- Correction cohérence : Deuil -->
        <td><?php echo round(($droit['droDeuil'] ?? 0) - (($deu[0] ?? 0) / 8.4)); ?></td>
        <!-- Correction cohérence : Mariage -->
        <td><?php echo round(($droit['droMariage'] ?? 0) - (($mar[0] ?? 0) / 8.4)); ?></td>
    </tr>
</table>

<?php include "../footer.php"; ?>
