<?php include('../header.php');

$id= $_SESSION[contact];
$don = $_GET[Id];
$bdd = new PDO($dsn,$user,$password); 
$_SESSION[contact] = $id;

$contact = $bdd -> query("SELECT conNom, conPrenom, conNpa, conLocaliter FROM tblContact WHERE conId ='$id'");
$contact = $contact -> fetch();

$historiqueDon = $bdd -> query("SELECT * FROM tblCotisation
	LEFT JOIN tblGenreCoti on tblGenreCot_GenCotId = gerCotiId
	LEFT JOIN tblTypeCotisation on tblTypeCotisation_tCotiId = tCotId
	WHERE tblContact_conId = '$id' AND cotiId != '$don'");

$cotisation = $bdd -> query("SELECT * FROM tblCotisation
	LEFT JOIN tblGenreCoti on tblGenreCot_GenCotId = gerCotiId
	LEFT JOIN tblTypeCotisation on tblTypeCotisation_tCotiId = tCotId
	WHERE cotiId = '$don'");

$lGenre =$bdd ->query("SELECT * FROM tblGenreCoti WHERE gerCotiId <> 2");
$lType =$bdd ->query("SELECT * FROM tblTypeCotisation");


if (isset($_POST['annuler']))
{

header("location: cotisation.php?Id=".$id);

}

if(isset($_POST['valider'])) // Si le formulaire a été validé
{	
	$date = $_POST[dateDon];
	$genre = $_POST[genre];
	$type = $_POST[type];
	$commentaire = $_POST[commentaire];
	$valeur = $_POST[valeur];
	if (!empty($_POST[remercier]) )
	{
		$dateRemerciemnt = ($_POST[remercier]);
		
	}
	else
	{
		$dateRemerciemnt = "";
	}
	$contact = $id;
	$insert = $bdd->prepare("UPDATE tblCotisation SET 
		tblGenreCot_GenCotId =:genre,
		 tblTypeCotisation_tCotiId =:type,
		 cotiCommentaire =:commentaire,
		 cotiDate =:dateDon,
		 cotiValeur =:valeur,
		 cotiRemercier =:remerciement,
		 tblContact_conId =:contact
		 WHERE cotiId = '$don'");


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
?>

<h1> Modification d'une contributions <?php echo $contact[conPrenom].' '.$contact[conNom].', '.$contact[conNpa ].' '.$contact[conLocaliter ] ?></h1>

<!-- Liste des ces cotisation -->

<table border="0">
	<tr>
		<th> Date </th>		
		<th> Genre</th>	
		<th> Source</th>
		<th> Commentaire</th>  
		<th> Montant</th>   
		<th> Remercié le</th> 
		<th></th>       
	</tr>
	<?php while($row = $historiqueDon->fetch()) { ?>
	<tr>

		<td><? echo DateToUser($row[cotiDate])?></td>
		<td><? echo $row['gerCotiNom']; ?></td>
		<td><? echo $row['tCotCode'].' - ' .$row['tCotNom']; ?></td>
		<td><? echo $row['cotiCommentaire']; ?></td>
		<td><? echo $row['cotiValeur']; ?></td>
		<td><? echo DateToUser($row[cotiRemercier])?></td>
		<td> <?php echo '<a href="modifCotisation.php?Id='.$row['cotiId'].'"> Modifier </a>';?>
			<?php echo '<a href="supCotisation.php?Id='.$row['cotiId'].'"> Supprimer </a>';?>
		</td>

	</tr>
	<? }   
	$historiqueDon->closeCursor();  
	?>

	<form name="add_user" method="post">
		<?php while($coti = $cotisation->fetch()) { ?>
		<td><input class="input100" type="date" name="dateDon" value="<?php echo ($coti[cotiDate]);?>"></td>
		<td><select name="genre">
		<?php 
			while ($genre = $lGenre->fetch())
			{
				if($coti['tblGenreCot_GenCotId'] == $genre['gerCotiId'] )
				{
					echo '<option value="'. $genre['gerCotiId'] . '" selected>'. $genre['gerCotiNom'] .'</option>' ;
					
				}
				else
				{			/* afficher l'?ment de la liste comme ?nt selected */
				echo '<option value="'. $genre['gerCotiId'] . '">'. $genre['gerCotiNom'] .'</option>' ;
				}
			}
		?>
		 </select>
		</td>
		<td><select name="type">
		<?php 
			while ($type = $lType->fetch())
			{
				if($coti['tblTypeCotisation_tCotiId'] == $type['tCotId'] )
				{
					echo '<option value="'. $type['tCotId'] . '" selected>'. $type['tCotCode'] .'</option>' ;
					
				}
				else
				{			/* afficher l'?ment de la liste comme ?nt selected */
				echo '<option value="'. $type['tCotId'] . '">'. $type['tCotCode'] .'</option>' ;
				}
			}
		
		?>	</select>
		</td>
		<td><input type="text" name="commentaire" value="<?php echo $coti[cotiCommentaire];?>"></td>
		<td><input class="input100" type="text" name="valeur" value="<?php echo $coti[cotiValeur];?>"></td>
		<td><input class="input100" type="date" name="remercier" value="<?php echo ($coti[cotiRemercier]);?>"></td>
		<td><input type="submit" name="valider" value="valider" class="ValiderPetit">
			<input type="submit" name="annuler" value="annuler" class="AnnulerPetit">
		</td>
		<? }   
		$cotisation->closeCursor();  
		?>
	</form>

</table>



<?php include('../footer.php'); ?>