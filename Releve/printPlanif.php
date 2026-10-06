<?php
include('../variables.php');



$id = $_GET['Id'];

$bdd = new PDO($dsn, $user, $password);


$infoInter = $bdd->query("SELECT civNom,conNom,conPrenom,conAdresse,conAdresse2,conComplement,conNpa,conLocaliter
  ,conTel1,conTel2,conTel3,conMail,(select tTelNom from tblContact INNER JOIN tblTypeTelephone on conTel1T = tTelId
WHERE conId = '$id')as telTyp1,
  (select tTelNom from tblContact INNER JOIN tblTypeTelephone on conTel2T = tTelId
  WHERE conId = '$id')as telTyp2,
  (select tTelNom from tblContact INNER JOIN tblTypeTelephone on conTel3T = tTelId
   WHERE conId = '$id')as telTyp3 FROM tblContact
  LEFT JOIN tblCiviliter on tblCiviliter_civId = civId WHERE conId ='$id'");
$infoInter = $infoInter->fetch();

$intervention = $bdd->query("SELECT * FROM tblIntervention
LEFT JOIN tblContact on intIntervenant = conId
LEFT JOIN tblGenreServices on intGenre = genSerId
LEFT JOIN tblTypeServices on intType = tServicesId WHERE intBeneficiaire = '$id' AND (intFacturable <=0 or intFacturable is NULL)  order by intDate ASC ");




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

            padding-left: 2mm;
            vertical-align: middle;
            text-align: left;
            color: #00AA33;
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

        .input {
            width: 10mm;
            height: 5mm;
            border: solid 1px;
        }

        .footer {
            border-top: solid 1px;
        }

    </style>

    <page> <!-- page 4 -->
        <page_header>
            <table>
                <tr>
                    <td style=" width:50%;  vertical-align: top;  padding: 0"><img style="height: 20mm"
                                                                                   src="../img/logo.jpg" alt=""></td>
                    <td style="text-align: right;width:50%; vertical-align: middle"> Service de relève à domicile <br>
                        <strong>Liste des interventions planifiées</strong> <br> <br> Edité le: <?php echo date("d/m/Y") ?></td>
                </tr>
            </table>
        </page_header>

        <table style="margin-top: 30mm; border: solid 1px;">


            <tr>
                <td colspan="10" style="width: 100%"><h2>Bénéficiaire</h2></td>

            </tr>
            <tr>
                <td><?php echo $infoInter['civNom'] ?></td>
            </tr>
            <tr>
                <td><?php echo $infoInter['conNom'] . ' ' . $infoInter['conPrenom'] ?> </td>
            </tr>
            <tr>

                <td><?php echo $infoInter['conAdresse'] ?></td>
                <th><?php echo $infoInter['telTyp1'] ?></th>
                <td><?php echo $infoInter['conTel1'] ?></td>
                <th><?php echo $infoInter['telTyp2'] ?></th>
                <td><?php echo $infoInter['conTel2'] ?></td>
            </tr>
            <tr>

                <td><?php echo $infoInter['conComplement'] ?></td>
                <th><?php echo $infoInter['telTyp3'] ?></th>
                <td><?php echo $infoInter['conTel3'] ?></td>
            </tr>

            <tr>

                <td><?php echo $infoInter['conNpa'] . ' ' . $infoInter['conLocaliter'] ?> </td>
                <th>e-mail</th>
                <td><?php echo $infoInter['conMail'] ?></td>
            </tr>
        </table>
        <table>
            <tr>
                <th><h2>Interventions planifiées</h2></th>
            </tr>
        </table>
        <table style="margin-top: 5mm; width: 210mm">

            <tr>
                <th> Date</th>
                <th style="width: 10%;"> Début</th>
                <th style="width: 10%;"> Fin</th>
                <th style="width: 30%;"> Intervenant</th>
                <th style="width: 30%;"> Téléphone(s)</th>

            </tr>

            <?php
            $Totale = 0;

            while ($row = $intervention->fetch()) { ?>

                <tr>
                    <td style="border-bottom: dotted 1px"><? echo DateToUser($row['intDate']); ?> </td>
                    <td style="border-bottom: dotted 1px"><? echo HeureHhMm($row['intDebut']); ?></td>
                    <td style="border-bottom: dotted 1px"><? echo HeureHhMm($row['intFin']); ?> </td>
                    <td style="border-bottom: dotted 1px"><? echo $row['conNom'] . " " . $row['conPrenom']; ?> </td>
                    <td style="border-bottom: dotted 1px"><? echo $row['conTel1']. " / ".$row['conTel2']; ?> </td>

                </tr>
            <?php }
            ?>
            <tr>
                <td colspan="7" style="border-top: solid 1px;"></td>
            </tr>

        </table>



        <page_footer>
            <?php include('../footerPrint.php'); ?>
        </page_footer>
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
