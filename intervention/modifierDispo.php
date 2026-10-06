<?php include('../header.php');
$bdd = new PDO($dsn, $user, $password);
$id = $_GET['Id'];
$searchedParam= $_GET['searchedParam'];

$diponibiliter1 = $bdd->query("SELECT regNom, regId FROM tblDiponibiliter 
LEFT JOIN tblRegionInter on dispRegion1 = regId WHERE tblContact_conId = '$id'");
$diponibiliter1 = $diponibiliter1->fetch();

$diponibiliter2 = $bdd->query("SELECT regNom, regId FROM tblDiponibiliter 
LEFT JOIN tblRegionInter on dispRegion2 = regId WHERE tblContact_conId = '$id'");
$diponibiliter2 = $diponibiliter2->fetch();

$diponibiliter3 = $bdd->query("SELECT regNom, regId FROM tblDiponibiliter 
LEFT JOIN tblRegionInter on dispRegion3 = regId WHERE tblContact_conId = '$id'");
$diponibiliter3 = $diponibiliter3->fetch();

$infoGeneral = $bdd->query("SELECT * FROM tblDiponibiliter WHERE  tblContact_conId ='$id'");
$infoGeneral = $infoGeneral->fetch();

$contact = $bdd->query("SELECT conNom, conPrenom FROM tblContact WHERE conId = $id");
$contact = $contact->fetch();

// liste déroulante Region


$region1 = $bdd->query("SELECT * FROM tblRegionInter");
$region2 = $bdd->query("SELECT * FROM tblRegionInter");
$region3 = $bdd->query("SELECT * FROM tblRegionInter");

if (isset($_POST['annuler'])) {
    header("location: disponibilite.php?Id=" . $id);


}

if (isset($_POST['validerInfo'])) {
    try{
        $new = $bdd->query("SELECT * FROM tblDiponibiliter WHERE tblContact_conId=$id");
        $new = $new->fetch();

        $region1 = $_POST['region1'];
        $region2 = $_POST['region2'];
        $region3 = $_POST['region3'];
        $experiance = $_POST['Experiences'];
        $referance = $_POST['References'];


        if (isset($new[0])) {// Màj de l'enregistrement


            $insert = $bdd->prepare("UPDATE tblDiponibiliter SET
                dispRegion1=:region1,
                dispRegion2=:region2,
                dispRegion3=:region3,
                dispExperiance=:experiance,
                dispReference=:referance
                WHERE tblContact_conId = '$id'");
            $insert->execute(array(
                'region1' => $region1,
                'region2' => $region2,
                'region3' => $region3,
                'experiance' => $experiance,
                'referance' => $referance,
                ));

        } else {// nouveau enregistrement
            $insert = $bdd->prepare("INSERT INTO tblDiponibiliter(dispRegion1,dispRegion2,dispRegion3,dispExperiance,dispReference,tblContact_conId)
                                    VALUES(:region1,:region2,:region3,:experiance,:referance, :contact)");
            $insert->execute(array(
                'region1' => $region1,
                'region2' => $region2,
                'region3' => $region3,
                'experiance' => $experiance,
                'referance' => $referance,
                'contact' => $id));
        }
        var_dump($insert);
        header("location: disponibilite.php?Id=".$id."&searchedParam=".$searchedParam);
    }catch(Exception $e){
        
    }
}

if (isset($_POST['validerDisp'])) {
    $Lmatin = $_POST['Lmatin'];
    $Mmatin = $_POST['Mmatin'];
    $MEmatin = $_POST['MEmatin'];
    $Jmatin = $_POST['Jmatin'];
    $Vmatin = $_POST['Vmatin'];
    $Smatin = $_POST['Smatin'];
    $Dmatin = $_POST['Dmatin'];

    $Lmidi = $_POST['Lmidi'];
    $Mmidi = $_POST['Mmidi'];
    $MEmidi = $_POST['MEmidi'];
    $Jmidi = $_POST['Jmidi'];
    $Vmidi = $_POST['Vmidi'];
    $Smidi = $_POST['Smidi'];
    $Dmidi = $_POST['Dmidi'];

    $Lamidi = $_POST['Lamidi'];
    $Mamidi = $_POST['Mamidi'];
    $MEamidi = $_POST['MEamidi'];
    $Jamidi = $_POST['Jamidi'];
    $Vamidi = $_POST['Vamidi'];
    $Samidi = $_POST['Samidi'];
    $Damidi = $_POST['Damidi'];

    $Lsouper = $_POST['Lsouper'];
    $Msouper = $_POST['Msouper'];
    $MEsouper = $_POST['MEsouper'];
    $Jsouper = $_POST['Jsouper'];
    $Vsouper = $_POST['Vsouper'];
    $Ssouper = $_POST['Ssouper'];
    $Dsouper = $_POST['Dsouper'];

    $Lsoir = $_POST['Lsoir'];
    $Msoir = $_POST['Msoir'];
    $MEsoir = $_POST['MEsoir'];
    $Jsoir = $_POST['Jsoir'];
    $Vsoir = $_POST['Vsoir'];
    $Ssoir = $_POST['Ssoir'];
    $Dsoir = $_POST['Dsoir'];

    $new = $bdd->query("SELECT * FROM tblDiponibiliter WHERE tblContact_conId=$id");
    $new = $new->fetch();


    if (isset($new[0])) {// Màj de l'enregistrement

        $insert = $bdd->prepare("UPDATE tblDiponibiliter SET 			
            dispLMatin =:dispLMatin,
            dispLSoir =:dispLSoir,
            dispLSouper =:dispLSouper,
            dispLaMidi =:dispLaMidi,
            dispLMidi =:dispLMidi,
            dispMmatin =:dispMmatin,
            dispMMidi =:dispMMidi,
            dispMaMidi =:dispMaMidi,
            dispMSouper =:dispMSouper,
            dispMSoir =:dispMSoir,
            dispMEmatin =:dispMEmatin,
            dispMEMidi =:dispMEmidi,
            dispMEaMidi =:dispMEaMidi,
            dispMESouper =:dispMESouper,
            dispMESoir =:dispMESoir,
            dispJmatin =:dispJmatin,
            dispJMidi =:dispJMidi,
            dispJaMidi =:dispJaMidi,
            dispJSouper =:dispJSouper,
            dispJSoir =:dispJSoir,
            dispVmatin =:dispVmatin,
            dispVMidi =:dispVMidi,
            dispVaMidi =:dispVaMidi,
            dispVSouper =:dispVSouper,
            dispVSoir =:dispVSoir,
            dispSmatin =:dispSmatin,
            dispSMidi =:dispSMidi,
            dispSaMidi =:dispSaMidi,
            dispSSouper =:dispSSouper,
            dispSSoir =:dispSSoir,
            dispDmatin =:dispDmatin,
            dispDMidi =:dispDMidi,
            dispDaMidi =:dispDaMidi,
            dispDSouper =:dispDSouper,
            dispDSoir =:dispDSoir
        WHERE tblContact_conId = '$id'");
        $insert->execute(array(


            'dispLMatin' => $Lmatin,
            'dispLMidi' => $Lmidi,
            'dispLaMidi' => $Lamidi,
            'dispLSouper' => $Lsouper,
            'dispLSoir' => $Lsoir,
            'dispMmatin' => $Mmatin,
            'dispMMidi' => $Mmidi,
            'dispMaMidi' => $Mamidi,
            'dispMSouper' => $Msouper,
            'dispMSoir' => $Msoir,
            'dispMEmatin' => $MEmatin,
            'dispMEmidi' => $MEmidi,
            'dispMEaMidi' => $MEamidi,
            'dispMESouper' => $MEsouper,
            'dispMESoir' => $MEsoir,
            'dispJmatin' => $Jmatin,
            'dispJMidi' => $Jmidi,
            'dispJaMidi' => $Jamidi,
            'dispJSouper' => $Jsouper,
            'dispJSoir' => $Jsoir,
            'dispVmatin' => $Vmatin,
            'dispVMidi' => $Vmidi,
            'dispVaMidi' => $Vamidi,
            'dispVSouper' => $Vsouper,
            'dispVSoir' => $Vsoir,
            'dispSmatin' => $Smatin,
            'dispSMidi' => $Smidi,
            'dispSaMidi' => $Samidi,
            'dispSSouper' => $Ssouper,
            'dispSSoir' => $Ssoir,
            'dispDmatin' => $Dmatin,
            'dispDMidi' => $Dmidi,
            'dispDaMidi' => $Damidi,
            'dispDSouper' => $Dsouper,
            'dispDSoir' => $Dsoir,
        ));
    } else {// nouveau enregistrement
        $insert = $bdd->prepare("INSERT INTO tblDiponibiliter(tblContact_conId, dispLMatin, dispLSoir, dispLSouper, dispLaMidi, dispLMidi, dispMmatin, dispMMidi,
 dispMaMidi, dispMSouper, dispMSoir, dispMEmatin, dispMEMidi, dispMEaMidi, dispMESouper, dispMESoir, dispJmatin,
  dispJMidi, dispJaMidi, dispJSouper, dispJSoir, dispVmatin, dispVMidi, dispVaMidi, dispVSouper, dispVSoir, dispSmatin, 
  dispSMidi, dispSaMidi, dispSSouper, dispSSoir, dispDmatin, dispDMidi, dispDaMidi, dispDSouper, dispDSoir) VALUES 
  (:tblContact_conId, :dispLMatin, :dispLSoir, :dispLSouper, :dispLaMidi, :dispLMidi, :dispMmatin, :dispMMidi,
 :dispMaMidi, :dispMSouper, :dispMSoir, :dispMEmatin, :dispMEMidi, :dispMEaMidi, :dispMESouper, :dispMESoir, :dispJmatin,
  :dispJMidi, :dispJaMidi, :dispJSouper, :dispJSoir, :dispVmatin, :dispVMidi, :dispVaMidi, :dispVSouper, :dispVSoir, :dispSmatin, 
  :dispSMidi, :dispSaMidi, :dispSSouper, :dispSSoir, :dispDmatin, :dispDMidi, :dispDaMidi, :dispDSouper, :dispDSoir)");
        $insert->execute(array(

            'tblContact_conId' => $id,
            'dispLMatin' => $Lmatin,
            'dispLMidi' => $Lmidi,
            'dispLaMidi' => $Lamidi,
            'dispLSouper' => $Lsouper,
            'dispLSoir' => $Lsoir,
            'dispMmatin' => $Mmatin,
            'dispMMidi' => $Mmidi,
            'dispMaMidi' => $Mamidi,
            'dispMSouper' => $Msouper,
            'dispMSoir' => $Msoir,
            'dispMEmatin' => $MEmatin,
            'dispMEmidi' => $MEmidi,
            'dispMEaMidi' => $MEamidi,
            'dispMESouper' => $MEsouper,
            'dispMESoir' => $MEsoir,
            'dispJmatin' => $Jmatin,
            'dispJMidi' => $Jmidi,
            'dispJaMidi' => $Jamidi,
            'dispJSouper' => $Jsouper,
            'dispJSoir' => $Jsoir,
            'dispVmatin' => $Vmatin,
            'dispVMidi' => $Vmidi,
            'dispVaMidi' => $Vamidi,
            'dispVSouper' => $Vsouper,
            'dispVSoir' => $Vsoir,
            'dispSmatin' => $Smatin,
            'dispSMidi' => $Smidi,
            'dispSaMidi' => $Samidi,
            'dispSSouper' => $Ssouper,
            'dispSSoir' => $Ssoir,
            'dispDmatin' => $Dmatin,
            'dispDMidi' => $Dmidi,
            'dispDaMidi' => $Damidi,
            'dispDSouper' => $Dsouper,
            'dispDSoir' => $Dsoir,
        ));
    }
    header("location: disponibilite.php?Id=" . $id."&searchedParam=".$searchedParam);
}
?>


    <h1> Profil de l'Intervenant <?php echo $contact['conNom'] . ' ' . $contact['conPrenom'] ?> </h1>
    <form method="post">
        <table style="width:100%; border-collapse:collapse; table-layout:fixed;">
            <tr>
                <th> Région</th>
                <!--th> Région possible</th>
                <th> Région pour dépannage</th-->
                <th colspan="7">Profil, experiences et compétences</th>
                <th colspan="7"> Commentaires</th>
            </tr>
            <tr>
                <td style="display: flex; align-items: top; width: 100%; margin: 0; padding: 0;"><select name="region1" style="width: 100%; height: 25px;">
                        <?php
                        ListeModif($region1, $diponibiliter1[1], regId, regNom)
                        ?>
                    </select></td>
                <td hidden><select name="region2">
                        <?php
                        ListeModif($region2, $diponibiliter2[1], regId, regNom)
                        ?>
                    </select></td>
                <td hidden><select name="region3">
                        <?php
                        ListeModif($region3, $diponibiliter3[1], regId, regNom)
                        ?>
                    </select></td>
                <td colspan="7" style="margin: 0; padding: 0;"><textarea name="Experiences" style="width: 100%; height: 200px;"><?php echo $infoGeneral['dispExperiance']; ?></textarea></td>
                <td colspan="7" style="margin: 0; padding: 0;"><textarea name="References" style="width: 100%; height: 200px;"><?php echo $infoGeneral['dispReference']; ?></textarea></td>
            </tr>
            <tr>
                <td colspan="3"><input type="submit" name="validerInfo" value="Valider" class="ValiderPetit"></td>
                <td colspan="2"><input type="submit" name="annuler" value="Annuler" class="SuprimerrPetit"></td>
            </tr>
        </table>
    </form>
    <?php /*
    <h1> Disponibilités</h1>
    <form name="disp" method="post">
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
                <td>Matin (07- 12)</td>
                <td><?php CheckBoxModif($infoGeneral['dispLMatin'], Lmatin); ?></td>
                <td><?php CheckBoxModif($infoGeneral['dispMmatin'], Mmatin); ?></td>
                <td><?php CheckBoxModif($infoGeneral['dispMEmatin'], MEmatin); ?></td>
                <td><?php CheckBoxModif($infoGeneral['dispJmatin'], Jmatin); ?></td>
                <td><?php CheckBoxModif($infoGeneral['dispVmatin'], Vmatin); ?></td>
                <td><?php CheckBoxModif($infoGeneral['dispSmatin'], Smatin); ?></td>
                <td><?php CheckBoxModif($infoGeneral['dispDmatin'], Dmatin); ?></td>
            </tr>
            <tr>
                <td>Midi (Repas)</td>
                <td><?php CheckBoxModif($infoGeneral['dispLMidi'], Lmidi); ?></td>
                <td><?php CheckBoxModif($infoGeneral['dispMMidi'], Mmidi); ?></td>
                <td><?php CheckBoxModif($infoGeneral['dispMEMidi'], MEmidi); ?></td>
                <td><?php CheckBoxModif($infoGeneral['dispJMidi'], Jmidi); ?></td>
                <td><?php CheckBoxModif($infoGeneral['dispVMidi'], Vmidi); ?></td>
                <td><?php CheckBoxModif($infoGeneral['dispSMidi'], Smidi); ?></td>
                <td><?php CheckBoxModif($infoGeneral['dispDMidi'], Dmidi); ?></td>
            </tr>
            <tr>
                <td>Aprés-midi</td>
                <td><?php CheckBoxModif($infoGeneral['dispLaMidi'], Lamidi); ?></td>
                <td><?php CheckBoxModif($infoGeneral['dispMaMidi'], Mamidi); ?></td>
                <td><?php CheckBoxModif($infoGeneral['dispMEaMidi'], MEamidi); ?></td>
                <td><?php CheckBoxModif($infoGeneral['dispJaMidi'], Jamidi); ?></td>
                <td><?php CheckBoxModif($infoGeneral['dispVaMidi'], Vamidi); ?></td>
                <td><?php CheckBoxModif($infoGeneral['dispSaMidi'], Samidi); ?></td>
                <td><?php CheckBoxModif($infoGeneral['dispDaMidi'], Damidi); ?></td>

            </tr>
            <tr>
                <td>Soir (repas)</td>
                <td><?php CheckBoxModif($infoGeneral['dispLSouper'], Lsouper); ?></td>
                <td><?php CheckBoxModif($infoGeneral['dispMSouper'], Msouper); ?></td>
                <td><?php CheckBoxModif($infoGeneral['dispMESouper'], MEsouper); ?></td>
                <td><?php CheckBoxModif($infoGeneral['dispJSouper'], Jsouper); ?></td>
                <td><?php CheckBoxModif($infoGeneral['dispVSouper'], Vsouper); ?></td>
                <td><?php CheckBoxModif($infoGeneral['dispSSouper'], Ssouper); ?></td>
                <td><?php CheckBoxModif($infoGeneral['dispDSouper'], Dsouper); ?></td>
            </tr>
            <tr>
                <td>Soirée</td>
                <td><?php CheckBoxModif($infoGeneral['dispLSoir'], Lsoir); ?></td>
                <td><?php CheckBoxModif($infoGeneral['dispMSoir'], Msoir); ?></td>
                <td><?php CheckBoxModif($infoGeneral['dispMESoir'], MEsoir); ?></td>
                <td><?php CheckBoxModif($infoGeneral['dispJSoir'], Jsoir); ?></td>
                <td><?php CheckBoxModif($infoGeneral['dispVSoir'], Vsoir); ?></td>
                <td><?php CheckBoxModif($infoGeneral['dispSSoir'], Ssoir); ?></td>
                <td><?php CheckBoxModif($infoGeneral['dispDSoir'], Dsoir); ?></td>

            </tr>
            <tr>
                <td colspan="4"><input type="submit" name="validerDisp" value="Valider" class="ValiderPetit"></td>
                <td colspan="4"><input type="submit" name="annuler" value="Annuler" class="SuprimerrPetit"></td>
            </tr>
        </table>
    </form>
    */
include('../footer.php'); ?>