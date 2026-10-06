<?php include('../header.php');


$intervenant  = $_SESSION[intervenant];


$id = $_GET[Id];

$bdd = new PDO($dsn,$user,$password); 
// intervention a modifier 
$intModif = $bdd->query("SELECT * FROM tblIntervention
	LEFT JOIN tblContact on intBeneficiaire = conId
	LEFT JOIN tblGenreServices on intGenre = genSerId
	LEFT JOIN tblTypeServices on intType = tServicesId WHERE intId =' $id' ");
$intModif = $intModif -> fetch ();

// historique
$intervention = $bdd->query("SELECT * FROM tblIntervention
	LEFT JOIN tblContact on intBeneficiaire = conId
	LEFT JOIN tblGenreServices on intGenre = genSerId
	LEFT JOIN tblTypeServices on intType = tServicesId WHERE intIntervenant = '$intervenant'  AND intId != '$id' order by intDate DESC ");

// liste dérouéante 

$beneficiaire = $bdd-> query("SELECT conNom, conPrenom ,conId, conHandicaper FROM tblContact WHERE conHandicaper = 1");
$Genre = $bdd -> query("SELECT * FROM tblGenreServices");
$Type = $bdd -> query("SELECT * FROM tblTypeServices"); 

if (isset($_POST['annuler']))
{

	header("location: intervention.php?Id=".$intervenant);

}

if(isset($_POST['valider'])) // Si le formulaire a été validé


{	
	
	$beneficiaire = $_POST['beneficiaire'];
	$dateInt = ($_POST['date']);
	$debut = $_POST['debut'];
	$fin = $_POST['fin'];
	$facturable = $_POST['facture'];
	$type = $_POST['type'];
	$genre = $_POST['services'];
	$subventioner = $_POST['intSubventioner'];
	$commentaire = $_POST['commentaire']; 



	$insert = $bdd->prepare("UPDATE tblIntervention SET 
		intBeneficiaire =:beneficiaire,
		intDate =:dateInt,
		intDebut =:debut,
		intFin =:fin,
		intFacturable =:facturable,
		intType =:type,
		intGenre =:genre,
		intCommentaire =:commentaire,
		intSubventioner =:subventioner 
		WHERE intId = '$id'");

	$insert->execute(array(

		'beneficiaire' => $beneficiaire,
		'dateInt' => $dateInt,            
		'debut' => $debut,
		'fin' => $fin,
		'facturable' => $facturable,
		'type' => $type,  
		'genre' => $genre,
		'commentaire' => $commentaire,  
		'subventioner' => $subventioner,

		));

	header("location: intervention.php?Id=".$intervenant);
}
?>


<nav id="menu2">
	<ul>
		
	</ul>
</nav>


<table class="affichage">
	<tr>
		<th> Bénéficiaire</th>
		<th> Date</th>
		<th> Début</th>
		<th> Fin</th>
		<th> Facture</th>
		<th> Type d'aide</th>
		<th> Type de services</th>
		<th> Subv.</th>
		<th> Commentaire</th>
		<th> Modifier</th>
	</tr>

	<form name="add_user" method="post">
		<tr>
			<td>
				<select name="beneficiaire" class="input150">
					<?php 
					while ($benfi = $beneficiaire->fetch())
					{
						if($intModif['intBeneficiaire'] == $benfi['conId'] )
						{
							echo '<option value="'. $benfi['conId'] . '" selected>'. $benfi['conNom'].' ' .$benfi['conPrenom'] .'</option>' ;

						}
						else
						{			/* afficher l'?ment de la liste comme ?nt selected */
						echo '<option value="'. $benfi['conId'] . '">'. $benfi['conNom'].' ' .$benfi['conPrenom'] .'</option>' ;
						}
					}
					?>
				</select>
			</td>
			<td><input style="width:130px" type="date" name="date" value="<?php echo ($intModif[intDate]);?>"></td>
			<td><input class="input1" type="text" name="debut" value="<?php echo HeureHhMm($intModif[intDebut]);?>"></td>
			<td><input class="input1" type="text" name="fin" value="<?php echo HeureHhMm($intModif[intFin]);?>"></td>
			<td><input class="input1" type="text" name="facture" value="<?php echo $intModif[intFacturable];?>"></td>
			<td>
				<select name="type">
					<?php 
					while ($serv = $Genre->fetch())
					{
						if($intModif['intGenre'] == $serv['genSerId'] )
						{
							echo '<option value="'. $serv['genSerId'] . '" selected>'. $serv['genSerNom'] .'</option>' ;

						}
						else
						{			/* afficher l'?ment de la liste comme ?nt selected */
						echo '<option value="'. $serv['genSerId'] . '">'. $serv['genSerNom'] .'</option>' ;
						}
					}
					?>	
				</select> 
			</td>
			<td>
				<select name="services" class="input150">
					<?php 
					while ($typ = $Type->fetch())
					{
						if($intModif['intType'] == $typ['tServicesId'] )
						{
							echo '<option value="'. $typ['tServicesId'] . '" selected>'. $typ['tServicesNom'] .'</option>' ;

						}
						else
						{			/* afficher l'?ment de la liste comme ?nt selected */
						echo '<option value="'. $typ['tServicesId'] . '">'. $typ['tServicesNom'] .'</option>' ;
						}
					}

					?>	
				</select> 
			</td>
			<td><input  type="checkbox" value="1" name="intSubventioner"<?php if ($intModif['intSubventioner'] == 1) {?>checked<?php } else {}?>/></td>
			<td><textarea name="commentaire"> <?php echo $intModif['intCommentaire'];?></textarea> </td>	
			<td><input type="submit" name="valider" value="valider" class="ValiderPetit">
				<input type="submit" name="annuler" value="annuler" class="AnnulerPetit"></td>
		</tr>
	</form>

<?php while($row = $intervention->fetch()) { ?>

<tr>
	<td><? echo $row['conNom']." ".$row['conPrenom'] ; ?> </td>
	<td><? echo DateToUser($row['intDate']); ?> </td>
	<td><? echo HeureHhMm($row['intDebut']); ?></td>
	<td><? echo HeureHhMm($row['intFin']); ?> </td>
	<td><? echo $row['intFacturable']; ?> </td>
	<td><? echo $row['genSerNom']; ?>  </td>
	<td><? echo $row['tServicesNom']; ?> </td>
	<td><?php 
		if ($row['intSubventioner'] == 1)
			{echo '<INPUT disabled type="checkbox" name="Parent" value="1" checked>';}
		else
			{echo '<INPUT disabled type="checkbox" name="Parent" value="0" >';}
		?></td>
		<td><? echo $row['intCommentaire']; ?> </td>
		<td><?php echo '<a href="modifIntervention.php?Id='.$row[intId].'"> Modifier </a>';?></td>
	</tr>
	<?php } ?>
</table>




<?php include('../footer.php'); ?>