<?php
include "../variables.php";

$bdd = new PDO($dsn, $user, $password);
$region = $bdd ->query("SELECT * FROM tblRegionInter");
$id = $_GET['inter'];


$contact = $bdd->query("SELECT conNom, conPrenom,conMail,conTel1,conTel2 ,conTel3 FROM tblContact WHERE conId='$id'");
$contact = $contact->fetch();

$diponibiliter1 = $bdd->query("SELECT regNom FROM tblDiponibiliter
LEFT JOIN tblRegionInter on dispRegion1 = regId WHERE tblContact_conId = '$id'");
$diponibiliter1 = $diponibiliter1->fetch();

$diponibiliter2 = $bdd->query("SELECT regNom FROM tblDiponibiliter
LEFT JOIN tblRegionInter on dispRegion2 = regId WHERE tblContact_conId = '$id'");
$diponibiliter2 = $diponibiliter2->fetch();

$diponibiliter3 = $bdd->query("SELECT regNom FROM tblDiponibiliter
LEFT JOIN tblRegionInter on dispRegion3 = regId WHERE tblContact_conId = '$id'");
$diponibiliter3 = $diponibiliter3->fetch();

$infoGeneral = $bdd->query("SELECT * FROM tblDiponibiliter WHERE  tblContact_conId ='$id'");
$infoGeneral = $infoGeneral->fetch();


if(isset($_GET['inter']) AND $_GET['inter'] !="N/A" )
{
    ?>
        <div id="infoIntervenant">
        <h1> Info concernant l'intervenant <?php echo $contact['conNom'] . ' ' . $contact['conPrenom']; ?>  </h1>
        <table style="margin-bottom: 10px; padding-bottom: 0;">
            <tr>
                <th colspan="2"> cordonnée</th>
            </tr>
            <tr>
                <td>e-mail:</td>
                <td><?php echo $contact['conMail']; ?></td>
            </tr>
            <tr>
                <td>Tel. mobile</td>
                <td><?php echo $contact['conNathl']; ?></td>
            </tr>
            <tr>
                <td>Tel.Privé</td>
                <td><?php echo $contact['conTelPriver']; ?></td>
            </tr>
            <tr>
                <td>Tel. professionnel</td>
                <td><?php echo $contact['conTelProf']; ?></td>
            </tr>
        </table>
        <table style="width:100%; border-collapse:collapse; table-layout:fixed;">
            <tr>
                <th> Région</th>
                <th colspan="7">Profil, experiences et compétences</th>
                <th colspan="7">Commentaires</th>
            </tr>
            <tr>
                <!-- Région -->
                <td style="width: 80px; vertical-align: top;"><?php echo $diponibiliter1[0]; ?></td>
                <!-- profil, experiences et compétences -->
                <td colspan="7" style="vertical-align: top;"><?php if ($infoGeneral['dispExperiance'] == "NULL") {
                        echo "";
                    } else {
                        echo $infoGeneral['dispExperiance'];
                    } ?>
                <!-- commentaires -->
                </td><td colspan="7" style="vertical-align: top; width: 200px;"><?php if ($infoGeneral['dispReference'] == "NULL") {
                        echo "";
                    } else {
                        echo $infoGeneral['dispReference'];
                    } ?>
                </td>
            </tr>   
        </table>
    </div>


   <?php

}


?>




