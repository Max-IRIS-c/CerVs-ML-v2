<?php include('../header.php');

$id= $_GET['Id'];
$bdd = new PDO($dsn,$user,$password); 
$_SESSION['contact'] = $id;



$contact = $bdd -> query("SELECT conNom, conPrenom, conNpa, conLocaliter, conDonateur,conMembre, conTypeMembre FROM tblContact WHERE conId ='$id'");
$contact = $contact -> fetch();

$historiqueDon = $bdd -> query("SELECT * FROM tblCotisation
	LEFT JOIN tblGenreCoti on tblGenreCot_GenCotId = gerCotiId
	LEFT JOIN tblTypeCotisation on tblTypeCotisation_tCotiId = tCotId
	WHERE tblContact_conId = '$id' ORDER BY cotiDate ASC");
/* Liste déroulante */
$lGenre =$bdd ->query("SELECT * FROM tblGenreCoti WHERE gerCotiId <> 2");
$lType =$bdd ->query("SELECT * FROM tblTypeCotisation ORDER BY tCotCode");
$contactType =$bdd ->query("SELECT * FROM tblMembreType");
$contactGenre  =$bdd ->query("SELECT * FROM tblMembre");

$TotCotisation = $bdd -> query("SELECT sum(cotiValeur) FROM tblCotisation WHERE tblContact_conId ='$id'");
$TotCotisation = $TotCotisation ->fetch();
if(isset($_POST['valider'])) // Si le formulaire a été validé
{		
	$date = $_POST['dateDon'] ;
	$genre = $_POST['genre'];
	$type = $_POST['type'];
	$commentaire = $_POST['commentaire'];
	$valeur = intVal($_POST['valeur']) ?? 0;
	if (!empty($_POST['remercier']) )$dateRemerciemnt = $_POST['remercier'];
	else $dateRemerciemnt = "";
	$contact = $id;
	$insert = $bdd->prepare('INSERT INTO tblCotisation(tblGenreCot_GenCotId, tblTypeCotisation_tCotiId, cotiCommentaire, cotiDate, cotiValeur, cotiRemercier, tblContact_conId)
		VALUES(:genre, :type, :commentaire, :dateDon, :valeur, :remerciement, :contact)');

	$insert->execute(array(
		'genre' => $genre,
		'type' => $type,            
		'commentaire' => $commentaire,
		'dateDon' => $date,
		'valeur' => $valeur,
		'remerciement' => $dateRemerciemnt,  
		'contact' => $contact,
		));
	header("location: cotisation.php?Id=".$id);

}

if(isset($_POST['ValiderContact']))
{
$donateur = $_POST['Donateur'];
$type = $_POST['type'];
$membre = $_POST['membre'];

	$insert = $bdd->prepare("UPDATE tblContact SET 
		 conDonateur =:donateur,
		 conMembre =:membre,
		 conTypeMembre =:type
		 WHERE conId = '$id'");


	$insert->execute(array(

		'donateur' => $donateur,
		'type' => $type,            
		'membre' => $membre,
		
		));

header("location: cotisation.php?Id=".$id);
}
?>



<nav id="menu2">
	<ul><li><?php echo '<a href="detContacte.php?conId='.$id.'">'.$mrp->getText('Retour au contact') .'</a>';?></li>
		
	</ul>
</nav>
<h1><?php echo $mrp->getText('Contributions') ?> - <?php echo $contact['conPrenom'].' '.$contact['conNom'].', '.$contact['conNpa' ].' '.$contact['conLocaliter'] ?></h1>
<!-- Détail de la personne -->
<table class="noMargin" style="margin-top: 10px;">

<form method="POST">
	<tr>
		<th style="width: 100px;"><?php echo $mrp->getText('Membre') ?></th>
		<td style=" width: 50px;"><select name="membre"> <?php
            while ($t = $contactGenre->fetch())
           {
           if($contact['conMembre' ] == $t['memId'] )
				{
				 echo '<option value="'. $t['memId'] . '" selected>'. $mrp->getText($t['memNom']) .'</option>' ;
					
				}
			else
				{			/* afficher l'?ment de la liste comme ?nt selected */
				echo '<option value="'. $t['memId'] . '">'. $mrp->getText($t['memNom']) .'</option>' ;
				}
                }
                ?>
            </select> </td>
        <th style="width: 100px;">Type </th>
		<td><select name="type">
		    <?php 
            while ($type = $contactType->fetch())
            {
            if($contact['conTypeMembre' ] == $type['mTypId'] )
				{
				 echo '<option value="'. $type['mTypId'] . '" selected>'. $mrp->getText($type['mTypNom']) .'</option>' ;
					
				}
			else
				{			/* afficher l'?ment de la liste comme ?nt selected */
				echo '<option value="'. $type['mTypId'] . '">'. $mrp->getText($type['mTypNom']) .'</option>' ;
				}
      		  }
   			 ?>
    		</select></td>
	</tr>
	<tr>
        <th style="width: 150px;" ><?php echo $mrp->getText('Donateur') ?></th>
		<td><?php  CheckBoxModif($contact['conDonateur'],'Donateur')?></td>
        <th style="width: 150px;" ><?php echo $mrp->getText('Total des contributions') ?>: </th>
		<td ><input class="input100"  disabled  name="ADRESSE" value="<?php echo $TotCotisation[0] ;?>"></td>
	</tr>
	<tr>
		<td colspan="4"><input  type="submit" name="ValiderContact" value="Valider" class="ValiderPetit"></td>
	</tr>
	    </form>  
</table>

<!-- Liste des ces cotisation -->



<table border="0">
	<tr>
		<th><?php echo $mrp->getText(' Date') ?> </th>		
		<th><?php echo $mrp->getText(' Genre') ?></th>	
		<th> <?php echo $mrp->getText('Source') ?></th>
		<th> <?php echo $mrp->getText('Commentaire') ?></th>  
		<th> <?php echo $mrp->getText('Montant') ?></th>   
		<th> <?php echo $mrp->getText('Remercié le') ?></th> 
		<th></th>       
	</tr>
	<?php while($row = $historiqueDon->fetch()) { ?>
	<tr>

		<td><? echo DateToUser($row['cotiDate'])?></td>
		<td><? echo $row['gerCotiNom']; ?></td>
		<td><? echo $row['tCotCode'].' - ' .$row['tCotNom']; ?></td>
		<td><? echo $row['cotiCommentaire']; ?></td>
		<td><? echo $row['cotiValeur']; ?></td>
		<td><?  echo DateToUser($row['cotiRemercier'])?></td>
		<td> <?php echo '<a href="modifCotisation.php?Id='.$row['cotiId'].'"> Modifier </a>';?>
			<?php echo '<a href="supCotisation.php?Id='.$row['cotiId'].'"> Supprimer </a>';?>
		</td>

	</tr>
	<? }   
	$historiqueDon->closeCursor();  
	?>
	<form name="add_user" method="post">
		<td><input type="date" class="input100" name="dateDon" value="<?php echo date('Y-m-d'); ?>"></td>
		<td><select name="genre"><?php 
			while ($genre = $lGenre->fetch())
			{	
				// suppression de "versement cervs" sans supprimer les données
				if(intval($genre['gerCotiId']) !== 2){ ?><option value="<?php echo $genre['gerCotiId']; ?>"> <?php echo $mrp->getText($genre['gerCotiNom']); ?></option><?php }
			}
		?> </select></td>
		<td><select name="type"><?php 
			while ($type = $lType->fetch())
			{
				?><option value="<?php echo $type['tCotId']; ?>"> <?php echo $type['tCotCode'].' - ' .$mrp->getText($type['tCotNom']); ?></option><?php
			}
		?>	</select></td>
		<td><input  class="input100" name="commentaire"></td>
		<td><input class="input100"  name="valeur"></td>
		<td><input  type="date" class="input100"  name="remercier" value="<?php echo date('Y-m-d'); ?>"></td>
		<td><input type="submit" name="valider" value="valider" class="ValiderPetit"></td>

	</form>

</table>



<?php include('../footer.php'); ?>