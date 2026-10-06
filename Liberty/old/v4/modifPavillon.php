<?php
include('../variables.php');
$id = $_GET['Id'];
$bdd = new PDO($dsn, $user, $password);
$pavillon= $bdd->query("SELECT * FROM tblLogement WHERE logId = $id");
$pavillon = $pavillon->fetch();
$uniter1 = $bdd->query("SELECT * FROM tblUnite");
$uniter2 = $bdd->query("SELECT * FROM tblUnite");
$uniter3 = $bdd->query("SELECT * FROM tblUnite");
$uniter4 = $bdd->query("SELECT * FROM tblUnite");
$tBien = $bdd->query("SELECT * FROM tblTypeBien");
$Lib1 = $bdd->query("SELECT * FROM tblTypePresta");
$Lib2 = $bdd->query("SELECT * FROM tblTypePresta");
$Lib3 = $bdd->query("SELECT * FROM tblTypePresta");
$Lib4 = $bdd->query("SELECT * FROM tblTypePresta");
if (isset($_POST['Valider'])){
    $insert = $bdd->prepare("UPDATE tblLogement SET 
logNom =:logNom,
logPrix1 =:logPrix1,
logPrix2 =:logPrix2,
logPrix3 =:logPrix3,
logPrix4 =:logPrix4,
logUniterPrix1 =:logUniterPrix1,
logUniterPrix2 =:logUniterPrix2,
logUniterPrix3 =:logUniterPrix3,
logUniterPrix4 =:logUniterPrix4,
logLibeller1 =:logLibeller1,
logLibeller2 =:logLibeller2,
logLibeller3 =:logLibeller3,
logLibeller4 =:logLibeller4,
logPersonneMax =:logPersonneMax,
logPersonneMin =:logPersonneMin,
logtype =:logtype, 
logCouleur =:Couleur
WHERE logId = '$id' ");

    $insert->execute(array(
        'logNom' =>$_POST['Nom'],
        'logPrix1' =>$_POST['logPrix1'],
        'logPrix2' =>$_POST['logPrix2'],
        'logPrix3' =>$_POST['logPrix3'],
        'logPrix4' =>$_POST['logPrix4'],
        'logUniterPrix1' =>$_POST['logUniterPrix1'],
        'logUniterPrix2' =>$_POST['logUniterPrix2'],
        'logUniterPrix3' =>$_POST['logUniterPrix3'],
        'logUniterPrix4' =>$_POST['logUniterPrix4'],
        'logLibeller1' =>$_POST['logLibeller1'],
        'logLibeller2' =>$_POST['logLibeller2'],
        'logLibeller3' =>$_POST['logLibeller3'],
        'logLibeller4' =>$_POST['logLibeller4'],
        'logPersonneMax' =>$_POST['nbrMax'],
        'logPersonneMin' =>$_POST['nbrMin'],
        'logtype' =>$_POST['logtype'],
        'logCouleur' =>$_POST['Color']
    ));

    header("location: detPavillon.php?Id=".$id);


}
if (isset($_POST['Annuler'])){

    header("location: liberty.php");

}




include('../heade.php'); ?>
    <nav>

    </nav>

    <h1>Modifier le bien <?php echo $pavillon['logNom']?></h1>

    <table>
        <form method="post">
            <tr>
                <th>Type de bien</th>
                <td><select name="logtype">
                        <?php ListeModif($tBien,$pavillon['logtype'],'tBienId','tBienNom')?>
                    </select></td>
            </tr>

            <tr>
                <th>Nom</th>
                <td colspan="4"><input name="Nom" value="<?php echo $pavillon['logNom'] ?> "></td>
            </tr>
            <tr>
                <th>Nombre de personne</th>
                <td>min</td>
                <td><input name="nbrMin" value="<?php echo $pavillon['logPersonneMin'] ?>"</td>
                <td>max</td>
                <td><input name="nbrMax" value="<?php echo $pavillon['logPersonneMax'] ?>" </td>
            </tr>
            <tr>
                <th><select name="logLibeller1" id=""><?php ListeModif($Lib1,$pavillon['logLibeller1'],'TprestaId','TprestaNom');?></select></th>
                <td><input name="logPrix1" value="<?php echo $pavillon['logPrix1'] ?>"></td>
                <td>
                    <select name="logUniterPrix1">
                        <?php ListeModif($uniter1,$pavillon['logUniterPrix1'],'uniId','uniNom')?>
                    </select>
                </td>
            </tr>
            <tr>
                <th><select name="logLibeller2" id=""><?php ListeModif($Lib2,$pavillon['logLibeller2'],'TprestaId','TprestaNom');?></select></th>

                <td><input name="logPrix2" value="<?php echo $pavillon['logPrix2'] ?>"></td>
                <td>
                    <select name="logUniterPrix2">
                        <?php ListeModif($uniter2,$pavillon['logUniterPrix2'],'uniId','uniNom')?>
                    </select>
                </td>
            </tr>
            <tr>
                <th><select name="logLibeller3" id=""><?php ListeModif($Lib3,$pavillon['logLibeller3'],'TprestaId','TprestaNom');?></select></th>

                <td><input name="logPrix3" value="<?php echo $pavillon['logPrix3'] ?>"></td>
                <td>
                    <select name="logUniterPrix3">
                        <?php ListeModif($uniter3,$pavillon['logUniterPrix3'],'uniId','uniNom')?>
                    </select>
                </td>
            </tr>
            <tr>
                <th><select name="logLibeller4" id=""><?php ListeModif($Lib4,$pavillon['logLibeller4'],'TprestaId','TprestaNom');?></select></th>

                <td><input name="logPrix4" value="<?php echo $pavillon['logPrix4'] ?>"></td>
                <td><select name="logUniterPrix4">
                        <?php ListeModif($uniter4,$pavillon['logUniterPrix4'],'uniId','uniNom')?>
                    </select>
                </td>
            </tr>
            <tr>
                <td>Couleur</td>
                <td><input type="color" value="#ff0000" name="Color">
                </td>
            </tr>
            <tr>
                <td colspan="2">
                    <input type="submit" value="Valider" name="Valider" class="valider">
                </td>
                <td colspan="2">
                    <input type="submit" value="Annuler" name="Annuler" class="Annuler">
                </td>
            </tr>
        </form>
    </table>


<?php include('../footer.php');