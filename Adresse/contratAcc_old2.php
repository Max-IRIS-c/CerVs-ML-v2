<?php include('../header.php');
	include('../Travail/verifi.php');
	$bdd = new PDO($dsn, $user, $password);
	$id = $_GET['Id'];
	/* Va chercher la liste déroulante concernant le type de contrat */
	$tAccompagnant = $bdd->query("SELECT * FROM tblTypeAcc");


	if (isset($_GET['Contrat'])) {
		$contratId = $_GET['Contrat'];

		$contrat = $bdd->query("SELECT * FROM tblContratAcc
 			LEFT JOIN tblTypeAcc on contType = tAccId
         		where conId = '$id'
         		AND contId != '$contratId'");

		$contratModif = $bdd->query("SELECT * FROM tblContratAcc WHERE contId = '$contratId'");

		$contratModif = $contratModif->fetch();
	} else {
		$contrat = $bdd->query("SELECT * FROM tblContratAcc
								LEFT JOIN tblTypeAcc on contType = tAccId where conId = '$id'");
	}

	$contact = $bdd->query("SELECT conNom,conPrenom,conDateNaissance, conNationalite, conAdresse, conNpa,
conLocaliter, conAvs, conBanque, conAgence, conIban FROM tblContact WHERE conId = '$id'");
	$contact = $contact->fetch();

	/* Créer ou modifier le contrat : */
	if (isset($_POST['Modifier']) and $_POST['Modifier'] = "Valider") {
		$annee = $_POST['annee'];
		$type = ($_POST['type']);
		$salaire = ($_POST['salairBrut']);
		$chauffeur = ($_POST['salairChauffeur']);
		$signer = $_POST['signer'];

		/* FC */
		// $employer = $id;
		/* FC */

		if (empty($signer)) {
			$signer = NULL;
		} else {
			$signer = $_POST['signer'];
		}


		$insert = $bdd->prepare("UPDATE tblContratAcc SET
			contType=:type,
			contSalaireBruit=:brut,
			contSalaireChauffeur=:Chauffeur,
			contAnnee=:annee,
			contSigner =:signer,
			contModification =:modifier
			
			 WHERE contId = '$contratId'");

		$insert->execute(array(
				'type' => $type,
				'brut' => $salaire,
				'Chauffeur' => $chauffeur,
				'annee' => $annee,
				'signer' => $signer,
				'modifier' => date('Y-m-d'),

		));
		header("location: contratAcc.php?Id=" . $id);
	}

	/* Supprimer l'entrée */
	if (isset($_POST['Supprimer']) and $_POST['Supprimer'] = "Supprimer") {
		$contId = intval($_POST['contId']);

		// $pdo->prepare("DELETE FROM users WHERE id=?")->execute([$id]);


		$supprimer = "DELETE FROM tblContratAcc where contId = $contratId";
		$stmt = $bdd->prepare($supprimer);
		$stmt->execute([$supprimer]);
		header("location: contratAcc.php?Id=" . $id);

		echo "<h2 style='font-weight: bold'>$supprimer</h2>";

		?>
		<?php
	}


	if (isset($_POST['Ajouter'])) {

		$annee = $_POST['annee'];
		$type = ($_POST['type']);
		$salaire = ($_POST['salairBrut']);
		$chauffeur = ($_POST['salairChauffeur']);
		$employer = $id;

		$insert = $bdd->prepare('INSERT INTO tblContratAcc
(conId,contCreation,contType,contSalaireBruit,contSalaireChauffeur,contAnnee)
		VALUES(:employer, :creation,:type, :salaireBrut, :salaireChauffeur,:annee)');

		$insert->execute(array(

				'employer' => $employer,
				'creation' => date('Y-m-d'),
				'type' => $type,
				'salaireBrut' => $salaire,
				'salaireChauffeur' => $chauffeur,
				'annee' => $annee,

		));
		header("location: contratAcc.php?Id=" . $id);
	}

	session_regenerate_id;

?>
<nav id="menu2">
	<ul>
		<li><?php echo '<a href="detContacte.php?conId=' . $id . '"> Retour au contact</a>'; ?></li>

	</ul>
</nav>

<h1>Liste des contrats accompagnant signés pour <?php echo $contact['conNom'] . ' ' . $contact['conPrenom'] ?> </h1>


<table class="affichage">
	<tr>
		<th>Annee</th>
		<th>Type de contrat</th>
		<th>Salaire brut</th>
		<th>Salaire Chauffeur</th>
		<th>Contrat signé le</th>
		<th></th>
	</tr>
	
	<?php
		if (isset($contratId)) {
			?>
			<form action="#" method="post">
				<tr>
					<td><input style="width: 25mm" name="annee" value="<? echo $contratModif['contAnnee']; ?>"></td>
					<td><select name="type" id=""> <?php ListeModif($tAccompagnant, $contratModif['contType'], tAccId, tAccNom); ?></select></td>
					<td><input style="width: 25mm" name="salairBrut" value="<? echo $contratModif['contSalaireBruit']; ?>"></td>
					<td><input style="width: 25mm" name="salairChauffeur" value="<? echo $contratModif['contSalaireChauffeur']; ?>"></td>
					<td><input style="width: 35mm" name="signer" TYPE="date" value="<? echo($contratModif['contSigne']); ?>"></td>
					<td style="background-color: #fff"> <?php // if(!isset($row['contSigner']))
							echo "<a class='Annuler' href='contratAcc.php?Id=$id'>Annuler</a>";
							echo "<input class='ValiderNew' type='submit' value='Valider' name='Modifier' class='ValiderPetit'>";
							//echo '<a class="ValiderNew" href="contratAcc.php?Id=' . $id . '&Contrat=' . $row['contId'] . '" >Valider</a>';
							echo "<input type='submit' value='Supprimer' name='Supprimer' class='Supprimer''>";
							
							?>
					</td>
				</tr>
			</form>

			<?php while ($row = $contrat->fetch()) { ?>
				<tr>
					<td><? echo($row['contAnnee']); ?> </td>
					<td><? echo $row['tAccNom'] ?> </td>
					<td><? echo $row['contSalaireBruit']; ?> / jour</td>
					<td><? echo $row['contSalaireChauffeur']; ?> / jour</td>
					<td><? echo dateToUser($row['contSigner']); ?></td>
					<td style="background-color: #fff">
						<div id="btZone">
							<!-- Imprimer -->
							<a class="Imprimer bt" href="contratPrintAcc.php?Id=<?php echo $row['contId']; ?>" target="_blank">Imprimer</a>
							<!-- Modifier -->
							<a class="Modifier bt" href="contratAcc.php?Id=<?php echo $id; ?>&Contrat=<?php echo $row['contId']; ?>">Modifier</a>
							<!-- Supprimer -->
							<form method="post" action="contratAcc.php?Id=<?php echo $id; ?>" style="display:inline;">
								<input type="hidden" name="contId" value="<?php echo $row['contId']; ?>">
								<input type="submit" value="Supprimer" name="Supprimer" class="Supprimer bt">
							</form>
						</div>
					</td>
				</tr>


			<?php }

		} else {

			while ($row = $contrat->fetch()) { ?>

				<tr>
					<td><? echo($row['contAnnee']); ?> </td>
					<td><? echo $row['tAccNom'] ?> </td>
					<td><? echo $row['contSalaireBruit']; ?> / jour</td>
					<td><? echo $row['contSalaireChauffeur']; ?> / jour</td>
					<td><? echo dateToUser($row['contSigner']); ?></td>
					<td style="background-color: #fff">
						<?php if (!isset($row['contSigner'])) {
							echo '<a class="Modifier bt" href="contratAcc.php?Id=' . $id . '&Contrat=' . $row['contId'] . '" >Modifier </a>';
						}
						?>
						
						<?php
							echo "<div id='btZone'>"; 
							//echo '<a class="Modifier" href="contratAcc.php?Id=' . $id . '&Contrat=' . $row['contId'] . '" >Modifier</a>';
							//echo '<a class="Imprimer" href="contratPrintAcc.php?Id=' . $row['contId'] . '" target="_blank"> Imprimer </a>'; 
							//echo "<input type='submit' value='Supprimer' name='Supprimer' class='Supprimer'>";
						?><div id="btZone">
							<!-- Modifier -->
							<a class="Modifier bt" href="contratAcc.php?Id=<?php echo $id; ?>&Contrat=<?php echo $row['contId']; ?>">Modifier</a>
							<!-- Imprimer -->
							<a class="Imprimer bt" href="contratPrintAcc.php?Id=<?php echo $row['contId']; ?>" target="_blank">Imprimer</a>
							<!-- Supprimer -->
							<form method="post" action="contratAcc.php?Id=<?php echo $id; ?>" style="display:inline;">
								<input type="hidden" name="contId" value="<?php echo $row['contId']; ?>">
								<input type="submit" value="Supprimer" name="Supprimer" class="Supprimer bt">
							</form>
						</div>
						<?php	
							echo '</div>';
						?>
					</td>
				</tr>
			<?php } ?>


			<form action="contratAcc.php?Id=<?php echo $id ?>" method="post">
				<tr>
					<td><input style="width: 25mm" name="annee"></td>
					<td><select name="type"> <?php ListeDeroulante($tAccompagnant, tAccId, tAccNom) ?></select></td>
					<td><input class="input150" name="salairBrut"></td>
					<td><input class="input150" name="salairChauffeur"></td>
					<td><input type="submit" value="Ajouter" name="Ajouter" class="ValiderNew"></td>
					<?php // echo "<td><a class='ValiderPetit2' href='contratAcc.php?Id=$id'>Recharger la page</a></td> "?>
				</tr>
			</form>

		<?php } ?>
</table>
<style>
	table {
		margin-bottom: 0em;

	}

	#btZone{
		display: flex;
		gap: 5px;
		align-items: center;
	}
	td
	{
		height: 2em;
	}
	.Modifier{
		background-color: #1e88e5;
		color: white;
		padding: 0;
		margin: 0;
		text-align: center;
		vertical-align: middle;
	}
	.Supprimer{
		background-color: #e53935;
		color: white;
		padding: 5px;
		border: none;
	}
	.ValiderNew,
	.Imprimer{
		background-color: #43a047;
		color: white;
		padding: 5px;
	}

	.Annuler {
		border: 2px solid black;
		background-color: lightblue;
		border-radius: 10px;
		padding: 0em 1em 0em 1em;
		font-weight: bold;

		width: 8em;
		margin-right: 0.5em;
	}

	.Annuler:hover {
		background-color: blue;
		color: white;
	}


</style>

<script>
	var form = document.getElementById('Supprimer');

	function alerteMessage() {
		$("Supprimer").click(function () {
					alert("élément supprimer !")
				}
		)
		/*
		if (form.checkValidity())
		{
			alert("élément supprimer !")
			return true;
		}
		*/
	}
</script>
<?php include('../footer.php'); ?>




