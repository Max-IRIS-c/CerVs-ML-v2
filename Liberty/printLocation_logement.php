<?php include_once __DIR__ . '/../src/dateFr.php'; ?><?php
include('../variables.php');
setlocale (LC_TIME, 'fr_FR.utf8','fra');
$bdd = new PDO($dsn, $user, $password);
$id = $_GET['Id'];
$location = $bdd->query("SELECT * FROM tblLocation 
LEFT Join tblContact on locConId = conId
LEFT JOIN tblLogement on locPavId = logId
WHERE locId ='$id'");
$location = $location->fetch();
$logId = $location['locPavId'];

$uniterPrix = $bdd->query("SELECT nuitee.uniNom,priseEnCharge.uniNom, pComplete.uniNom,Entretien.uniNom, 
logtype,tBienNom,
 Lib1.TprestaNom as lib1, Lib2.TprestaNom as lib2, Lib3.TprestaNom as lib3, Lib4.TprestaNom as lib4  FROM tblLogement
  LEFT JOIN tblUnite as nuitee on logUniterPrix1 = nuitee.uniId
  LEFT JOIN tblUnite as pComplete on  logUniterPrix2  = pComplete.uniId
  LEFT JOIN tblUnite as priseEnCharge on  logUniterPrix3 = priseEnCharge.uniId
  LEFT JOIN tblUnite as Entretien on  logUniterPrix4 = Entretien.uniId
   LEFT JOIN tblTypeBien on logtype = tBienId 
   LEFT JOIN tblTypePresta as Lib1 on logLibeller1 = Lib1.TprestaId
  LEFT JOIN tblTypePresta as Lib2 on logLibeller2 = Lib2.TprestaId
  LEFT JOIN tblTypePresta as Lib3 on logLibeller3 = Lib3.TprestaId
  LEFT JOIN tblTypePresta as Lib4 on logLibeller4 = Lib4.TprestaId WHERE logId = $logId");
$uniterPrix = $uniterPrix->fetch();

$chargesSup = $uniterPrix[4];
$prixSup = $bdd->query("SELECT * FROM tblOptLocation where optAttribue = $chargesSup");
setlocale(LC_TIME, 'fra_fra');
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
            width: 100%;
            color: #9A0000;
        }

        td {
            vertical-align: middle;
            text-align: left;
            padding: 1mm;

        }

        th {
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

        h1 {
            width: 100%;
            text-align: center;
            font-size: 20px;
        }

        h2 {
            color: #000;
            margin-top: 3mm;
            font-size: 14px;
            padding-left: 3mm;
        }
        p {
            text-align: justify;
            margin-bottom: 3mm;        }
        hr{
            height: 1px;
            margin-top: -1mm;
        }
    </style>
    <page backtop="25mm" backbottom="25mm" backleft="10mm" backright="10mm">
        <page_header>
            <table>
                <tr style="width: 100%; ">
                    <td rowspan="2" style=" vertical-align: top;  padding-left: 5mm"><img style="width: 70mm; margin-right: 30mm; margin-left:5mm " src="../img/logoLiberty.jpg"></td>
                    <td style="text-align: center; margin-left: 20mm; border-bottom: solid 1px;">Logement de vacances adapté - Vétroz Valais</td>
                </tr>
                <tr>
                    <td style="text-align: center; margin-left: 20mm;">Sion, le <? echo strftimeFr("%d %B %Y",strtotime(date('d-m-Y')))?>  </td>
                </tr>
            </table>
        </page_header>
     <h1 >Contrat de location</h1>
        <hr>
        <p style=" margin-top: 10px; text-align: center" > <strong>Entre La Fondation Liberty (bailleur) d'une part </strong>
         <br>c/o Association Cerebral Valais, Av. Tourbillon 9, 1950 Sion
            <br> <strong>et
                <br> <?php echo $location['conNom']?> </strong>
            <br> <?php echo $location['conAdresse'].' ' .$location['conNpa'].' '.$location['conLocaliter']?></p>
        <table >
            <tr>
                <td colspan="2" style="width: 50%; "><strong>Coordonnées du responsable administratif</strong></td>

                <td style="width: 50%; " colspan="2"  ><strong>du responsable du groupe</strong></td>
            </tr>
            <tr>
                <td>Nom et Prénom</td>
                <td>: <?php echo $location['locRespNomPrenom']?></td>
                <td>Nom et Prénom :</td>
                <td> <?php echo $location['locCoRespNomPrenom']?></td>
            </tr>
            <tr>
                <td>Téléphone</td>
                <td>: <?php echo $location['locRespTel']?></td>
                <td>Téléphone :</td>
                <td> <?php echo $location['locCoRespTel']?></td>
            </tr>
            <tr>
                <td>E-Mail</td>
                <td>: <?php echo $location['locRespMail']?></td>
                <td>E-Mail :</td>
                <td> <?php echo $location['locCoRespMail']?></td>
            </tr>
        </table>
        <h2>Définition de l'objet</h2>
        <hr>
        <table >
            <tr>
                <td><strong><?php echo $uniterPrix['tBienNom']?> loué</strong></td>
                <td>
                    <?php echo '<strong>'.$location['logNom'].'</strong> ('.$location['logPersonneMin'].' à '.$location['logPersonneMax'].' personnes)'?></td>
            </tr>
            <tr>
                <td  style="width: 155px; vertical-align: top"><strong>Nombre de personnes </strong></td>
                <td><?php echo '<strong>' .$location['locNbrPers'].' personnes </strong> <br> (dont '.$location['locNbrPersAcc'].' personnes en situation  de handicap)'?></td>
            </tr>
            <tr>
                <td style="vertical-align: top" rowspan="2"><Strong>Date de la location </Strong></td>
                <td><?php echo 'du <strong> '.strftimeFr("%d %B %Y",strtotime($location['locDateEnt'])).' </strong> au  <strong>'.strftimeFr("%d %B %Y",strtotime($location['locDateDep'])).'</strong>'?></td>
            </tr>
            <tr><td>Arrivée à <?php echo '<strong>' .HeureHhMm($location['locArrivee']).' </strong> Départ à  <strong>'.HeureHhMm($location['locDepart'])?></strong></td></tr>
        </table>
        <h2>Conditions</h2>
        <hr>
        
        <table style="margin-bottom: 0">
            <tr>
                <td style="vertical-align: top ;width: 155px"><strong>Tarifs facturés</strong> <br>(TVA non comprise)</td>
                <td><?php echo ' :  <strong>'.$uniterPrix['lib1']. '  '.$location['logPrix1'].' </strong>'.$uniterPrix[0];
                if ($location['logPrix2'] !=""){ echo '<br>+ <strong> '.$uniterPrix['lib2']. '  '.$location['logPrix2'].'</strong> '.$uniterPrix[2];}
                if ($location['logPrix3'] !=""){ echo ' <br>+ <strong> '.$uniterPrix['lib3']. ' '.$location['logPrix3'].'</strong> '.$uniterPrix[1];}
                if ($location['logPrix4'] !=""){echo '<br>+ <strong> '.$uniterPrix['lib4']. ' '.$location['logPrix4'].'</strong> '.$uniterPrix[3];} ?></td>
            </tr>
            <tr>
                <td style="vertical-align: top"><strong>Modalités de paiement</strong></td>
                <td>: <strong>Un acompte de 20% sera facturé dès réception du contrat signé.  </strong>
                        <br> &nbsp; Montant à régler sous 30 jours.</td>
            </tr><tr>
                <td style="vertical-align: top"><strong>Paiement du solde</strong> </td>
                <td>: Sur facture, à la fin du séjour. Payable à 30 jours</td>
            </tr><tr>
                <td style="vertical-align: top"><strong>Conditions générales</strong> </td>
                <td>: Les conditions générales et le règlement d'utilisation font l'objet de deux <br> &nbsp; documents annexés.
                    Ceux-ci doivent être également signés et <strong>font partie <br> &nbsp; intégrante du présent contrat. </strong></td>
            </tr><tr>
                <td style="vertical-align: top "><strong>Charges supplémentaires</strong><BR>(Options)</td>
                <td>: <?php while ($r = $prixSup->fetch()){ echo $r['optNom'].'  '.$r['optPrix'].'<br> &nbsp;&nbsp;';} ?></td>
            </tr>
            <tr>
                <td style="vertical-align: top"><strong>Bases légale</strong></td>
                <td>: Pour tout éventuel litige concernant le présent contrat, les parties s'en remettent au 
                    <br> &nbsp; Code des Obligations. Les articles du Titre huitième étaient applicables par analogie.
                    <br> &nbsp; Le for juridique convenu est Sion</td>
            </tr>
        </table>
        <p style="text-align: Left; margin-top: 10mm; margin-left: 108mm">Lieu et date: ...............................................<br><br>
            Pour le bailleur : <br>Fondation Liberty<br> </p>
        <p style="text-align: left; margin-top: -18mm; margin-bottom: 5mm">Lieu et date: .....................................................<br><br>
            Pour le locataire <br><?php echo $location['conNom']?></p>
        <p style>Signature:</p>
        <p style="text-align: right; margin-right: 56mm;margin-top: -6mm">Signature:</p>

        <page_footer>
            <hr>
            <p style="font-size: 10px; text-align: center">Fondation Liberty, c/o Association Cerebral Valais  Tél. +41 27 346 70 44  www.cerebral-valais.ch/locations
                <br>Raiffeisen de Sion : CH51 8080 8006 9065 5555 0 </p>

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
    $pdf->output('contratIntervenant.pdf');
    $pdf->Output('../pdfContrats/ContratN°'.$id.'.pdf','F');

} catch (\Spipu\Html2Pdf\Exception\Html2PdfException $e) {
    die($e);
};