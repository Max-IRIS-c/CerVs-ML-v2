<?php
include('../variables.php');
$bdd = new PDO($dsn, $user, $password);
$typeActivite = $bdd->query("SELECT * FROM tblTypeActivite");
$responsable = $bdd->query("SELECT conId,conNom,conPrenom FROM tblContact WHERE conResponsable =1  AND conStatu =1 ");
$Coresponsable = $bdd->query("SELECT conId,conNom,conPrenom FROM tblContact WHERE conCoResponsable =1 AND conStatu =1 ");
$cuisiniere = $bdd->query("SELECT conId,conNom,conPrenom FROM tblContact WHERE conStatu = 1 AND conCuisinier =1 order by conNom ASC");
$infirmier = $bdd->query("SELECT conId,conNom,conPrenom FROM tblContact WHERE conStatu = 1 AND conInfirmiere =1 order by conNom ASC");
$codeOfas = $bdd->query("SELECT * FROM tblTraCat1 WHERE cat1Id BETWEEN 21 AND 24");
$codeTypeOfas = $bdd->query("SELECT * FROM tblOfasType ");
$codeCatOfas = $bdd->query("SELECT * FROM tblOfasCategorie ");
if (isset($_POST['Valider']) OR $_POST['Ajout']) {
    $actId = $bdd->query("SELECT MAX(actId)FROM tblActivites");
    $actId = $actId->fetch();
    $IdAct = $actId[0] + 1;

    $insert = $bdd->prepare("INSERT INTO tblActivites (actNom, actDebut,actFin,actDec,actType,actResponsable,
        actCoResponsable, actLieu, actTheme, actCodeOfas,actCuisiniere,actInfirmier,actOfaCat,actOfasType )
        VALUES(:actNom,:actDebut,:actFin,:actDec,:actType,:actResponsable, :actCoresponsable, :actLieu, :actTheme, :Ofas,
        :actCuisinier,:actInfirmier, :catOfas, :typOfas )");



    $insert->execute(array(
        'actNom' => $_POST['Nom'],
        'actDebut' => $_POST['Debut'],
        'actFin' => $_POST['Fin'],
        'actDec' => $_POST['editeur'],
        'actType' => $_POST['TActivite'],
        'actResponsable' => $_POST['Responsable'],
        'actCoresponsable' => $_POST['CoResponsable'],
        'actCuisinier' => $_POST['Cuisinier'],
        'actInfirmier' => $_POST['Infirmier'],
        'actLieu' => $_POST['Lien'],
        'actTheme' => $_POST['Theme'],
        'Ofas' => $_POST['CodeOfas'],
        'catOfas' => $_POST['codeCatOfas'],
        'typOfas' => $_POST['codeTypeOfas'],

        ));
    if (isset($_POST['Ajout'])) {
        header("location: modifParticipant.php?id=" . $IdAct);
    } else {
        header("location: participants.php?Id=" . $IdAct );
    }

}

include('../heade.php');

?>
<script type="text/javascript" src="ckeditor/ckeditor.js"></script>


<h1>Nouvelle activité</h1>

<form action="#" method="post">
    <table>
        <tr>
            <th>Type d'activité</th>
            <th>Nom de l'activité</th>
            <th>Date de début</th>
            <th>Date de fin</th>

            <tr>
                <td><select name="TActivite"><?php ListeDeroulante($typeActivite, 'tActId', 'tActNom'); ?></select></td>
                <td><input style="width: 238px" name="Nom"></td>

                <td><input style="width:130px;" type="Date" name="Debut"></td>
                <td><input style="width:130px;" type="Date" name="Fin"></td>

            </tr>
            <tr>
                <td style="padding-bottom: 10px"></td>
            </tr>
            <tr>
                <th>Lieu</th>
                <th colspan="3">Thème</th>


            </tr>
            <tr>
                <td><input style="width:238px" name="Lien"></td>
                <td colspan="3"><input style="width: 730px" name="Theme"></td>
            </tr>
            <tr>
                <td style="padding-bottom: 10px"></td>
            </tr>
            <tr>
                <th>Code OFAS</th>
                <th>Type de cours</th>
                <th>Critères d'attribution</th>
            </tr>
            <tr>
                <td><select style="width: 238px;" name="CodeOfas">
                    <option> -></option><?php ListeDeroulante2($codeOfas, 'cat1Id', 'cat1Code','cat1Nom') ?>
                </select></td>

                <td><select style="width: 238px;" name="codeTypeOfas">
                    <option> -></option><?php ListeDeroulante($codeTypeOfas, 'ofaTypId', 'ofaTypNom') ?>
                </select></td>
                <td><select style="width: 238px;" name="codeCatOfas">
                    <option> -></option><?php ListeDeroulante($codeCatOfas, 'ofaCatId', 'ofaCatNom') ?>
                </select></td>
            </tr>
            <tr>
                <td style="padding-bottom: 10px"></td>
            </tr>
            <tr>
                <th>Responsable</th>
                <th>Co-responsable</th>
                <th>Cuisinier</th>
                <th>Infirmier</th>
            </tr>
            <tr>
                <td><select style="width: 238px;" name="Responsable">
                    <option> -></option><?php ListeDeroulante2($responsable, 'conId', 'conNom', 'conPrenom') ?>
                </select></td>
                <td><select style="width: 238px;" name="CoResponsable">
                    <option> -></option><?php ListeDeroulante2($Coresponsable, 'conId', 'conNom', 'conPrenom') ?>
                </select></td>
                <td><select style="width: 238px;" name="Cuisinier">
                    <option> -></option><?php ListeDeroulante2($cuisiniere, 'conId', 'conNom', 'conPrenom') ?>
                </select></td>
                <td><select style="width: 239px;" name="Infirmier">
                    <option> -></option><?php ListeDeroulante2($infirmier, 'conId', 'conNom', 'conPrenom') ?>
                </select></td>

            </tr>
            <tr>
                <td style="padding-bottom: 10px"></td>
            </tr>
            <tr>
                <th colspan="4">Description de l'activité</th>
            </tr>
            <tr>
                <td colspan="4" >
                    <textarea cols="100" class="ckeditor" id="editeur" name="editeur" rows="10"></textarea>
                </tr>
                <tr>
                    <td><input type="submit" name="Valider" VALUE="Valider" class="ValiderPetit"></td>
                    <td>
                        <input type="submit" name="Ajout" VALUE="Ajout Participant" class="ValiderPetit"></td>
                    </tr>

                </table>
            </form>



            <?php include('../footer.php'); ?>