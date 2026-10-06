
<?php include('../header.php');
/**
 * Created by PhpStorm.
 * User: Lionel
 * Date: 23/08/2017
 * Description de la page
 */

// ini_set('display_errors', 1);
// error_reporting(E_ALL);
$id = $_GET['Id'];
$searchedParam = $_GET['searchedParam'];
$bdd = new PDO($dsn, $user, $password);
$bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$contact = $bdd->query("SELECT conNom, conPrenom FROM tblContact WHERE conId ='$id'");
$contact = $contact->fetch();
$lstParent = $bdd->query("SELECT conNom, conPrenom, conId, conParent FROM tblContact WHERE conParent = 1 ORDER BY conNom");
$lstMere = $bdd->query("SELECT conNom, conPrenom, conId, conParent FROM tblContact WHERE conParent = 1  ORDER BY conNom");
$lstTuteur = $bdd->query("SELECT conNom, conPrenom, conId, conParent FROM tblContact WHERE conParent = 1 ORDER BY conNom");
$lstInstitution = $bdd->query("SELECT conNom, conPrenom, conId, conParent FROM tblContact WHERE conInstitution = 1  ORDER BY conNom");
$lstUrgence = $bdd->query("SELECT conNom, conPrenom, conId, conParent FROM tblContact WHERE conParent = 1  ORDER BY conNom");


$infoSocial = $bdd->query("SELECT * FROM tblSocial WHERE socConId ='$id'");
$infoSocial = $infoSocial->fetch();

$socId = $infoSocial['socId'];

?>
<?php

