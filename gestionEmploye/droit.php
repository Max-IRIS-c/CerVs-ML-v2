<?php include('../header.php');
/**
 * Created by PhpStorm.
 * User: Lionel
 * Date: 09/08/2017
 * Description de la page  : gestion des droits au absences
 */
$id = $_GET['Id'];
$bdd = new PDO($dsn, $user, $password);
$employer = $bdd->query("SELECT empNom,empPrenom FROM tblEmployer WHERE empId = '$id'");
$employer = $employer->fetch();
if (isset($_GET['action']) AND ($_GET['action']) == "modifier")
{
    $droitId = $_GET['droitId'];
    $droits = $bdd->query("SELECT * FROM tblDroit WHERE tblEmplyer_empId = '$id' AND droId!='$droitId'");
    $droitsModif = $bdd->query("SELECT * FROM tblDroit WHERE droId='$droitId'");
}
else
{
    $droits = $bdd->query("SELECT * FROM tblDroit WHERE tblEmplyer_empId = '$id'");
}

if (isset($_POST['Supprimer']))
{
    $droitId = $_GET['droitId'];
    $sql = "DELETE FROM tblDroit where droId= ".$droitId;
    $stmt = $bdd->prepare($sql);
    $stmt->execute();



    header("location: droit.php?Id=" . $id);
}

