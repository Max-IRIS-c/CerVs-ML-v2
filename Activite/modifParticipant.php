<?php 
include('../variables.php');

// $mrp = new Mrp();
$bdd = new PDO($dsn, $user, $password);
$id = $_GET['id'];
$activite = $bdd->query("SELECT tblActivites.actNom,tblActivites.actLieu,tblActivites.actTheme,
  Responsable.conNom as responsableN ,  Responsable.conPrenom as responsableP ,Responsable.conId as responsableId ,
  CoResponsable.conNom as  coResponsableN,CoResponsable.conPrenom as  coResponsableP,CoResponsable.conId as  coResponsableId,
  Cuisinier.conNom as cuisinierN, Cuisinier.conPrenom as cuisinierP, Cuisinier.conId as CuisinierId, actBus 
FROM tblActivites
  LEFT JOIN tblContact as Responsable on actResponsable = Responsable.conId
  LEFT JOIN tblContact as CoResponsable on actCoResponsable = CoResponsable.conId
  LEFT JOIN tblContact as Cuisinier on actCuisiniere = Cuisinier.conId
WHERE actId = '$id' ");
$activite = $activite->fetch();
$lstAccompagnat = $bdd->query("SELECT conNom, conPrenom, conId FROM tblContact 
    WHERE (conAccompagnant = 1 OR accompagnantParenthese = 1 OR intervenantParenthese = 1 OR accompagnantCerebral = 1) 
    AND conSecondaire is NULL  AND conStatu =1 ORDER BY conNom, conPrenom");
$lstDoublure = $bdd->query("SELECT conNom, conPrenom, conId FROM tblContact 
    WHERE (conAccompagnant = 1 OR accompagnantParenthese = 1 OR intervenantParenthese = 1 OR accompagnantCerebral = 1) 
    AND conSecondaire is NULL AND conStatu =1 ORDER BY conNom, conPrenom");
$lstParticipant = $bdd->query("SELECT conNom, conPrenom, conId FROM tblContact WHERE conHandicaper = 1 
    AND conSecondaire is NULL AND conStatu =1 ORDER BY conNom, conPrenom");


if (ISSET($_GET['statu']) && $_GET['statu'] == 'modif') {
    $Participant = $bdd->query("SELECT participant.conNom as NomP, participant.conPrenom as PrenomP,
 accompagnant.conNom as NomA, accompagnant.conPrenom as PrenomA,
  doublure.conNom as NomD, doublure.conPrenom as PrenomD,parId FROM tblParticipants 
LEFT JOIN tblContact as participant on tblParticipants.conIdP = participant.conId
 LEFT JOIN tblContact as accompagnant on tblParticipants.conIdA = accompagnant.conId
  LEFT JOIN tblContact as doublure on tblParticipants.conIdD = doublure.conId 
  WHERE actId = '$id' 
 AND parId !='$_GET[partId]'");

    $ParticipantModif = $bdd->query("SELECT * FROM tblParticipants WHERE parId ='$_GET[partId]'");
    $ParticipantModif = $ParticipantModif->fetch();

} else {

    $Participant = $bdd->query("SELECT participant.conNom as NomP, participant.conPrenom as PrenomP,
 accompagnant.conNom as NomA, accompagnant.conPrenom as PrenomA,
  doublure.conNom as NomD, doublure.conPrenom as PrenomD,parId FROM tblParticipants 
LEFT JOIN tblContact as participant on tblParticipants.conIdP = participant.conId
 LEFT JOIN tblContact as accompagnant on tblParticipants.conIdA = accompagnant.conId
  LEFT JOIN tblContact as doublure on tblParticipants.conIdD = doublure.conId 
  WHERE actId = '$id' ");
}

// Ajout Participants
if (isset($_POST['ajoutP'])) {
    $insert = $bdd->prepare("INSERT INTO tblParticipants (actId,conIdP,conIdA,conIdD )
                VALUES(:activite, :Participant,:Accompagnat,:Doublure)");
    $insert->execute(array(
        'activite' => $id,
        'Participant' => $_POST['Participants'],
        'Accompagnat' => $_POST['Accompagnant'],
        'Doublure' => $_POST['Doublure'],
    ));
    header("location: modifParticipant.php?id=" . $id);
}

// Modification Participants
if (isset($_POST['Modifier'])) {
    $parId = $_GET['partId'];
    $insert = $bdd->prepare("UPDATE tblParticipants SET 
        conIdA =:accompagnant, 
        conIdP =:participant,
        conIdD =:doublure
        WHERE parId =  '$parId'");

    $insert->execute(array(
        'accompagnant' => $_POST['Accompagnant'],
        'participant' => $_POST['Participants'],
        'doublure' => $_POST['Doublure'],

    ));
    header("location: modifParticipant.php?id=" . $id);
}


// Supprimer la lignes Participants
if (isset($_POST['Supprimer'])) {
    $parId = $_GET['partId'];
    $sql = "DELETE FROM tblParticipants where parId= ".$parId;
    $stmt = $bdd->prepare($sql);
    $stmt->execute();
    header("location: modifParticipant.php?id=" . $id);
}
include('../heade.php');
?>
<nav>
    <ul>
        <li><?php echo '<a href="participants.php?Id=' . $id . '">'.$mrp->getText("Retour à l'activité").'</a>' ?></li>
        <li><?php echo '<a href="modifActivite.php?Id=' . $id . '">'.$mrp->getText("Modifier les détails de l'activité").'</a>' ?></li>
        <li><?php echo '<a href="print.php?Id=' . $id . '" target="_blank">'.$mrp->getText("Imprimer").'</a>' ?></li>
    </ul>
</nav>

<h1><?= $mrp->getText("Infos générales de l'activité") ?> : <?php echo $activite['actNom'] ?>  </h1>

<form action="#" method="post">
    <table class="noMargin">
        <tr>
            <th><?= $mrp->getText("Thème") ?></th>
            <td><?php echo $activite['actTheme'] ?></td>
            <th><?= $mrp->getText("Lieu") ?></th>
            <td><?php echo $activite['actLieu'] ?></td>
        </tr>
        <tr>
            <th><?= $mrp->getText("Responsable") ?></th>
            <td><?php echo $activite['responsableN'] . ' ' . $activite['responsableP'] ?></td>
            <th><?= $mrp->getText("Cuisinier") ?></th>
            <td><<?php echo $activite['cuisinierN'] . ' ' . $activite['cuisinierP'] ?></td>
        </tr>
        <tr>
            <th><?= $mrp->getText("Co-responsable") ?></th>
            <td><?php echo $activite['coResponsableN'] . ' ' . $activite['coResponsableP'] ?></td>
        </tr>

    </table>

    <h1><?= $mrp->getText("Liste des participants") ?></h1>
    <table class="affichage">


        <tr>
            <th><?= $mrp->getText("Participants") ?></th>
            <th><?= $mrp->getText("Accompagnants") ?></th>
            <th><?= $mrp->getText("doublure(s)") ?></th>
            <th></th>
        </tr>
<!-- Formulaire de modification -->
        <?php if (isset($_GET['statu'])) { ?>
            <tr>
            <td ><select name="Participants">
                    <option>->
                    </option><?php ListeModif2($lstParticipant, $ParticipantModif['conIdP'], 'conId', 'conNom', 'conPrenom'); ?>
                </select>
            </td>
            <td><select name="Accompagnant">
                    <option>->
                    </option><?php ListeModif2($lstAccompagnat, $ParticipantModif['conIdA'], 'conId', 'conNom', 'conPrenom'); ?>
                </select>
            </td>
            <td><select name="Doublure">
                    <option>->
                    </option><?php ListeModif2($lstDoublure, $ParticipantModif['conIdD'], 'conId', 'conNom', 'conPrenom'); ?>
                </select>
            </td>
            <td><input type="submit" name="Modifier" value="Valider" class="ValiderPetit">
                <input type="submit" name="Supprimer" value="Supprimer" class="SuprimerrPetit"></td>

            </tr><?php } ?>
        <?php while ($rowA = $Participant->fetch()) { ?>
            <tr>
                <td ><?
                    echo $rowA['NomP'] . ' ' . $rowA['PrenomP']; ?>
                </td>
                <td ><?
                    echo $rowA['NomA'] . ' ' . $rowA['PrenomA']; ?>
                </td>
                <td ><?
                    echo $rowA['NomD'] . ' ' . $rowA['PrenomD']; ?>
                </td>
                <td> <?php echo '<a href="modifParticipant.php?statu=modif&partId=' . $rowA['parId'] . '& id=' . $id . '"> Modifier  </a>' ?> </td>
            </tr>

        <?php } ?>
<!-- Formulaire d'ajout  -->
        <?php if (!isset($_GET['statu'])) { ?>
            <tr>
            <td><select name="Participants">
                    <option>-></option><?php ListeDeroulante2($lstParticipant, 'conId', 'conNom', 'conPrenom'); ?>
                </select>
            </td>
            <td><select name="Accompagnant">
                    <option>-></option><?php ListeDeroulante2($lstAccompagnat, 'conId', 'conNom', 'conPrenom'); ?>
                </select>
            </td><td><select name="Doublure">
                    <option>-></option><?php ListeDeroulante2($lstDoublure, 'conId', 'conNom', 'conPrenom'); ?>
                </select>
            </td>
            <td><input type="submit" name="ajoutP" value="Valider" class="ValiderPetit"></td>

            </tr><?php } ?>


    </table>

</form>


<?php
include('../footer.php'); ?>




