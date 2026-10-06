<?php include('../header.php');
/**
 * Created by PhpStorm.
 * User: Lionel
 * Date: 08/08/2017
 * Description de la page: Gestion des dossiers de cerebral Valais ( Ajout, Supprimer, Modifier)
 */
$bdd = new PDO($dsn, $user, $password);

$lstCompta = $bdd->query("SELECT * FROM tblCodeCompta ORDER BY codeNo");
if ($_GET['action'] == "Modifier" AND isset($_GET['action'])) {
    $id = $_GET['Id'];
    $dossier = $bdd->query("SELECT * FROM tblTraCat4 
LEFT JOIN tblCodeCompta on cat4Compta = codeId WHERE cat4Statu = 1 AND cat4Id !='$id' ORDER BY cat4Code");
    $modifDossier = $bdd->query("SELECT * FROM tblTraCat4
 LEFT JOIN tblCodeCompta on cat4Compta = codeId WHERE cat4Statu = 1 AND cat4Id = '$id' ");
    $modifDossier = $modifDossier->fetch();
} else {
    $dossier = $bdd->query("SELECT * FROM tblTraCat4
 LEFT JOIN tblCodeCompta on cat4Compta = codeId WHERE cat4Statu = 1 ORDER BY cat4Code");
}
// Ajout d'un nouveau dossier
if (isset ($_POST[valider]) AND !isset($_GET['action'])) {

    $nom = $_POST['Nom'];
    $code = $_POST['Code'];
    $compta = $_POST['Compta'];
    $insert = $bdd->prepare("INSERT INTO tblTraCat4(cat4Nom, cat4Code,cat4Compta)
VALUES(:nom,:code, :compta)");
    $insert->execute(array(
        'nom' => $nom,
        'code' => $code,
        'compta' => $compta,
    ));

    header("location: dossier.php");
}
// Modification d'un dossier
if (isset ($_POST['valider']) AND isset($_GET['action']) AND $_GET['action']=="Modifier" ) {

    $nom = $_POST['Nom'];
    $code = $_POST['Code'];
    $compta = $_POST['Compta'];
    $id = $_GET['Id'];
    $insert = $bdd->prepare(" UPDATE tblTraCat4 SET 
    cat4Nom = :nom,
    cat4Code = :code,
    cat4Compta = :compta
    
    WHERE cat4Id = '$id'");

    $insert->execute(array(
        'nom' => $nom,
        'code' => $code,
        'compta' => $compta,

    ));

    header("location: dossier.php");
}
// suppression d'un dossier
if (isset ($_POST['Supprimer']) AND isset($_GET['action']) AND $_GET['action']=="Modifier" ) {

   $statu = 0;
    $id = $_GET['Id'];
    $insert = $bdd->prepare(" UPDATE tblTraCat4 SET 
    cat4Statu = :statu 
    
    WHERE cat4Id = '$id'");

    $insert->execute(array(
        'statu' => $statu,


    ));

    header("location: dossier.php");
}

if(isset ($_POST['annuler'])){

    header("location: dossier.php#top");
}

?>
<nav>
    <ul>
        <li>
            <a href="#Ajout"><?= $mrp->getText("Ajouter un dossier")?><i>(<?= $mrp->getText("attention aux doublons") ?>)</i> </a>
            <a href="charges.php"> <?= $mrp->getText("Centre de charges") ?> </a>
        </li>
    </ul>
</nav>
<h1><?= $mrp->getText("Liste des dossiers") ?></h1>
<form method="post">
    <table class="affichage">
        <tr>
            <th><?= $mrp->getText("Nom") ?></th>
            <th><?= $mrp->getText("Description") ?></th>
            <th><?= $mrp->getText("Code compta") ?></th>
            <th></th>
        </tr>
       <?php if ($_GET['action'] == "Modifier" AND isset($_GET['action'])) {
        ?> <!-- Modifiaction du dossier -->
        <tr style="border-bottom: solid 1px">
            <td><input name="Code" value="<?php echo $modifDossier['cat4Code']; ?>"</td>
            <td><input name="Nom" value="<?php echo $modifDossier['cat4Nom']; ?>"></td>
            <td><select name="Compta" ><?php ListeModif2($lstCompta,$modifDossier['codeId'],codeId,codeNo,codeNom) ?></select></td>
            <td><input type="submit" name="valider" value="Valider" class="ValiderPetit">
                <input type="submit" name="Supprimer" value="Supprimer" class="AnnulerPetit"></td>
        </tr>
       <?php } ?>



<!-- affichage des dossier  -->
        <?php while ($row = $dossier->fetch()) {
            ?>
            <tr>
                <td><?php echo $row['cat4Code']; ?></td>
                <td><?php echo $row['cat4Nom']; ?></td>
                <td><?php echo $row['codeNo'].'-'.$row['codeNom']; ?></td>
                <td><?php echo '<a href="dossier.php?Id=' . $row['cat4Id'] . '&action=Modifier"> Modifier</a>'; ?></td>
            </tr>
            <?php
        }

        if ($_GET['action'] == "Modifier" AND isset($_GET['action'])) {

        } else {
            ?>
            <!-- Ajout d'un dossier -->
            <tr id="Ajout">
                <td><input name="Code"></td>
                <td><input name="Nom"></td>
                <td><select name="Compta"> <option>-></option>
  <?php ListeDeroulante2($lstCompta,codeId,codeNo, codeNom) ?> </select></td>
                <td><input type="submit" name="valider" value="Valider" class="ValiderPetit">
                    <input type="submit" name="annuler" value="Annuler" class="AnnulerPetit"></td>
            </tr>
        <?php } ?>
    </table>
</form>

<?php include('../footer.php'); ?>
