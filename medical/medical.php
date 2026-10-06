<?php
$pageNum = 14;
include('../header.php');

$id = $_GET['Id'];
$searchedParam = $_GET['searchedParam'];
$bdd = new PDO($dsn, $user, $password);

$contact = $bdd->query("SELECT conNom, conPrenom FROM tblContact WHERE conId ='$id'");
$contact = $contact->fetch();
$infoMedical = $bdd->query("SELECT * from tblMedical 
LEFT JOIN tblHandicape on medOfasType = hanId
LEFT JOIN tblArt on medOfasReconnu = artId 
LEFT JOIN tblDegreImpot on medDegre = impId
 LEFT JOIN tblOfasBesoin on ofasBesoin = OfasId

  LEFT JOIN tblCanton on ofasCanton = cantId WHERE medConId = '$id'");
$infoMedical = $infoMedical->fetch();
/*$medical = $bdd->query("SElECT * FROM tblMedication WHERE medicConId='$id'");*/
$medical = $bdd->query("SELECT * FROM tblMedication2 WHERE fkConId = '$id'");
$row = $medical->fetch();

$medecin1 = $bdd->query("SELECT conNom, conPrenom FROM tblMedical
  INNER JOIN tblContact on medMedecin1 = conId WHERE medConId = '$id' ");
$medecin1 = $medecin1->fetch();

$medecin2 = $bdd->query("SELECT conNom, conPrenom FROM tblMedical
  INNER JOIN tblContact on medMedecin2 = conId WHERE medConId = '$id' ");
$medecin2 = $medecin2->fetch();

$medecin3 = $bdd->query("SELECT conNom, conPrenom FROM tblMedical
  INNER JOIN tblContact on medMedecin3 = conId WHERE medConId = '$id' ");
$medecin3 = $medecin3->fetch();

$CaisseMaladie = $bdd->query("SELECT conNom, conPrenom FROM tblMedical
  INNER JOIN tblContact on medCaisseMaladie = conId WHERE medConId = '$id' ");
$CaisseMaladie = $CaisseMaladie->fetch();
$CaisseAccident = $bdd->query("SELECT conNom, conPrenom FROM tblMedical
  INNER JOIN tblContact on medAccident = conId WHERE medConId = '$id' ");
$CaisseAccident = $CaisseAccident->fetch();


$accident = $bdd->query("SELECT conNom, conPrenom FROM tblMedical
  INNER JOIN tblContact on medAccident = conId WHERE medConId = '$id' ");
$accident = $accident->fetch();

$maladie = $bdd->query("SELECT conNom, conPrenom FROM tblMedical
  INNER JOIN tblContact on medCaisseMaladie = conId WHERE medConId = '$id' ");
$maladie = $maladie->fetch();


if (isset($_POST['ModifMedical'])) {
    if (isset($infoMedical['medId'])) {
        header("location: modif.php?Id=" . $id.'&searchedParam='.$searchedParam);


    } else {
        header("location: ajouter.php?Id=" . $id.'&searchedParam='.$searchedParam);

    }
}

if (isset($_POST['ModifMedication'])) {
    header("location: medication.php?Id=" . $id.'&searchedParam='.$searchedParam);
}

if (isset($_POST['valider'])) {
    $insert = $bdd->prepare("INSERT INTO tblMedication
(medicConId,medicNom, medicPrecicom, medFabriquand, medicPrisemidi, medicPrisenoctu, medicDossages, medicPrisesoir, medicPrisematin)  
 VALUES 
 (:medicConId, :medicNom,:medicPrecicom, :medFabriquand, :medicPrisemidi, :medicPrisenoctu, :medicDossages, :medicPrisesoir, :medicPrisematin)");
    $insert->execute(array(
        'medicConId' => $id,
        'medicNom' => $_POST['nom'],
        'medicPrecicom' => $_POST['remarque'],
        'medFabriquand' => $_POST['fabricant'],
        'medicPrisemidi' => $_POST['midi'],
        'medicPrisenoctu' => $_POST['nuit'],
        'medicDossages' => $_POST['dossage'],
        'medicPrisesoir' => $_POST['soir'],
        'medicPrisematin' => $_POST['matin'],

    ));
    header("location: medical.php?Id=" . $id.'&searchedParam='.$searchedParam);

}
?>
<nav>
    <ul>
        <?php
        echo '<li class="textGauche"><a href="../Adresse/detContacte.php?conId=' . $id.'&searchedParam='.$searchedParam.'"> '.$mrp->getText("Retour au contact").'</a></li>';
        if (($auth >= 3) OR ($auth == 1)) {
            if (isset($infoMedical['medId'])) {


                echo '<li class="textGauche"><a href="modif.php?Id=' . $id .'&searchedParam='.$searchedParam.'"> '.$mrp->getText("Edition des infos médicales").'</a></li>';
            } else {
                echo '<li class="textGauche"><a href="ajouter.php?Id=' . $id.'&searchedParam='.$searchedParam.'"> '.$mrp->getText("Edition des infos médicales").'</a></li>';
            }
            echo '<li class="textGauche"><a href="medication.php?Id=' . $id .'&searchedParam='.$searchedParam.'"> '.$mrp->getText("Edition de la médication").'</a></li>';
        }
        echo '<li class="textGauche"><a href="../social/social.php?Id=' . $id .'&searchedParam='.$searchedParam.'"> '.$mrp->getText("Infos sociales").'</a></li>';
        echo '<li class="textGauche"><a href="../social/print.php?Id=' . $id . '" target="_blank"> '.$mrp->getText("Fiche infos participant").'</a></li>';
        ?>
    </ul>
</nav>
<?php

echo "<h1>".$mrp->getText("Infos médicales")." ".$mrp->getText("pour") . ' ' . $contact['conPrenom'] . '  ' . $contact['conNom'] . "</h1>";
?>

<div id="information" style="margin-bottom: 20px;">
    <div id="contact" style=" float: left; border: solid 1px; width: 32%;">
        <h2><?php echo $mrp->getText("Contacts") ?></h2>
        <table class="noMargin">

            <tr>
                <td><?php echo $mrp->getText("Médecin") ?> 1</td>
                <td><input class="input3" disabled
                           value="<?php echo $medecin1['conNom'] . ' ' . $medecin1['conPrenom']; ?>"></td>
            </tr>
            <tr>
                <td><?php echo $mrp->getText("Médecin") ?> 2</td>
                <td><input class="input3" disabled
                           value="<?php echo $medecin2['conNom'] . ' ' . $medecin2['conPrenom']; ?>"></td>
            </tr>
            <tr>
                <td><?php echo $mrp->getText("Médecin") ?> 3</td>
                <td><input class="input3" disabled
                           value="<?php echo $medecin3['conNom'] . ' ' . $medecin3['conPrenom']; ?>"></td>
            </tr>
            <tr>
                <td><?php echo $mrp->getText("Caisse maladie") ?></td>
                <td><input class="input3" disabled
                           value="<?php echo $CaisseMaladie['conNom'] . ' ' . $CaisseMaladie['conPrenom']; ?>"></td>
            </tr>
            <tr>
                <td><?php echo $mrp->getText("N° d'assuré") ?></td>
                <td><input class="input3" disabled
                           value="<?php echo $infoMedical['medNoAssure']; ?>"></td>
            </tr>
            <tr>
                <td><?php echo $mrp->getText("Assurance accident") ?></td>
                <td><input class="input3" disabled
                           value="<?php echo $CaisseAccident['conNom'] . ' ' . $CaisseAccident['conPrenom']; ?>"></td>
            </tr>
            <tr>
                <td><?php echo $mrp->getText("N° d'assuré") ?></td>
                <td><input class="input3" disabled
                           value="<?php echo $infoMedical['medNoAssureAccident']; ?>"></td>
            </tr>
        </table>
    </div>
    <div id="Soin" style=" margin-left:34.6%; border: solid 1px; width: 65%; min-height: 225px">
        <h2><?php echo $mrp->getText("Soins") ?></h2>
        <table class="noMargin"> 
            <tr>
                <td colspan="0.3"><?php echo $mrp->getText("Sonde"); //$mrp->getText(); ?>
                <?php CheckBox($infoMedical['medSonde']); ?>
                <?php echo $mrp->getText("Protection"); //$mrp->getText(); ?>
                <?php CheckBox($infoMedical['medProtection']); ?>
                <?php echo $mrp->getText('Mise WC'); //$mrp->getText(); ?>
                <?php CheckBox($infoMedical['medMiseWc']); ?></td>
            </tr>
            <tr>
                <th><?php echo $mrp->getText("Aide à l'élimination") ?></th>
            </tr>
            <tr>
                <td class="zoneTexte1"><?php echo $infoMedical['medEncontinence']; ?></td>
            </tr>    
            <tr>
                <th><?php echo $mrp->getText("Habitudes pour la douche") ?></th>
            </tr>
            <tr>
                <td>
                    <div style="border: solid 1px"><?php echo $infoMedical['medToilette']; ?></div>
                </td>
            </tr>
            <tr>
                <th><?php echo $mrp->getText("Soins particuliers") ?></th>
            </tr>
            <tr>
                <td>
                    <div style="border: solid 1px"><?php echo $infoMedical['medEscarres']; ?></div>
                </td>
            </tr>
        </table>
    </div>
    <table style="margin: 0; padding-top: 10px;   width:101%;">
        <tr>
            <td style="width:26%; margin-left: -1mm; padding: 0">
                <div id="infoMedical"
                     style=" border: solid 1px;  margin-right: 2%; margin-top: 10px">
                    <h2><?php echo $mrp->getText("Infos médicales") ?></h2>
                    <table class="noMargin">
                        <tr>
                            <th><?php echo $mrp->getText("Types de handicap") ?></th>
                            <th><?php echo $mrp->getText(" Degré impotence") ?></th>
                        </tr>
                        <tr>
                            <td><input style="width: 100%;" disabled
                                       value="<?php echo $infoMedical['medTypeHandicap']; ?>"></td>
                            <td><input style="width: 80%;" disabled value="<?php echo $infoMedical['impNom']; ?>"></td>
                        </tr>
                        <tr>
                            <th colspan="2"><?php echo $mrp->getText("Handicaps associés") ?></th>
                        </tr>
                        <tr>
                            <td colspan="2"><input style="width: 100%;" disabled
                                                   value="<?php echo $infoMedical['medHandicapsAsso']; ?>"></td>
                        </tr>
                        <tr>
                            <th colspan="2"><?php echo $mrp->getText("Allergies") ?></th>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <div style="background-color: rgb(235,235,228); border: solid 1px;"><?php echo $infoMedical['medAllergies']; ?></div>
                            </td>
                        </tr>
                    </table>
                </div>
            </td>
            <td style="width: 50%; vertical-align: top;padding: 0; padding-top: 10px">
                <div id="boisson" style=" border: solid 1px;   min-height: 197px">
                    <h2><?php echo $mrp->getText("Boissons") ?></h2>
                    <table class="noMargin">
                        <tr>
                            <th colspan="2"><?php echo $mrp->getText("De quelle manière et conseils hydratation") ?></th>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <div style="border: solid 1px"> <?php echo $infoMedical['medBoisCondition']; ?> </div>
                            </td>
                        </tr>
                        <tr>
                            <th><?php echo $mrp->getText("Boissons préférées") ?></th>
                            <!--td><?php //echo $mrp->getText("Régime") ?></td-->
                        </tr>
                        <tr>
                            <td>
                                <div style="border: solid 1px"> <?php echo $infoMedical['medBoisPrefere']; ?> </div>
                            </td>
                            <!--td width="50%;">
                                <div style="border: solid 1px"> <?php //echo $infoMedical['medBoisRegime']; ?> </div>
                            </td-->
                        </tr>
                        <tr>
                            <th><?php echo $mrp->getText("Déconseillées") ?></th>
                        </tr>
                        <tr>
                            <td width="50%;">
                                <div style="border: solid 1px"> <?php echo $infoMedical['medBoisDecons']; ?> </div>
                            </td>
                        </tr>
                        <tr><th><?php echo $mrp->getText("Interdites") ?></th></tr>
                        <tr>
                            <td>
                                <div style="border: solid 1px"> <?php echo $infoMedical['medBoisInterdit']; ?> </div>
                            </td>
                        </tr>
                    </table>
                </div>
            </td>
        </tr>
    </table>
    <table style="margin: 0; padding-top: 10px;   width:101%;margin-top: -60px">
        <tr>
            <td style="width: 50%; vertical-align: top;padding: 0; ">
                <div id="allimentation" style="border: solid 1px; ">
                    <h2><?php  echo $mrp->getText("Alimentation") ?></h2>
                    <table class="noMargin">
                        <div id="consist">
                            <div id="consist-title"><?php  echo $mrp->getText("consistance du repas") ?> :</div>
                            <div><input type="text" value="<?php echo determineConsistence($infoMedical['medFoodConsistence']); ?>"/></div>
                            <?php //<td><?php ListeDeroulante4($infoMedical['medFoodConsistence'], 2); </td> ?>
                        </div>
                        <tr>
                            <th colspan="2"><?php  echo $mrp->getText("De quelles manières et conseils alimentation") ?></th>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <div style="border: solid 1px"> <?php echo $infoMedical['medAlimCommentair']; ?> </div>
                            </td>
                        </tr>
                        <tr>
                            <th><?php  echo $mrp->getText("Aliments préférés") ?></th>
                            <!--td><?php // echo $mrp->getText("Régime") ?></td-->
                        </tr>
                        <tr>
                            <td>
                                <div style="border: solid 1px"> <?php echo $infoMedical['medAlimPrefere']; ?> </div>
                            </td>
                            <!--td width="50%;">
                                <div style="border: solid 1px"> <?php //echo $infoMedical['medAlimRegime']; ?> </div>
                            </td-->
                        </tr>
                        <tr>
                            <th><?php  echo $mrp->getText("Déconseillé") ?></th>
                        </tr>
                        <tr>
                            <td>
                                <div style="border: solid 1px"> <?php echo $infoMedical['medAlimDecons']; ?> </div>
                            </td>  
                        </tr>
                        
                        <tr><th><?php  echo $mrp->getText("Interdit") ?></th></tr>
                        <tr><td>
                                <div style="border: solid 1px"> <?php echo $infoMedical['medAlimInterdit']; ?> </div>
                            </td></tr>
                    </table>
                </div>
            </td>
        </tr>
    </table>

    <div style="background-color: #99ccff">


    </div>
    <?php if (($auth >= 3) Or ($auth == 1)) { ?>

        <form method="post">
            <input type="submit" value="Edition" name="ModifMedical" class="valider">
        </form>

    <?php } ?>

</div>
<div id="medication" style="margin-top: 25px; margin-bottom: 100px; width: 90%; ">  
    <h1>Médication: <?php echo (isset($row['isMedicated']) && intval($row['isMedicated']) === 1) ? 'Oui' : 'Non'; ?></h1>
    <div style="text-align: center;">
        <?php if(intval($row['isMedicated'])){  ?>
        <div style="display: flex; gap: 10px; width:75%; justify-content: center; align-items: center; margin: auto">
            <div>
                <p>Matin</p> 
                <?php CheckBox($row['matin'])?>
            </div>
            <div>
                <p>Midi</p> 
                <?php CheckBox($row['midi'])?>
            </div>
            <div>
                <p>Soir</p> 
                <?php CheckBox($row['soir'])?>
            </div>
            <div>
                <p>Nuit</p> 
                <?php CheckBox($row['nuit'])?>
            </div>
        </div>     
        <hr style="margin-top: 10px; margin-bottom: 10px; width: 75%;">   
        <div>
            <p>Habitudes: </p>
            <textarea name="habitudes" style="width: 60%; height: 15vh;"><?php echo $row['habitudes']; ?></textarea>
        </div>
        <?php } ?>
    </div>
    <?php if (($auth >= 3) Or ($auth == 1)) { ?>
        <form method="post">
            <input type="submit" value="Edition" name="ModifMedication" class="valider">
        </form>
    <?php } ?>
</div>

<style>
    #medication-div{
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        align-items: center;
    }
    #consist{
        width: 50%;
        display: flex; 
        justify-content: space-between;
        align-items: center;
    }
    #consist > div{
        width: 50%;
    }
    #consist-title{
        background-color :#5083c1;
        color: white;
    }
    #consist input{
        width: 100%;
    }
</style>
<?php include('../footer.php'); ?>
