<?php include('../header.php');
/**
 * Created by PhpStorm.
 * User: Lionel
 * Date: 08/08/2017
 * Description de la page: Gestion des dossiers de cerebral Valais ( Ajout, Supprimer, Modifier)
 */
$bdd = new PDO($dsn, $user, $password);


if ($_GET['action'] == "Modifier" AND isset($_GET['action'])) {
    $id = $_GET['Id'];
    $dossier = $bdd->query("SELECT * FROM tblCodeCompta WHERE  codeId !='$id' ORDER BY codeId");
    $modifDossier = $bdd->query("SELECT * FROM tblCodeCompta WHERE  codeId = '$id' ");
    $modifDossier = $modifDossier->fetch();
} else {
    $dossier = $bdd->query("SELECT * FROM tblCodeCompta");
}
// Ajout d'un nouveau dossier
if (isset ($_POST[valider]) AND !isset($_GET['action'])) {

    $nom = $_POST['Nom'];
    $code = $_POST['Code'];

    $insert = $bdd->prepare("INSERT INTO tblCodeCompta(codeNo, codeNom)
VALUES(:code,:nom)");
    $insert->execute(array(
        'code' => $code,
        'nom' => $nom,

    ));

   header("location: charges.php");
}
// Modification d'un dossier
if (isset ($_POST['valider']) AND isset($_GET['action']) AND $_GET['action']=="Modifier" ) {


    var_dump($_POST);
    $nom = $_POST['Nom'];
    $code = $_POST['Code'];

    $id = $_GET['Id'];

    $insert = $bdd->prepare(" UPDATE tblCodeCompta SET 
    codeNom = :nom,
    codeNo = :codeNo
    
    
    WHERE codeId = '$id'");

    $insert->execute(array(
        'nom' => $nom,
        'codeNo' => $code,


    ));

    header("location: charges.php");
}
// suppression d'un dossier
if (isset ($_POST['Supprimer']) AND isset($_GET['action']) AND $_GET['action']=="Modifier" ) {


    $sql = "DELETE FROM tblCodeCompta where codeId= ".$id;
    $stmt = $bdd->prepare($sql);
    $stmt->execute();

    header("location: charges.php");
}

if(isset ($_POST['annuler'])){

    header("location: charges.php#top");
}

?>
<nav>
    <ul>
        <li>
            <a href="dossier.php"><?= $mrp->getText("Listes des dossiers") ?></a>
        </li>
    </ul>
</nav>
<h1><?= $mrp->getText("Liste des centre de charges") ?></h1>
<form method="post">
    <table>
        <tr>
            <th><?= $mrp->getText("Code") ?> </th>
            <th> <?= $mrp->getText("Description") ?></th>
            <th></th>
        </tr>
        <?php if ($_GET['action'] == "Modifier" AND isset($_GET['action'])) {
            ?> <!-- Modifiaction du dossier -->
            <tr style="border-bottom: solid 1px">
                <td><input name="Code" value="<?php echo $modifDossier['codeNo']; ?>"></td>
                <td><input name="Nom" value="<?php echo $modifDossier['codeNom']; ?>"></td>
                <td>
                    <input type="submit" name="valider" value="Valider" class="ValiderPetit">
                    <input type="submit" name="Supprimer" value="Supprimer" class="AnnulerPetit">
                </td>
            </tr>
        <?php } ?>




        <?php while ($row = $dossier->fetch()) {
            ?>
            <tr>
                <td><?php echo $row['codeNo']; ?></td>
                <td><?php echo $row['codeNom']; ?></td>

                <td><?php echo '<a href="charges.php?Id=' . $row['codeId'] . '&action=Modifier"> Modifier</a>'; ?></td>
            </tr>
            <?php
        }

        if ($_GET['action'] == "Modifier" AND isset($_GET['action'])) {

        } else {
            ?>
            <tr id="Ajout">
                <td><input name="Code"></td>
                <td><input name="Nom"></td>
                <td><input type="submit" name="valider" value="Valider" class="ValiderPetit">
                    <input type="submit" name="annuler" value="Annuler" class="AnnulerPetit"></td>
            </tr>
        <?php } ?>
    </table>
</form>

<?php include('../footer.php'); ?>
