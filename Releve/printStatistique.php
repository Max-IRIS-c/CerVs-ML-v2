<?php
    require '../vendor/autoload.php';
    include("../variables.php");

    use Spipu\Html2Pdf\Html2Pdf;

    setlocale(LC_TIME, 'fr_FR.utf8', 'fra');

    // Connexion BDD
    try {
        $bdd = new PDO($dsn, $user, $password, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]);
    } catch (PDOException $e) {
        die("Erreur de connexion : " . $e->getMessage());
    }

    // Récupération POST & GET
    $debut              = $_POST['debut'] ?? null;
    $fin                = $_POST['fin'] ?? null;
    $typeR              = (int)($_POST['tServices'] ?? 0);
    $subventionnedStatus = $_POST['subventionnedStatus'] ?? 'both';

    $statSubventionned = $subventionnedStatus === 'yes' ? 
    'Subventionné' : ($subventionnedStatus === 'no' ? 
    'Non-subventionné' : 'Subventionné et non-subventionné');

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
        $type = "Erreur";
    }

    // Requêtes statistiques
    $statistique      = getStatistiquesDetaillees($bdd, $subventionnedStatus, $debut, $fin, $typeR);
    $statistiqueResum = getStatistiquesResume($bdd, $debut, $fin, $typeR, $subventionnedStatus);
    $colspan8 = ($subventionnedStatus === 'both') ? 8 : 7;
    $colspan4 = ($subventionnedStatus === 'both') ? 4 : (($subventionnedStatus ===  'yes') ? 4 : 4);

    ob_start();
?>

