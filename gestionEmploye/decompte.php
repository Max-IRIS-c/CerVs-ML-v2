<?php
include('../variables.php');
$id = $_GET['Id'];
$debut = $_GET['debut'];
$fin = $_GET['fin'];
$bdd = new PDO($dsn, $user, $password);



try{
    $employer = $bdd->query("SELECT empNom,empPrenom,fonNom,empTaux,conId FROM tblEmployer
    LEFT JOIN tblFonction on tblFonction_fonId = fonId WHERE empId =$id");
    $employer = $employer->fetch();

    $idContact = $employer['conId'] ?? $id;
    $contact =$bdd->query("SELECT conNom, conPrenom, conAdresse, conNpa, conLocaliter,conAvs, CivNom FROM tblContact LEFT JOIN tblCiviliter on tblCiviliter_civId = civId where conId = '$idContact'");
    $contact = $contact->fetch();

    $heure = $bdd->query("SELECT  SUM(traHeureTot) FROM tblTravail WHERE tblEmployer_empId = '$id' 
    AND traDate BETWEEN '$debut' AND '$fin'  AND traCat3 <= 10 ");
    $heure = $heure->fetch();

    $maladie = $bdd->query("SELECT  SUM(traHeureTot) FROM tblTravail WHERE tblEmployer_empId = '$id' 
    AND traDate BETWEEN '$debut' AND '$fin' AND traCat3 = 30 ");
    $maladie = $maladie->fetch();

    $accident = $bdd->query("SELECT  SUM(traHeureTot) FROM tblTravail WHERE tblEmployer_empId = '$id' 
    AND traDate BETWEEN '$debut' AND '$fin' AND traCat3 = 29 ");
    $accident = $accident->fetch();


    $heureAbsence = $bdd->query("SELECT  SUM(traHeureTot) FROM tblTravail WHERE tblEmployer_empId = '$id' 
    AND traDate BETWEEN '$debut' AND '$fin' AND  traCat3 >= 20");
    $heureAbsence = $heureAbsence->fetch();

    $heureAbsenceTOT = $bdd->query("SELECT SUM(traHeureTot) FROM tblTravail WHERE tblEmployer_empId = '$id' 
    AND traDate BETWEEN '$debut' AND '$fin' AND  traCat3 IN (20,21,22,23,31,32,33,34,40,41,42) ");
    $heureAbsenceTOT = $heureAbsenceTOT->fetch();


    $droitPris = $bdd->query("SELECT round(traHeureTot,2), cat3Code FROM tblTravail
    LEFT JOIN tblTraCat3 on cat3Id = traCat3 WHERE cat3Id >=3 AND tblEmployer_empId= '$id'");
    $droitPris = $droitPris->fetch();

    $taux = $bdd->query("SELECT empTaux FROM tblEmployer WHERE empId ='$id' ");
    $taux = $taux->fetch();

    $apg = $bdd->query("SELECT SUM(traHeureTot)FROM tblTravail WHERE traCat3 = 31 AND  traDate BETWEEN '$debut' AND '$fin'
    AND tblEmployer_empId= '$id'");
    $apg = $apg->fetch();

    $vac = $bdd->query("SELECT SUM(traHeureTot) FROM tblTravail WHERE traCat3 in (21,20) AND  traDate BETWEEN '$debut' AND '$fin'
    AND tblEmployer_empId= '$id'");
    $vac = $vac->fetch();

    $fer = $bdd->query("SELECT SUM(traHeureTot) FROM tblTravail WHERE traCat3 = 22 AND  traDate BETWEEN '$debut' AND '$fin'
    AND tblEmployer_empId= '$id'");
    $fer = $fer->fetch();

    $cho = $bdd->query("SELECT SUM(traHeureTot) FROM tblTravail WHERE traCat3 = 23 AND  traDate BETWEEN '$debut' AND '$fin'
    AND tblEmployer_empId= '$id'");
    $cho = $cho->fetch();

    $mat = $bdd->query("SELECT SUM(traHeureTot) FROM tblTravail WHERE traCat3 = 32 AND  traDate BETWEEN '$debut' AND '$fin'
    AND tblEmployer_empId= '$id'");
    $mat = $mat->fetch();

    $dem = $bdd->query("SELECT SUM(traHeureTot) FROM tblTravail WHERE traCat3 = 41 AND  traDate BETWEEN '$debut' AND '$fin'
    AND tblEmployer_empId= '$id'");
    $dem = $dem->fetch();

    $mar = $bdd->query("SELECT SUM(traHeureTot) FROM tblTravail WHERE traCat3 = 33 AND  traDate BETWEEN '$debut' AND '$fin'
    AND tblEmployer_empId= '$id'");
    $mar = $mar->fetch();

    $deu = $bdd->query("SELECT round(traHeureTot,2) FROM tblTravail WHERE traCat3 = 40 AND  traDate BETWEEN '$debut' AND '$fin'
    AND tblEmployer_empId= '$id'");
    $deu = $deu->fetch();
    $annee = substr($debut, 0, 4);

    $droit = $bdd->query("SELECT * FROM tblDroit WHERE droAnnee = '$annee' AND tblEmplyer_empId = '$id'");
    $droit = $droit->fetch();

    $heureDiff = $bdd->query("SELECT droDiffe FROM tblDroit WHERE droAnnee = $annee and tblEmplyer_empId = $id");
    $heureDiff = $heureDiff->fetch();
    $heureDiff = $heureDiff[0];
}catch(Exception $e){
    echo $e->getMessage();
}


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
        .affichage td{

            border-bottom: dotted #00AA00;
        }
        .signatur td{

            padding-top: 3mm;

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
            color: #fff;
            background-color: #00AA00;
            font-size: 12px;
            font-style: normal;
            font-weight: normal !important;
            padding-right: 2mm;
        }

        .footer td {
            vertical-align: bottom
        }

        h2 {

            margin: 0;
            padding-bottom: 5mm;
            padding-top: 5mm;
            font-size: 14px;
        }


    </style>

    <page backtop="30mm" backleft="5mm" backright="5mm">
        <page_header style="margin-top: -5mm">
            <table>

                <tr>
                    <td style="vertical-align: top;  padding: 0"><img style="height: 30mm"
                                                                                   src="../img/logo.jpg" alt=""></td>
                    <td style="text-align: right; vertical-align: top ;width:70%;font-size: 18px"> Décompte d'heure employé<br>
                        <strong>
                            Période du <?php echo DateToUser($debut).' au '.DateToUser($fin )?></strong></td>
                </tr>
            </table>
        </page_header>

        <table>
            <tr>

                <td>Fonction:</td>
                <td style="width: 95mm"><strong><?php echo $employer['fonNom'] ?></strong></td>
                <td></td>
                <td ><strong><?php echo $contact['CivNom'] ?></strong></td>
            </tr>
            <tr>
                <td>Taux:</td>
                <td style="width: 95mm"><strong><?php echo $employer['empTaux'] ?>%</strong></td>
                <td></td>
                <td><strong><?php echo$contact['conPrenom'].' '.$contact['conNom']?></strong></td>
            </tr>
            <tr>
                <td>N° AVS:</td>
                <td style="width: 95mm"><strong><?php echo $contact['conAvs']?></strong></td>
                <td></td>
                <td><strong><?php echo$contact['conAdresse']?></strong></td>
            </tr>
            <tr>
                <td></td>
                <td style="width: 95mm"></td>
                <td></td>
                <td><strong><?php echo$contact['conNpa'].' '.$contact['conLocaliter']?></strong></td>
            </tr>



        </table>

<h2 style="margin-top: 20mm;">Décompte effectif des heures <?php echo substr($debut,0,4)?> (période selectionée) </h2>
        <table style="margin-top: 3mm">
            <?php
            $heureTot = JourOuvrable($debut, $fin);
            $heureTot = $heureTot * 8.4 / 100 * $employer['empTaux'];
            $heureTotEff = $heure[0] + $heureAbsence[0];
            $totMaladi = $maladie[0] + $accident[0];
            ?>
     
            <tr>
                <th colspan="2">Désignation</th>
                <th>Heures dûes</th>
                <th>Heures Jusifiées</th>
            </tr>
            <tr>
                <td>Heures à effectuer selon contrat</td>
                <td></td>
                <td ><?php
                        echo round($heureTot, 2);
                        $heureInitial = round($heureTot, 2);

                    ?></td>
                <td style="border-left: dotted"></td>
            </tr>
            <tr>
                <td style="border-bottom: dotted">Solde année précédente (reporté en début d'année) </td>
                <td style="border-bottom: dotted"></td>
                <td style="border-bottom: dotted"></td>
                <td style="border-left: dotted;border-bottom: dotted"><?php echo $heureDiff?></td>
            </tr>
            <tr>
                <td>Heures travaillés</td>
                <td></td>
                <td></td>
                <td style="border-left:dotted  "><?php echo round($heure[0], 2); ?></td>
            </tr>
            <tr>
                <td>Absences maladie et accident</td>
                <td></td>
                <td></td>
                <td style="border-left:dotted  "><?php echo round($totMaladi, 2); ?></td>
            </tr>
            <tr>
                <td>Congés payés (vacances, fériées, APG etc..) </td>
                <td></td>
                <td></td>
                <td style="border-left:dotted  "><?php echo round($heureAbsenceTOT[0], 2); ?></td>
            </tr>
            <tr>
                <td colspan="2" style="border-top: solid">Total </td>
                <td style="border-top: solid"><?php
                    $totDu = round($heureTot, 2);
                    echo $totDu ?></td>
                <td style="border-left:dotted;border-top: solid  "><?php
                    $totJusti = round($heureTotEff, 2)+$heureDiff;

                    echo $totJusti; ?></td>
            </tr>
            <tr>
                
                <td><strong>Différence</strong></td>
                <td></td>
                <th colspan="2" style="text-align: center"><strong style="color: #ff0000"><?php echo  $totJusti-$totDu; ?></strong> </th>
                
            </tr>
        </table>
<h2 style="margin-top:15mm; margin-bottom: 3mm">Décompte des jours au absences <?php echo substr($debut,0,4)?> (période selectionée)</h2>
        <table class="affichage">

            <tr>
                <td style="border:none;"></td>
                <th colspan="2">Absences</th>
                <th colspan="8">Congés payés</th>
            </tr>
            <tr>
                <td style="border:none;"></td>
                <th>Maladie</th>
                <th>Accident</th>
                <th>Vacances/congés</th>
                <th>Fériés</th>
                <th>Chômés</th>
                <th>APG</th>
                <th>Maternité</th>
                <th>Déménag.</th>
                <th>Deuil</th>
                <th>Mariage</th>



            </tr>

            <tr>
                <th>Droit</th>
                <td><?php echo $droit['droMaladie']; ?></td>
                <td><?php echo $droit['droAccident']; ?></td>
                <td><?php echo $droit['droVacances']; ?></td>
                <td><?php echo $droit['droFerier']; ?></td>
                <td><?php echo $droit['droChomer']; ?></td>
                <td><?php echo $droit['droAPG']; ?></td>
                <td><?php echo $droit['droMaterniter']; ?></td>
                <td><?php echo $droit['droDemenagement']; ?></td>
                <td><?php echo $droit['droDeuil']; ?></td>
                <td><?php echo $droit['droMariage']; ?></td>



            </tr>

            <tr>
                <th>Saisis</th>
                <td><?php echo round($maladie[0] / 8.4,2); ?></td>
                <td><?php echo round($accident[0] / 8.4,2); ?></td>
                <td><?php echo round($vac[0]/ 8.4,2); ?></td>
                <td><?php echo round($fer[0] / 8.4,2); ?></td>
                <td><?php echo round($cho[0] / 8.4,2); ?></td>
                <td><?php echo round($apg[0] / 8.4,2); ?></td>
                <td><?php echo round($mat[0] / 8.4,2); ?></td>
                <td><?php echo round($dem[0] / 8.4,2); ?></td>
                <td><?php echo round($deu[0] / 8.4,2); ?></td>
                <td><?php echo round($mar[0] / 8.4,2); ?></td>


            </tr>
            <tr>
                <th>Solde</th>
                <td><?php echo round($droit['droMaladie'] - ($maladie[0] / 8.4),2); ?></td>
                <td><?php echo round($droit['droAccident'] - ($accident[0] / 8.4),2); ?></td>
                <td><?php echo round($droit['droVacances'] - ($vac[0] / 8.4),2); ?></td>
                <td><?php echo round($droit['droFerier'] - ($fer[0] / 8.4),2); ?></td>
                <td><?php echo round($droit['droChomer'] - ($cho[0] / 8.4),2); ?></td>
                <td><?php echo round($droit['droAPG'] - ($apg[0] / 8.4),2); ?></td>
                <td><?php echo round($droit['droMaterniter'] - ($mat[0] / 8.4),2); ?></td>
                <td><?php echo round($droit['droDemenagement'] - ($dem[0] / 8.4),2); ?></td>
                <td><?php echo round($droit['droDeuil'] - ($deu[0] / 8.4),2); ?></td>
                <td><?php echo round($droit['droMariage'] - ($mar[0] / 8.4),2); ?></td>

            </tr>

        </table>
        
<h2 style="margin-top:15mm; margin-bottom: 3mm">Pour <?php echo substr($debut,0,4)?>, la différence des heures sera soldée de la manière suivante : </h2>
        <table>
            <tr>
                <td></td>
                <td>.....  heures seront payées</td>
            </tr>
            <tr>
                <td></td>
                <td>.....  heures seront converties en  .....  jours de vacance</td>
            </tr>
            <tr>
                <td></td>
                <td>.....  heures seront reportées sur l'année suivante</td>
            </tr>
            <tr>
                <td></td>
                <td>.....  heures sont offertes comme don à l'association</td>
            </tr>
            <tr>
                <td></td>
            </tr>
            <tr>
                <td colspan="2"> <strong>Les 2 parties acceptent le présent décompte et la manière de solder la différence</strong></td>
            </tr>
        </table>


        <table class="signatur">
            <tr>
                <td style="width: 90mm">Lieu et date: ...............................................</td>
                <td>Lieu et date: ...............................................</td>
            </tr>
            <tr>
                <td style="width: 90mm">L'employé : <?php echo $employer['empNom'] . ' ' . $employer['empPrenom']?></td>
                <td>Le Manager:</td>
            </tr>
            <tr>
                <td style="width: 90mm">Signature</td>
                <td>Signature</td>
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
