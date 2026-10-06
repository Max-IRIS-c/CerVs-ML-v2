<?php 

include('../header.php');
// $idUtilisateur vient depuis une variable de session

$bdd = new PDO($dsn, $user, $password);
//liste déroulante

$historique = $bdd->query("SELECT * FROM tblTravail
 LEFT JOIN tblTraCat1 ON  traCat1 = cat1Id
   LEFT JOIN tblTraCat3 ON  traCat3 = cat3Id
    LEFT JOIN tblTraCat4 ON  traCat4 = cat4Id
    LEFT JOIN tblContact ON  tblContact_conId = conId
    WHERE tblEmployer_empId = '$idUtilisateur' ORDER BY  traDate DESC");
?>

<nav id="menu2">
    <ul>
        <li class="textGauche"><a href="ajoutTravail.php"><?php echo $mrp->getText("Saisie séquences horaires") ?></a></li>
        <li class="textGauche"><a href="periode.php"><?php echo $mrp->getText("Saisie des absences (par jour(s))") ?></a></li>
        <li class="textGauche"><a href="decTravail.php"><?php echo $mrp->getText("Décompte employé(e)") ?> </a></li>
	
        <?php 
	if ($auth>=4)
	{
	?>
	
            <li class="textGauche"><a href="../gestionEmploye/gestion.php"><?php echo $mrp->getText("Gestion des employés") ?> </a></li>

        <?php 
	
	}
	?>

    </ul>
</nav>

<h1><?php echo $mrp->getText("Liste des saisies") ?></h1>

<table class="affichage">
    <tr>
        <th><?php echo $mrp->getText("Date début") ?></th>
        <th><?php echo $mrp->getText("Date fin") ?></th>
        <th><?php echo $mrp->getText("Heure début") ?></th>
        <th><?php echo $mrp->getText("Heure fin") ?></th>
        <th><?php echo $mrp->getText("Total") ?></th>
        <th><?php echo $mrp->getText("Statut") ?></th>
        <th><?php echo $mrp->getText("Code OFAS") ?></th>
        <th><?php echo $mrp->getText("Dossier") ?></th>
        <th><?php echo $mrp->getText("Bénéficiaire") ?></th>
        <th><?php echo $mrp->getText("Bénévole") ?></th>
        <th><?php echo $mrp->getText("Détails") ?></th>
    </tr>
    <?php while ($row = $historique->fetch()) { ?>
        <tr>
            <td><?php echo dateToUser($row['traDate']); ?></td>
            <td><?php echo dateToUser($row['traDateFin']); ?></td>
            <td><?php echo HeureHhMm($row['traDebut']); ?></td>
            <td><?php echo HeureHhMm($row['traFin']); ?></td>
            <td><?php echo HeureHhMm($row['traHeureTot']);; ?></td>
            <td><?php echo $row['cat3Code']; ?></td>
            <td><?php echo $row['cat1Code']; ?></td>
            <td><?php echo $row['cat4Code']; ?></td>
            <td><?php echo $row['conNom'] . " " . $row['conPrenom']; ?></td>
            <td><?php  CheckBox($row['traBenevole']); ?></td>
            <td>
                <?php if (isset ($row['traDateFin'])) {
                    echo '<a href="modifPeriode.php?Id=' . $row['traId'] . '">'.$mrp->getText("Modifier").'</a>';
                } else {
                    echo '<a href="modifTravail.php?Id=' . $row['traId'] . '">'.$mrp->getText("Modifier").' </a>';
                } ?>
            </td>
        </tr>

    <?php } ?>
</table>
<?php include('../footer.php'); ?>