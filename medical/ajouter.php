<?php include('../header.php');
/**
 * Created by PhpStorm.
 * User: Lionel
 * Date: 24/08/2017
 * Description de la page
 */

$id = $_GET['Id'];
$bdd = new PDO($dsn, $user, $password);
$contact = $bdd->query("SELECT conNom, conPrenom FROM tblContact WHERE conId ='$id'");
$contact = $contact->fetch();
$infoMedical = $bdd->query("SELECT * from tblMedical WHERE medConId = '$id'");
$infoMedical = $infoMedical->fetch();
$medical = $bdd->query("SElECT * FROM tblMedication WHERE medicConId='$id'");



$medId = $infoMedical['medId'];

$lstOfasType = $bdd->query("SELECT * FROM tblHandicape ORDER BY hanNom");
$lstOfasReconnu = $bdd->query("SELECT * FROM tblArt ORDER BY artNom");
$lstMedecin1 = $bdd->query("SELECT conNom, conPrenom, conId FROM tblContact WHERE conMedecin = 1 ORDER BY conNom ASC");
$lstMedecin2 = $bdd->query("SELECT conNom, conPrenom, conId FROM tblContact WHERE conMedecin = 1 ORDER BY conNom ASC");
$lstMedecin3 = $bdd->query("SELECT conNom, conPrenom, conId FROM tblContact WHERE conMedecin = 1 ORDER BY conNom ASC");
$lstCaMaladie = $bdd->query("SELECT conNom, conPrenom, conId FROM tblContact WHERE conAssurance = 1 ORDER BY conNom ASC");
$lstCaAccident = $bdd->query("SELECT conNom, conPrenom, conId FROM tblContact WHERE conAssurance = 1 ORDER BY conNom ASC");
$lstImpotence = $bdd->query("SELECT * FROM tblDegreImpot order by impNom");
$lstOfasBesoin = $bdd->query("SELECT * FROM tblOfasBesoin ORDER BY OfasNom");
$lstCanton = $bdd->query("SELECT * FROM tblCanton");