if (isset($_POST['Annuler']))
{
    header("location: social.php?Id=".$id.'&searchedParam='.$searchedParam);

}
if (isset($_POST['Valider'])) {

    try{

    $insert = $bdd->prepare("UPDATE tblSocial SET
        socgilet = :socgilet,
        socAmis = :socAmis,
        socPere = :socPere,
        socGereseul =:socGereseul ,
        socExpresssion =:socExpresssion ,
        socMere = :socMere,
        socInstitution =:socInstitution ,
        socFamille = :socFamille,
        socFauteuilElec = :socFauteuilElec,               
        socFauteuilMan = :socFauteuilMan,
        socatache = :socatache,
        socAvecconseil = :socAvecconseil,
        socbarriere =  :socbarriere,
        socMarchauxi = :socMarchauxi,
        socPassions = :socPassions,
        socPublication = :socPublication,
        socSomeil = :socSomeil, 
        socPiscineGilet = :socPiscineGilet,
        socMarcheseul =  :socMarcheseul,
        socPossitionJour = :socPossitionJour,
        socMarchaide = :socMarchaide,
        socdrapspec = :socdrapspec,
        socHabillement = :socHabillement,
        socUrgence = :socUrgence,
        socTuteur = :socTuteur,     
        socPiscine = :socPiscine,
        socFraterie = :socFraterie,
        socDeplacement = :socDeplacement,
        socFrequenceJour = :socFrequenceJour,
        socNegerepas = :socNegerepas,
        socAvecaidephys = :socAvecaidephys,
        socAimePas = :socAimePas,        
        socReferentInstitut = :socReferentInstitut,
        socMaj =:Maj,
        socSiege = :socSiege,
        socExpressionVerbal = :socExpressionVerbal,
        socComportement = :socComportement
         WHERE socId = :socId");


    $insert->execute(array(

        'socgilet' => (!isset($_POST['gilet']) || $_POST['gilet'] === "") ? null : intval($_POST['gilet']),
        'socAmis' => $_POST['amis'],
        'socPere' => $_POST['pere'],
        'socGereseul' => $_POST['gereSeule'],
        'socExpresssion' => $_POST['communication'],
        'socMere' => $_POST['mere'],
        'socInstitution' => $_POST['institution'],
        'socFamille' => $_POST['contexFamille'],
        'socFauteuilElec' => $_POST['electrique'],
        'socFauteuilMan' => $_POST['manuel'],
        'socatache' => (!isset($_POST['attache']) || $_POST['attache'] === "") ? null : $_POST['attache'] ?? null,
        'socAvecconseil' => $_POST['gereConseil'],
        'socbarriere' => (!isset($_POST['barriere']) || $_POST['barriere'] === "") ? null : $_POST['barriere'],
        'socMarchauxi' => $_POST['marcheAuxilaire'],
        'socPassions' => $_POST['passion'],
        'socPublication' => (!isset($_POST['publication']) || $_POST['publication'] === "") ? null : $_POST['publication'],
        'socSomeil' => $_POST['commNuit'],
        'socPiscineGilet' => (!isset($_POST['giletFlotant']) || $_POST['giletFlotant'] === "") ? null : $_POST['giletFlotant'],
        'socMarcheseul' => $_POST['marchSeul'],
        'socPossitionJour' => $_POST['changementPosition'],
        'socMarchaide' => $_POST['marcheAide'],
        'socdrapspec' => (!isset($_POST['drapSpecial']) || $_POST['drapSpecial'] === "") ? null : $_POST['drapSpecial'] ?? null, //$_POST['drapSpecial'],
        'socHabillement' => $_POST['comHabillement'],
        'socUrgence' => $_POST['Urgence'],
        'socTuteur' => $_POST['tuteur'],
        'socPiscine' => $_POST['comPiscine'],
        'socFraterie' => $_POST['famille'],
        'socDeplacement' => $_POST['comInfoPratique'],
        'socFrequenceJour' => $_POST['frequence'],
        'socNegerepas' => $_POST['gerePas'],
        'socAvecaidephys' => $_POST['gereAidePhysique'],
        'socAimePas' => $_POST['aimePas'],
        'socReferentInstitut' => $_POST['referant'],
        'Maj'=> date("Y-m-d"),
        'socSiege' => intval($_POST['socSiege']) ?? 0,
        'socComportement'=> $_POST['socComportement'] ?? null,
        'socExpressionVerbal' => (!isset($_POST['socExpressionVerbal']) || $_POST['socExpressionVerbal'] === "") ? null : $_POST['socExpressionVerbal'] ,
        'socId'=> $socId
    ));
    }catch(Exception $err){       
        echo "<pre>Erreur PDO : " . $err->getMessage() . "</pre>";
    }
   header("location: social.php?Id=".$id.'&searchedParam='.$searchedParam);
}
?>


<?php echo " <h1>".$mrp->getText("Info sociale pour"). ' ' . $contact['conNom'] . ' ' . $contact['conPrenom'] . " </h1 > ";
?>
<form method="post">
    <div id="photo" style="float: right;width: 150px; height: 255px; border: solid 1px; display: flex; flex-direction: column; justify-content: center; align-items: center;">
        <?php
        $photo = " ../imgParticipant/$id/portrait.jpg";
        $portrait = "../imgParticipant/$id/portrait.jpg";

        if (file_exists($portrait)) { /*?>
            <img src="../imgParticipant/<?php echo $id; ?>/portrait.jpg" style="height: 250px; width: auto;"> <?php */
            echo '<a style="text-align: center; vertical-align: middle;" class="valider" href="ajoutPhoto.php?Id=' . $id . '&genre=portrait&src=Modif&searchedParam='.$searchedParam.'"> Modifier la  photo </a>';
        }
        else
        {
            echo '<a class="valider" href="ajoutPhoto.php?Id=' . $id . '&genre=portrait&src=Modif&searchedParam='.$searchedParam.'"> Ajouter une photo </a>';
        } ?>
    </div>
    <div id="jour"
         style="border-color: #0e84b5; border: solid 1px ; width: 46.5%; height: 255px; float: right; margin-right: 10px;">
        <h2><?php echo $mrp->getText("Confort jour") ?></h2>
        <table style="margin-bottom: 15px; padding-bottom: 0;">
            <tr>
                <td colspan="2"><?php echo $mrp->getText("habitudes pour la sieste position") ?></td>
                <td><?php echo $mrp->getText("Fréquence") ?></td>
            </tr>
            <tr>
                <td colspan="2">
                    <div style="border: solid 1px;"><textarea name="changementPosition" cols="30" rows="3"><?php echo $infoSocial['socPossitionJour']; ?></textarea>
                    </div>
                </td>
                <td style="vertical-align:top;">
                    <div style="border: solid 1px;"><textarea name="frequence" cols="25" rows="3"><?php echo $infoSocial['socFrequenceJour']; ?></textarea>
                    </div>
                </td>
            </tr>
            <tr>
                <td style="width:33%;"><?php echo $mrp->getText("Illustration possible") ?></td>
                <td style="width:33%; border: solid 1px;"><?php $photo = "../imgParticipant/$id/pja.jpg";

                    if (file_exists($photo)) {
                        ?>

                        <img src="<?php echo $photo ?>" style="width: 150px;">
                        <?php
                        echo '<a class="valider" href="ajoutPhoto.php?Id=' . $id . '&genre=pja&src=Modif&searchedParam='.$searchedParam.'">'.$mrp->getText("Modifier la photo").'</a>';

                    } else {
                        echo '<a class="valider" href="ajoutPhoto.php?Id=' . $id . '&genre=pja&src=Modif&searchedParam='.$searchedParam.'">'.$mrp->getText("Ajouter une photo ").'</a>';
                    } ?></td>


            </tr>


        </table>

    </div>

    <div id="contact" style=" border: solid 1px; width: 33%; height: 255px; "><h2>Contact</h2>
        <table style="margin-bottom: 15px; padding-bottom: 0;">
            <tr>
                <td><?php echo $mrp->getText("Enfant de") ?></td>
                <td><select name="pere" class="input200">
                        <?php ListeModif2($lstParent, $infoSocial['socPere'], 'conId', 'conNom', 'conPrenom'); ?>
                    </select></td>
            </tr>
            <tr>
                <td><?php echo $mrp->getText("et de ( si séparé)") ?></td>
                <td><select name="mere"class="input200">
                        <?php ListeModif2($lstMere, $infoSocial['socMere'], 'conId', 'conNom', 'conPrenom'); ?>
                    </select>
                </td>
            </tr>
            <tr>
                <td><?php echo $mrp->getText("Sous tutelle de") ?></td>
                <td><select name="tuteur"class="input200">
                        <?php ListeModif2($lstTuteur, $infoSocial['socTuteur'], 'conId', 'conNom', 'conPrenom'); ?>
                    </select></td>
            </tr>
            <tr>
                <td><?php echo $mrp->getText("Dans l'institutuion") ?></td>
                <td><select name="institution"class="input200">
                        <?php ListeModif2($lstInstitution, $infoSocial['socInstitution'], 'conId', 'conNom', 'conPrenom'); ?>
                    </select></td>
            </tr>
            <tr>
                <td style="color: #9a0000;"><?php echo $mrp->getText("en cas d'urgence") ?></td>
                <td><select name="Urgence"class="input200">
                        <?php ListeModif2($lstUrgence, $infoSocial['socUrgence'], 'conId', 'conNom', 'conPrenom'); ?>
                    </select></td>
            </tr>
            <tr>
                <td><?php echo $mrp->getText("Référent dans l'institution") ?></td>
                <td></td>
            </tr>
            <tr>
                <td colspan="2"><input style="width:325px; " name="referant"
                                       value="<?php echo $infoSocial['socReferentInstitut']; ?>"></td>
            </tr>
        </table>
    </div>
    <div style="width: 63.5%; border: solid 1px; margin-top: 10px; margin-bottom: 100px; float: right; height: 287px;">
        <h2><?php echo $mrp->getText("Confort nuit") ?></h2>
        <table class="noMargin">
            <tr>
                <td colspan="2">Pour la nuit</td>
                <td><?php echo $mrp->getText("Drap spécial") ?></td>
                <td>
                    <select id="drapSpecial" class="listeDeroulante4Zone" name='drapSpecial'>
                         <?php ListeDeroulante4($infoSocial['socbarriere'], 0, null); ?>
                    </select>
                </td>
                <td><?php echo $mrp->getText("Gilet") ?></td>
                <td>
                    <select id="gilet" class="listeDeroulante4Zone" name='gilet'>
                         <?php ListeDeroulante4($infoSocial['socgilet'], 0, null); ?>
                    </select>
                </td>
                <td><?php echo $mrp->getText("Ataches") ?></td>
                <td>
                    <select id="attache" class="listeDeroulante4Zone" name='attache'>
                         <?php ListeDeroulante4($infoSocial['socatache'], 0, null); ?>
                    </select>
                </td>
                <td><?php echo $mrp->getText("Barrière") ?></td>
                <td>
                    <select id="barriere" class="listeDeroulante4Zone" name='barriere'>
                         <?php ListeDeroulante4($infoSocial['socbarriere'],0, null); ?>
                    </select>
                </td>
            </tr>
                <?php /*<td><?php echo $mrp->getText("Drap spécial") ?></td>
                <td><?php CheckBoxModif($infoSocial['socdrapspec'], 'drapSpecial') ?></td>
                <td><?php echo $mrp->getText("Gilet") ?></td>
                <td><?php CheckBoxModif($infoSocial['socgilet'], 'gilet') ?></td>
                <td><?php echo $mrp->getText("Ataches") ?></td>
                <td><?php CheckBoxModif($infoSocial['socatache'], 'attache') ?></td>
                <td><?php echo $mrp->getText("Barrière") ?></td>
                <td><?php CheckBoxModif($infoSocial['socbarriere'], 'barriere') ?></td>
                */ ?>
            </tr>
            <tr>
                <td><?php echo $mrp->getText("habitude - rituel du couché") ?></td>
                <td colspan="9">
                    <div>
                        <div style="border: solid 1px; padding: 5px; margin-bottom: 15px;"><textarea name="commNuit" cols="78" rows="3"><?php echo $infoSocial['socSomeil']; ?></textarea>
                        </div>
                    </div>
                </td>
            </tr>
            <tr>
                <td colspan="3" style="text-align: left;"><?php echo $mrp->getText("Illustration positions") ?></td>
                <td style="border: solid 1px" ><?php $photo = "../imgParticipant/$id/pna.jpg";

                    if (file_exists($photo)) {
                        ?>

                        <img src="<?php echo $photo ?>" style="width: 150px;">
                        <?php

                    } else {
                        echo '<a class="valider" href="ajoutPhoto.php?Id=' . $id . '&genre=pna&src=Modif">'.$mrp->getText("Ajouter une photo").' </a>';
                    } ?></td>


            </tr>
        </table>
    </div>
    <div style="width: 33%; border: solid 1px; margin-top: 10px; margin-bottom: 100px;">
        <h2><?php echo $mrp->getText("Info pratiques") ?></h2>
        <table class="noMargin">
            <tr>
                <th colspan="4"><?php echo $mrp->getText("Argent de poche") ?></th>
            </tr>
            <tr>
                <td><?php echo $mrp->getText("Gère seule") ?></td>
                <td><?php CheckBoxModif($infoSocial['socGereseul'], 'gereSeule') ?></td>
                <td><?php echo $mrp->getText("Ne gère pas") ?></td>
                <td><?php CheckBoxModif($infoSocial['socNegerepas'], 'gerePas') ?></td>
            </tr>
            <tr>
                <td><?php echo $mrp->getText("avec aide physique") ?></td>
                <td><?php CheckBoxModif($infoSocial['socAvecaidephys'], 'gereAidePhysique') ?></td>
                <td><?php echo $mrp->getText("avec conseil") ?></td>
                <td><?php CheckBoxModif($infoSocial['socAvecconseil'], 'gereConseil') ?></td>
            </tr>
        </table>
        <table class="noMargin">
            <tr>
                <th colspan="4">Mobilité</th>
            </tr>
            <tr>
                <td><?php echo $mrp->getText("Marche seule") ?></td>
                <td><?php CheckBoxModif($infoSocial['socMarcheseul'], 'marchSeul') ?></td>
                <td colspan="2"><?php echo $mrp->getText("Fauteuil roulant") ?></td>
            </tr>
            <tr>
                <td><?php echo $mrp->getText("avec aide") ?></td>
                <td><?php CheckBoxModif($infoSocial['socMarchaide'], 'marcheAide') ?></td>
                <td><?php echo $mrp->getText("Manuel") ?></td>
                <td><?php CheckBoxModif($infoSocial['socFauteuilMan'], 'manuel') ?></td>
            </tr>
            <tr>
                <td><?php echo $mrp->getText("avec moyen auxillaires") ?></td>
                <td><?php CheckBoxModif($infoSocial['socMarchauxi'], 'marcheAuxilaire') ?></td>
                <td><?php echo $mrp->getText("Electrique") ?></td>
                <td><?php CheckBoxModif($infoSocial['socFauteuilElec'], 'electrique') ?></td>
            </tr>     
            <tr>
                <td><? echo $mrp->getText("Siège bus") ?></td>
                <td><? CheckBoxModif(intval($infoSocial['socSiege']), 'socSiege') ?></td>
            </tr>
            <tr>
                <th colspan="4"><?php echo $mrp->getText("technique transferts et déplacements") ?></th>
            </tr>
            <tr>
                <td colspan="4">
                    <div class="zoneTexte1"><textarea name="comInfoPratique" cols="48"
                                                      rows="5"><?php echo $infoSocial['socDeplacement']; ?></textarea>
                    </div>
                </td>
            </tr>
        </table>
        <table class="noMargin">
            <tr>
                <th colspan="4"><?php echo $mrp->getText("Habillement") ?></th>
            </tr>
            <tr>
                <td colspan="4">
                    <div class="zoneTexte1"><textarea name="comHabillement" cols="48"
                                                      rows="5"><?php echo $infoSocial['socHabillement']; ?></textarea>
                    </div>
                </td>
            </tr>
        </table>
        <table class="noMargin" style="width: 100%;">
            <tr>
                <th colspan="2"><?php echo $mrp->getText("Piscine") ?></th>
                <th><?php echo $mrp->getText("Gilet flottant") ?></th>
                <th>
                    <select name="giletFlotant" id="giletFlotant">
                        <?php ListeDeroulante4($infoSocial['socPiscineGilet'], 0); //<th><?php CheckBoxModif($infoSocial['socPiscineGilet'], 'giletFlotant'); ?>
                    </select>
                </th>
            </tr>
            <tr>
                <td colspan="4">
                    <div class="zoneTexte1"><textarea name="comPiscine" cols="48"
                                                      rows="5"><?php echo $infoSocial['socPiscine']; ?></textarea></div>
                </td>
            </tr>
            <tr>
                <td colspan="3">
                    <?php echo $mrp->getText("Est d'accord d'être publié") ?>
                </td>
                <td>
                    <select name='publication'>
                        <?php ListeDeroulante4($infoSocial['socPublication'], 0); ?>
                    </select>
                </td>
            </tr>
        </table>
    </div>
    <div style="border: solid 1px; padding: 5px; width: 63.5%; float: right; margin-top: -480px;">
        <h2><?php echo $mrp->getText("Tissus social") ?></h2>
        <table class="noMargin">
            <div>       
                <!----------------------- De quelle manière communiquer----------------------------------->
                <div class="sub-title-blue"><?php echo $mrp->getText("De quelle manière communiquer") ?></div>
                <div>
                    <?php echo $mrp->getText("Expression verbale : "); ?>
                    <select id="socExpressionVerbal" class="listeDeroulante4Zone" name='socExpressionVerbal'>
                        <?php ListeDeroulante4($infoSocial['socExpressionVerbal'], 1); ?>
                    </select> 
                </div>
                <div>
                    <textarea name="communication" class="updatedTextArea">
                        <?php echo $infoSocial['socExpresssion']; ?>
                    </textarea>
                </div>
                <!----------------------- peu appréciés, phobies ----------------------------------->
                <div class="sub-title-blue"><?php echo $mrp->getText("Activités peu appréciées, phobies"); ?></div>
                <div><textarea name="aimePas" class="updatedTextArea"><?php echo $infoSocial['socAimePas']; ?></textarea></div>
                <!----------------------- Passion, loisirs ----------------------------------->
                <div class="sub-title-blue"><?php echo $mrp->getText("Passion, loisirs") ?></div>
                <div>
                    <textarea name="passion" class="updatedTextArea"><?php echo $infoSocial['socPassions']; ?></textarea>
                </div>
                
            </div>
        </table>
        <table class="noMargin">
            <!----------------------- Fraterie ----------------------------------->
            <div class="sub-title-blue"><?php echo $mrp->getText("Fraterie") ?></div>
            <div>
                <textarea name="famille" class="updatedTextArea"><?php echo $infoSocial['socFraterie']; ?></textarea>
            </div>
            <!----------------------- Contexte familial ----------------------------------->
            <div class="sub-title-blue"><?php echo $mrp->getText("Contexte familial") ?></div>
             <div>
                <textarea name="contexFamille" class="updatedTextArea"><?php echo $infoSocial['socFamille']; ?></textarea>
            </div>
            <!----------------------- amis -----------------------------------> 
            <div class="sub-title-blue"><?php echo $mrp->getText("Amis") ?></div>
             <div>
                <textarea name="amis" class="updatedTextArea"><?php echo $infoSocial['socAmis']; ?></textarea>
            </div>
            <!----------------------- Comportement et attitude ----------------------------->
            <div class="sub-title-blue"><?php echo $mrp->getText("Comportement et attitude") ?></div>
             <div>
                <textarea name="socComportement" class="updatedTextArea"><?php echo $infoSocial['socComportement']; ?></textarea>
            </div>
        </table>
    </div>
    <div id="btn-confirmation-modifSocial">
        <input type="submit" name="Valider" value="Valider" class="valider">
        <input type="submit" name="Annuler" value="Annuler" class="Annuler">
    </div>
</form>

<style>
    .sub-title-blue{
        color: white;
        background-color: #77aaff;
    }
    .sub-text{
        border: 1px solid black;
        margin: 5px;
        padding: 15px;
    }
    .listeDeroulante4Zone{
        text-align: center;
        color: black;
        font-weight: bold;
    }
    .updatedTextArea{
        width: 100%;
        padding-top: 20px;
        padding-left: 7px;
        padding-right: 7px;
        padding-bottom: 20px;
    }
</style>

<?php include('../footer.php'); ?>
