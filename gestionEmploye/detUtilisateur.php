<?php include('../header.php');

$id = $_GET["Id"];
$bdd = new PDO($dsn, $user, $password);

$sql = 'SELECT * FROM tblEmployer
LEFT JOIN tblAutorisation ON tblAutorisation_autId = autId
LEFT JOIN  tblFonction ON tblFonction_fonId = fonId';
$req = $bdd->prepare($sql . ' WHERE empId =  :id');
$req->execute(['id' => $_GET['Id']]);



?>
    <nav id="menu2">
        <ul>
            <li><a href="gestion.php"><?= $mrp->getText("Retour à la liste des employées") ?></a></li>
            <li><a href="decTravail.php?Type=1&Id=<?php echo $id?>"><?= $mrp->getText("Decompte employé(e)") ?></a></li>
            <li><a href="decTravail.php?Type=2&Id=<?php echo $id?>"><?= $mrp->getText("Ventilation employé(e)") ?></a></li>
            <li><?php echo '<a href="modifUtilisateur.php?Id=' . $id . '">'.$mrp->getText("Modifier l'employé(e)").'</a>'; ?></li>
            <li><?php echo '<a href="droit.php?Id=' . $id . '">'.$mrp->getText("Droits aux absences").'</a>'; ?></li>
        </ul>
    </nav>
    <h1><?= $mrp->getText("modifier l'utilisateur") ?></h1>

        <?php
        while ($donnees = $req->fetch()) {
            ?>
            <table class="affichage">
                <tr>
                    <td> <?= $mrp->getText("Nom") ?> :</td>
                    <td><?php echo($donnees['empNom']); ?></td>
                </tr>
                <tr>
                    <td> <?= $mrp->getText("Prénom") ?> :</td>
                    <td><?php echo($donnees['empPrenom']); ?></td>
                </tr>
                <tr>
                    <td> <?= $mrp->getText("Login") ?> :</td>
                    <td><?php echo($donnees['empLogin']); ?></td>
                </tr>
                <tr>
                    <td> <?= $mrp->getText("Autorisation") ?> :</td>
                    <td><?php echo($donnees['autNom']); ?></td>
                </tr>
                <tr>
                    <td> <?= $mrp->getText("Fonction") ?> :</td>
                    <td><?php echo($donnees['fonNom']); ?></td>
                </tr>
                <tr>
                    <td><?= $mrp->getText("Taux d'activité") ?>:</td>
                    <td><?php echo($donnees['empTaux']); ?></td>
                </tr>
            </table>
        <?php } ?>
<?php include('../footer.php'); ?>