if (isset($_POST['Valider']))
{ // variable pour la modification ou l'ajout
    $annee = $_POST['annee'];
    $vacances = $_POST['vacances'];
    $ferier = $_POST['ferier'];
    $chomer = $_POST['chomer'];
    $materniter = $_POST['materniter'];
    $demenagement = $_POST['demenagement'];
    $deuil = $_POST['deuil'];
    $apg = $_POST['apg'];
    $maladie = $_POST['Maladie'];
    $accident = $_POST['Accident'];
    $mariage = $_POST['Mariage'];
    $soldeReprt = $_POST['soldeReprt'];
    $employer = $id;
    if (isset($_GET['action']) AND ($_GET['action']) == "modifier")
    { // fonction qui modifie
        $droitId = $_GET['droitId'];


       $insert = $bdd->prepare("UPDATE tblDroit SET
	             
			 tblEmplyer_empId = :id,
			 droAPG = :apg,
			 droChomer = :chomer,
			 droDeuil = :deuil,
			 droAnnee =:annee,
			 droFerier =:ferier,
			 droDemenagement =:demenagement,
			 droMaterniter =:materniter,
			 droVacances =:vacances,
			 droMaladie =:maladie,
			 droAccident =:accident,
			 droMariage =:mariage,
			 droDiffe =:diff

			 WHERE droId = '$droitId'");

        $insert->execute(array(
            'id' => $employer,
            'apg' => $apg,
            'chomer' => $chomer,
            'deuil' => $deuil,
            'annee' => $annee,
            'ferier' => $ferier,
            'demenagement' => $demenagement,
            'materniter' => $materniter,
            'vacances' => $vacances,
            'maladie' => $maladie,
            'accident' => $accident,
            'mariage' => $mariage,
            'diff' => $soldeReprt,
        ));
    }
    else
    { // fonction qui ajoute
        $insert = $bdd->prepare('INSERT INTO tblDroit (tblEmplyer_empId, droAPG, droChomer, droDeuil,droAnnee, droFerier,
        droDemenagement , droMaterniter, droVacances,droMaladie,droAccident,droMariage,droDiffe)
	         						 VALUES(:id, :apg, :chomer, :deuil,:annee, :ferier, :demenagement,
	         						  :materniter, :vacances, :maladie, :accident,:mariage,:diffe)');
        $insert->execute(array(
            'id' => $employer,
            'apg' => $apg,
            'chomer' => $chomer,
            'deuil' => $deuil,
            'annee' => $annee,
            'ferier' => $ferier,
            'demenagement' => $demenagement,
            'materniter' => $materniter,
            'vacances' => $vacances,
            'maladie' => $maladie,
            'accident' => $accident,
            'mariage' => $mariage,
            'diffe' => $soldeReprt,
        ));
    }
    header("location: droit.php?Id=" . $id);
}
?>
<nav>
<ul>
    <li> <a href="gestion.php"><?php echo $mrp->getText("Retour à la liste des utilisateurs"); ?></a>   </li>
</ul>
</nav>
<h1><?= $mrp->getText("Droits aux absences payées pour") ?> <?php echo $employer['empNom'].' ' .$employer['empPrenom']?></h1>
<table>
        <tr>
        <td></td>
        <td><?= $mrp->getText("En nbre de jour(s) de") ?>...</td>
    </tr>
    <tr>
        <th><?= $mrp->getText("Année") ?></th>
        <th><?= $mrp->getText("Solde heures année précédente") ?></th>
        <th><?= $mrp->getText("Vacances") ?></th>
        <th><?= $mrp->getText("Fériés") ?></th>
        <th><?= $mrp->getText("Chômés") ?></th>
        <th><?= $mrp->getText("APG") ?></th>
        <th><?= $mrp->getText("Maternité") ?></th>
        <th><?= $mrp->getText("Maladie") ?></th>
        <th><?= $mrp->getText("Accident") ?></th>
        <th><?= $mrp->getText("Déménagement") ?></th>
        <th><?= $mrp->getText("Deuil") ?></th>
        <th><?= $mrp->getText("Mariage") ?></th>

        <th></th>
    </tr>

    <?php while ($row = $droits->fetch()) { ?>

        <tr>
            <td><?php echo substr($row['droAnnee'], 0, 4); ?></td>
            <td><?php echo $row['droDiffe']; ?></td>
            <td><?php echo $row['droVacances']; ?></td>
            <td><?php echo $row['droFerier']; ?></td>
            <td><?php echo $row['droChomer']; ?></td>
            <td><?php echo $row['droAPG']; ?></td>
            <td><?php echo $row['droMaterniter']; ?></td>
            <td><?php echo $row['droMaladie']; ?></td>
            <td><?php echo $row['droAccident']; ?></td>
            <td><?php echo $row['droDemenagement']; ?></td>
            <td><?php echo $row['droDeuil']; ?></td>
            <td><?php echo $row['droMariage']; ?></td>
<td></td>
<td></td>
            <td><?php echo '<a href="droit.php?Id=' . $id . '&action=modifier&droitId=' . $row['droId'] . '"> Modifier</a>'; ?></td>
        </tr>

    <?php } ?>

</table>

<?php

if ($_GET['action'] == 'modifier') { ?>
    <h2> Modifications</h2>
    <form method="post">
    <table>
    <tr>
        <td></td>
        <td>En nbre de jour(s) de...</td>
    </tr>
    <tr>
    <th>Année</th>
    <th>Solde heures<br>année précédente</th>
    <th>Vacances</th>
    <th>Fériés</th>
    <th>Chômés</th>
    <th>APG</th>
    <th>Maternité</th>
    <th>Maladie</th>
    <th>Accident</th>
    <th>Déménagement</th>
    <th>Deuil</th>
    <th>Mariage</th>

    <th></th>
    <?php while ($modif = $droitsModif->fetch()) { ?>
        <tr>
            <td><input class="input1" name="annee" value="<?php echo substr($modif['droAnnee'], 0, 4); ?>"></td>
            <td><input class="input1" name="soldeReprt" value="<?php echo $modif['droDiffe']; ?>"></td>
            <td><input class="input1" name="vacances" value="<?php echo $modif['droVacances']; ?>"></td>
            <td><input class="input1" name="ferier" value="<?php echo $modif['droFerier']; ?>"></td>
            <td><input class="input1" name="chomer" value="<?php echo $modif['droChomer']; ?>"></td>
            <td><input class="input1" name="apg" value="<?php echo $modif['droAPG']; ?>"></td>
            <td><input class="input1" name="materniter" value="<?php echo $modif['droMaterniter']; ?>"></td>
            <td><input class="input1" name="Maladie" value="<?php echo $modif['droMaladie']; ?>"></td>
            <td><input class="input1" name="Accident" value="<?php echo $modif['droAccident']; ?>"></td>
            <td><input class="input1" name="demenagement" value="<?php echo $modif['droDemenagement']; ?>"></td>
            <td><input class="input1" name="deuil" value="<?php echo $modif['droDeuil']; ?>"></td>
            <td><input class="input1" name="Mariage" value="<?php echo $modif['droMariage']; ?>"></td>
<td></td>
<td></td>
            <td><input class="ValiderPetit" type="submit" value="Valider" name="Valider">
                <input class="AnnulerPetit" type="submit" value="Supprimer" name="Supprimer"></td>
        </tr>
        </table>
        </form>
        <?php
    }
} else { ?>
    <h2> Ajouter un droit</h2>
    <form method="post">
        <table>
            <tr>
                <th>Année</th>
                <th>Solde heures<br>année précédente</th>
                <th>Vacances</th>
                <th>Fériés</th>
                <th>Chômés</th>
                <th>APG</th>
                <th>Maternité</th>
                <th>Maladie</th>
                <th>Accident</th>
                <th>Déménagement</th>
                <th>Deuil</th>
                <th>Mariage</th>
                <th></th>
            </tr>
            <tr>
                <td><input class="input1" name="annee"></td>
                <td><input class="input1" name="soldeReprt"></td>
                <td><input class="input1" name="vacances"></td>
                <td><input class="input1" name="ferier"></td>
                <td><input class="input1" name="chomer"></td>
                <td><input class="input1" name="apg"></td>
                <td><input class="input1" name="materniter"></td>
                <td><input class="input1" name="Maladie"></td>
                <td><input class="input1" name="Accident"></td>
                <td><input class="input1" name="demenagement"></td>
                <td><input class="input1" name="deuil"></td>
                <td><input class="input1" name="Mariage"></td>
<td></td>
<td></td>
                <td><input class="ValiderPetit" type="submit" value="Valider" name="Valider"></td>
            </tr>
        </table>
    </form>
    <?php
}
?>
<?php include('../footer.php'); ?>
