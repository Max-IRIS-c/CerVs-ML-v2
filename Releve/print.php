<?php
include('../variables.php');


$bdd = new PDO($dsn, $user, $password);
$id = $_GET['Id'];
$intervention = $bdd->query("SELECT * FROM tblIntervention 
LEFT JOIN tblGenreServices on intGenre = genSerId 
LEFT JOIN tblTypeServices on intType = tServicesId WHERE intId = '$id'");
$intervention = $intervention->fetch();
$beneficiaireId = $intervention['intBeneficiaire'];
$intervenantId = $intervention['intIntervenant'];
$infoBenefi = $bdd->query("SELECT civNom,conNom,conPrenom,conAdresse,conAdresse2,conComplement,conNpa,conLocaliter
  ,conTel1,conTel2,conTel3,conMail,(select tTelNom from tblContact INNER JOIN tblTypeTelephone on conTel1T = tTelId
WHERE conId = '$beneficiaireId')as telTyp1,
  (select tTelNom from tblContact INNER JOIN tblTypeTelephone on conTel2T = tTelId
  WHERE conId = '$beneficiaireId')as telTyp2,
  (select tTelNom from tblContact INNER JOIN tblTypeTelephone on conTel3T = tTelId
   WHERE conId = '$beneficiaireId')as telTyp3 FROM tblContact
  LEFT JOIN tblCiviliter on tblCiviliter_civId = civId WHERE conId ='$beneficiaireId'");
$infoBenefi = $infoBenefi->fetch();

$infoInter = $bdd->query("SELECT civNom,conNom,conPrenom,conAdresse,conAdresse2,conComplement,conNpa,conLocaliter
  ,conTel1,conTel2,conTel3,conMail,(select tTelNom from tblContact INNER JOIN tblTypeTelephone on conTel1T = tTelId
WHERE conId = '$intervenantId')as telTyp1,
  (select tTelNom from tblContact INNER JOIN tblTypeTelephone on conTel2T = tTelId
  WHERE conId = '$intervenantId')as telTyp2,
  (select tTelNom from tblContact INNER JOIN tblTypeTelephone on conTel3T = tTelId
   WHERE conId = '$intervenantId')as telTyp3 FROM tblContact
  LEFT JOIN tblCiviliter on tblCiviliter_civId = civId WHERE conId ='$intervenantId'");
$infoInter = $infoInter->fetch();


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

    <page>
        <page_header>
            <table>
                <tr>
                    <td style=" width:50%;  vertical-align: top;  padding: 0"><img style="height: 25mm"
                                                                                   src="../img/logo.jpg" alt=""></td>
                    <td style="text-align: right;width:50%; vertical-align: middle"> Service de relève à domicile <br>
                        <strong>Fiche d'intervention</strong></td>
                </tr>
            </table>
        </page_header>
        <table style="margin-top: 30mm; border: solid 1px;">

            <tr>
                <th style="width: 50%" colspan="4"><h2>Bénéficiaire</h2></th>
                <th style="width: 50%" colspan="4"><h2>Intervenant</h2></th>
            </tr>

            <tr>
                <td colspan="4"><?php echo $infoBenefi['civNom'] ?></td>
                <td colspan="4"><?php echo $infoInter['civNom'] ?></td>
            </tr>

            <tr>
                <td colspan="4"><?php echo $infoBenefi['conNom'] . ' ' . $infoBenefi['conPrenom'] ?></td>
                <td colspan="4"><?php echo $infoInter['conNom'] . ' ' . $infoInter['conPrenom'] ?></td>
            </tr>
            <tr>
                <td colspan="4"><?php echo $infoBenefi['conAdresse'] ?></td>
                <td colspan="4"><?php echo $infoInter['conAdresse'] ?></td>
            </tr>
            <tr>
                <td colspan="4"><?php echo $infoBenefi['conComplement'] ?></td>
                <td colspan="4"><?php echo $infoInter['conComplement'] ?></td>
            </tr>
            <tr>
                <td colspan="4"><?php echo $infoBenefi['conNpa'].' '.$infoBenefi['conLocaliter'] ?></td>

                <td><?php echo $infoInter['conNpa'].' '.$infoInter['conLocaliter'] ?></td>

            </tr>
            <tr>
                <th><?php echo $infoBenefi['telTyp1'] ?></th>
                <td><?php echo $infoBenefi['conTel1'] ?></td>
                <th><?php echo $infoBenefi['telTyp2'] ?></th>
                <td><?php echo $infoBenefi['conTel2'] ?></td>
                <th><?php echo $infoInter['telTyp1'] ?></th>
                <td><?php echo $infoInter['conTel1'] ?></td>
                <th><?php echo $infoInter['telTyp2'] ?></th>
                <td><?php echo $infoInter['conTel2'] ?></td>
            </tr>
            <tr>
                <th><?php echo $infoBenefi['telTyp3'] ?></th>
                <td colspan="3"><?php echo $infoBenefi['conTel3'] ?></td>
                <th><?php echo $infoInter['telTyp3'] ?></th>
                <td colspan=""><?php echo $infoInter['conTel3'] ?></td>
            </tr>
            <tr>
                <th>e-mail</th>
                <td colspan="3"><?php echo $infoBenefi['conMail'] ?></td>
                <th>e-mail</th>
                <td colspan="3"><?php echo $infoInter['conMail'] ?></td>
            </tr>
        </table>

        <table  style=" border: solid 1px;">
            <tr>
                <th>Date:</th>
                <td style="font-size: 15px;"> <strong><?php echo dateToUser($intervention['intDate']) ?></strong></td>
                <th>Subventionné: <?php CheckBoxPDF($intervention['intSubventioner']) ?></th>
                <th>Type de service:</th>
                <td><?php echo $intervention['tServicesNom'] ?></td>
                <th>Type de besoin:</th>
                <td><?php echo $intervention['genSerNom'] ?></td>
            </tr>
            <tr>
                <th>Début:</th>
                <td> <strong><?php echo HeureHhMm($intervention['intDebut']) ?></strong></td>
                <td style="border-color: #aaaaaa" class="input"></td>
                <th>Fin :</th>
                <td> <strong><?php echo HeureHhMm($intervention['intFin']) ?></strong></td>
                <td style="border-color: #aaaaaa" class="input"></td>

            </tr>
            <tr>
                <th style="height=10mm">Temps effectif</th>
                <td class="input"  style="border-color: #aaaaaa""></td>

            </tr>
            <tr>
                <th>Commentaire</th>
                <td colspan="9" rowspan="2" style="border: solid 1px;border-color: #aaaaaa;width: 170mm; height: 15mm; vertical-align:top;"><?php echo $intervention['intCommentaire'] ?></td>
            </tr>
        </table>
        <table>
            <tr>
                <td>
                    Afin d'assurer la qualité de notre service, nous souhaitons avoir des commentaires sur le
                    déroulement des interventions, des suggestions pour améliorer le service ou encore des éventuels
                    problèmes rencontrés.
                </td>
            </tr>
        </table>
        <table>
            <tr>
                <td style="width: 50%; vertical-align: top "><Strong>Commentaires et remarques du bénéficiaire ou de son représentant légal :</Strong></td>
                <td style="width: 50%; vertical-align: top; text-align: right "><Strong>Commentaires et remarques de l'intervenant :</Strong></td>
            </tr>
            <tr>
                <td style="width: 50%;height: 50mm; vertical-align: top; border-right:solid 2px"></td>
                <td style="width: 50%; vertical-align: top; text-align: right "></td></tr>
        </table>
        <table>
            <tr>
                <td style="text-align: center">
                    <strong>Par leurs signatures, le bénéficiaire reconnait avoir reçu et l'intervenant délivré les prestations
                        conformément aux indications exprimées ci-dessus.</strong>
                </td>
            </tr>
        </table>
        <table>
            <tr>
                <td style="width: 50%;padding-bottom: 2mm">Lieu :</td>
                <td style="width: 50%;padding-bottom: 2mm">Lieu :</td>
            </tr>
            <tr>
                <td style="width: 50%;padding-bottom: 2mm">Date :</td>
                <td style="width: 50%;padding-bottom: 2mm">Date :</td>
            </tr>
            <tr>
                <td style="width: 50%;padding-bottom: 2mm">Signature</td>
                <td style="width: 50%;padding-bottom: 2mm">Signature</td>
            </tr>
            <tr>
                <td style="height: 20mm;">    </td>
                <td>    </td>
            </tr>
            <tr>
                <td style="height: 15mm;">    </td>
                <td><strong><i>Cette fiche est à retourner dûment signée, <br> au plus tard pour le 20 du mois au secrétariat <br> de l'association.</i></strong></td>
            </tr>
            <tr>
                <td></td>
                <td><strong><i>Attention, pour les fiches retournées après le 20, <br>le salaire sera payé le mois suivant.</i></strong></td>
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
