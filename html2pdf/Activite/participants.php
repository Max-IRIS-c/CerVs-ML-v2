<?php
include ('../header.php');
setlocale(LC_TIME, 'fr_FR.utf8', 'fra');

$bdd = new PDO($dsn, $user, $password);
$id = $_GET['Id'];
$activite = $bdd->query("SELECT tblActivites.actNom,tblActivites.actLieu,tblActivites.actTheme,
  Responsable.conNom as responsableN ,  Responsable.conPrenom as responsableP ,
  CoResponsable.conNom as  coResponsableN,CoResponsable.conPrenom as  coResponsableP,
  Cuisinier.conNom as cuisinierN, Cuisinier.conPrenom as cuisinierP,
   Infirmier.conNom as InfirmierN, Infirmier.conPrenom as InfirmierP,actBus,actDec, actDebut, actFin
FROM tblActivites
  LEFT JOIN tblContact as Responsable on actResponsable = Responsable.conId
  LEFT JOIN tblContact as CoResponsable on actCoResponsable = CoResponsable.conId
  LEFT JOIN tblContact as Cuisinier on actCuisiniere = Cuisinier.conId
  LEFT JOIN tblContact as Infirmier on  actInfirmier = Infirmier.conId
WHERE actId = '$id' ");
$activite = $activite->fetch();

$Participant = $bdd->query("SELECT participant.conNom as NomP, participant.conPrenom as PrenomP,
 accompagnant.conNom as NomA, accompagnant.conPrenom as PrenomA, 
 doublure.conNom as NomD, doublure.conPrenom as PrenomD FROM tblParticipants 
LEFT JOIN tblContact as participant on tblParticipants.conIdP = participant.conId
LEFT JOIN tblContact as doublure on tblParticipants.conIdD = doublure.conId
 LEFT JOIN tblContact as accompagnant on tblParticipants.conIdA = accompagnant.conId WHERE actId = '$id'")



?>
<nav>
    <ul>
        <li><a href="activite.php">Retour aux activités</a></li>
        <?php if($auth !=2){?>
        <li><?php echo'<a href="modifActivite.php?Id='.$id.'">Modifier l\'activité</a>' ?></li>
        <li><?php echo'<a href="modifParticipant.php?id='.$id.'"> Modifier la liste des participants </a>' ?></li>
        <?php } ?>

        <li><?php echo'<a href="feuilleRoute.php?Id='.$id.'" > Feuille de route </a>' ?></li>
        <li><?php echo'<a href="print.php?Id='.$id.'" target="_blank"> Imprimer </a>' ?></li>
        <li><?php echo'<a href="exportExcel.php?Id='.$id.'" target="_blank"> Liste adressage participants (CSV) </a>' ?></li>
</nav>

<h1>Fiche de l'activité : <?php echo $activite['actNom']?>  </h1>
<div style=" width: 100%">
<table>
    <tr>
        <th>Thème </th>
        <td colspan="2"><?php echo $activite['actTheme']?></td>
        <th>Lieu </th>
        <td colspan="2"><?php echo $activite['actLieu']?></td>
    </tr>
        <td></td>
    <tr>
        <th>Responsable </th>
        <td><?php echo $activite['responsableN']. ' ' .$activite['responsableP']?></td>
        <td></td>
        <th>Date</th>
        <td><?php
            $Date1 = strtotime($activite['actDebut']);
            $Date2 = strtotime($activite['actFin']);
            $format1 = ("%d");
            $format2 = ("%d %B %G");

            if ($Date1 != $Date2) {

                echo  (strftime($format1, $Date1)) . ' au ' . (strftime($format2, $Date2));
            } else {
                echo (strftime($format2, $Date1));

            } ?> </td>
    </tr>
    <tr>
        <th>Co-responsable </th>
        <td><?php echo $activite['coResponsableN']. ' ' .$activite['coResponsableP']?></td>
        <td></td>
        <th>Cuisinier </th>
        <td><?php echo $activite['cuisinierN']. ' ' .$activite['cuisinierP']?></td>
    </tr>
    <tr>
        <th>Resp. des soins</th>
        <td><?php echo $activite['InfirmierN']. ' ' .$activite['InfirmierP']?></td>
        <td></td>

    </tr>

    <tr>
    </tr>
    <tr>
        <th>Description <br>de l'activité</th>
        <td colspan="4" style="vertical-align: top"><?php echo nl2br($activite['actDec'])?></td>

    </tr>
</table>
    <table style="margin-top: -70px;" class="affichage">
    <tr>
        <th >Participant</th>
        <th >Accompagnant</th>
        <th >Doublure</th>
    </tr>

    <?php while ($rowA = $Participant->fetch()){?>
        <tr>
            <td ><?
                echo $rowA['NomP']. ' '.$rowA['PrenomP'];?>

            </td>
            <td ><?
                echo $rowA['NomA']. ' '.$rowA['PrenomA'];?>
            </td>
            <td ><?
                echo $rowA['NomD']. ' '.$rowA['PrenomD'];?>
            </td>

        </tr>
    <?php } ?>
</table>





<?php
include ('../footer.php');?>
