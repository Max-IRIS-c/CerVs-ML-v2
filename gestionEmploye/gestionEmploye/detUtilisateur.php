<?php include('../header.php');

$id = $_GET["Id"];
$bdd = new PDO($dsn, $user, $password);

$sql = 'SELECT * FROM tblEmployer
LEFT JOIN tblAutorisation ON tblAutorisation_autId = autId
LEFT JOIN  tblFonction ON tblFonction_fonId = fonId';
$req = $bdd->prepare($sql . ' WHERE empId =  :id');
$req->execute(['id' => $_GET['Id']]);



?>
    <nav
    <nav id="menu2">
        <ul>

            <li><a href="gestion.php">Retour à la liste des employées</a></li>
            <li><a href="decTravail.php?Type=1&Id=<?php echo $id?>">Decompte employé(e)</a></li>
            <li><a href="decTravail.php?Type=2&Id=<?php echo $id?>">Ventilation employé(e)</a></li>

            <li><?php echo '<a href="modifUtilisateur.php?Id=' . $id . '"> Modifier l\'employé(e)</a>'; ?></li>
            <li><?php echo '<a href="droit.php?Id=' . $id . '"> Droits aux absences</a>'; ?></li>

        </ul>
    </nav>
    <h1> Coordonnées de l'employé(e) </h1>

        <?php
        while ($donnees = $req->fetch()) {
            ?>


            <table class="affichage">
                <tr>
                    <td> Nom :</td>
                    <td><?php echo($donnees['empNom']); ?></td>
                </tr>
                <tr>
                    <td> Prénom :</td>
                    <td><?php echo($donnees['empPrenom']); ?></td>
                </tr>
                <tr>
                    <td> Login :</td>
                    <td><?php echo($donnees['empLogin']); ?></td>
                </tr>
                <tr>
                    <td> Autorisation:</td>
                    <td><?php echo($donnees['autNom']); ?></td>
                </tr>
                <tr>
                    <td> Fonction:</td>
                    <td><?php echo($donnees['fonNom']); ?></td>
                </tr>
                <tr>
                    <td> Taux d'activité:</td>
                    <td><?php echo($donnees['empTaux']); ?></td>
                </tr>
            </table>


        <?php } ?>




<?php include('../footer.php'); ?>