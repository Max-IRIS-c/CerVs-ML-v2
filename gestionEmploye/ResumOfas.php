<?
$bdd = new PDO($dsn, $user, $password);


$ventilation = $bdd->query("SELECT round(sum(traHeureTot), 2) as somme, concat(empNom,' ',empPrenom) as 'employer', traDate, cat1Code, cat1Nom
FROM tblTravail
       LEFT JOIN tblTraCat1 ON traCat1 = cat1Id
LEFT JOIN tblEmployer on tblEmployer_empId = empId
WHERE traDate BETWEEN '$debut' AND '$fin'
  ANd traCat3 in (2, 3)
GROUP BY cat1Code,empNom
ORDER BY empNom ASC,cat1Code");


$ventilationCumul = $bdd->query("SELECT round(sum(traHeureTot),2) as somme,traDate,cat1Code, cat1Nom FROM tblTravail
                                                                                             LEFT JOIN tblTraCat1 ON traCat1 = cat1Id
WHERE  traDate BETWEEN '$debut' AND '$fin' ANd traCat3 in (2,3)   GROUP BY  cat1Code ORDER BY cat1Code");

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

<page backtop="20mm" backleft="5mm" backbottom="15mm">
    <page_header>
        <table>

            <tr>
                <td style=" width:30%;  vertical-align: top;  padding: 0"><img style="height: 10mm"
                                                                               src="../img/logo.jpg" alt=""></td>
                <td style="text-align: right;width:70%;  vertical-align: middle"> Rapport des temps de travail <br>
                    <strong>Critères Code OFAS </strong></td>
            </tr>
        </table>
    </page_header>

    <page_footer>
        <?php include('../footerPrint.php'); ?>
    </page_footer>

    <?php
    $compte = 0;
    $total = 0;
    $emp = 'n/a';
    $totEmp = 0;

    ?>

    <table>
        <tr>
            <th> Code OFAS </th>
            <th> Nom </th>
            <th> Nombre d'heure </th>
        </tr>
        <?php
        while ($data = $ventilation->fetch()) {

            if ($emp != $data['employer']){
                if ($emp != 'n/a')
                {
                    echo '<tr><td colspan="2" style="background-color: #00AA00; text-align: right">Total </td><td style="background-color: #00AA00; text-align: right">'.$totEmp.'</td></tr>';

                }

                echo '<tr><td colspan="3" style="background-color: #00AA00; ">'.$data['employer'].'</td></tr>';
                $totEmp = 0;
            }


            ?>

            <tr>
                <td style="border-bottom: dotted 1px; width: 30mm;"><?= $data['cat1Code'] ?></td>
                <td style="border-bottom: dotted 1px; width: 120mm;"><?= $data['cat1Nom'] ?></td>
                <td style="border-bottom: dotted 1px; width: 30mm; text-align:right"><?= $data['somme'] ?></td>
            </tr>


       <? $total += $data['somme'];
        $emp = $data['employer'];
        $totEmp += $data['somme'];}

        echo '<tr><td colspan="2" style="background-color: #00AA00; text-align: right">Total </td><td style="background-color: #00AA00; text-align: right">'.$totEmp.'</td></tr>';

        ?>

        <tr>
            <th colspan="2" style="text-align: right">Total:</th>
            <th><strong><?= $total ?></strong></th>
        </tr>
    </table>

    <h2 style="margin-top: 10mm">Cumul de tous les employés</h2>
    <table>
        <tr>
            <th> Code OFAS </th>
            <th> Nom </th>
            <th> Nombre d'heure </th>
        </tr>
<?

    $total2 = 0;
        while ($data2 = $ventilationCumul->fetch()) { ?>

               <tr>
                <td style="border-bottom: dotted 1px; width: 30mm;"><?= $data2['cat1Code'] ?></td>
                <td style="border-bottom: dotted 1px; width: 120mm;"><?= $data2['cat1Nom'] ?></td>
                <td style="border-bottom: dotted 1px; width: 30mm; text-align:right"><?= $data2['somme'] ?></td>
            </tr>
           <?
        $total2 += $data2['somme'];

        }

echo '<tr><td colspan="2" style="background-color: #00AA00; text-align: right">Total </td><td style="background-color: #00AA00; text-align: right">'.$total2.'</td></tr>';

            ?>

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