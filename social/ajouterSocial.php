<!--<meta http-equiv="refresh" content="3">-->

<?php include('../header.php');
/**
 * Created by PhpStorm.
 * User: Lionel
 * Date: 23/08/2017
 * Description de la page
 */
$id = $_GET['Id'];
$bdd = new PDO($dsn, $user, $password);

$bdd = new PDO($dsn, $user, $password);
$contact = $bdd->query("SELECT conNom, conPrenom FROM tblContact WHERE conId ='$id'");
$contact = $contact->fetch();
$parent = $bdd->query("SELECT conNom, conPrenom, conId, conParent FROM tblContact WHERE conParent = 1  ORDER BY conNom");
$mere = $bdd->query("SELECT conNom, conPrenom, conId, conParent FROM tblContact WHERE conParent = 1 ORDER BY conNom");
$tuteur = $bdd->query("SELECT conNom, conPrenom, conId, conParent FROM tblContact WHERE conParent = 1  ORDER BY conNom");
$institution = $bdd->query("SELECT conNom, conPrenom, conId, conParent FROM tblContact WHERE conInstitution = 1  ORDER BY conNom");
$urgence = $bdd->query("SELECT conNom, conPrenom, conId, conParent FROM tblContact WHERE conParent = 1  ORDER BY conNom");


// Création du dossier du bénéficiaire si il n'exite pas encore
$dossier = "../imgParticipant/$id";
if (!is_dir($dossier)) {
    mkdir($dossier);
}


