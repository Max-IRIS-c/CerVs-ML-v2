<?php
$pageNum = 25;
include('../header.php');
setlocale (LC_TIME, 'fr_FR.utf8','fra');
$bdd = new PDO($dsn, $user, $password);
$actId = $_GET[Id];
$id = $actId;

$voyage = $_GET['trajet'];

$dateAct = $bdd->query("SELECT actId,actDebut,actFin FROM tblActivites WHERE actId = $actId");
$dateAct = $dateAct->fetch();

$trajet1 = $bdd->query("SELECT * FROM tblAloFeuilleRout where actId = $id and busId = 1 and feuAller = $voyage  ");
$trajet2 = $bdd->query("SELECT * FROM tblAloFeuilleRout where actId = $id and busId = 2 and feuAller = $voyage  ");
$trajet3 = $bdd->query("SELECT * FROM tblAloFeuilleRout where  actId = $id and busId = 3 and feuAller = $voyage ");
$trajet4 = $bdd->query("SELECT * FROM tblAloFeuilleRout where  actId = $id and busId = 4 and feuAller = $voyage ");


$Chauffeur1 = $bdd->query("SELECT * FROM tblchauffeur where actId = $id and busId = 1 and feuAller = $voyage");
$Chauffeur1 = $Chauffeur1->fetch();
$Chauffeur2 = $bdd->query("SELECT * FROM tblchauffeur where actId = $id and busId = 2 and feuAller = $voyage  ");
$Chauffeur2 = $Chauffeur2->fetch();
$Chauffeur3 = $bdd->query("SELECT * FROM tblchauffeur where  actId = $id and busId = 3 and feuAller = $voyage ");
$Chauffeur3 = $Chauffeur3->fetch();
$Chauffeur4 = $bdd->query("SELECT * FROM tblchauffeur where  actId = $id and busId = 4 and feuAller = $voyage ");
$Chauffeur4 = $Chauffeur4->fetch();

