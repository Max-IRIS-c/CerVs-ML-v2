<?php
require '../vendor/autoload.php';
use Spipu\Html2Pdf\Html2Pdf;

// Chargement variables
include("../variables.php");
setlocale(LC_TIME, 'fr_FR.utf8', 'fra');

// Connexion DB
try {
    $bdd = new PDO($dsn, $user, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);
} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}

// Récupération POST & GET
$debut = $_POST['debut'] ?? null;
$fin   = $_POST['fin'] ?? null;
$typeR = (int)($_POST['tServices'] ?? 0);

// Détermination du type de service
try {
    if ($typeR === 0) {
        $type = 'tous les services';
    } else {
        $stmt = $bdd->prepare("SELECT tServicesNom FROM tblTypeServices WHERE tServicesId = :id");
        $stmt->execute(['id' => $typeR]);
        $type = $stmt->fetchColumn() ?: "Service inconnu";
    }
} catch (Exception $e) {
    $type = "Erreur récupération type";
}

// Récupération des données
$data = getData($bdd, $debut, $fin, $typeR);
$groupedData = groupResultsByIntervenants($data);

// Démarrage du buffer
ob_start();
?>

<style>
table {
    width: 95%;
    table-layout: fixed;
    border-collapse: collapse;
}
th, td {
    vertical-align: middle;
    text-align: left;
    padding: 2mm 1mm;
    font-size: 11px;
    border-bottom: 0.2mm solid #ccc;
    word-wrap: break-word;
}
th {
    color: #00AA33;
    font-weight: normal;
    border-bottom: solid 0.3mm #000;
}
h2 {
    color: #000;
    margin: 5mm 0;
    font-size: 14px;
    padding-left: 3mm;
}
.footer td {
    font-size: 10px;
    vertical-align: bottom;
}
</style>

<page backtop="25mm" backbottom="25mm" backleft="10mm" backright="10mm">

    <page_header>
        <table style="width: 100%; border: none;">
            <tr>
                <td style="width: 30%; vertical-align: top;">
                    <img style="width: 25mm" src="../img/logo.jpg" alt="logo">
                </td>
                <td style="width: 70%; text-align: right; vertical-align: top;">
                    <h2 style="margin: 0;">Statistique des prestations par Intervenant</h2>
                    <h2 style="margin: 0; font-weight: normal;">
                        Période considérée :
                        <?= htmlspecialchars(DateToUser($debut)) . ' au ' . htmlspecialchars(DateToUser($fin)); ?>
                    </h2>
                    <h2>Type de service : <?= htmlspecialchars($type) ?></h2>
                </td>
            </tr>
        </table>
    </page_header>

    <table style="margin-top: 10mm;">
        <tr style="border-bottom: 1px solid black;">
            <th style="width: 15%;">Nom</th>
            <th style="width: 15%;">Prénom</th>
            <th style="width: 15%;">Localité</th>
            <th style="width: 15%;">N° Avs</th>
            <th style="width: 10%;">Moyenne</th>
            <th style="width: 15%;">Nbr.</th>
            <th style="width: 15%;">Heures</th>
        </tr>

        <?php 
            if ($groupedData): 
                $allMoyennes = 0;
                $totalNbr = 0;
                $totalHeures = 0;
        ?>
            <?php foreach ($groupedData as $intervention): ?>
                <tr style="border-bottom: 1px solid black;">
                    <td colspan="7" style="color: green; padding: 5px 0;">
                        Intervenant : <?= htmlspecialchars($intervention['intervenant']['name']) ?>
                    </td>
                </tr>
            <?php 
                $allMoyennesOfInt = 0;
                $intervenantTotalNbr = 0;
                $intervenantTotalHeures = 0;
                foreach ($intervention['beneficiaires'] as $beneficiaire): 
                    $moyenne = $beneficiaire['nbr_intervention'] > 0 ? round($beneficiaire['heures'] / $beneficiaire['nbr_intervention'], 2) : 0;
                    $allMoyennesOfInt += $moyenne;
                    $intervenantTotalNbr += $beneficiaire['nbr_intervention'];                        
                    $intervenantTotalHeures += $beneficiaire['heures'];
            ?>
            <tr>
                <td><?= htmlspecialchars($beneficiaire['lastname'] ?? '') ?></td>
                <td><?= htmlspecialchars($beneficiaire['firstname'] ?? '') ?></td>
                <td><?= htmlspecialchars($beneficiaire['localite'] ?? '') ?></td>
                <td><?= htmlspecialchars($beneficiaire['avs'] ?? '') ?></td>
                <td><?= $moyenne ?></td>
                <td><?= htmlspecialchars($beneficiaire['nbr_intervention'] ?? 0) ?></td>
                <td><?= htmlspecialchars($beneficiaire['heures'] ?? 0) ?></td>
            </tr>
            <?php 
                endforeach;
                $intervenantTotalMoyennes = round($allMoyennesOfInt/count($intervention['beneficiaires']),2); 
                $allMoyennes += $intervenantTotalMoyennes;
                $totalHeures += $intervenantTotalHeures;
                $totalNbr += $intervenantTotalNbr;
            ?>
            <tr style="background-color:#e0ffe0;">
                <td colspan="2">total pour <strong><?php echo $intervention['intervenant']['name']; ?></strong></td>
                <td colspan="2"><?php echo count($intervention['beneficiaires']); ?> bénéficiaires</td>
                <td><strong><?php echo $intervenantTotalMoyennes; ?></strong></td>
                <td><strong><?php echo $intervenantTotalNbr; ?></strong></td>
                <td><strong><?php echo $intervenantTotalHeures; ?></strong></td>
            </tr>
        <?php endforeach; ?>
        <?php else: ?>
        <tr>
            <td colspan="7" style="text-align: center; padding: 10px; color: red;">
                Aucune donnée disponible
            </td>
        </tr>
        <?php 
            endif;
            $totalMoyennes = round($allMoyennes/count($groupedData), 2);  
        ?>
        <tr style="padding: 15px;"></tr>
        <tr style="background-color:#e0ffe0;">
            <td colspan="4">Totaux : </td>
            <td><strong><?php echo $totalMoyennes; ?></strong></td>
            <td><strong><?php echo $totalNbr; ?></strong></td>
            <td><strong><?php echo $totalHeures; ?></strong></td>
        </tr>
        <tr style="background-color:#e0ffe0;">
            <td colspan="4">Nombre total d'intervenants : </td>
            <td colspan="3"><strong><?php echo count($groupedData); ?></strong></td>
        </tr>
    </table>

    <page_footer>
        <table class="footer" style="width:100%;">
            <tr>
                <td style="width: 25%">
                    Document produit par<br>
                    <img style="height: 10mm" src="../img/alunis.gif" alt="logo alunis">
                </td>
                <td style="width:50%; text-align: center">
                    Association Cerebral Valais <br>
                    Avenue de Tourbillon 9 <br>
                    Tel. 027 346 70 44
                </td>
                <td style="text-align: right;width: 25%">
                    www.cerebral-vs.ch <br>
                    1950 Sion <br>
                    info@cerebral-vs.ch
                </td>
            </tr>
        </table>
    </page_footer>
