<?php include_once __DIR__ . '/../src/dateFr.php'; ?><?php
include("../variables.php");
setlocale (LC_TIME, 'fr_FR.utf8','fra');
header('Content-Type: text/pdf');
header('Content-Disposition: attachment; filename="export.pdf"');

$bdd = new PDO($dsn, $user, $password);
$bdd->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

$marqueur = $_GET['Id'];

$req = $bdd->prepare("SELECT civNom, conNom,conPrenom,conComplement, 
 conAdresse, conAdresse2, conNpa, conLocaliter, conTel1, conTel2 ,conTel3 from tblContact
  LEFT JOIN tblCiviliter on tblCiviliter_civId = civId 
  WHERE conMarquage ='$marqueur' AND conStatu = 1 ORDER BY conNom,conPrenom ");
$req->execute();

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

        }

        th {

            vertical-align: middle;
            text-align: left;
            color: #00AA33;
            font-size: 12px;
            font-style: normal;
            font-weight: normal !important;
            border-bottom: solid 1PX;
            padding: 2mm;
        }

        .footer td {
            vertical-align: bottom
        }

        h1 {
            width: 100%;
            text-align: right;
            font-size: 20px;

        }

        h2 {

            color: #000;
            margin-top: 5mm;
            margin-bottom: 5mm;
            font-size: 14px;
            padding-left: 3mm;
        }

        p {
            text-align: justify;
            margin-bottom: 3mm;

        }
        .afficher td{
            border-bottom: dashed   1px;
            padding: 1mm;
        }


    </style>
    <page backtop="20mm" backbottom="25mm" backleft="5mm" backright="5mm">

        <page_header>
            <table  style="width=285mm; color: #4dff95">
                <tr>
                    <td style=" vertical-align: top; "><img style="width: 20mm" src="../img/logo.jpg"></td>
                </tr>
            </table>
            <h2 style="text-align: right; margin-top: -15mm">Liste d'adresses selon marquage manuel : <?php echo $marqueur?></h2>
            <h2 style="text-align: right; margin-top: -2mm "> <?php echo (strftimeFr("%A, %d %B %G")); ;?></h2>
        </page_header>


        <table class="afficher" style="width: 280mm">

            <tr>
                <th>Nom</th>
                <th>Prénom</th>
                <th>Complément</th>
                <th>Adresse</th>
                <th>Npa</th>
                <th>Localité</th>
                <th>Téléphone 1</th>
                <th>Téléphone 2</th>
                <th>Téléphone 3</th>
                

            </tr>

            <?php while ($row = $req->fetch()) { ?>
            <tr>
                <td><? echo $row['conNom']; ?> </td>
                <td><? echo $row['conPrenom']; ?> </td>
                <td><? echo $row['conComplement']; ?> </td>
                <td><? echo $row['conAdresse']; ?> </td>
                <td><? echo $row['conNpa']; ?> </td>
                <td><? echo $row['conLocaliter']; ?> </td>
                <td><? echo $row['conTel1']; ?> </td>
                <td><? echo $row['conTel2']; ?> </td>
                <td style="border-right: solid 1px"><? echo $row['conTel3']; ?> </td>
                

            </tr>
                <?php } ?>

            </table>
        <page_footer>
            <table class="footer">
                <tr style="width: 100%; ">
                    <td style="width: 25%">Document produit par<img style="height: 10mm" src="../img/alunis.gif" alt=""></td>

                    <td style="width:50%; text-align: center"> Association Cerebral Valais <br>Avenu de
                        tourbillon 9
                        <br>Tel. 027 346 70 44
                    </td>
                    <td style="text-align: right;width: 25%">www.cerebral-vs.ch <br>1950 Sion <br>info@cerebral-vs.ch</td>

                </tr>
            </table>
        </page_footer>
    </page>


<?php

try {
    $content = ob_get_clean();
    require _('../vendor/autoload.php');
    $family = 'coucou';
    $style = 'regular';
    $file = '../vendor/tecnickcom/tcpdf/fonts/helvetica.php';
    $pdf = new HTML2PDF('L', 'A4', 'fr');
    $pdf->pdf->SetDisplayMode('fullwidth', 'tworight');

    $pdf->writeHTML($content);

    $pdf->addFont($family, $style, $file);
	ob_get_clean();
    $pdf->output("MarquageManuel'_'$marqueur.pdf", 'D');
    //$pdf->output('contratIntervenant.pdf');
} catch (\Spipu\Html2Pdf\Exception\Html2PdfException $e) {
    die($e);
};



