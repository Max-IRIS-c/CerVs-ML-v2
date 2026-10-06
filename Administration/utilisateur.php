<?php include('../header.php'); 


try {
    $bdd = new PDO($dsn, $user, $password);
}
catch(Exception $e) {
        die('Erreur : ' . $e->getMessage());
}

/*
$sql = 'SELECT * FROM tblEmployer
LEFT JOIN tblAutorisation ON tblEmployer.autId = tblAutorisation.autId
WHERE empStatut = 1';
*/
$sql="SELECT * FROM tblEmployer,tblAutorisation WHERE tblEmployer.tblAutorisation_autId= tblAutorisation.autid AND tblEmployer.empStatu=1";

$req = $bdd->query($sql); 



?>

<nav id="menu2">
<ul>

<li><a href="ajoutUtilisateur.php"><?php echo $mrp->getText("Ajouter un utilisateur") ?></a></li>

</ul>
</nav>


<div class="contenu">
    <h1><?php echo $mrp->getText("Liste des utilisateurs") ?></h1>
    <table border="0" class="affichage">
      <tr>
        <th><?php echo $mrp->getText("Nom") ?> </th>
        <th><?php echo $mrp->getText("Prénom") ?> </th>
		<th><?php echo $mrp->getText("Login") ?> </th>
		<th><?php echo $mrp->getText("Autorisation") ?></th>
        <th>  </th>        
      </tr>
	 

<?php while($row = $req->fetch()) { ?>
<tr>
        <td><?php echo $row['empNom']; ?></td>
        <td><?php echo $row['empPrenom']; ?></td> 
		<td><?php echo $row['empLogin']; ?></td>
		<td><?php echo $row['autNom']; ?></td> 
		<td><?php echo '<a href="detUtilisateur.php?Id='.$row['empId'].'">'.$mrp->getText("Détail").'</a>';?></td>
	 
		
 
 
 
</tr>
<?php }   
$req->closeCursor();   
?>
</table>
  </div>


<?php include('../footer.php'); ?>

