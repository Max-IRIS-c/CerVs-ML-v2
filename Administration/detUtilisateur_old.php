<?php include('../header.php');

$id = $_GET["Id"];
$bdd = new PDO($dsn, $user, $password);

$sql = 'SELECT * FROM tblEmployer
LEFT JOIN tblAutorisation ON tblAutorisation.autId = tblEmployer.autId
LEFT JOIN  tblFonction ON tblFonction.fonId = tblEmployer.fonId';
$req = $bdd->prepare($sql . ' WHERE empId =  :id');
$req->execute(['id' => $_GET['Id']]);



?>
    <nav id="menu2">
        <ul>
            <li><a href="utilisateur.php"><?php echo $mrp->getText("Retour à la liste des utilisateurs"); ?></a></li>
            <li><a href="supUtilisateur.php?Id=<?php echo $id; ?>"><?php echo $mrp->getText('Supprimer l\'utilisateur'); ?></a></li>
            <li><a href="modifUtilisateur.php?Id=<?php echo $id; ?>"><?php echo $mrp->getText('Modifier l\'utilisateur'); ?></a></li>
        </ul>
    </nav>
    <h1><?php echo $mrp->getText("Coordonnées de l'utilisateur") ?> </h1>
    
        <?php
        while ($donnees = $req->fetch()) {
            ?>
            <table class="affichage">
                <tr>
                    <td> <?php echo $mrp->getText("Nom") ?> :</td>
                    <td><?php echo($donnees['empNom']); ?></td>
                </tr>
                <tr>
                    <td> <?php echo $mrp->getText("Prénom") ?> :</td>
                    <td><?php echo($donnees['empPrenom']); ?></td>
                </tr>
                <tr>
                    <td> <?php echo $mrp->getText("Login") ?> :</td>
                    <td><?php echo($donnees['empLogin']); ?></td>
                </tr>
                <tr>
                    <td> <?php echo $mrp->getText("Autorisation") ?>:</td>
                    <td><?php echo($donnees['autNom']); ?></td>
                </tr>
                <tr>
                    <td><?php echo $mrp->getText("Fonction") ?>:</td>
                    <td><?php echo($donnees['fonNom']); ?></td>
                </tr>
                <tr>
                    <td> <?php echo $mrp->getText("Taux d'activité") ?>:</td>
                    <td><?php echo($donnees['empTaux']); ?></td>
                </tr>
            </table>


        <?php } ?>




<?php include('../footer.php'); ?>