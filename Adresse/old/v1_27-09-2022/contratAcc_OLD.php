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


	/* Créer le contrat : */
	if (isset($_POST['Modifier']) and $_POST['Modifier'] = "Valider") {
		$annee = $_POST['annee'];
		$type = ($_POST['type']);
		$salaire = ($_POST['salairBrut']);
		$chauffeur = ($_POST['salairChauffeur']);
		$signer = $_POST['signer'];

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
		echo "!!!";

		$contId = $_POST['contId'];
		$supprimer = "DELETE FROM tblContratAcc where contId = $contId";
		$stmt = $bdd->prepare($supprimer);
		$stmt->execute();
		header("location: contratAcc.php?Id=" . $id);

		?>
		<script>
			//var MyJSStringVar = "<?php //Print($id); ?>";
			// location.replace("contratAcc.php?Id=" + MyJSStringVar);
		</script>
		<?php
	}


	if (isset($_POST['Cree'])) {

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


?>
<nav id="menu2">
	<ul>
		<li><?php echo '<a href="detContacte.php?conId=' . $id . '">' . $mrp->getText("Retour au contact") . '</a>'; ?></li>

	</ul>
</nav>

<h1><?php echo $mrp->getText("Liste des contrats") . ' ' . $mrp->getText("accompagnant") . ' ' . $mrp->getText("signés") . ' ' . $mrp->getText("pour") ?><?php echo $contact['conNom'] . ' ' . $contact['conPrenom'] ?> </h1>


<table class="affichage">
	<tr>
		<th><?php echo $mrp->getText("Annee") ?></th>
		<th><?php echo $mrp->getText("Type de contrat") ?></th>
		<th><?php echo $mrp->getText("Salaire brut") ?></th>
		<th><?php echo $mrp->getText("Salaire Chauffeur") ?></th>
		<th><?php echo $mrp->getText("Contrat signé le") ?> </th>
		<th></th>
	</tr>


	<?php
		if (isset($contratId)) {
			?>


			<form action="#" method="post">
				<tr>
					<td><input style="width: 25mm" name="annee" value="<? echo $contratModif['contAnnee']; ?>"></td>
					<td><select name="type"
					            id=""> <?php ListeModif($tAccompagnant, $contratModif['contType'], tAccId, tAccNom); ?></select>
					</td>
					<td><input style="width: 25mm" name="salairBrut"
					           value="<? echo $contratModif['contSalaireBruit']; ?>"></td>
					<td><input style="width: 25mm" name="salairChauffeur"
					           value="<? echo $contratModif['contSalaireChauffeur']; ?>"></td>
					<td><input style="width: 35mm" name="signer" TYPE="date"
					           value="<? echo($contratModif['contSigne']); ?>"></td>
					<td><input type="submit" value="Valider" name="Modifier" class="ValiderPetit">

						<input type="submit" value="Supprimer" name="Supprimer" class="SuprimerrPetit"></td>
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
						<?php if (!isset($row['contSigner'])) {
							echo '<a href="contratAcc.php?Id=' . $id . '&Contrat=' . $row['contId'] . '" >' . $mrp->getText("Modifier") . ' </a>';
						}
						?>
						<?php echo '<a href="contratPrintAcc.php?Id=' . $row['contId'] . '" target="_blank">' . $mrp->getText("Imprimer") . '</a>'; ?></td>
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
							echo '<a href="contratAcc.php?Id=' . $id . '&Contrat=' . $row['contId'] . '" > ' . $mrp->getText("Modifier") . '  </a>';
						}
						?>
						<?php echo '<a href="contratPrintAcc.php?Id=' . $row['contId'] . '" target="_blank"> ' . $mrp->getText("Imprimer") . ' </a>'; ?></td>
				</tr>
			<?php } ?>
			<form action="#" method="post">
				<tr>
					<td><input style="width: 25mm" name="annee"></td>
					<td><select name="type"> <?php ListeDeroulante($tAccompagnant, tAccId, tAccNom) ?></select></td>
					<td><input class="input150" name="salairBrut"></td>
					<td><input class="input150" name="salairChauffeur"></td>
					<td><input type="submit" value="Crée" name="<?php echo $mrp->getText('Cree') ?>"
					           class="ValiderPetit"></td>
				</tr>
			</form>

		<?php } ?>


</table>
<?php include('../footer.php'); ?>




