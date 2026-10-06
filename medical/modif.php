<?php include('../header.php');
/**
 * Created by PhpStorm.
 * User: Lionel
 * Date: 24/08/2017
 * Description de la page
 */
 ini_set('display_errors', 1);
 error_reporting(E_ALL);
$id = $_GET['Id'];
$searchedParam = $_GET['searchedParam'];
$bdd = new PDO($dsn, $user, $password);
$contact = $bdd->query("SELECT conNom, conPrenom FROM tblContact WHERE conId ='$id'");
$contact = $contact->fetch();
$infoMedical = $bdd->query("SELECT * from tblMedical WHERE medConId = '$id'");
$infoMedical = $infoMedical->fetch();


$medId = $infoMedical['medId'];

$lstOfasType = $bdd->query("SELECT * FROM tblHandicape");
$lstOfasReconnu = $bdd->query("SELECT * FROM tblArt");
$lstMedecin1 = $bdd->query("SELECT conNom, conPrenom, conId FROM tblContact WHERE conMedecin = 1 ORDER BY conNom ASC");
$lstMedecin2 = $bdd->query("SELECT conNom, conPrenom, conId FROM tblContact WHERE conMedecin = 1 ORDER BY conNom ASC");
$lstMedecin3 = $bdd->query("SELECT conNom, conPrenom, conId FROM tblContact WHERE conMedecin = 1 ORDER BY conNom ASC");
$lstCaMaladie = $bdd->query("SELECT conNom, conPrenom, conId FROM tblContact WHERE conAssurance = 1 ORDER BY conNom ASC");
$lstCaAccident = $bdd->query("SELECT conNom, conPrenom, conId FROM tblContact WHERE conAssurance = 1 ORDER BY conNom ASC");
$lstImpotence = $bdd->query("SELECT * FROM tblDegreImpot");
$lstOfasBesoin = $bdd->query("SELECT * FROM tblOfasBesoin ORDER BY OfasNom");
$lstCanton = $bdd->query("SELECT * FROM tblCanton");
if (isset($_POST['Annuler'])){
    header("location: medical.php?Id=" . $id.'&searchedParam='.$searchedParam);
}

