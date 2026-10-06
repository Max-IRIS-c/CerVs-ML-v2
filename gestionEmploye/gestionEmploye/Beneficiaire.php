<?
$bdd = new PDO($dsn, $user, $password);

$employer = $bdd->query("SELECT empNom,empPrenom,fonNom FROM tblEmployer
LEFT JOIN tblFonction on tblFonction_fonId = fonId WHERE empId =$id");
$employer = $employer->fetch();

$ventilation = $bdd->query("SELECT round(sum(traHeureTot),2) as somme,tblEmployer_empId,traDate,conId, conNom,conPrenom FROM tblTravail
LEFT JOIN tblContact ON tblContact_conId = conId
 WHERE tblEmployer_empId = $id AND traDate BETWEEN '$debut' AND '$fin' GROUP BY  conNom ORDER BY conId");

$tpstotal = $bdd->query("SELECT round(sum(traHeureTot),2)FROM tblTravail WHERE tblEmployer_empId = $id AND traDate BETWEEN '$debut' AND '$fin'  ");
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

    table {

        margin-top: 10px;
        width: 100%;
        color: #9A0000;

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
        color: #000;
        background-color: #BDBFC1;
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

<page backtop="20mm" backleft="5mm">
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
            <td style="width: 66mm"><strong><?php echo $employer['empNom'] ?></strong></td>
            <td style="width: 35mm">Fonction :</td>
            <td width=""><strong><?php echo $employer['fonNom'] ?></strong></td>
        </tr>
        <tr>
            <td style="width: 66mm"><strong><?php echo $employer['empPrenom'] ?></strong></td>
            <td style="width: 35mm">Période considérée :</td>
            <td width=""><?php echo DateToUser($debutSql) . ' au ' . DateToUser($finSql) ?></td>
        </tr>
    </table>
    <?php
    $compte = 0;
    $total = 0;

    ?>

    <table>
        <tr>
            <th> Bénéficiaire </th>

            <th> Nombre d'heure </th>
        </tr>
        <?php
        while ($data = $ventilation->fetch()) {
            ?>

            <tr>
                <td style="border-bottom: dotted 1px; width: 155mm;"><?= $data['conNom'].' '.$data['conPrenom'] ?></td>

                <td style="border-bottom: dotted 1px; "><?= $data['somme'] ?></td>
            </tr>


       <? $total += $data['somme'];} ?>

        <tr>
            <th colspan="1" style="text-align: right">Total:</th>
            <th><strong><?= $total ?></strong></th>
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