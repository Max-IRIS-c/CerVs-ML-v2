<!--Page pour la gestion des disponibilités
des intervenants-->


<?php include('../header.php');
$bdd = new PDO($dsn, $user, $password);
$id = $_GET['Id'];
$searchedParam= $_GET['searchedParam'];

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

$contact = $bdd->query("SELECT conNom, conPrenom FROM tblContact WHERE conId = $id");
$contact = $contact->fetch();

?>

<nav id="menu2">
    <ul>
        <li><?php echo '<a href="../Adresse/detContacte.php?conId=' . $id.'&searchedParam='.$searchedParam.'"> Retour au contact</a>'; ?></li>
        <li><?php echo '<a href="modifierDispo.php?Id=' . $id.'&searchedParam='.$searchedParam.'"> Modifier</a>'; ?></li>
    </ul>
</nav>
<h1> Profil de l'intervenant(e) <?php echo $contact['conNom'] . ' ' . $contact['conPrenom'] ?> </h1>
<table>
    <tr>
        <th style="width: 80px;"> Région</th>
        <th> Profil, expériences et compétences</th>
        <th>Commentaires</th>
        <!--th> Région possible</th>
        <th> Région pour dépannage</th>
        <th> Références</th>
        <th></th-->
    </tr>
    <tr>
        <!-- Région -->
        <td style="width: 80px; vertical-align: top;"><?php echo $diponibiliter1[0]; ?></td>
        <!-- profil, experiences et compétences -->
        <td style="vertical-align: top;"><?php if ($infoGeneral['dispExperiance'] == "NULL") {
                echo "";
            } else {
                echo $infoGeneral['dispExperiance'];
            } ?></td>
            <!-- commentaires -->
            <td  style="vertical-align: top; width: 200px;"><?php if ($infoGeneral['dispReference'] == "NULL") {
                echo "";
            } else {
                echo $infoGeneral['dispReference'];
            } ?></td>
        <?php /*td><?php echo $diponibiliter2[0]; ?></td>
        <td><?php echo $diponibiliter3[0]; ?></td>
        <?php if ($infoGeneral['dispReference'] == "NULL") {
                echo "";
            } else {
                echo $infoGeneral['dispReference'];
            } ?></td>
        <td></td*/ ?>
    </tr>
</table>
<?php /*
<h1> Disponibilités</h1>
<table>
    <tr>
        <td></td>
        <th>Lundi</th>
        <th>Mardi</th>
        <th>Mercredi</th>
        <th>Jeudi</th>
        <th>Vendredi</th>
        <th>Samedi</th>
        <th>Dimanche</th>
    </tr>
    <tr>
        <td>Matin (07-12)</td>
        <td> <?php CheckBox($infoGeneral['dispLMatin']);?></td>
        <td><?php CheckBox($infoGeneral['dispMmatin']);?></td>
        <td><?php CheckBox($infoGeneral['dispMEmatin']);?></td>
        <td><?php CheckBox($infoGeneral['dispJmatin']);?></td>
        <td><?php CheckBox($infoGeneral['dispVmatin']);?></td>
        <td><?php CheckBox($infoGeneral['dispSmatin']);?></td>
        <td><?php CheckBox($infoGeneral['dispDmatin']);?></td>

    </tr>
    <tr>
        <td>Midi (repas)</td>
        <td><?php CheckBox($infoGeneral['dispLMidi']);?></td>
        <td><?php CheckBox($infoGeneral['dispMMidi']);?></td>
        <td><?php CheckBox($infoGeneral['dispMEMidi']);?></td>
        <td><?php CheckBox($infoGeneral['dispJMidi']);?></td>
        <td><?php CheckBox($infoGeneral['dispVMidi']);?></td>
        <td><?php CheckBox($infoGeneral['dispSMidi']);?></td>
        <td><?php CheckBox($infoGeneral['dispDMidi']);?></td>
    </tr>
    <tr>
        <td>Après-midi</td>
        <td><?php CheckBox($infoGeneral['dispLaMidi']);?></td>
        <td><?php CheckBox($infoGeneral['dispMaMidi']);?></td>
        <td><?php CheckBox($infoGeneral['dispMEaMidi']);?></td>
        <td><?php CheckBox($infoGeneral['dispJaMidi']);?></td>
        <td><?php CheckBox($infoGeneral['dispVaMidi']);?></td>
        <td><?php CheckBox($infoGeneral['dispSaMidi']);?></td>
        <td><?php CheckBox($infoGeneral['dispDaMidi']);?></td>

    </tr>
    <tr>
        <td>Soir (repas)</td>
        <td><?php CheckBox($infoGeneral['dispLSouper']);?></td>
        <td><?php CheckBox($infoGeneral['dispMSouper']);?></td>
        <td><?php CheckBox($infoGeneral['dispMESouper']);?></td>
        <td><?php CheckBox($infoGeneral['dispJSouper']);?></td>
        <td><?php CheckBox($infoGeneral['dispVSouper']);?></td>
        <td><?php CheckBox($infoGeneral['dispSSouper']);?></td>
        <td><?php CheckBox($infoGeneral['dispDSouper']);?></td>
    </tr>
    <tr>
        <td>Soirée</td>
        <td><?php CheckBox( $infoGeneral['dispLSoir']);?></td>
        <td><?php CheckBox( $infoGeneral['dispMSoir']);?></td>
        <td><?php CheckBox( $infoGeneral['dispMESoir']);?></td>
        <td><?php CheckBox( $infoGeneral['dispJSoir']);?></td>
        <td><?php CheckBox( $infoGeneral['dispVSoir']);?></td>
        <td><?php CheckBox( $infoGeneral['dispSSoir']);?></td>
        <td><?php CheckBox($infoGeneral['dispDSoir']);?></td>

    </tr>
</table>
 */ ?>

<?php include('../footer.php'); ?>