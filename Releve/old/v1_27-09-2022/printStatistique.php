<?php
include("../variables.php");
setlocale(LC_TIME, 'fr_FR.utf8', 'fra');
//header('Content-Type: text/pdf');
//header('Content-Disposition: attachment; filename="export.pdf"');

$bdd = new PDO($dsn, $user, $password);
$debut = $_POST['debut'];
$fin = $_POST['fin'];
$typeR = $_POST['tServices'];


if ($_POST['tServices'] == 0) {
    $type = 'tous les services';
} else {
    $idServices = $_POST['tServices'];
    $type = $bdd->query("SELECT tServicesNom FROM tblTypeServices WHERE tServicesId= $idServices");
    $type = $type->fetch();
    $type = $type[0];
}


$statistique = $bdd->query("SELECT COUNT(intDate)AS nbr, sum(intFacturable)AS Total, conNom,conPrenom,
conLocaliter, conAvs,conRegion, regConNom,intFacturable,conId,conLocaliter FROM tblIntervention
  LEFT JOIN tblContact ON intBeneficiaire = conId
  LEFT JOIN tblRegionCon ON conRegion = regConId WHERE intDate BETWEEN '$debut' AND '$fin' AND (intType = $typeR) GROUP BY conId ORDER BY regConNom, conNom ");

$statistiqueReseum = $bdd->query("SELECT COUNT(intDate)AS nbr, sum(intFacturable)AS Total FROM tblIntervention 
WHERE intDate BETWEEN '$debut' AND '$fin' AND intType = $typeR");

$statistiqueReseum = $statistiqueReseum->fetch();


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
            padding: 2mm;

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

        .afficher td {
            border-bottom: dashed 1px;
            padding: 1mm;
        }


    </style>
    <page backtop="30mm" backbottom="25mm" backleft="5mm" backright="5mm">

        <page_header>
            <table style="width=285mm; color: #4dff95">
                <tr>
                    <td style=" vertical-align: top; "><img style="width: 25mm" src="../img/logo.jpg"></td>
                </tr>
            </table>
            <h2 style="text-align: right; margin-top: -15mm">Statistique des prestations</h2>
            <h2 style="text-align: right; margin-top: -2mm "> Période considéré
                : <?php echo DateToUser($_POST['debut']) . ' au ' . DateToUser($_POST['fin']); ?></h2>
        </page_header>

        <table style="height:100%;">
            <tr>
                <th colspan="7"> Type de service: <? echo $type ?> </th>
            </tr>
            <tr>
                <th>Nom</th>
                <th>Prénom</th>
                <th>Localité</th>
                <th>N° Avs</th>
                <th>Moyenne</th>
                <th>Nbr</th>
                <th>Heures</th>
            </tr>
            <?php
            $region = "";
            $beneficiaire = "";
            $newRegion = "non";
            $FirstLin = 1;
            $totNbr = "";
            $totHeure = "";

            while ($row = $statistique->fetch()) {

                if ($region != $row['regConNom']) {

                    if ($region=="")
                    {echo "<tr><th>Région: $row[regConNom]</th></tr> ";
                        $region = $row[regConNom];

                    }
                    else
                        {
                            if ($totHeure != "")
                            {$totMoyenne = $totHeure/$totNbr;}
                            echo "<tr style='background-color: #4dff95'> 
                        <td colspan='4' style='text-align: right'>Totaux pour la région $region</td>
                        <td>".round($totMoyenne,2)."</td>
                        <td>$totNbr</td> 
                        <td>$totHeure</td></tr> ";
                            $FirstLin = 0;
                            $totHeure = 0;
                            $totNbr = 0;


                            echo "<tr><th>Région: $row[regConNom]</th></tr> ";
                            $region = $row[regConNom];
                        }
                        }



                if ($beneficiaire != $row['conId']) {
                    $total = $row[1];
                    $nbr = $row[0];
                    $moyenne = $total / $nbr;

                    echo " <tr>
                <td>$row[conNom]</td>
                <td> $row[conPrenom]</td>
                <td> $row[conLocaliter]</td>
                <td>$row[conAvs]</td>
                <td> ".round($moyenne,2)."</td>
                <td> $nbr</td>
                <td> $total</td>
                     </tr>";
                    $beneficiaire = $row['conId'];

                }
                $totNbr = $totNbr + $nbr;
                $totHeure = $totHeure +$total;




            }

            if ($totHeure != "")
            {$totMoyenne = $totHeure/$totNbr;}
            echo "<tr style='background-color: #4dff95'> 
                        <td colspan='4' style='text-align: right'>Totaux pour la region $region</td>
                        <td>".round($totMoyenne,2)."</td>
                        <td>$totNbr</td> 
                        <td>$totHeure</td></tr> ";
            $FirstLin = 'non';
            $totHeure = 0;
            $totNbr = 0;
            if (($statistiqueReseum[1] == '0.00') OR ($statistiqueReseum[1] == NULL)) {
            }
            else
            {
                $totFinalMoyen = $statistiqueReseum[0]/$statistiqueReseum[1];
            }



            ?>



            <tr style="background-color: #4dff95">
                <td colspan="4">Nombre d'intervention type:  <?php echo $type ?> </td>
                <td> <?php echo round($totFinalMoyen,2);?></td>
                <td><?php echo $statistiqueReseum[0]?> </td>
                <td><?php echo $statistiqueReseum[1]?> </td>

            </tr>

        </table>


        <page_footer>
            <table class="footer">
                <tr style="width: 100%; ">
                    <td style="width: 25%">Document produit par<img style="height: 10mm" src="../img/alunis.gif" alt="">
                    </td>

                    <td style="width:50%; text-align: center"> Association Cerebral Valais <br>Avenu de
                        tourbillon 9
                        <br>Tel. 027 346 70 44
                    </td>
                    <td style="text-align: right;width: 25%">www.cerebral-vs.ch <br>1950 Sion <br>info@cerebral-vs.ch
                    </td>

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
    $pdf = new HTML2PDF('P', 'A4', 'fr');
    $pdf->pdf->SetDisplayMode('fullwidth', 'tworight');

    $pdf->writeHTML($content);

    $pdf->addFont($family, $style, $file);
    //$pdf->output("StatistiquePrestation.pdf", 'D');
	ob_get_clean();
    $pdf->output('contratIntervenant.pdf');
} catch (\Spipu\Html2Pdf\Exception\Html2PdfException $e) {
    die($e);
};



