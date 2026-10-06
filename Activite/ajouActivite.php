<?php
include('../variables.php');
include_once "../src/class/Db.class.php";
include_once "../src/class/Mrp.class.php";

$mrp = new Mrp();

// we need to have the MRP object created.

if(isset($_GET["lang"]) && !empty($_GET["lang"])){
    $mrp->setLanguage($_GET["lang"]);
}
$bdd = new PDO($dsn, $user, $password);
$typeActivite = $bdd->query("SELECT * FROM tblTypeActivite");
$responsable = $bdd->query("SELECT conId,conNom,conPrenom FROM tblContact WHERE conResponsable =1  AND conStatu =1 ORDER BY conNom ASC");
$Coresponsable = $bdd->query("SELECT conId,conNom,conPrenom FROM tblContact WHERE conCoResponsable =1 AND conStatu =1 ORDER BY conNom ASC ");
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
include('../src/language.php');
?>
<script type="text/javascript" src="ckeditor/ckeditor.js"></script>


<h1><?php echo $mrp->getText('Nouvelle activité') ?></h1>

<form action="#" method="post">
    <table>
        <tr>
            <th><?php echo $mrp->getText('Type d\'activité') ?></th>
            <th><?php echo $mrp->getText('Nom de l\'activité') ?></th>
            <th><?php echo $mrp->getText('Date de début') ?></th>
            <th><?php echo $mrp->getText('Date de fin') ?></th>

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
                <th><?php echo $mrp->getText('Lieu') ?></th>
                <th colspan="3"><?php echo $mrp->getText('Thème') ?></th>


            </tr>
            <tr>
                <td><input style="width:238px" name="Lien"></td>
                <td colspan="3"><input style="width: 730px" name="Theme"></td>
            </tr>
            <tr>
                <td style="padding-bottom: 10px"></td>
            </tr>
            <tr>
                <th><?php echo $mrp->getText('Code OFAS') ?></th>
                <th><?php echo $mrp->getText('Type de cours') ?></th>
                <th><?php echo $mrp->getText('Critères d\'attribution') ?></th>
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
                <th><?php echo $mrp->getText('Responsable') ?></th>
                <th><?php echo $mrp->getText('Co-responsable') ?></th>
                <th><?php echo $mrp->getText('Cuisinier') ?></th>
                <th><?php echo $mrp->getText('Infirmier') ?></th>
            </tr>
	    <style>
		    option
		    {
			    text-transform: capitalize;
		    }
	    </style>
	    <script>
		    // document.getElement("option").textTransform = "lowercase";
		    // document.getElement("option").style.color = "blue";

	    </script>
	    
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
                <th colspan="4"><?php echo $mrp->getText('Description de l\'activité') ?></th>
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