?>
<?php
if (isset($_POST['Valider'])) {
    $insert = $bdd->prepare("INSERT INTO tblSocial
(socConId,socgilet,socAmis, socPere, socGereseul, socExpresssion, socMere, socInstitution, socFamille, socFauteuilElec, 
socFauteuilMan, socatache, socAvecconseil, socbarriere, socMarchauxi, socPassions, socPublication, socSomeil, 
socPiscineGilet, socMarcheseul, socPossitionJour, socMarchaide, socdrapspec, socHabillement, socUrgence, socTuteur,
 socPiscine, socFraterie, socDeplacement, socFrequenceJour,  socNegerepas, socAvecaidephys, socAimePas, 
 socReferentInstitut, socMaj)
 VALUES
(:socConId,:socgilet,:socAmis,:socPere,:socGereseul,:socExpresssion,:socMere,:socInstitution,
 :socFamille,:socFauteuilElec,:socFauteuilMan,:socatache,:socAvecconseil,:socbarriere,:socMarchauxi,:socPassions,
 :socPublication,:socSomeil,:socPiscineGilet,:socMarcheseul,:socPossitionJour,:socMarchaide,:socdrapspec,
 :socHabillement,:socUrgence,:socTuteur,:socPiscine,:socFraterie,:socDeplacement,:socFrequenceJour,:socNegerepas,
 :socAvecaidephys,:socAimePas,:socReferentInstitut,:Maj)");

    $insert->execute(array(
        'socConId'=>$id,
        'socgilet' => $_POST['giletNuit'],
        'socAmis' => $_POST['amis'],
        'socPere' => $_POST['pere'],
        'socGereseul' => $_POST['gereSeule'],
        'socExpresssion' => $_POST['communication'],
        'socMere' => $_POST['mere'],
        'socInstitution' => $_POST['institution'],
        'socFamille' => $_POST['contexFamille'],
        'socFauteuilElec' => $_POST['electrique'],
        'socFauteuilMan' => $_POST['manuel'],
        'socatache' => $_POST['attaches'],
        'socAvecconseil' => $_POST['avecConseil'],
        'socbarriere' => $_POST['barriere'],
        'socMarchauxi' => $_POST['moyenAuxilaire'],
        'socPassions' => $_POST['passion'],
        'socPublication' => $_POST['publication'],
        'socSomeil' => $_POST['commNuit'],
        'socPiscineGilet' => $_POST['giletFlottant'],
        'socMarcheseul' => $_POST['marcheSeule'],
        'socPossitionJour' => $_POST['changementPosition'],
        'socMarchaide' => $_POST['marcheAide'],
        'socdrapspec' => $_POST['drap'],
        'socHabillement' => $_POST['comHabillement'],
        'socUrgence' => $_POST['Urgence'],
        'socTuteur' => $_POST['tuteur'],
        'socPiscine' => $_POST['comPiscine'],
        'socFraterie' => $_POST['famille'],
        'socDeplacement' => $_POST['comInfoPratique'],
        'socFrequenceJour' => $_POST['frequence'],
        'socNegerepas' => $_POST['gerePas'],
        'socAvecaidephys' => $_POST['aidePhysique'],
        'socAimePas' => $_POST['aimePas'],
        'socReferentInstitut' => $_POST['referant'],
        'Maj'=> date("Y-m-d")
    ));

    header("location: social.php?Id=".$id);
}
?>


<?php echo "<h1> Info sociale pour" . ' ' . $contact['conNom'] . ' ' . $contact['conPrenom'] . "</h1>";
?>
<form method="post">
    <div id="photo" style="float: right;width: 150px; height: 250px; border: solid 1px">
        <?php $photo = "../imgParticipant/$id/portrait.jpg";

        if (file_exists($photo)) {
            ?>

            <img src="<?php echo $photo ?>" style="width: 150px;">
            <?php

        } else {
            echo '<a class="valider" href="ajoutPhoto.php?Id=' . $id . '&genre=portrait"> Ajouter une photo </a>';
        } ?>


    </div>
    <div id="jour"
         style="border-color: #0e84b5; border: solid 1px ; width: 46.5%; height: 250px; float: right; margin-right: 10px;">
        <h2>Confort jour</h2>
        <table  style="margin-bottom: 15px; padding-bottom: 0">
            <tr>
                <td colspan="2"> Changement de position</td>
                <td> Fréquence</td>
            </tr>
            <tr>
                <td colspan="2">
                    <div style="border: solid 1px"><textarea name="changementPosition" cols="30" rows="3"></textarea>
                    </div>
                </td>
                <td style="vertical-align:top">
                    <div style="border: solid 1px"><textarea name="frequence" cols="25" rows="3"></textarea>
                    </div>
                </td>
            </tr>
            <tr>
                <td style="width:33%"><?php $photo = "../imgParticipant/$id/pja.jpg";

                    if (file_exists($photo)) {
                        ?>

                        <img src="<?php echo $photo ?>" style="width: 150px;">
                        <?php

                    } else {
                        echo '<a class="valider" href="ajoutPhoto.php?Id=' . $id . '&genre=pja"> Ajouter une photo </a>';
                    } ?></td>
                <td style="width:33%"> Illustration possible</td>
                <td style="width:33%">
                    <?php $photo = "../imgParticipant/$id/pjb.jpg";

                    if (file_exists($photo)) {
                        ?>

                        <img src="<?php echo $photo ?>" style="width: 150px;">
                        <?php

                    } else {
                        echo '<a class="valider" href="ajoutPhoto.php?Id=' . $id . '&genre=pjb"> Ajouter une photo </a>';
                    } ?>
                </td>
            </tr>


        </table>

    </div>

    <div id="contact" style=" border: solid 1px; width: 33%; height: 250px; "><h2>Contact</h2>
        <table style="margin-bottom: 15px; padding-bottom: 0;">
            <tr>
                <td>Enfant de</td>
                <td><select name="pere"class="input200">

                        <?php echo ListeDeroulante2($parent, conId, conNom, conPrenom) ?>
                    </select></td>
            </tr>
            <tr>
                <td>et de ( si séparé)</td>
                <td><select name="mere"class="input200">

                        <?php echo ListeDeroulante2($mere, conId, conNom, conPrenom) ?>
                    </select>
                </td>
            </tr>
            <tr>
                <td>Sous tutelle de</td>
                <td><select name="tuteur"class="input200">

                        <?php echo ListeDeroulante2($tuteur, conId, conNom, conPrenom) ?>
                    </select></td>
            </tr>
            <tr>
                <td>Dans l'institutuion</td>
                <td><select name="institution"class="input200">

                        <?php echo ListeDeroulante2($institution, conId, conNom, conPrenom) ?>
                    </select></td>
            </tr>
            <tr>
                <td style="color: #9a0000;">en cas d'urgence</td>
                <td><select name="Urgence"class="input200">

                        <?php echo ListeDeroulante2($urgence, conId, conNom, conPrenom) ?>
                    </select></td>
            </tr>
            <tr>
                <td>Référent dans l'intitution</td>
                <td></td>
            </tr>
            <tr>
                <td colspan="2"><input style="width:300px; " name="referant"
                                       value=""></td>
            </tr>
        </table>
    </div>
    <div style="width: 63.5%; border: solid 1px; margin-top: 10px; margin-bottom: 100px; float: right; height: 287px;">
        <h2>Confort nuit</h2>
        <table class="noMargin">
            <tr>
                <td colspan="2">Pour la nuit</td>
                <td>Drap spécial</td>
                <td><input class="input0" type="checkbox" name="drap" value="1"></td>
                <td>Gilet</td>
                <td><input class="input0" type="checkbox" name="giletNuit" value="1"></td>
                <td>Attaches</td>
                <td><input class="input0" type="checkbox" name="attaches" value="1"></td>
                <td>Barrière</td>
                <td><input class="input0" type="checkbox" name="barriere" value="1"></td>
            </tr>
            <tr>
                <td>Consigne remarques pour le someil</td>
                <td colspan="9">
                    <div>
                        <div style="border: solid 1px; padding: 5px; margin-bottom: 15px;"><textarea name="commNuit" cols="70" rows="3"></textarea>
                        </div>
                    </div>
                </td>
            </tr>
            <tr>
                <td colspan="1"><?php $photo = "../imgParticipant/$id/pna.jpg";

                    if (file_exists($photo)) {
                        ?>

                        <img src="<?php echo $photo ?>" style="width: 150px;">
                        <?php

                    } else {
                        echo '<a class="valider" href="ajoutPhoto.php?Id=' . $id . '&genre=pna"> Ajouter une photo </a>';
                    } ?></td>
                <td colspan="6" style="text-align: center;"> Illustration positions</td>
                <td colspan="3"><?php $photo = "../imgParticipant/$id/pnb.jpg";

                    if (file_exists($photo)) {
                        ?>

                        <img src="<?php echo $photo ?>" style="width: 150px;">
                        <?php

                    } else {
                        echo '<a class="valider" href="ajoutPhoto.php?Id=' . $id . '&genre=pnb"> Ajouter une photo </a>';
                    } ?></td>
            </tr>
        </table>
    </div>
    <div style="width: 33%; border: solid 1px; margin-top: 10px; margin-bottom: 100px;">
        <h2>Info pratiques</h2>
        <table class="noMargin">
            <tr>
                <th colspan="4">Argent de poche</th>
            </tr>
            <tr>
                <td>Gère seule</td>
                <td><input class="input0" type="checkbox" name="gereSeule" value="1"></td>
                <td>Ne gère pas</td>
                <td><input class="input0" type="checkbox" name="gerePas" value="1"></td>
            </tr>
            <tr>
                <td>avec aide physique</td>
                <td><input class="input0" type="checkbox" name="aidePhysique" value="1"></td>
                <td>avec conseil</td>
                <td><input class="input0" type="checkbox" name="avecConseil" value="1"></td>
            </tr>
        </table>
        <table class="noMargin">
            <tr>
                <th colspan="4">Mobilité</th>
            </tr>
            <tr>
                <td>Marche seule</td>
                <td><input class="input0" type="checkbox" name="marcheSeule" value="1"></td>
                <td colspan="2"> Fauteuil roulant</td>
            </tr>
            <tr>
                <td>avec aide</td>
                <td><input class="input0" type="checkbox" name="marcheAide" value="1"></td>
                <td>Manuel</td>
                <td><input class="input0" type="checkbox" name="manuel" value="1"></td>
            </tr>
            <tr>
                <td>avec moyen auxillaires</td>
                <td><input class="input0" type="checkbox" name="moyenAuxilaire" value="1"></td>
                <td>Electrique</td>
                <td><input class="input0" type="checkbox" name="electrique" value="1"></td>
            </tr>
            <tr>
                <td colspan="4"> Commentaire</td>
            </tr>
            <tr>
                <td colspan="4">
                    <div class="zoneTexte1"><textarea name="comInfoPratique" cols="48" rows="5"></textarea></div>
                </td>
            </tr>
        </table>
        <table class="noMargin">
            <tr>
                <th colspan="4">Habillement</th>
            </tr>
            <tr>
                <td colspan="4">
                    <div class="zoneTexte1"><textarea name="comHabillement" cols="48" rows="5"></textarea></div>
                </td>
            </tr>
        </table>
        <table class="noMargin" style="width: 100%;">
            <tr>
                <th colspan="2">Piscine</th>
                <th>Gilet flottant</th>
                <td><input class="input0" type="checkbox" name="giletFlottant" value="1"></td>
            </tr>
            <tr>
                <td colspan="4">
                    <div class="zoneTexte1"><textarea name="comPiscine" cols="48" rows="5"></textarea></div>
                </td>
            </tr>
            <tr>
                <td colspan="3">
                    Est d'accord d'être publié
                </td>
                <td><input class="input0" type="checkbox" name="publication" value="1"></td>
            </tr>
        </table>
    </div>
    <div style="border: solid 1px; padding: 5px; width: 63.5%; float: right; margin-top: -467px;">
        <h2>Tissus social</h2>
        <table class="noMargin">
            <tr>
                <th style="width: 33%;">Communication, expression</th>
                <th style="width: 33%;">Passion, loisirs</th>
                <th style="width: 33%;">peu appréciés, phobies</th>
            </tr>
            <tr>
                <td style="vertical-align: top;">
                    <div class="zoneTexte1"><textarea name="communication" cols="27" rows="5"></textarea></div>
                </td>
                <td style="vertical-align: top;">
                    <div class="zoneTexte1"><textarea name="passion" cols="27" rows="5"></textarea></div>
                </td>
                <td style="vertical-align: top;">
                    <div class="zoneTexte1"><textarea name="aimePas" cols="27" rows="5"></textarea></div>
                </td>
            </tr>
        </table>
        <table class="noMargin">
            <tr>
                <th style="width: 33%;">Fraterie</th>
                <th style="width: 33%;">Contexte familial</th>
                <th style="width: 33%;">Amis</th>
            </tr>
            <tr>
                <td style="vertical-align: top;">
                    <div class="zoneTexte1"><textarea name="famille" cols="27" rows="5"></textarea></div>
                </td>
                <td style="vertical-align: top;">
                    <div class="zoneTexte1"><textarea name="contexFamille" cols="27" rows="5"></textarea></div>
                </td>
                <td style="vertical-align: top;">
                    <div class="zoneTexte1"><textarea name="amis" cols="27" rows="5"></textarea></div>
                </td>
            </tr>
        </table>
    </div>
    <div id="btn" style="float: right;margin-top: -140px;margin-right: 160px;">
        <input type="submit" name="Valider" value="Valider" class="valider">
        <input type="submit" name="annuler" value="Annuler" class="Annuler">
    </div>
</form>

<?php include('../footer.php'); ?>
