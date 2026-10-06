<?php
include('../variables.php');


$debut = $_POST['debut'];
$fin = $_POST['fin'];
$id = $_POST['Id'];
$debutSql = ($debut);
$finSql = ($fin);
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
LEFT JOIN tblContact on intBeneficiaire = conId
LEFT JOIN tblGenreServices on intGenre = genSerId
LEFT JOIN tblTypeServices on intType = tServicesId WHERE  (intDate BETWEEN '$debutSql' AND '$finSql') 
AND intIntervenant = '$id' order by intDate ASC, intDebut ASC");


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



    </style>

    <page backtop="20mm" >
        <page_header>
            <table>
                <tr>
                    <td style=" width:50%;  vertical-align: top;  padding: 0"><img style="height: 10mm"
                                                                                   src="../img/logo.jpg" alt=""></td>
                    <td style="text-align: right;width:50%; vertical-align: middle"> Service de relève à domicile <br>
                        <strong>Liste des interventions par intervenant</strong></td>
                </tr>
            </table>
        </page_header>
        <table style=" border: solid 1px;">

            <tr>
                <td colspan="2" style="width: 50%"><h2>Intervenant</h2></td>
                <td colspan="4" style="width: 50%"><h2>Période du: <?php echo dateToUser( $_POST['debut']) ?>
                        au <?php echo dateToUser($_POST['fin']) ?></h2></td>
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
                <th></th>
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
        <table style="margin-top: 10mm;">
            <tr>
                <th> Date</th>
                <th style="width: 30%;"> Bénéficiaire</th>
                <th style="width: 10%;"> Début</th>
                <th style="width: 10%;"> Fin</th>
                <th style="width: 20%;">Type de besoin</th>
                <th style="width: 20%;"> Temps de travail</th>

            </tr>

        <?php
        $Totale = 0;

        while ($row = $intervention->fetch()) { ?>

            <tr>
                <td style="border-bottom: dotted"><? echo DateToUser($row['intDate']); ?> </td>
                <td style="border-bottom: dotted"><? echo $row['conNom'] . " " . $row['conPrenom']; ?> </td>
                <td style="border-bottom: dotted"><? echo HeureHhMm($row['intDebut']); ?></td>
                <td style="border-bottom: dotted"><? echo HeureHhMm($row['intFin']); ?> </td>
                <td style="border-bottom: dotted"><? echo $row['genSerNom']; ?>  </td>
                <td style="border-bottom: dotted" class="input0"><? echo $row['intFacturable'];
                    $Totale = $Totale + $row['intFacturable']; ?> </td>
            </tr>
        <?php }
        ?>
            <tr>
                <td colspan="6" style="border-top: solid 1px;"></td>
            </tr>
        <tr>
            <td colspan="2"></td>
            <td colspan="3"> <strong>Total des heures de travail fournies:</strong></td>
            <td> <strong><?php echo $Totale; ?></strong></td>
        </tr>
        </table>
        <page_footer>
            <?php include ('../footerPrint.php');?>
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
