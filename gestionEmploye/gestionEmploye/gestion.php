<?php include('../header.php'); 


try {
    $bdd = new PDO($dsn, $user, $password);
}
catch(Exception $e) {
        die('Erreur : ' . $e->getMessage());
}


$sql = 'SELECT * FROM tblEmployer
LEFT JOIN tblAutorisation ON tblAutorisation_autId = autId
WHERE empStatu = 1';
$req = $bdd->query($sql); 

?>




<div class="contenu">
    <h1> Liste des employés</h1>
    <table border="0" class="affichage">
      <tr>
        <th>Nom </th>
        <th>Prénom </th>
		<th>Login </th>
		<th>Autorisation </th>
        <th>  </th>        
      </tr>
	 

<?php while($row = $req->fetch()) { ?>
<tr>
        <td><?php echo $row['empNom']; ?></td>
        <td><?php echo $row['empPrenom']; ?></td> 
		<td><?php echo $row['empLogin']; ?></td>
		<td><?php echo $row['autNom']; ?></td> 
		<td><?php echo '<a href="detUtilisateur.php?Id='.$row['empId'].'"> Détail</a>';?></td>
	 
		
 
 
 
</tr>
<?php }   
$req->closeCursor();   
?>
</table>
  </div>


<?php include('../footer.php'); ?>

