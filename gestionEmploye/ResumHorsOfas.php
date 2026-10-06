<?
$bdd = new PDO($dsn, $user, $password);



$ventilation = $bdd->query("SELECT round(sum(traHeureTot), 2) as somme, concat(empNom,' ',empPrenom) as 'employer', codeNo, codeNom, cat4Code
FROM tblTravail
            LEFT JOIN tblTraCat4 ON traCat4 = cat4Id
            LEFT JOIN tblCodeCompta on cat4Compta = codeId
            LEFT JOIN tblEmployer on tblEmployer_empId = empId

WHERE traDate BETWEEN '$debut' AND '$fin'
  AND traCat3 in (1,10,42)
GROUP BY cat4Code,empNom
ORDER BY empNom ASC,cat4Code ASC");

$ventilationCumul = $bdd->query("SELECT round(sum(traHeureTot), 2) as somme,codeNo, codeNom, cat4Code
FROM tblTravail
            LEFT JOIN tblTraCat4 ON traCat4 = cat4Id
            LEFT JOIN tblCodeCompta on cat4Compta = codeId
           

WHERE traDate BETWEEN '$debut' AND '$fin'
  AND traCat3 in (1,10,42)
GROUP BY cat4Code
ORDER BY cat4Code ASC");





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
                        <strong>Critères dossiers Cerebral, ventilation / Hors 74 </strong></td>
                </tr>
            </table>
        </page_header>
        <page_footer>
            <?php include('../footerPrint.php'); ?>
        </page_footer>
        <table style="margin-left: 35mm">
            <tr>
                <td style="width: 35mm">Période considérée :</td>
                <td width=""><?php echo DateToUser($debutSql) . ' au ' . DateToUser($finSql) ?></td>
            </tr>
        </table>
        <?php
        $compte = 0;
        $total = 0;
        $emp = 'n/a';
        $totEmp = 0;

        ?>

        <table>
            <tr>
                <th> Nom </th>
                <th> Nombre d'heure </th>
            </tr>
            <?php
            while ($data = $ventilation->fetch()) {

                if ($emp != $data['employer']){
                    if ($emp != 'n/a')
                    {
                        echo '<tr><td style="background-color: #00AA00; text-align: right">Total </td><td style="background-color: #00AA00; text-align: right" colspan="2">'.$totEmp.'</td></tr>';

                    }

                    echo '<tr><td style="background-color: #00AA00;" colspan="3">'.$data['employer'].'</td></tr>';
                    $totEmp = 0;
                }


                if ($compte != $data['codeId']) {
                    ?>


                    <tr>
                        <td>Dossier: <?php echo $data['cat4Code'] ?></td>
                        <td style="text-align: right"><?php echo $data['somme'] ?></td>
                    </tr>

                    <?
                    $total = $total + $data['somme'];
                    $compte = $data['codeId'];
                    $compteNom = $data['codeNo'] . ' - ' . $data['codeNom'];

                }
                else
                { ?>
                    <tr>
                        <td style="border-bottom: dotted 1px; width: 120mm;" >Dossier: <?php echo $data['cat4Code'] ?></td>
                        <td  style="border-bottom: dotted 1px; width: 30mm; text-align:right"><?php echo $data['somme'] ?></td>
                    </tr>

                    <? $total = $total + $data['somme'];
                }


                $emp = $data['employer'];
                $totEmp += $data['somme'];}
            echo '<tr><td  style="background-color: #00AA00; text-align: right">Total </td><td style="background-color: #00AA00; text-align: right">'.$totEmp.'</td></tr>';?>

        </table>
    </page>
    <page backtop="20mm" backleft="5mm" backbottom="15mm">
        <page_header>
            <table>

                <tr>
                    <td style=" width:30%;  vertical-align: top;  padding: 0"><img style="height: 10mm"
                                                                                   src="../img/logo.jpg" alt=""></td>
                    <td style="text-align: right;width:70%;  vertical-align: middle"> Rapport des temps de travail <br>
                        <strong>Critères dossiers Cerebral, ventilation / centres de charges </strong></td>
                </tr>
            </table>
        </page_header>
        <page_footer>
            <?php include('../footerPrint.php'); ?>
        </page_footer>
        <h2 style="margin-top: 10mm">Cumul de tous les employés</h2>
        <table>
            <tr>
                <th> Nom </th>
                <th> Nombre d'heure </th>
            </tr>
            <?

            $total2 = 0;
            while ($data2 = $ventilationCumul->fetch()) { ?>

                <tr>

                    <td style="border-bottom: dotted 1px; width: 120mm;"><?= $data2['cat4Code'] ?></td>
                    <td style="border-bottom: dotted 1px; width: 30mm; text-align:right"><?= $data2['somme'] ?></td>
                </tr>
                <?
                $total2 += $data2['somme'];

            }

            echo '<tr><td style="background-color: #00AA00; text-align: right">Total </td><td style="background-color: #00AA00; text-align: right">'.$total2.'</td></tr>';

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