<style>
    /*
    * {
        margin: 0;
        padding: 0;
        font-family: "helvetica", sans-serif;
    }*/
    table {
        width: 95%;
        table-layout: fixed; /* colonnes fixes */
        border-collapse: collapse;
    }
    th, td {
        margin-right: 5em;
        vertical-align: middle;
        text-align: left;
        padding-top: 2mm;
        padding-bottom: 2mm;
        font-size: 11px;
        border-bottom: 0.2mm solid #ccc;
        word-wrap: break-word;
        overflow: hidden;
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
                <td style="width: 30%; border: none;">
                    <img style="width: 25mm" src="../img/logo.jpg">
                </td>
                <td style="width: 70%; text-align: right; border: none;">
                    <h2>Statistique des prestations par Bénéficiaire</h2>
                    <h2 style="font-weight: normal;">
                        Période considérée : <?= htmlspecialchars(DateToUser($debut)) ?> au <?= htmlspecialchars(DateToUser($fin)) ?>
                    </h2>
                    <h2>Type de service : <?= htmlspecialchars($type) ." - ". htmlspecialchars($statSubventionned) ?></h2>
                </td>
            </tr>
        </table>
    </page_header>
    <table style="padding-top: 20mm;">
        <tr>
            <th style="width: 15%;">Nom</th>
            <th style="width: 15%;">Prénom</th>
            <th style="width: 15%;">Localité</th>
            <th style="width: 15%;">N° Avs</th>
            <th style="width: 10%;">Moyenne</th>
            <th style="width: 10%;">Nbr</th>
            <th style="width: 10%;">Heures</th>
            <?php if ($subventionnedStatus === 'both'): ?>
                <th style="width: 10%;">Subv.</th>
            <?php endif; ?>
        </tr>

        <?php
        $region   = "";
        $totNbr   = 0;
        $totHeure = 0;
        $totSubv  = 0;
        $allMoyennes = [];
        

        foreach ($statistique as $row):
            if ($region !== $row['regConNom']):
                // affichage totaux région précédente
                if ($region !== "" && $totNbr > 0):
                    $totMoyenne = $totHeure / $totNbr;
                    $allMoyennes[] = $totMoyenne;
                    ?>
                    <tr style="background-color:#e0ffe0;">
                        <td colspan="4" style="text-align:center">
                            Totaux pour la région <strong><?= htmlspecialchars($region) ?></strong>
                        </td>
                        <td><strong><?= round($totMoyenne, 2) ?></strong></td>
                        <td><strong><?= $totNbr ?></strong></td>
                        <td><strong><?= $totHeure ?></strong></td>
                        <?php if ($subventionnedStatus === 'both'): ?>
                            <td><strong><?= $totSubv ?></strong></td>
                        <?php endif; ?>
                    </tr>
                    <?php
                    $totNbr = $totHeure = $totSubv = 0;
                endif;
                ?>
                <tr>
                    <th colspan="<?= $colspan8 ?>">Région : <?= htmlspecialchars($row['regConNom']) ?></th>
                </tr>
                <?php
                $region = $row['regConNom'];
            endif;

            $nbr     = $row['nbr'];
            $total   = $row['Total'];
            $moyenne = $nbr > 0 ? $total / $nbr : 0;
            $totalSubventionned = $row['totalSubv'] ?? 0;
            ?>
            <tr>
                <td><?= htmlspecialchars($row['conNom']) ?></td>
                <td><?= htmlspecialchars($row['conPrenom']) ?></td>
                <td><?= htmlspecialchars($row['conLocaliter']) ?></td>
                <td><?= htmlspecialchars($row['conAvs']) ?></td>
                <td><?= round($moyenne, 2) ?></td>
                <td><?= $nbr ?></td>
                <td><?= $total ?></td>
                <?php if ($subventionnedStatus === 'both'): ?>
                    <td><?= $totalSubventionned ?></td>
                <?php endif; ?>
            </tr>
            <?php
            $totNbr   += $nbr;
            $totHeure += $total;
            $totSubv  += $totalSubventionned;
        endforeach;

        if ($region !== "" && $totNbr > 0):
            $totMoyenne = $totHeure / $totNbr;
            ?>
            <tr style="background-color:#e0ffe0; padding-bottom: 10px;">
                <td colspan="<?= $colspan4 ?>" style="text-align:center">
                    Totaux pour la région <strong><?= htmlspecialchars($region) ?></strong>
                </td>
                <td><strong><?= round($totMoyenne, 2) ?></strong></td>
                <td><strong><?= $totNbr ?></strong></td>
                <td><strong><?= $totHeure ?></strong></td>
                <?php if ($subventionnedStatus === 'both'): ?>
                    <td><strong><?= $totSubv ?></strong></td>
                <?php endif; ?>
            </tr>
        <?php endif; ?>
        <?php 
            $moyenne = count($allMoyennes) <= 0 ? 0 : array_sum($allMoyennes)/count($allMoyennes);
            $finalMoyenne = htmlspecialchars(round($moyenne), 2); 
            $finalNbr = htmlspecialchars($statistiqueResum['nbr']);
            $finalHeures = htmlspecialchars($statistiqueResum['Total']);
        ?>
        <tr style="background-color:#e0ffe0; padding-bottom: 10px;">
            <td colspan="<?= $colspan4 ?>" style="text-align:center">Totaux de toutes les régions</td>
            <td><strong><?php echo $finalMoyenne ?? '0.00'; ?></strong></td>
            <td><strong><?php echo $finalNbr ?? '0.00'; ?></strong></td>
            <td><strong><?php echo $finalHeures ?? '0.00' ?></strong></td>
            <?php if ($subventionnedStatus === 'both'): ?>
            <td><strong><?php echo htmlspecialchars($statistiqueResum['NbrSubventionnes']); ?></strong></td>
            <?php endif; ?>
        </tr>
        <tr style="background-color:#e0ffe0; padding-bottom: 10px;">
            <td colspan="<?= $colspan4+1 ?>" style="text-align:center">Nombre total de bénéficiaires</td>
            <td colspan="3"><strong><?php echo $statistiqueResum['totalBenef']; ?></strong></td>
        </tr>
    </table>
    <page_footer>
        <table class="footer" style="width:100%; border:none;">
            <tr>
                <td style="width: 25%; border:none;">
                    Document produit par<br>
                    <img style="height: 10mm" src="../img/alunis.gif" alt="">
                </td>
                <td style="width:50%; text-align: center; border:none;">
                    Association Cerebral Valais <br>
                    Avenue de Tourbillon 9 <br>
                    Tel. 027 346 70 44
                </td>
                <td style="width: 25%; text-align: right; border:none;">
                    www.cerebral-vs.ch <br>
                    1950 Sion <br>
                    info@cerebral-vs.ch
                </td>
            </tr>
        </table>
    </page_footer>
</page>
<?php
try {
    $content = ob_get_clean();
    require '../vendor/autoload.php';
    $pdf = new Html2Pdf('P', 'A4', 'fr');
    $pdf->pdf->SetDisplayMode('fullpage', 'twopage');
    $pdf->writeHTML($content);
    $pdf->output('StatistiquePrestations.pdf');
} catch (\Spipu\Html2Pdf\Exception\Html2PdfException $e) {
    die($e);
}

/**********************************************************/
/***************** functions ******************************/
/**********************************************************/

function getStatistiquesResume(PDO $bdd, string $debut, string $fin, int $typeR, string $subventionnedStatus): array
{
    $whereSubvention = $subventionnedStatus === 'yes' ? ' AND intSubventioner = 1' : ($subventionnedStatus === 'no' ? ' AND intSubventioner IS NULL' : '');
    $sql = "
        SELECT 
            COUNT(DISTINCT intBeneficiaire) AS totalBenef,
            COUNT(intDate) AS nbr, 
            SUM(intFacturable) AS Total,
            SUM(CASE WHEN intSubventioner = 1 THEN intFacturable ELSE 0 END) AS NbrSubventionnes,
            SUM(CASE WHEN intSubventioner IS NULL THEN intFacturable ELSE 0 END) AS NbrNonSubventionnes
        FROM tblIntervention
        WHERE intDate BETWEEN :debut AND :fin
          AND intType = :typeR $whereSubvention
    ";
    $stmt = $bdd->prepare($sql);
    $stmt->execute([
        'debut' => $debut,
        'fin'   => $fin,
        'typeR' => $typeR
    ]);
    return $stmt->fetch() ?: [
        'nbr' => 0, 
        'Total' => 0,
        'NbrSubventionnes' => 0,
        'NbrNonSubventionnes' => 0
    ];
}
function getStatistiquesDetaillees(PDO $bdd, string $subventionnedStatus, string $debut, string $fin, int $typeR)
{
    try{
        $sql = defineRequestFromContactStatus($subventionnedStatus);
        $stmt = $bdd->prepare($sql);
        $stmt->execute([
            'debut' => $debut,
            'fin'   => $fin,
            'typeR' => $typeR
        ]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }catch(Exception $e){
        return [];
    }
}

function defineRequestFromContactStatus(string $subventionnedStatus): string
{
    switch ($subventionnedStatus) {
        case 'yes':
            return "
                SELECT COUNT(intDate) AS nbr, 
                       SUM(intFacturable) AS Total, 
                       conNom, conPrenom, conLocaliter, 
                       conAvs, conRegion, regConNom, conId
                FROM tblIntervention
                LEFT JOIN tblContact ON intBeneficiaire = conId
                LEFT JOIN tblRegionCon ON conRegion = regConId 
                WHERE (intDate BETWEEN :debut AND :fin)
                  AND (intType = :typeR) 
                  AND intSubventioner = 1
                GROUP BY conId
                ORDER BY regConNom, conNom
            ";
        case 'no':
            return "
                SELECT COUNT(intDate) AS nbr, 
                       SUM(intFacturable) AS Total, 
                       conNom, conPrenom, conLocaliter, 
                       conAvs, conRegion, regConNom, conId
                FROM tblIntervention
                LEFT JOIN tblContact ON intBeneficiaire = conId
                LEFT JOIN tblRegionCon ON conRegion = regConId 
                WHERE (intDate BETWEEN :debut AND :fin)
                  AND (intType = :typeR) 
                  AND (intSubventioner IS NULL OR intSubventioner = 0)
                GROUP BY conId
                ORDER BY regConNom, conNom
            ";
        case 'both':
        default:
            return "
                SELECT COUNT(intDate) AS nbr, 
                       SUM(intFacturable) AS Total, 
                       SUM(CASE WHEN intSubventioner = 1 THEN intFacturable ELSE 0 END) AS totalSubv,
                       conNom, conPrenom, conLocaliter, 
                       conAvs, conRegion, regConNom, conId
                FROM tblIntervention
                LEFT JOIN tblContact ON intBeneficiaire = conId
                LEFT JOIN tblRegionCon ON conRegion = regConId 
                WHERE intDate BETWEEN :debut AND :fin 
                  AND (intType = :typeR)
                GROUP BY conId
                ORDER BY regConNom, conNom
            ";
    }
}
