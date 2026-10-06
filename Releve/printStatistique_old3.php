
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
<?php
    include("../variables.php");
    setlocale(LC_TIME, 'fr_FR.utf8', 'fra');
    use Spipu\Html2Pdf\Html2Pdf;

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
    $debut         = $_POST['debut'] ?? null;
    $fin           = $_POST['fin'] ?? null;
    $typeR         = (int)($_POST['tServices'] ?? 0);
    $subventionnedStatus = $_POST['subventionnedStatus'];
    $statSubventionned = $subventionnedStatus === 'yes' ? 'Subventionné' : ($subventionnedStatus === 'no' ? 'Non-subventionné' : '');

    // Détermination du type de service
    try{
        if ($typeR === 0) {
            $type = 'tous les services';
        } else {
            $stmt = $bdd->prepare("SELECT tServicesNom FROM tblTypeServices WHERE tServicesId = :id");
            $stmt->execute(['id' => $typeR]);
            $type = $stmt->fetchColumn() ?: "Service inconnu";
        }
    }catch(Exception $e){
        $err = $e->getMessage();
    }

    // Requêtes de statistiques
    $statistique      = getStatistiquesDetaillees($bdd, $subventionnedStatus, $debut, $fin, $typeR);
    $statistiqueResum = getStatistiquesResume($bdd, $debut, $fin, $typeR);
    $colspan8 = ($subventionnedStatus === 'both') ? '8' : '7';
    $colspan4 = ($subventionnedStatus === 'both') ? '4' : '3';
    ?>
    <page backtop="25mm" backbottom="25mm" backleft="0mm" backright="0mm">
        <page_header>
            <table style="width: 100%;">
                <tr>
                    <!-- Logo à gauche -->
                    <td style="width: 30%; vertical-align: top;">
                        <img style="width: 25mm" src="../img/logo.jpg">
                    </td>
                    <!-- Titre à droite -->
                    <td style="width: 70%; text-align: right; vertical-align: top;">
                        <h2 style="margin: 0;">Statistique des prestations par Bénéficiaire</h2>
                        <h2 style="margin: 0; font-weight: normal;">
                            Période considérée : <?= DateToUser($debut) . ' au ' . DateToUser($fin); ?>
                        </h2>
                        <h2></h2>
                        <h2><?php echo $statSubventionned; ?></h2>
                    </td>
                </tr>
            </table>
        </page_header>
        <table style="margin-top: 10mm;">
            <tr>
                <th colspan="<?php echo $colspan8; ?>">Type de service : <?= htmlspecialchars($type) ?></th>
            </tr>
            <tr>
                <th style="width: 15%;">Nom</th>
                <th style="width: 15%;">Prénom</th>
                <th style="width: 15%;">Localité</th>
                <th style="width: 15%;">N° Avs</th>
                <th style="width: 10%;">Moyenne</th>
                <th style="width: 10%;">Nbr</th>
                <th style="width: 10%;">Heures</th>
                <?php 
                    if($subventionnedStatus === 'both'){ 
                        echo '<th style="width: 10%;">Subv.</th>'; 
                    }
                ?>
            </tr>
            <?php
            $region   = "";
            $totNbr   = 0;
            $totHeure = 0;
            $totSubv  = 0;            
            foreach($statistique as $row){ //while ($row = $statistique->fetch()) {
                if ($region !== $row['regConNom']) {
                    if ($region !== "" && $totNbr > 0) {
                        $totMoyenne = $totHeure / $totNbr;
                        if(!$region) $region = 'Ailleurs';
                        echo "<tr style='background-color: #e0ffe0'>
                                <td colspan='".$colspan4."' style='text-align: center'>Totaux pour la région <strong> $region </strong> </td>
                                <td><strong>" . round($totMoyenne, 2) . "</strong></td>
                                <td><strong>$totNbr</strong></td> 
                                <td><strong>$totHeure</strong></td>";
                                if($subventionnedStatus === 'both'){
                                    echo '<td><strong>'.$totSubv.'</strong></td>';
                                } 
                        echo '</tr>';
                        $totNbr = 0;
                        $totHeure = 0;
                        $totSubv = 0;
                    }
                    echo "<tr>
                            <th colspan='$colspan8'><strong>Région : {$row['regConNom']}</region></th>
                        </tr>";
                    $region = $row['regConNom'];
                }
                $nbr     = $row['nbr'];
                $total   = $row['Total'];
                $moyenne = $nbr > 0 ? $total / $nbr : 0;
                $totalSubventionned = $row['totalSubv'];
                echo "<tr>
                        <td>{$row['conNom']}</td>
                        <td>{$row['conPrenom']}</td>
                        <td>{$row['conLocaliter']}</td>
                        <td>{$row['conAvs']}</td>
                        <td>" . round($moyenne, 2) . "</td>
                        <td>$nbr</td>
                        <td>$total</td>";
                if($subventionnedStatus === 'both'){
                    echo '<td>'.$totalSubventionned.'</td>';
                } 
                echo '</tr>';
                $totNbr   += $nbr;
                $totHeure += $total;
                $totSubv  += $totalSubventionned;
            }
            echo "<tr style='background-color: #e0ffe0'>
                    <td colspan='".$colspan4."' style='text-align: center'>Totaux pour la région <strong>$region</strong></td>
                    <td><strong>" . round($totMoyenne, 2) . "</strong></td>
                    <td><strong>$totNbr</strong></td> 
                    <td><strong>$totHeure</strong></td>";
            if ($subventionnedStatus === 'both') {
                echo "<td><strong>$totSubv</strong></td>";
            }
            echo "</tr>";

            /*
            if ($totNbr > 0) {
                $totMoyenne = $totHeure / $totNbr;
                echo "<tr style='background-color: #e0ffe0'>
                        <td style='text-align: right'>Totaux pour la région $region : </td>
                        <td>" . round($totMoyenne, 2) . "</td>
                        <td>$totNbr</td> 
                        <td>$totHeure</td>";
                echo ($subventionnedStatus ==='both') ? "<td>$totSubv</td>" : null;
                echo "</tr>";
            }
            
            $totFinalMoyen = !empty($statistiqueResum['Total'])
                ? $statistiqueResum['Total'] / $statistiqueResum['nbr']
                : 0;*/
            ?>
            <?php /*<tr style="background-color: #e0ffe0">
                <td>Nombre d'intervention type : <?= htmlspecialchars($type) ?></td>
                <td><?= round($totFinalMoyen, 2) ?></td>
                <td><?= $statistiqueResum['nbr'] ?></td>
                <td><?= $statistiqueResum['Total'] ?></td>
            </tr> 
            <?php if($statSubventionned === 'both'){ ?>
                <tr>
                    <td>Subventionnées : </td>
                    <td><?= $statistiqueResum['NbrSubventionnes'] ?></td>
                </tr>
            <?php } ?>*/ ?>
        </table>

        <page_footer>
            <table class="footer" style="width:100%;">
                <tr>
                    <td style="width: 25%">
                        Document produit par
                        <img style="height: 10mm" src="../img/alunis.gif" alt="">
                    </td>
                    <td style="width:50%; text-align: center">
                        Association Cerebral Valais <br>
                        Avenu de tourbillon 9 <br>
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