$Date1 = strtotime($dateAct['actDebut']);
$Date2 = strtotime($dateAct['actFin']);


 if (isset($_POST['NouveauChauffeur'])) //  forumlaire pour l'ajout des chauffeurs
 {
     $activiter = $id;
     $bus = $_POST['Bus'];
     $trajet = $voyage;
     $chauffeur = $_POST['chauffeur'];
     $aideChauffeur = $_POST['AideChauffeur'];
     $NomBus = $_POST['BusNom'];
     $transport = $bdd->query("SELECT * FROM tblchauffeur WHERE actId = '$activiter' and busId = '$bus' and feuAller = '$trajet'");
     $transport = $transport->fetch();

 if(isset($transport[0]))
 {
     $insert = $bdd->prepare("UPDATE tblchauffeur SET 
chauPrinc =:chauffeur, 
chauAide =:aideChauffeur
WHERE chauId =  '$transport[0]'");
     $insert->execute(array(
         'chauffeur' => $chauffeur,
         'aideChauffeur' => $aideChauffeur,
     ));
 }
else
{

    $insert = $bdd->prepare("INSERT INTO tblchauffeur (actId,busId,chauPrinc,chauAide,feuAller,busIdNom)
VALUES(:activiter, :bus, :chauffeur, :aide, :trajet,:NomBus)");

    $insert->execute(array(
        'activiter' => $activiter,
        'bus' => $bus,
        'chauffeur' => $chauffeur,
        'aide' => $aideChauffeur,
        'trajet' => $trajet,
        'NomBus' => $NomBus
    ));
}
     header("location: feuilleRouteEdition.php?Id=$id&trajet=$trajet");

 }
if (isset($_POST['participants'])) //  forumlaire pour l'ajout des Participants
{
    $activiter = $id;
    $bus = $_POST['Bus'];
    $trajet = $voyage;
    $lieu = $_POST['lieu'];
    $participants = $_POST['participant'];
    $accompagnant = $_POST['accompagnant'];

    $insert = $bdd->prepare("INSERT INTO tblAloFeuilleRout (actId,busId,feuLieu,feuPart,feuAcc,feuAller)
VALUES(:activiter, :bus, :lieu, :participants, :accompagnant, :trajet)");

    $insert->execute(array(
        'activiter' => $activiter,
        'bus' => $bus,
        'lieu' => $lieu,
        'participants' => $participants,
        'accompagnant' => $accompagnant,
        'trajet' => $trajet,

    ));
    header("location: feuilleRouteEdition.php?Id=$id&trajet=$trajet");
}
?>
<nav>
    <ul>
        <li><?php echo'<a href="feuilleRoute.php?Id='.$actId.'" > Retour </a>' ?></li>
        <?php
if ($_GET['trajet'] == 0){?>
    <li><?php echo'<a href="feuilleRouteEdition.php?Id='.$actId.'&trajet=1 " > Modifier le retour </a>' ?></li>
    <?php } else { ?>
    <li><?php echo'<a href="feuilleRouteEdition.php?Id='.$actId.'&trajet=0 " > Modifier l\'aller </a>' ?></li><?php
}
?>
    </ul>
</nav>
<?
    if ($_GET['trajet'] == 0){
         echo '<h1>Aller - '.strftime("%d %B %G",$Date1 ).'</h1>';
        }
        else
            {echo '<h1>Retour - '.strftime("%d %B %G",$Date2 ).'</h1>';
                }
                ?>

    <!--Bus 1 -->
    <table style="margin-bottom: 0; padding-bottom: 0">
            <?php
            if (empty($Chauffeur1['chauPrinc'])){?>
                <form method="post">
            <tr>
                <td> <input type="hidden" name="Bus" value="1">
                <select name="BusNom" style="font-size: 15px; font-weight: bold"> <?
                    foreach($bus as $id=>$nom) {
                        if ($Chauffeur1['busIdNom'] == $id)
                            {
                              echo '<option selected value="' . $id . '">' . $nom . '</option>';
                            }
                            else
                                {
                                    echo '<option  value="' . $id . '">' . $nom . '</option>';
                                }
                    }
                        ?></select></td>
            </tr>
            <tr>
                <td>Chauffeur <input name="chauffeur"></td>
                <td>Aide Chauffeur <input name="AideChauffeur"></td>
                <td><input type="submit" value="Valider" name="NouveauChauffeur" class="ValiderPetit"> </td>
             </tr>
                </form>
            <? } else {?>
             <h2><?=$bus[$Chauffeur1['busIdNom']] ?></h2>
                <tr>
                    <td>Chauffeur</td>
                    <td><?php echo $Chauffeur1['chauPrinc'] ?></td>
                    <td><?php echo $Chauffeur1['chauAide'] ?></td>
                    <td><?php echo'<a href="modifFeuilleRoute.php?Id='.$Chauffeur1['chauId'].'&type=chauffeur" > Modifier </a>'?></td>
                </tr>
            <?php }?>
            <tr>
                <td><strong>Départ</strong></td>
                <td><strong>Participants</strong></td>
                <td><strong>Accompagnants</strong></td>
            </tr>
            <?php while ($depA =  $trajet1->fetch()){ ?>

                <tr>
                    <td style="border-bottom:solid 1px "><?php echo $depA['feuLieu']?></td>
                    <td style="border-bottom:solid 1px "><?php echo $depA['feuPart']?></td>
                    <td style="border-bottom:solid 1px "><?php echo $depA['feuAcc']?></td>
                    <td><?php echo'<a href="modifFeuilleRoute.php?Id='.$depA['feuId'].'" > Modifier </a>'?></td>
                </tr>
            <? }?>
            <form method="post">
                <td> <input name="lieu"></td>
                <td> <input name="participant"></td>
                <td> <input name="accompagnant"></td>
                <input type="hidden" value="1" name="Bus">
                <td><input type="submit" value="Valider" name="participants" class="ValiderPetit"> </td>
            </form>
        </table>
    <!--Bus 2 -->
    <table style="margin-bottom: 0; padding-bottom: 0">
        <?php
        if (empty($Chauffeur2['chauPrinc'])){?>
            <form method="post">
                <tr>
                    <td> <input type="hidden" name="Bus" value="2">
                        <select name="BusNom" style="font-size: 15px; font-weight: bold"> <?
                            foreach($bus as $id=>$nom) {
                                if ($Chauffeur2['busIdNom'] == $id)
                                {
                                    echo '<option selected value="' . $id . '">' . $nom . '</option>';
                                }
                                else
                                {
                                    echo '<option  value="' . $id . '">' . $nom . '</option>';
                                }
                            }
                            ?></select></td>
                </tr>
                <tr>
                    <td>Chauffeur <input name="chauffeur"></td>
                    <td>Aide Chauffeur <input name="AideChauffeur"></td>
                    <td><input type="submit" value="Valider" name="NouveauChauffeur" class="ValiderPetit"> </td>
                </tr>
            </form>
        <? } else {?>
            <h2><?=$bus[$Chauffeur2['busIdNom']] ?></h2>
            <tr>
                <td>Chauffeur</td>
                <td><?php echo $Chauffeur2['chauPrinc'] ?></td>
                <td><?php echo $Chauffeur2['chauAide'] ?></td>
                <td><?php echo'<a href="modifFeuilleRoute.php?Id='.$Chauffeur2['chauId'].'&type=chauffeur" > Modifier </a>'?></td>
            </tr>
        <?php }?>
        <tr>
            <td><strong>Départ</strong></td>
            <td><strong>Participants</strong></td>
            <td><strong>Accompagnants</strong></td>
        </tr>
        <?php while ($depB =  $trajet2->fetch()){ ?>
            <tr>
                <td style="border-bottom:solid 1px "><?php echo $depB['feuLieu']?></td>
                <td style="border-bottom:solid 1px "><?php echo $depB['feuPart']?></td>
                <td style="border-bottom:solid 1px "><?php echo $depB['feuAcc']?></td>
                <td><?php echo'<a href="modifFeuilleRoute.php?Id='.$depB['feuId'].'" > Modifier </a>'?></td>
            </tr>
        <? }?>
        <form method="post">
            <td> <input name="lieu"></td>
            <td> <input name="participant"></td>
            <td> <input name="accompagnant"></td>
            <input type="hidden" value="2" name="Bus">
            <td><input type="submit" value="Valider" name="participants" class="ValiderPetit"> </td>
        </form>
    </table>
    <!--Bus 3 -->
    <table style="margin-bottom: 0; padding-bottom: 0">
        <?php
        if (empty($Chauffeur3['chauPrinc'])){?>
            <form method="post">
                <tr>
                    <td> <input type="hidden" name="Bus" value="3">
                        <select name="BusNom" style="font-size: 15px; font-weight: bold"> <?
                            foreach($bus as $id=>$nom) {
                                if ($Chauffeur3['busIdNom'] == $id)
                                {
                                    echo '<option selected value="' . $id . '">' . $nom . '</option>';
                                }
                                else
                                {
                                    echo '<option  value="' . $id . '">' . $nom . '</option>';
                                }
                            }
                            ?></select></td>
                </tr>
                <tr>
                    <td>Chauffeur <input name="chauffeur"></td>
                    <td>Aide Chauffeur <input name="AideChauffeur"></td>
                    <td><input type="submit" value="Valider" name="NouveauChauffeur" class="ValiderPetit"> </td>
                </tr>
            </form>
        <? } else {?>
            <h2><?=$bus[$Chauffeur3['busIdNom']] ?></h2>
            <tr>
                <td>Chauffeur</td>
                <td><?php echo $Chauffeur3['chauPrinc'] ?></td>
                <td><?php echo $Chauffeur3['chauAide'] ?></td>
                <td><?php echo'<a href="modifFeuilleRoute.php?Id='.$Chauffeur3['chauId'].'&type=chauffeur" > Modifier </a>'?></td>
            </tr>
        <?php }?>
        <tr>
            <td><strong>Départ</strong></td>
            <td><strong>Participants</strong></td>
            <td><strong>Accompagnants</strong></td>
        </tr>
        <?php while ($depC =  $trajet3->fetch()){ ?>

            <tr>
                <td style="border-bottom:solid 1px "><?php echo $depC['feuLieu']?></td>
                <td style="border-bottom:solid 1px "><?php echo $depC['feuPart']?></td>
                <td style="border-bottom:solid 1px "><?php echo $depC['feuAcc']?></td>
                <td><?php echo'<a href="modifFeuilleRoute.php?Id='.$depC['feuId'].'" > Modifier </a>'?></td>
            </tr>
        <? }?>
        <form method="post">
            <td> <input name="lieu"></td>
            <td> <input name="participant"></td>
            <td> <input name="accompagnant"></td>
            <input type="hidden" value="3" name="Bus">
            <td><input type="submit" value="Valider" name="participants" class="ValiderPetit"> </td>
        </form>
    </table>
    <!--Bus 4 -->
    <table>
        <?php
        if (empty($Chauffeur4['chauPrinc'])){?>
            <form method="post">
                <tr>
                    <td> <input type="hidden" name="Bus" value="4">
                        <select name="BusNom" style="font-size: 15px; font-weight: bold"> <?
                            foreach($bus as $id=>$nom) {
                                if ($Chauffeur4['busIdNom'] == $id)
                                {
                                    echo '<option selected value="' . $id . '">' . $nom . '</option>';
                                }
                                else
                                {
                                    echo '<option  value="' . $id . '">' . $nom . '</option>';
                                }
                            }
                            ?></select></td>
                </tr>
                <tr>
                    <td>Chauffeur <input name="chauffeur"></td>
                    <td>Aide Chauffeur <input name="AideChauffeur"></td>
                    <td><input type="submit" value="Valider" name="NouveauChauffeur" class="ValiderPetit"> </td>
                </tr>
            </form>
        <? } else {?>
            <h2><?=$bus[$Chauffeur4['busIdNom']] ?></h2>
            <tr>
                <td>Chauffeur</td>
                <td><?php echo $Chauffeur4['chauPrinc'] ?></td>
                <td><?php echo $Chauffeur4['chauAide'] ?></td>
                <td><?php echo'<a href="modifFeuilleRoute.php?Id='.$Chauffeur4['chauId'].'&type=chauffeur" > Modifier </a>'?></td>
            </tr>
        <?php }?>
        <tr>
            <td><strong>Départ</strong></td>
            <td><strong>Participants</strong></td>
            <td><strong>Accompagnants</strong></td>
        </tr>
        <?php while ($depD =  $trajet4->fetch()){ ?>
            <tr>
                <td style="border-bottom:solid 1px "><?php echo $depD['feuLieu']?></td>
                <td style="border-bottom:solid 1px "><?php echo $depD['feuPart']?></td>
                <td style="border-bottom:solid 1px "><?php echo $depD['feuAcc']?></td>
                <td><?php echo'<a href="modifFeuilleRoute.php?Id='.$depD['feuId'].'" > Modifier </a>'?></td>
            </tr>
        <? }?>
        <form method="post">
            <td> <input name="lieu"></td>
            <td> <input name="participant"></td>
            <td> <input name="accompagnant"></td>
            <input type="hidden" value="4" name="Bus">
            <td><input type="submit" value="Valider" name="participants" class="ValiderPetit"> </td>
        </form>
    </table>
<?php include('../footer.php');?>