</page>

<?php
$content = ob_get_clean();

try {
    $pdf = new Html2Pdf('P', 'A4', 'fr');
    $pdf->pdf->SetDisplayMode('fullpage');
    $pdf->writeHTML($content);
    $pdf->output('StatistiquePrestations.pdf', 'I'); // I = inline dans navigateur
} catch (\Spipu\Html2Pdf\Exception\Html2PdfException $e) {
    die($e);
}

/**********************************************************/
/***************** functions ******************************/
/**********************************************************/
function groupResultsByIntervenants($givenData) {
    try {
        $finalResult = [];
        if (!$givenData || empty($givenData)) {
            throw new Exception();
        }
        foreach ($givenData as $intervention) {
            $idIntervenant = $intervention['intervenant_id'];
            if (!isset($finalResult[$idIntervenant]['intervenant'])) {
                $finalResult[$idIntervenant] = [
                    'intervenant' => [
                        "name" => $intervention['intervenant_nom']." ".$intervention['intervenant_prenom']
                    ]
                ];
            }
            $finalResult[$idIntervenant]['beneficiaires'][] = [
                "id" => $intervention['beneficiaire_id'],
                "lastname" => $intervention['beneficiaire_nom'],
                "firstname" => $intervention['beneficiaire_prenom'],
                "localite" => $intervention['beneficiaire_localite'],
                "avs" => $intervention['beneficiaire_avs'],
                "nbr_intervention" => $intervention['nombre_interventions'],
                "heures" => $intervention['total_heures']
            ];
        }
        return $finalResult;
    } catch (Exception $e) {
        return null;
    }
}

function getData($bdd, $debut, $fin, $typeR) {
    try {
        $sql = "SELECT 
                    Intervention.intIntervenant               AS intervenant_id,
                    Intervenant.conNom                        AS intervenant_nom,
                    Intervenant.conPrenom                     AS intervenant_prenom,
                    Beneficiaire.conId                        AS beneficiaire_id,
                    Beneficiaire.conNom                       AS beneficiaire_nom,
                    Beneficiaire.conPrenom                    AS beneficiaire_prenom,
                    Beneficiaire.conAvs                       AS beneficiaire_avs,
                    Beneficiaire.conLocaliter                 AS beneficiaire_localite,
                    COUNT(Intervention.intId)                 AS nombre_interventions,
                    SUM(Intervention.intFacturable)           AS total_heures
                FROM tblIntervention Intervention
                INNER JOIN tblContact Intervenant ON Intervention.intIntervenant = Intervenant.conId
                INNER JOIN tblContact Beneficiaire ON Intervention.intBeneficiaire = Beneficiaire.conId
                WHERE Intervention.intDate BETWEEN :debut AND :fin
                AND Intervention.intType = :typeR
                GROUP BY Intervention.intIntervenant, Beneficiaire.conId
                ORDER BY Intervenant.conNom, Beneficiaire.conNom;";
        $stmt = $bdd->prepare($sql);
        $stmt->execute([
            'debut' => $debut,
            'fin'   => $fin,
            'typeR' => $typeR
        ]);
        return $stmt->fetchAll();
    } catch (Exception $e) {
        return null;
    }
}

