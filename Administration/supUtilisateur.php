<?php include('../header.php'); ?>

<?php
$id  = $_GET["Id"] ;
$bdd = new PDO($dsn,$user,$password); 
$sql = "SELECT * FROM tblEmployer where empId= ".$id;
$req = $bdd->query($sql);



if(isset($_POST['annuler'])) // Si le formulaire a été validé
{
	
	 header("location: utilisateur.php");
}
{
	
}
if(isset($_POST['submit'])) // Si le formulaire a ? valid?
{
      
   
        
		
		$Statu = 0;	
		$date = date("Y-m-d");		
				
				
	 $insert = $bdd->prepare('UPDATE tblEmployer SET	 
	 empStatu = :statu,
	 empSortie = :sortie
	 

	 WHERE empId = "'.$id.'"');
	 $insert->execute(array(			
            'statu' => $Statu,
            'sortie'=>$date,            
														
							));
	
	

     if($insert) echo 'L\'ajout de l\'utilisateur a réussi !'; // Si c'est bon, message OK
	   
       else echo 'L\'ajout de l\'utilisateur a raté !'; // Sinon, on affiche un message d'erreur
			
		
	   	header("location: utilisateur.php");
	   
		
		  }
		  else 
		  {
			
		  }
	 


?>




<div class="contenu">
<h1> <?php echo $mrp->getText("Supprimer l'utilisateur") ?>  </h1>
<form name="add_user" method="post">
<?php 
		while ($donnees = $req->fetch()) 
		{ 
		?> 

		<h1 class="rouge"><?php echo $mrp->getText("Veuillez confirmer la suppression de l'utilisateur") ?> <?php echo $donnees['empLogin'];?></h1>
		 
		<?php 
		} 
		?> 
		 
<input type="submit" name="annuler"  value="<?php echo $mrp->getText("Annuler") ?>" class="Annuler" />
<input type="submit" name="submit" value="<?php echo $mrp->getText("Valider") ?>" class="Valider" />
</div>


</form>

<?php include('../footer.php'); ?>