/**********************************************************/
/***************** display PDF*****************************/
/**********************************************************/

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

// résumé
function getStatistiquesResume(PDO $bdd, string $debut, string $fin, int $typeR): array
{
    $sql = "
        SELECT 
                COUNT(intDate) AS nbr, 
                SUM(intFacturable) AS Total,
                COUNT(CASE WHEN intSubventioner = 1 THEN 1 END) AS NbrSubventionnes,
                COUNT(CASE WHEN intSubventioner = 0 THEN 1 END) AS NbrNonSubventionnes
        FROM tblIntervention
        WHERE intDate BETWEEN :debut AND :fin
          AND intType = :typeR
    ";
    $stmt = $bdd->prepare($sql);
    $stmt->execute([
        'debut' => $debut,
        'fin'   => $fin,
        'typeR' => $typeR
    ]);
    return $stmt->fetch() ?: ['nbr' => 0, 'Total' => 0];
}
// get data
function getStatistiquesDetaillees(PDO $bdd, $subventionnedStatus, string $debut, string $fin, int $typeR){
    try {
        $sql = defineRequestFromContactStatus($subventionnedStatus);
        $stmt = $bdd->prepare($sql);
        $stmt->execute([
            'debut' => $debut,
            'fin'   => $fin,
            'typeR' => $typeR
        ]);
        return $stmt;
    } catch (Exception $e) {
        return null;
    }
}
function defineRequestFromContactStatus($subventionnedStatus){   
    switch($subventionnedStatus){
        case 'yes': 
            return "
                SELECT COUNT(intDate) AS nbr, 
                    SUM(intFacturable) AS Total, 
                    conNom, conPrenom, conLocaliter, 
                    conAvs, conRegion, regConNom, 
                    conId
                FROM tblIntervention
                LEFT JOIN tblContact ON intBeneficiaire = conId
                LEFT JOIN tblRegionCon ON conRegion = regConId 
                WHERE (intDate BETWEEN :debut AND :fin)
                AND (intType = :typeR) AND intSubventioner = 1
                GROUP BY conId
                ORDER BY regConNom, conNom
            ";
            break;
        case 'no':
             return "
                SELECT COUNT(intDate) AS nbr, 
                    SUM(intFacturable) AS Total, 
                    conNom, conPrenom, conLocaliter, 
                    conAvs, conRegion, regConNom, 
                    conId
                FROM tblIntervention
                LEFT JOIN tblContact ON intBeneficiaire = conId
                LEFT JOIN tblRegionCon ON conRegion = regConId 
                WHERE (intDate BETWEEN :debut AND :fin)
                AND (intType = :typeR) AND intSubventioner = 0
                GROUP BY conId
                ORDER BY regConNom, conNom
            "; 
            break; 
        case 'both': 
            return " 
                SELECT COUNT(intDate) AS nbr, 
                    SUM(intFacturable) AS Total, 
                    SUM(intSubventioner) AS totalSubv,
                    conNom, conPrenom, conLocaliter, 
                    conAvs, conRegion, regConNom, 
                    conId
                FROM tblIntervention
                LEFT JOIN tblContact ON intBeneficiaire = conId
                LEFT JOIN tblRegionCon ON conRegion = regConId 
                WHERE intDate BETWEEN :debut AND :fin AND (intType = :typeR)
                GROUP BY conId
                ORDER BY regConNom, conNom
            ";
            break;
    }        
}
?>