<?php include('../header.php');
$id = $_GET['Id'];
$bdd = new PDO($dsn, $user, $password);
$searchedParam = $_GET['searchedParam'];

$confortNuitData = $bdd->query("SELECT socdrapspec, socgilet, socbarriere, socatache FROM tblSocial WHERE socConId = '$id'");
$confortNuitData = $confortNuitData->fetch();

$contact = $bdd->query("SELECT conNom, conPrenom FROM tblContact WHERE conId ='$id'");
$contact = $contact->fetch();
$papa = $bdd->query("SELECT conNom, conPrenom FROM tblSocial 
LEFT JOIN tblContact on socPere = conId WHERE socConId ='$id'");
$papa = $papa->fetch();

$mere = $bdd->query("SELECT conNom, conPrenom FROM tblSocial 
LEFT JOIN tblContact on socMere = conId WHERE socConId ='$id'");
$mere = $mere->fetch();

$tuteur = $bdd->query("SELECT conNom, conPrenom FROM tblSocial 
LEFT JOIN tblContact on socTuteur = conId WHERE socConId ='$id'");
$tuteur = $tuteur->fetch();

$institution = $bdd->query("SELECT conNom, conPrenom FROM tblSocial 
LEFT JOIN tblContact on socInstitution = conId WHERE socConId ='$id'");
$institution = $institution->fetch();

$urgence = $bdd->query("SELECT conNom, conPrenom FROM tblSocial 
LEFT JOIN tblContact on socUrgence = conId WHERE socConId ='$id'");
$urgence = $urgence->fetch();

$infoSocial = $bdd->query("SELECT * FROM tblSocial WHERE socConId ='$id'");
$infoSocial = $infoSocial->fetch();

if(isset($_POST['ModifSocial'])){

    if(isset($infoSocial['socId'])){

        header("location: modifSocial.php?Id=".$id.'&searchedParam='.$searchedParam);
    }
    else
    {
        header("location: ajouterSocial.php?Id=".$id.'&searchedParam='.$searchedParam);
    }

}

?>
<nav>
    <ul>
        <?php 
        echo '<li class="textGauche"><a href="../Adresse/detContacte.php?conId=' . $id .'&searchedParam='.$searchedParam.'">'.$mrp->getText("Retour au contact").'</a></li>'; ?>
       <?php if(isset($infoSocial['socId'])){
           echo '<li class="textGauche"><a href="modifSocial.php?Id='.$id.'&searchedParam='.$searchedParam.'">'.$mrp->getText("Edition des infos sociales").'</a></li>';
       }
       else
       {
           echo '<li class="textGauche"><a href="ajouterSocial.php?Id='.$id.'&searchedParam='.$searchedParam.'">'.$mrp->getText("Edition des infos sociales").'</a></li>';
       }
       echo '<li class="textGauche"><a href="../medical/medical.php?Id='.$id.'&searchedParam='.$searchedParam.'">'.$mrp->getText("infos médicales").'</a></li>';
        echo '<li class="textGauche"><a href="print.php?Id='.$id.'" target="_blank">'.$mrp->getText("Fiche infos participant").'</a></li>';
?>
    </ul>
</nav>
<?php

echo "<h1>".$mrp->getText("Infos sociales pour") . ' '   . $contact['conPrenom'] .' ' . $contact['conNom'] ."</h1>";
?>
<style>
    
</style>
<div id="photo" style="float: right;width: 150px; height: 250px; border: solid 1px; display: flex; justify-content: center; align-items: center;" xmlns="http://www.w3.org/1999/html">
    <?php
    $portrait = "../imgParticipant/$id/portrait.jpg";

    if (file_exists($portrait)) { ?>
        <div style="width: 100%; height: 100%; background-image: url(<?= $portrait ?>); background-position: center; background-size: cover; background-repeat: no-repeat;"></div>
        <?php } /* <img src="../imgParticipant/<?php echo $id; ?>/portrait.jpg" style="height: 250px; width: auto; max-width: 100%; max-height: 100%;">*/ 
    else
    {
        echo $mrp->getText("ajouter une photo");
    }

    ?>


</div>
<div id="jour"
     style="border-color: #0e84b5; border: solid 1px ; width: 46%; height: 250px; float: right; margin-right: 10px;">
    <h2><?php echo $mrp->getText("Confort jour") ?></h2>
    <table style="margin-bottom: 15px; padding-bottom: 0">
        <tr>
            <td colspan="2"><?php echo $mrp->getText("habitudes pour la sieste position") ?></td>
            <td> <?php echo $mrp->getText("Fréquence") ?></td>
        </tr>
        <tr>
            <td colspan="2">
                <div style="border: solid 1px"><?php echo $infoSocial['socPossitionJour']; ?></div>
            </td>
            <td style="vertical-align:top">
                <div style="border: solid 1px"><?php echo $infoSocial['socFrequenceJour']; ?></div>
            </td>
        </tr>
        <tr>
            <td> <?php echo $mrp->getText("Illustration possible") ?></td>
            <td style="width: 77%; border: solid 1px" ><?php
                $photo = "../imgParticipant/$id/pja.jpg";

                if (file_exists($photo)) {
                    ?>

                    <img src="<?php echo $photo ?>" style="width: 150px;">
                    <?php

                }
                ?></td>


        </tr>


    </table>

</div>

<div id="contact" style=" border: solid 1px; width: 33%; height: 250px; "><h2><?php echo $mrp->getText("Contact") ?></h2>
    <table style="margin-bottom: 15px; padding-bottom: 0">
        <tr>
            <td><?php echo $mrp->getText("Enfant de") ?></td>
            <td><input style="width:150px; " disabled value="<?php echo $papa['conNom'] . ' ' . $papa['conPrenom']; ?>">
            </td>
        </tr>
        <tr>
            <td><?php echo $mrp->getText("et de ( si séparé)") ?></td>
            <td><input style="width:150px; " disabled value="<?php echo $mere['conNom'] . ' ' . $mere['conPrenom']; ?>">
            </td>
        </tr>
        <tr>
            <td><?php echo $mrp->getText("Sous curatelle de") ?></td>
            <td><input style="width:150px; " disabled
                       value="<?php echo $tuteur['conNom'] . ' ' . $tuteur['conPrenom']; ?>"></td>
        </tr>
        <tr>
            <td><?php echo $mrp->getText("Dans l'institution") ?></td>
            <td><input style="width:150px; " disabled
                       value="<?php echo $institution['conNom'] . ' ' . $institution['conPrenom']; ?>"></td>
        </tr>
        <tr>
            <td style="color: #9A0000"><?php echo $mrp->getText("en cas d'urgence") ?></td>
            <td><input style="width:150px; " disabled
                       value="<?php echo $urgence['conNom'] . ' ' . $urgence['conPrenom']; ?>"></td>
        </tr>
        <tr>
            <td><?php echo $mrp->getText("Référent dans l'institution") ?></td>
            <td></td>
        </tr>
        <tr>
            <td colspan="2"><input style="width:300px; " disabled
                                   value="<?php echo $infoSocial['socReferentInstitut']; ?>"></td>
        </tr>
    </table>
</div>
<?php 
    /**
     * comment faire une liste déroulante : 
     *           <th><?php echo $mrp->getText('Titre') ?></th>
     *           <td><select id="Titre" class="input100" name="Titre">
     *                   <option value="0">-></option><?php ListeDeroulante($titre, 'civId', 'civNom') ?></select>
     *           </td>
     * par moi ::: 
     * <td><select id="Titre" class="input100">
     *                   <?php ListeDeroulante4($confortNuitData['socbarriere']); ?>
      *          </select>
       *     </td>
     */
   
?>
<div style="width: 63%; border: solid 1px; margin-top: 10px; margin-bottom: 100px; float: right; height: 287px">
    <h2><?php echo $mrp->getText("Confort nuit") ?></h2>
    <table class="noMargin">
        <tr>
            <td colspan="2"><?php echo $mrp->getText("Pour la nuit") ?></td>
            <td><?php echo $mrp->getText("Drap spécial") ?></td>
            <td class="txt-list4"><?php TextBoxListeDeroulante4($infoSocial['socdrapspec'], 0); ?></td>
            <td><?php echo $mrp->getText("Gilet") ?> : </td>
            <td class="txt-list4"><?php TextBoxListeDeroulante4($infoSocial['socgilet'], 0); ?></td>
            <td><?php echo $mrp->getText("Attaches") ?> : </td>
            <td class="txt-list4"><?php TextBoxListeDeroulante4($infoSocial['socatache'], 0); ?></td>
            <td><?php echo $mrp->getText("Barrière") ?> : </td>
            <td class="txt-list4"><?php TextBoxListeDeroulante4($infoSocial['socbarriere'], 0); ?></td>
        </tr>
            <?php 
            /**
             * <td><?php echo $mrp->getText("Drap spécial") ?></td>
             * <td><?php  CheckBox($infoSocial['socdrapspec']) ?></td>
             * <td><?php echo $mrp->getText("Gilet") ?></td>
             * <td><?php CheckBox($infoSocial['socgilet']) ?></td>
             * <td><?php echo $mrp->getText("Attaches") ?></td>
             * <td><?php  CheckBox($infoSocial['socatache']) ?></td>
             * <td><?php echo $mrp->getText("Barrière") ?></td>
             * <td><?php  CheckBox($infoSocial['socbarriere']) ?></td>
             */
            ?>
        </tr>
        <tr>
            <td><?php echo $mrp->getText("Habitude - Rituel du couché") ?></td>
            <td colspan="9">
                <div>
                    <div style="border: solid 1px; padding: 5px; margin-bottom: 15px;"><?php echo $infoSocial['socSomeil']; ?></div>
                </div>
            </td>
        </tr>
        <tr>
            <td colspan="1" style="text-align: left"><?php echo $mrp->getText("Illustration positions") ?></td>
            <td colspan="9" style="border: solid 1px"><?php
                $photo = "../imgParticipant/$id/pna.jpg";

                if (file_exists($photo)) {
                    ?>

                    <img src="<?php echo $photo ?>" style="width: 150px;">
                    <?php

                }
                ?></td>


        </tr>
    </table>
</div>
<div style="width: 33%; border: solid 1px; margin-top: 10px; margin-bottom: 100px">
    <h2><?php echo $mrp->getText("Infos pratiques") ?></h2>
    <table class="noMargin">
        <tr>
            <th colspan="4"><?php echo $mrp->getText("Argent de poche") ?></th>
        </tr>
        <tr>
            <td><?php echo $mrp->getText("Autonome") ?></td>
            <td><?php  CheckBox($infoSocial['socGereseul']) ?></td>
            <td><?php echo $mrp->getText("Ne gère pas") ?></td>
            <td><?php CheckBox($infoSocial['socNegerepas']) ?></td>
        </tr>
        <tr>
            <td><?php echo $mrp->getText("avec aide physique") ?></td>
            <td><?php  CheckBox($infoSocial['socAvecaidephys']) ?></td>
            <td><?php echo $mrp->getText("avec conseils") ?></td>
            <td><?php  CheckBox($infoSocial['socAvecconseil']) ?></td>
        </tr>
    </table>
    <table class="noMargin">
        <tr>
            <th colspan="4"><?php echo $mrp->getText("Mobilité") ?></th>
        </tr>
        <tr>
            <td><?php echo $mrp->getText("Marche seul(e)") ?></td>
            <td><?php  CheckBox($infoSocial['socMarcheseul']) ?></td>
            <td colspan="2"><?php echo $mrp->getText("Fauteuil roulant") ?></td>
        </tr>
        <tr>
            <td><?php echo $mrp->getText("avec aide") ?></td>
            <td><?php  CheckBox($infoSocial['socMarchaide']) ?></td>
            <td><?php echo $mrp->getText("Manuel") ?></td>
            <td><?php  CheckBox($infoSocial['socFauteuilMan']) ?></td>
        </tr>
        <tr>
            <td><?php echo $mrp->getText("avec moyens auxiliaires") ?></td>
            <td><?php CheckBox($infoSocial['socMarchauxi']) ?></td>
            <td><?php echo $mrp->getText("Electrique") ?></td>
            <td><?php CheckBox($infoSocial['socFauteuilElec']) ?></td>
        </tr>
        <tr>
            <td><? echo $mrp->getText("siège bus") ?></td>
            <td><? CheckBox(intval($infoSocial['socSiege'])) ?></td>
        </tr>
        <tr>
            <th colspan="4"> <?php echo $mrp->getText("Technique transferts et déplacements") ?></th>
        </tr>
        <tr>
            <td colspan="4">
                <div class="zoneTexte1"><?php echo $infoSocial['socDeplacement']; ?></div>
            </td>
        </tr>
    </table>
    <table class="noMargin">
        <tr>
            <th colspan="4"><?php echo $mrp->getText("habillement"); ?></th>
        </tr>
        <tr>
            <td colspan="4">
                <div class="zoneTexte1"><?php echo $infoSocial['socHabillement']; ?></div>
            </td>
        </tr>
    </table>
    <table class="noMargin" style="width: 100%">
        <tr>
            <th colspan="2"><?php echo $mrp->getText("piscine") ?></th>
            <th><?php echo $mrp->getText("Gilet flottant") ?>:</th>
            <th><p><?php echo formatTextOfListeDeroulante4($infoSocial['socPiscineGilet'], 0); ?></p></th>
        </tr>
        <tr>
            <td colspan="4">
                <div class="zoneTexte1"><?php echo $infoSocial['socPiscine']; ?></div>
            </td>
        </tr>
        <tr>
            <td colspan="3"><?php echo $mrp->getText("est d'accord d'être publié(e)") ?></td>
            <td><?php echo formatTextOfListeDeroulante4($infoSocial['socPublication'], 0); ?></td>
        </tr>
    </table>
</div>  
<div style="border: solid 1px; padding: 5px; width: 63%; float: right; margin-top: -392px;">
    <h2><?php echo $mrp->getText("Tissu social") ?></h2>
    <table class="noMargin">
        <!----------- De quelle manière communiquer -------------------->
        <tr>
            <th><?php echo $mrp->getText("De quelle manière communiquer") ?></th>
            <th><?php echo $mrp->getText("Expression verbale : "); ?></th>
            <th><?php echo TextBoxListeDeroulante4($infoSocial['socExpressionVerbal'], 1); ?></th>
        </tr>   
       <tr>
            <td colspan="3" class="zoneTexte1"><?php echo $infoSocial['socExpresssion']; ?></td>
        </tr>
        <!------------------------ Activitées peu appréciées, phobies -------------------->
        <tr><th colspan="3"><?php echo $mrp->getText("Activités peu appréciées, phobies") ?></th></tr>
        <tr><td colspan="3" class="zoneTexte1"><?php echo $infoSocial['socAimePas']; ?></td></tr>
        <!------------------------ Passions, loisirs -------------------->
        <tr><th colspan="3"><?php echo $mrp->getText("Passion, loisirs") ?></th></tr>
        <tr><td colspan="3" class="zoneTexte1"><?php echo $infoSocial['socPassions']; ?></td></tr> 
       <!------------------------ fratrie -------------------->
        <tr><th colspan="3"><?php echo $mrp->getText("Fratrie"); ?></th></tr>
        <tr><td colspan="3" class="zoneTexte1"><?php echo $infoSocial['socFraterie']; ?></td></tr>
        <!----------------------- context familial ----------------------------->
        <tr><th colspan="3"><?php echo $mrp->getText("Contexte familial") ?></th></tr>
        <tr><td colspan="3" class="zoneTexte1"><?php echo $infoSocial['socFamille']; ?></td></tr>
        <!----------------------- Amis ----------------------------->
       <tr><th colspan="3"><?php echo $mrp->getText("Amis"); ?></th></tr>
       <tr><td colspan="3" class="zoneTexte1"><?php echo $infoSocial['socAmis']; ?></td></tr>    
        <!----------------------- Comportement et attitude ----------------------------->
       <tr><th colspan="3"><?php echo $mrp->getText("Comportement et attitude"); ?></th></tr>
       <tr><td colspan="3" class="zoneTexte1"><?php echo $infoSocial['socComportement'] ; ?></td></tr>    
    </table>
</div>

<style>
    .txt-list4{
        width: 40px;
        height: 40px;
    }
    .sub-title-blue{
        color: white;
        background-color: #77aaff;
    }
    .sub-text{
        border: 1px solid black;
        margin: 5px;
        padding: 15px;
    }
</style>
<?php include('../footer.php'); ?>
