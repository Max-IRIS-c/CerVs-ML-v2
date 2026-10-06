<?
$bdd = new PDO($dsn, $user, $password);

$employer = $bdd->query("SELECT empNom,empPrenom,fonNom FROM tblEmployer
LEFT JOIN tblFonction on tblFonction_fonId = fonId WHERE empId =$id");
$employer = $employer->fetch();

$ventilation = $bdd->query("SELECT 
round(sum(if(tblTravail.traCat1 = 3,traHeureTot,0)),2) as 'sommeKga2a',
round(sum(if(tblTravail.traCat1 = 4,traHeureTot,0)),2) as 'sommeKga2p',
round(sum(if(tblTravail.traCat1 = 5,traHeureTot,0)),2) as 'sommeKga3a', 
round(sum(if(tblTravail.traCat1 = 6,traHeureTot,0)),2) as 'sommeKga3p',
round(sum(if(tblTravail.traCat3 != 2,traHeureTot,0)),2) as 'sommeH74',
round(sum(if(tblTravail.traCat1 = 1, traHeureTot, 0)),2) as 'sommeCSAA',
round(sum(if(tblTravail.traCat1 = 2, traHeureTot, 0)),2) as 'sommeCSAP',
tblEmployer_empId,traDate,conId, conNom,conPrenom
FROM tblTravail
LEFT JOIN tblContact ON tblContact_conId = conId
 WHERE (tblEmployer_empId = $id) AND (traDate BETWEEN '$debut' AND '$fin') AND (tblContact_conId is not null) 
GROUP BY  conNom ORDER BY conNom");


$tpstotal = $bdd->query("SELECT round(sum(traHeureTot),2)FROM tblTravail WHERE tblEmployer_empId = $id AND traDate BETWEEN '$debut' AND '$fin' AND tblContact_conId is not null
ANd tblContact_conId != 2146190079");
$tpstotal = $tpstotal->fetch();



use Spipu\Html2Pdf\Html2Pdf;

ob_start();
?>
<style>
    * {

        margin: 0;
        padding: 0;
        color: #000;
        font-family: "helvetica", sans-serif;
    }
    .strong{
        color: white;
    }
    .whi{
        color: white;
    }
    table {

        margin-top: 10px;
        width: 100%;
        color: #9A0000;
    }
    .greyy{
        background-color: grey;
    }
    td {

        padding-left: 2mm;

        vertical-align: middle;
        text-align: left;

    }

    th {
        height: 5mm;
        padding-left: 2mm;
        vertical-align: middle;
        text-align: left;
        color: white;
        background-color: #4b86c1;
        font-size: 12px;
        font-style: normal;
        font-weight: normal !important;
    }

    .footer td {
        vertical-align: bottom
    }

    h2 {
        background-color: #00AA00;
        color: #fff;
        margin: 0;
        width: 25mm;
        font-size: 14px;
    }


</style>

<page backtop="20mm" backleft="2mm" backbottom="15mm">
    <page_header>
        <table>

            <tr>
                <td style=" width:30%;  vertical-align: top;  padding: 0"><img style="height: 10mm"
                                                                               src="../img/logo.jpg" alt=""></td>
                <td style="text-align: right;width:70%;  vertical-align: middle"> Rapport des temps de travail <br>
                    <strong>Critères dossiers Cerebral, ventilation / Bénéficiaire / employé(e)</strong></td>
            </tr>
        </table>
    </page_header>
    <page_footer>
        <?php include('../footerPrint.php'); ?>
    </page_footer>    
    <table style="margin-left: 35mm">
        <tr>
            <td style="width: 66mm"><strong><?php echo $employer['empNom']; ?></strong></td>
            <td style="width: 35mm">Fonction :</td>
            <td width=""><strong><?php echo $employer['fonNom']; ?></strong></td>
        </tr>
        <tr>
            <td style="width: 66mm"><strong><?php echo $employer['empPrenom']; ?></strong></td>
            <td style="width: 35mm">Période considérée :</td>
            <td width=""><?php echo DateToUser($debutSql) . ' au ' . DateToUser($finSql); ?></td>
        </tr>
    </table>
    <?php
    $compte = 0;
    $totalKGA2 = 0;
    $totalKGA3 = 0;
    $totalH74 = 0;
    $totalCSA = 0;
    ?>

    <table>
        <tr>
            <th> Bénéficiaire </th>
            <th class="strong"> Heures <strong class="strong">CS A </strong></th>
            <th class="strong"> Heures <strong class="strong">PPA </strong></th>
            <th class="strong"> Heures <strong class="strong">CPP </strong></th>
            <th style="background-color: #546e7a;"  class="strong"> Total <strong class="strong">Art. 74</strong></th>        
            <th style="background-color: #009e3a;"  class="strong"> Heures <strong class="strong">Hors Art. 74 </strong></th>
        </tr>
        <?php
        while ($data = $ventilation->fetch()) {
            $totalCs = $data['sommeCSAP']+$data['sommeCSAA'];
            $total1 = $data['sommeKga2a']+$data['sommeKga2p'];
            $total2 = $data['sommeKga3a']+$data['sommeKga3p'];
            $totalArt74 = $totalCs + $total1 + $total2;
            ?>

            <tr>
                <td style="border-bottom: dotted 1px; width: 50mm;"><?= $data['conNom'].' '.$data['conPrenom'] ?></td>
                <td style="border-bottom: dotted 1px;"><?= number_format($totalCs, 2, '.', ''); ?></td>
                <td style="border-bottom: dotted 1px; "><?= number_format($total1, 2, '.', ''); ?></td>
                <td style="border-bottom: dotted 1px; "><?= number_format($total2, 2, '.', ''); ?></td>
                <td style="border-bottom: dotted 1px; background-color: #cfd8dc;"><?= number_format($totalArt74, 2, '.', ''); ?></td>
                <td style="border-bottom: dotted 1px; background-color: #b9f6ca;"><?= $data['sommeH74'] ?></td>
            </tr>
            <?  
                $totalCSA += $data['sommeCSAA']+$data['sommeCSAP'];
                $totalKGA2 += $data['sommeKga2a']+$data['sommeKga2p'];
                $totalKGA3 += $data['sommeKga3a']+$data['sommeKga3p'];                
                $totalArt74 += $totalKGA3 + $totalKGA2 + $totalCSA;
                $totalH74 += $data['sommeH74'];
            } ?>
        <tr>
            <th colspan="1" style="text-align: right">Total:</th>
            <th><strong class="strong">CS A: <?= number_format($totalCSA, 2, '.', ''); ?></strong></th>
            <th><strong class="strong">PPA: <?= number_format($totalKGA2, 2, '.', ''); ?></strong></th>
            <th><strong class="strong">CPP: <?= number_format($totalKGA3, 2, '.', ''); ?></strong></th>
            <th style="background-color: #546e7a;"><strong  class="strong">Art. 74:  <?= $totalArt74 ?></strong></th>
            <th style="background-color: #009e3a;"><strong  class="strong">Hors 74:  <?= $totalH74 ?></strong></th>
        </tr>
    </table>

</page>


<?php

try {
    $content = ob_get_clean();
    require _('../vendor/autoload.php');
    $family = 'coucou';
    $style = 'regular';
    $file = '../vendor/tecnickcom/tcpdf/fonts/helvetica.php';
    $pdf = new HTML2PDF('P', 'A4', 'fr');
    $pdf->pdf->SetDisplayMode('fullwidth', 'tworight');

    $pdf->writeHTML($content);

    $pdf->addFont($family, $style, $file);
ob_get_clean();
    $pdf->output('FicheIntervention.pdf');
} catch (\Spipu\Html2Pdf\Exception\Html2PdfException $e) {
    die($e);
};