if (isset($_POST['valider'])) {
    try{
    $insert = $bdd->prepare("UPDATE tblMedical SET
    medMedecin1 = :medMedecin1,
    medMedecin2 = :medMedecin2,
    medMedecin3 = :medMedecin3,
    medNoAssure = :medNoAssure,
    medCaisseMaladie = :medCaisseMaladie,
    medAccident = :medAccident,
    medTypeHandicap = :medTypeHandicap,
    medAllergies = :medAllergies,
    medDegre = :medDegre,
    medHandicapsAsso = :medHandicapsAsso,
    medOfasType = :medOfasType,
    medOfasPluri = :medOfasPluri,
    medOfasReconnu = :medOfasReconnu,
    medBoisInterdit = :medBoisInterdit,
    medBoisDecons = :medBoisDecons,
    /*medBoisRegime = :medBoisRegime,*/
    medBoisCondition = :medBoisCondition,
    medBoisPrefere = :medBoisPrefere,
    medAlimPrefere = :medAlimPrefere,
    medAlimDecons = :medAlimDecons,
    medAlimInterdit = :medAlimInterdit,
    /*medAlimRegime = :medAlimRegime,*/
    medAlimCommentair= :medAlimCommentair,
    medToilette = :medToilette,
    medEscarres = :medEscarres,
    medEncontinence = :medEncontinence,
    medMaj = :Maj,
    ofasBesoin =:Besoin,
    ofasNouveau=:Nouveau,
    ofasCanton=:CantonOfas,
    ofasProche=:statuOfas,
    medNoAssureAccident=:NoAssurAccident, 
    medSonde= :medSonde,
    medProtection= :medProtection,
    medMiseWc = :medMiseWc,
    medFoodConsistence = :medFoodConsistence
WHERE medId = :medId");

    $insert->execute(array(
        'medId' => intval($medId),
        'medMedecin1' => $_POST['medecin1'],
        'medMedecin2' => $_POST['medecin2'],
        'medMedecin3' => $_POST['medecin3'],
        'medNoAssure' => $_POST['noAssure'],
        'medCaisseMaladie' => $_POST['caMaladie'],
        'medAccident' => $_POST['caAccident'],
        'medTypeHandicap' => $_POST['medTypeHandicap'],
        'medAllergies' => $_POST['medAllergies'],
        'medDegre' => $_POST['medDegre'],
        'medHandicapsAsso' => $_POST['medHandicapsAsso'],
        'medOfasType' => $_POST['medOfasType'],
        'medOfasPluri' => $_POST['pluri'],
        'medOfasReconnu' => $_POST['medOfasReconnu'],
        'medBoisInterdit' => $_POST['medBoisInterdit'],
        'medBoisDecons' => $_POST['medBoisDecons'],
        /*'medBoisRegime' => $_POST['medBoisRegime'],*/
        'medBoisCondition' => $_POST['medBoisCondition'],
        'medBoisPrefere' => $_POST['medBoisPrefere'],
        'medAlimPrefere' => $_POST['medAlimPrefere'],
        'medAlimDecons' => $_POST['medAlimDecons'],
       'medAlimInterdit' => $_POST['medAlimInterdit'],
        /*'medAlimRegime' => $_POST['medAlimRegime'],*/
        'medAlimCommentair' => $_POST['medAlimCommentair'],
       'medToilette' => $_POST['medToilette'],
        'medEscarres' => $_POST['medEscarres'],
        'medEncontinence' => $_POST['medEncontinence'],
        'Maj'=> date("Y-m-d"),
        'Nouveau' =>$_POST['Nouveau'],
        'Besoin' => $_POST['OfasBesoin'],
        'CantonOfas' => $_POST['cantonOFAS'],
        'statuOfas' => $_POST['statuOfas'],
        'NoAssurAccident' => $_POST['noAssure2'],
        'medSonde' => (!isset($_POST['medSonde']) || $_POST['medSonde'] === "") ? 0 : intval($_POST['medSonde']),
        'medProtection' => (!isset($_POST['medProtection']) || $_POST['medProtection'] === "") ? 0 : intval($_POST['medProtection']),
        'medMiseWc' => (!isset($_POST['medMiseWc']) || $_POST['medMiseWc'] === "") ? 0 : intval($_POST['medMiseWc']),
        'medFoodConsistence' => intval($_POST['medFoodConsistence']) ?? null
    ));
    var_dump($insert->errorInfo());
    header("location: medical.php?Id=" . $id.'&searchedParam='.$searchedParam);
    }catch(Exception $e){
        echo $e->getMessage();
    }
}
?>

<?php

echo "<h1> Modifier les info médicales pour" . ' ' . $contact['conNom'] . ' ' . $contact['conPrenom'] . "</h1>";
?>

<div id="information" style="margin-bottom: 20px;">
    <form method="post">
        <div id="contact" style=" float: left; border: solid 1px; width: 32%;">
            <h2>Contacts</h2>
            <table class="noMargin">

                <tr>
                    <td>Médecin 1</td>
                    <td>
                        <select name="medecin1"
                                style="width: 200px"><?php ListeModif2($lstMedecin1, $infoMedical['medMedecin1'], 'conId', 'conNom', 'conPrenom') ?> </select>

                </tr>
                <tr>
                    <td>Médecin 2</td>
                    <td>
                        <select name="medecin2"
                                style="width: 200px"><?php ListeModif2($lstMedecin2, $infoMedical['medMedecin2'], 'conId', 'conNom', 'conPrenom') ?> </select>
                    </td>
                </tr>
                <tr>
                    <td>Médecin 3</td>
                    <td>
                        <select name="medecin3"
                                style="width: 200px"><?php ListeModif2($lstMedecin3, $infoMedical['medMedecin3'], 'conId', 'conNom', 'conPrenom') ?> </select>
                    </td>
                </tr>
                <tr>
                    <td>Caissse maladie</td>
                    <td>
                        <select name="caMaladie"
                                style="width: 200px"><?php ListeModif2($lstCaMaladie, $infoMedical['medCaisseMaladie'], 'conId', 'conNom', 'conPrenom') ?> </select>
                    </td>
                </tr>
                <tr>
                    <td>N° d'assuré</td>
                    <td><input name="noAssure" class="input3" value="<?php echo $infoMedical['medNoAssure']; ?>"></td>
                </tr>
                <tr>
                    <td>Assurance accident</td>
                    <td>
                        <select name="caAccident"
                                style="width: 200px"><?php ListeModif2($lstCaAccident, $infoMedical['medAccident'], 'conId', 'conNom', 'conPrenom') ?> </select>
                    </td>
                </tr>
                <tr>
                    <td>N° d'assuré</td>
                    <td><input name="noAssure2" class="input3" value="<?php echo $infoMedical['medNoAssureAccident']; ?>"></td>
                </tr>
            </table>
        </div>
        <div id="Soin" style=" margin-left:34.6%; border: solid 1px; width: 65%; min-height: 225px">
            <h2>Soins</h2>
            <table class="noMargin">
                <tr>
                    <div id="specTr">
                        <?php echo 'Sonde: '; //$mrp->getText(); ?>
                        <?php CheckBoxModif($infoMedical['medSonde'], 'medSonde'); ?>
                        <?php echo 'Protection: '; //$mrp->getText(); ?>
                        <?php CheckBoxModif($infoMedical['medProtection'], 'medProtection'); ?>
                        <?php echo 'Mise WC: '; //$mrp->getText(); ?>
                        <?php CheckBoxModif($infoMedical['medMiseWc'], 'medMiseWc'); ?>
                    </div>
                </tr>
                <tr>
                    <th><?php echo $mrp->getText("aide à l'élimination") ?></th>
                </tr>
                <tr>    
                    <td>
                       <textarea style="width: 100%;" cols="49" name="medEncontinence"><?php echo ($infoMedical['medEncontinence']); ?></textarea>
                    </td>
                </tr>
                <tr><th><?php echo $mrp->getText("Habitudes pour la douche") ?></th></tr>
                <tr>        
                    <td>
                        <textarea name="medToilette" style="width: 100%;" cols="49"><?php echo ($infoMedical['medToilette']); ?></textarea>
                    </td>
                </tr>  
                <tr>
                    <th><?php echo $mrp->getText("Soins particuliers"); ?></th>
                </tr>
                <tr>
                    <td colspan="2">
                        <textarea style="width: 100%;" cols="49"  name="medEscarres"><?php echo $infoMedical['medEscarres']; ?></textarea>
                    </td>
                </tr>
            </table>
        </div>
        <div id="infoMedical" style=" float: left; border: solid 1px; width: 32%; margin-right: 2%; margin-top: 10px">
            <h2>Infos médicales</h2>
            <table class="noMargin">
                <tr>
                    <th>types de handicap</th>
                    <th>degré impotence</th>
                </tr>
                <tr>
                    <td><input style="width: 100%;" name="medTypeHandicap"
                               value="<?php echo $infoMedical['medTypeHandicap']; ?>"></td>
                    <td><select name="medDegre" id=""><?php ListeModif($lstImpotence,$infoMedical['medDegre'],'impId','impNom')?></select></td>
                </tr>
                <tr>
                    <th colspan="2">handicaps associés</th>
                </tr>
                <tr>
                    <td colspan="2"><input style="width: 100%;" name="medHandicapsAsso"
                                           value="<?php echo $infoMedical['medHandicapsAsso']; ?>"></td>
                </tr>
                <tr>
                    <th colspan="2">allergies</th>
                </tr>
                <tr>
                    <td colspan="2">
                        <textarea name="medAllergies" cols="49"><?php echo $infoMedical['medAllergies']; ?></textarea>
                    </td>
                </tr>
            </table>
        </div>
        <div id="boisson"
             style=" border: solid 1px; width: 65%; margin-left:34.6%; margin-top: 10px; margin-bottom: 10px; min-height: 197px">
            <h2>Boissons</h2>
            <table class="noMargin">
                <tr><th colspan="2"> de quelle manière et conseils hydratation</th></tr>
                <tr>
                    <td colspan="2">
                        <textarea name="medBoisCondition"cols="102"><?php echo $infoMedical['medBoisCondition']; ?></textarea>
                    </td>
                </tr>
                <tr>
                    <th>boisson préférés</th>
                    <!--td>Régime</td-->
                </tr>
                <tr>
                    <td>
                        <div><textarea name="medBoisPrefere" style="width: 90%;"
                                  cols="49"><?php echo $infoMedical['medBoisPrefere']; ?> </textarea>
                        </div>
                    </td>
                    <!--td width="50%;">
                        <textarea name="medBoisRegime"
                                  cols="49"> <?php //echo $infoMedical['medBoisRegime']; ?> </textarea>
                    </td-->
                </tr>
                <tr>
                    <th>déconseillée</th>
                </tr>
                <tr>
                    <td>
                        <textarea style="width: 90%;" name="medBoisDecons" cols="49"><?php echo $infoMedical['medBoisDecons']; ?></textarea>
                    </td>
                </tr>
                <tr><th>interdit</th></tr>
                <tr>
                    <td>
                        <textarea style="width: 90%;" name="medBoisInterdit"
                                  cols="49"><?php echo $infoMedical['medBoisInterdit']; ?></textarea>
                    </td>
                </tr>
            </table>
        </div>
        <div id="ofas" style=" border: solid 1px; width: 32%; margin-right: 2%;  float: left;">
            <h2>OFAS (LAI)</h2>
            <table class="noMargin">

                <tr>
                    <th>Canton de résidence</th>
                    <td><select name="cantonOFAS">
                            <option value="">-></option>
                            <?php ListeModif($lstCanton,$infoMedical['ofasCanton'],'cantId','cantNom')?>
                        </select></td>
                </tr>

                <tr>
                    <th>Statut</th>
                    <td><select style="width: 100px;" name="statuOfas">

                            <?php if($infoMedical['ofasProche'] == 0){echo ' <option value="">-></option>
                            <option selected value="0">Proches</option>
                            <option value="1">En situation de handicape</option>';}
                            elseif($infoMedical['ofasProche'] == 1){echo'<option value="">-></option>
                            <option value="0">Proches</option>
                            <option selected value="1">En situation de handicap</option>';}
                            else
                                {echo'<option selected value="">-></option>
                            <option value="0">Proches</option>
                            <option value="1">En situation de handicap</option>';}?>

                        </select>
                    </td>
                </tr>

                <tr>
                    <th>Types de handicap</th>
                    <th>dont plurihandicap</th>
                </tr>
                <tr>
                    <td>
                        <select name="medOfasType"
                                style="width: 200px"><?php ListeModif($lstOfasType, $infoMedical['medOfasType'], 'hanId', 'hanNom') ?> </select>
                    </td>
                    <td><?php CheckBoxModif($infoMedical['medOfasPluri'], 'pluri') ?></td>
                </tr>
                <tr>
                    <th>reconnu au titre de</th>
                    <td>
                        <select name="medOfasReconnu"
                                style="width: 100px"><?php ListeModif($lstOfasReconnu, $infoMedical['medOfasReconnu'], 'artId', 'artNom') ?> </select>
                    </td>
                </tr>
                <tr>
                    <th>nouveau en </th>
                    <td><input style="width: 100px;;"
                               value="<?php echo $infoMedical['ofasNouveau']; ?>" name="Nouveau"></td>
                </tr>

                <tr>
                    <th>besoin</th>
                    <td>
                        <select name="OfasBesoin"
                                style="width: 100px"><?php ListeModif($lstOfasBesoin, $infoMedical['ofasBesoin'], 'OfasId', 'OfasNom') ?> </select>
                    </td>
                </tr>


            </table>


        </div>
        <div id="allimentation" style="border: solid 1px; width: 65%; margin-left:34.6%;">
            <h2>Alimentation</h2>
            <table class="noMargin">
                <div id="consist">
                    <div id="consist-title">consistace du repas :</div>
                    <div>
                        <select name="medFoodConsistence">
                            <?php ListeDeroulante4($infoMedical['medFoodConsistence'], 2); ?>
                        </select>
                    </div> 
                </div>
                <tr>
                    <th colspan="2"> de quelles manières et conseils alimentation</th>
                </tr>
                <tr>
                    <td colspan="2"> 
                        <textarea name="medAlimCommentair" style="width: 90%;"
                                  cols="102"> <?php echo $infoMedical['medAlimCommentair']; ?> </textarea>
                    </td>
                </tr>
                <tr>
                    <th>aliments préférés</th>
                </tr>
                <tr>
                    <td>
                        <textarea name="medAlimPrefere" style="width: 90%;"
                                  cols="49"> <?php echo $infoMedical['medAlimPrefere']; ?> </textarea>
                    </td>
                    <!--td width="50%;">
                        <textarea name="medAlimRegime"
                                  cols="49"> <?php //echo $infoMedical['medAlimRegime']; ?> </textarea>
                    </td-->
                </tr>
                <tr>
                    <th>déconseillée</th>
                </tr>
                <tr>
                    <td>
                        <textarea name="medAlimDecons" style="width: 90%"
                                  cols="49"><?php echo $infoMedical['medAlimDecons']; ?> </textarea>
                    </td>
                </tr>
                <tr>                   
                    <th>interdit</th>
                </tr>
                <tr>         
                    <td>
                        <textarea name="medAlimInterdit" style="width: 90%"
                                  cols="49"><?php echo $infoMedical['medAlimInterdit']; ?> </textarea>
                    </td>
                </tr>
            </table>
        </div>
        <input type="submit" name="valider" value="Valider" class="valider" style="margin-bottom: 50px;">
        <input type="submit" name="Annuler" value="Annuler" class="Annuler" style="margin-bottom: 50px;">
    </form>
</div>

<style>
    #specTr{
        margin-bottom: 20px;
        display: flex;
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
