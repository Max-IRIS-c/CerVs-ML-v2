<?php 
$formulaireNew =1;
include('../header.php'); ?>

<?php
$contact = $_SESSION[contact];
$id  = $_GET["Id"] ;
$bdd = new PDO($dsn,$user,$password); 
$Donnee = $bdd ->query("SELECT * FROM tblCotisation  WHERE cotiId ='$id'");



if(isset($_POST['annuler'])) // Si le formulaire a été validé
{
header("location: cotisation.php?Id= $contact");	
	
}

if(isset($_POST['submit'])) // Si le formulaire a ? valid?
{



	$sql = "DELETE FROM tblCotisation where cotiId= ".$id;
	$stmt = $bdd->prepare($sql);
	$stmt->execute();		
	header("location: cotisation.php?Id= $contact");
}


?>




<div class="contenu">
	<h1> Suppression d'une saisie  </h1>
	<form name="add_user" method="post">
		<?php 
		while ($donnees = $Donnee->fetch()) 
		{ 
			?> 

			<h1 class="rouge"> Veuillez confirmer la supression de la saisie du <?php echo dateToUser($donnees['cotiDate']);?>  </h1>

			<?php 
		} 
		?> 

		<input type="submit" name="annuler"  value="Annuler" class="Annuler" />
		<input type="submit" name="submit" value="Valider" class="Valider" />
	</div>


</form>

<?php include('../footer.php'); ?>