if (isset($_POST['valider'])) {

    $insert = $bdd->prepare("INSERT INTO tblMedical
 (medConId,medMedecin1, medMedecin2, medMedecin3, medNoAssure, medCaisseMaladie, 
medAccident, medTypeHandicap, medAllergies, medDegre, medHandicapsAsso, medOfasType, medOfasPluri, medOfasReconnu, medBoisInterdit, 
medBoisDecons, medBoisRegime, medBoisCondition, medBoisPrefere, medAlimPrefere, medAlimDecons, medAlimInterdit, medAlimRegime, 
medAlimCommentair, medToilette, medEscarres, medEncontinence, ofasBesoin,ofasNouveau,ofasCanton,ofasProche)
VALUES
 (:medConId,
:medMedecin1, :medMedecin2, :medMedecin3, :medNoAssure, :medCaisseMaladie, :medAccident, :medTypeHandicap, :medAllergies, 
:medDegre, :medHandicapsAsso, :medOfasType, :medOfasPluri, :medOfasReconnu, :medBoisInterdit, :medBoisDecons, :medBoisRegime, 
:medBoisCondition, :medBoisPrefere, :medAlimPrefere, :medAlimDecons, :medAlimInterdit, :medAlimRegime, :medAlimCommentair, 
:medToilette, :medEscarres, :medEncontinence,:Besoin, :Nouveau, :CantonOfas,:statuOfas )");

    $insert->execute(array(
        'medConId'=>$id,
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
        'medOfasType' => $_POST['OfasType'],
        'medOfasPluri' => $_POST['OfasPluri'],
        'medOfasReconnu' => $_POST['OfasReconnu'],
        'medBoisInterdit' => $_POST['medBoisInterdit'],
        'medBoisDecons' => $_POST['medBoisDecons'],
        'medBoisRegime' => $_POST['medBoisRegime'],
        'medBoisCondition' => $_POST['medBoisCondition'],
        'medBoisPrefere' => $_POST['medBoisPrefere'],
        'medAlimPrefere' => $_POST['medAlimPrefere'],
        'medAlimDecons' => $_POST['medAlimDecons'],
        'medAlimInterdit' => $_POST['medAlimInterdit'],
        'medAlimRegime' => $_POST['medAlimRegime'],
        'medAlimCommentair' => $_POST['medAlimCommentair'],
        'medToilette' => $_POST['medToilette'],
        'medEscarres' => $_POST['medEscarres'],
        'medEncontinence' => $_POST['medEncontinence'],
        'Nouveau' =>$_POST['OfasNouveau'],
        'Besoin' => $_POST['OfasBesoin'],
        'CantonOfas' => $_POST['cantonOFAS'],
        'statuOfas' => $_POST['statuOfas']


    ));
   header("location: medical.php?Id=" . $id);
}
?>

<?php

echo "<h1> Modifier les infos médicales pour" . ' ' . $contact['conNom'] . ' ' . $contact['conPrenom'] . "</h1>";
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
                </tr
                <tr>
                    <td>Assurance accident</td>
                    <td>
                        <select name="caAccident"
                                style="width: 200px"><?php ListeModif2($lstCaAccident, $infoMedical['medAccident'], 'conId', 'conNom', 'conPrenom') ?> </select>
                    </td>
                </tr>
            </table>
        </div>
        <div id="Soin" style=" margin-left:34.6%; border: solid 1px; width: 65%; min-height: 225px">
            <h2>Soins</h2>
            <table class="noMargin">
                <tr>
                    <td> Indication sur la toilette</td>
                </tr>
                <tr>
                    <td>
                        <textarea name="medToilette" cols="102"><?php echo $infoMedical['medToilette']; ?></textarea>
                    </td>
                </tr>
                <tr>
                    <td> WC, incontinence</td>
                </tr>
                <tr>
                    <td>
                        <textarea name="medEncontinence"
                                  cols="102"><?php echo $infoMedical['medEncontinence']; ?></textarea>
                    </td>
                </tr>
                <tr>
                    <td> Escarres</td>
                </tr>
                <tr>
                    <td>
                        <textarea name="medEscarres" cols="102"><?php echo $infoMedical['medEscarres']; ?></textarea>
                    </td>
                </tr>
            </table>
        </div>
        <div id="infoMedical" style=" float: left; border: solid 1px; width: 32%; margin-right: 2%; margin-top: 10px">
            <h2>Infos médicales</h2>
            <table class="noMargin">
                <tr>
                    <td>Types de handicap</td>
                    <td> Degré impotence</td>
                </tr>
                <tr>
                    <td><input style="width: 100%;" name="medTypeHandicap"
                               value="<?php echo $infoMedical['medTypeHandicap']; ?>"></td>
                    <td><select name="medDegre" id=""><?php ListeDeroulante($lstImpotence,'impId','impNom')?></select>
                        </td>
                </tr>
                <tr>
                    <td colspan="2">Handicaps associés</td>
                </tr>
                <tr>
                    <td colspan="2"><input style="width: 100%;" name="medHandicapsAsso"
                                           value="<?php echo $infoMedical['medHandicapsAsso']; ?>"></td>
                </tr>
                <tr>
                    <td colspan="2">Allergies</td>
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
                <tr>
                    <td colspan="2"> Dans quelles condition</td>
                </tr>
                <tr>
                    <td colspan="2">
                        <textarea name="medBoisCondition"
                                  cols="102"><?php echo $infoMedical['medBoisCondition']; ?></textarea>
                    </td>
                </tr>
                <tr>
                    <td>Boisson préférés</td>
                    <td>Régime</td>
                </tr>
                <tr>
                    <td width="50%;">
                        <textarea name="medBoisPrefere"
                                  cols="49"><?php echo $infoMedical['medBoisPrefere']; ?> </textarea>
                    </td>
                    <td width="50%;">
                        <textarea name="medBoisRegime"
                                  cols="49"> <?php echo $infoMedical['medBoisRegime']; ?> </textarea>
                    </td>
                </tr>
                <tr>
                    <td>Déconseillées</td>
                    <td>Interdites</td>
                </tr>
                <tr>
                    <td width="50%;">
                        <textarea name="medBoisDecons" cols="49"><?php echo $infoMedical['medBoisDecons']; ?></textarea>
                    </td>
                    <td width="50%;">
                        <textarea name="medBoisInterdit"
                                  cols="49"><?php echo $infoMedical['medBoisInterdit']; ?></textarea>
                    </td>
                </tr>
            </table>
        </div>
        <div id="ofas" style=" border: solid 1px; width: 32%; margin-right: 2%;  float: left;">
            <h2>OFAS(LAI)</h2>


            <table class="noMargin">
                <tr>
                    <td>Canton de résidence</td>
                    <td><select name="cantonOFAS">
                            <option value="">-></option>
                            <?php ListeDeroulante($lstCanton,'cantId','cantNom')?>
                        </select></td>
                </tr>

                <tr>
                    <td>Statut</td>
                    <td><select name="statuOfas">
                            <option value="">-></option>
                            <option value="0">Proches</option>
                            <option value="1">En situation de handicape</option>
                        </select>
                    </td>
                </tr>

                <tr>
                    <td>Types de handicap</td>
                    <td>dont plurihandicapés</td>
                </tr>
                <tr>
                    <td>
                        <select name="OfasType"
                                style="width: 200px"><?php ListeDeroulante($lstOfasType, 'hanId', 'hanNom') ?> </select>
                    </td>
                    <td><?php CheckBoxModif($infoMedical['medOfasPluri'], 'OfasPluri') ?></td>
                </tr>
                <tr>
                    <td>reconnus au titre de</td>
                    <td>
                        <select name="OfasReconnu"
                                style="width: 100px"><?php ListeDeroulante($lstOfasReconnu,  'artId', 'artNom') ?> </select>
                    </td>
                </tr>
                <tr>
                    <td>Nouveau en </td>
                    <td><input style="width: 100%;" name="OfasNouveau"  value="<?php echo $infoMedical['ofasNouveau'];?>"></td>
                </tr>

                <tr>
                    <td>Besoin</td>
                    <td>
                        <select name="OfasBesoin"
                                style="width: 100px"><?php ListeDeroulante($lstOfasBesoin, 'OfasId', 'OfasNom') ?> </select>
                    </td>
                </tr>


            </table>


        </div>
        <div id="allimentation" style="border: solid 1px; width: 65%; margin-left:34.6%;">
            <h2>Alimentation</h2>
            <table class="noMargin">
                <tr>
                    <td colspan="2"> Dans quelles condition</td>
                </tr>
                <tr>
                    <td colspan="2">
                        <textarea name="medAlimCommentair"
                                  cols="102"> <?php echo $infoMedical['medAlimCommentair']; ?> </textarea>
                    </td>
                </tr>
                <tr>
                    <td>Aliment préférés</td>
                    <td>Régime</td>
                </tr>
                <tr>
                    <td width="50%;">
                        <textarea name="medAlimPrefere"
                                  cols="49"> <?php echo $infoMedical['medAlimPrefere']; ?> </textarea>
                    </td>
                    <td width="50%;">
                        <textarea name="medAlimRegime"
                                  cols="49"> <?php echo $infoMedical['medAlimRegime']; ?> </textarea>
                    </td>
                </tr>
                <tr>
                    <td>Déconseillées</td>
                    <td>Interdites</td>
                </tr>
                <tr>
                    <td width="50%;">
                        <textarea name="medAlimDecons"
                                  cols="49"><?php echo $infoMedical['medAlimDecons']; ?> </textarea>
                    </td>
                    <td width="50%;">
                        <textarea name="medAlimInterdit"
                                  cols="49"><?php echo $infoMedical['medAlimInterdit']; ?> </textarea>
                    </td>
                </tr>
            </table>
        </div>
        <input type="submit" name="valider" value="Valider" class="valider">
    </form>
</div>


<div id="medication" style="border: solid 1px; margin-top: 25px; margin-bottom: 100px; width: 99%; ">
    <h2>Médication</h2>
    <table class="affichage">

        <tr>
            <th>Nom du médicament</th>
            <th>Fabriquant</th>
            <th>Dossages</th>
            <th>Matin</th>
            <th>Midi</th>
            <th>Soir</th>
            <th>Nuit</th>
            <th>Remarque(s)</th>
        </tr>

        <?php while ($row = $medical->fetch()) { ?>

            <tr>
                <td><?php echo $row['medicNom']; ?></td>
                <td><?php echo $row['medFabriquand']; ?></td>
                <td><?php echo $row['medicDossages']; ?></td>
                <td><?php echo $row['medicPrisematin']; ?></td>
                <td><?php echo $row['medicPrisemidi']; ?></td>
                <td><?php echo $row['medicPrisesoir']; ?></td>
                <td><?php echo $row['medicPrisenoctu']; ?></td>
                <td><?php echo $row['medicPrecicom']; ?></td>
                <td><? echo '<a href="medication.php?Id='.$row['medicId'].'&statu=Modif"> Modification</a>';?></td>
            </tr>

        <?php } ?>
    </table>

</div>


<?php include('../footer.php'); ?>
