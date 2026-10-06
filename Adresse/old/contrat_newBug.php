<?php include('../header.php');
	include('../Travail/verifi.php');
	$bdd = new PDO($dsn, $user, $password);
	$id = $_GET['Id'];


	if (isset($_GET['Contrat'])) {
		$contratId = $_GET['Contrat'];
		$contrat = $bdd->query("SELECT * FROM tblContrat where cont_conId = '$id' AND contId != '$contratId'");
		$contratModif = $bdd->query("SELECT * FROM tblContrat WHERE contId = '$contratId'");
		$contratModif = $contratModif->fetch();
	} else {
		$contrat = $bdd->query("SELECT * FROM tblContrat where cont_conId = '$id'");
	}

	$contact = $bdd->query("SELECT conNom,conPrenom,conDateNaissance, conNationalite, conAdresse, conNpa,
conLocaliter, conAvs, conBanque, conAgence, conIban FROM tblContact WHERE conId = '$id'");
	$contact = $contact->fetch();

	if (isset($_POST['Modifier']) and $_POST['Modifier'] = "Valider") {
		$annee = $_POST['annee'];
		$debut = ($_POST['debut']);
		$fin = ($_POST['fin']);

		$signer = $_POST['signer'];

		if (empty($signer)) {
			$signer = NULL;
		} else {
			$signer = $_POST['signer'];
		}


		$insert = $bdd->prepare("UPDATE tblContrat SET
contDebut=:debut,
contFin=:fin,
contSigne=:signer,
contAnnee=:annee
 WHERE contId = '$contratId'");

		$insert->execute(array(
				'debut' => $debut,
				'fin' => $fin,
				'signer' => $signer,
				'annee' => $annee,

		));
		header("location: contrat.php?Id=" . $id);
	}

	if (isset($_POST['Supprimer']) and $_POST['Supprimer'] = "Supprimer") {
		$contId = $_POST['IdContrat'];

		$sql = "DELETE FROM tblContrat where contId= " . $contId;
		$stmt = $bdd->prepare($sql);
		$stmt->execute();
		header("location: contrat.php?Id=" . $id);
	}


	if (isset($_POST['Cree'])) {


		$annee = $_POST['annee'];
		$debut = ($_POST['debut']);
		$fin = ($_POST['fin']);
		$employer = $id;

		$insert = $bdd->prepare('INSERT INTO tblContrat
(cont_conId,contCreation,contDebut,contFin,contAnnee)
		VALUES(:employer, :creation,:debut,:fin,:annee)');

		$insert->execute(array(

				'employer' => $employer,
				'creation' => date('Y-m-d'),
				'debut' => $debut,
				'fin' => $fin,
				'annee' => $annee,

		));
		header("location: contrat.php?Id=" . $id);

	}


?>
<nav id="menu2">
	<ul>
		<li><?php echo '<a href="detContacte.php?conId=' . $id . '"> Retour au contact</a>'; ?></li>

	</ul>
</nav>

<h1>Liste des contrats intervenant avec <?php echo $contact['conPrenom'] . ' ' . $contact['conNom'] ?> </h1>

<style>
	.button
	{
		height: auto;
		width: auto;
		font-size: small;
		padding: 5px;
		color: black;
		background-color: lightgray;
		border-radius: 0px;
		font-weight: bold;
	}
	
	.button:hover
	{
		background-color: lightgreen;
		border: 2px solid blue;
	}
</style>

<table class="affichage">
	<tr>
		<th>Année</th>
		<th>Date du début</th>
		<th>Date de fin</th>
		<th>Signé le</th>
	</tr>


	<?php
		if (isset($contratId)) {
			?>


			<form action="#" method="post">
				<tr>
					<td><input style="width: 25mm" name="annee" value="<? echo $contratModif['contAnnee']; ?>"></td>
					<td><input type="date" class="input150" name="debut" value="<? echo $contratModif['contDebut']; ?>"></td>
					<td><input type="date" class="input150" name="fin" value="<? echo $contratModif['contFin']; ?>"></td>
					<td><input type="date" class="input150" name="signer" value="<? echo $contratModif['contSigne']; ?>"></td>
					<td><input type="submit" value="Valider" name="Modifier" class="ValiderPetit">
						<input type="hidden" name="IdContrat" VALUE="<? echo $contratModif['contId']; ?>">

						<input type="submit" value="Supprimer" name="Supprimer" class="SuprimerrPetit"></td>
				</tr>
			</form>
			<?php while ($row = $contrat->fetch()) { ?>
				<tr>
					<td><? echo($row['contAnnee']); ?> </td>
					<td><? echo DateToUser($row['contDebut']); ?> </td>
					<td><? echo DateToUser($row['contFin']); ?> </td>
					<td><? echo DateToUser($row['contSigne']); ?> </td>

					<td style="background-color: #fff">
						<?php if (!isset($row['contSigne'])) {
							echo '<a href="contrat.php?Id=' . $id . '&Contrat=' . $row['contId'] . '" > Modifier </a>';
						}
						?>
						<?php echo
								'<a class="button fr" href="contratPrint.php?Id=' . $row['contId'] . '" target="_blank">Imprimer en français</a>' .
								'<a class="button all" href="contratPrintAcc.php?Id=' . $row['contId'] . '" target="_blank">Imprimer en allemand</a>';
						?>
					</td>
				</tr>


			<?php }

		} else {

			while ($row = $contrat->fetch()) { ?>

				<tr>
					<td><? echo($row['contAnnee']); ?> </td>
					<td><? echo DateToUser($row['contDebut']); ?> </td>
					<td><? echo DateToUser($row['contFin']); ?> </td>
					<td><? echo DateToUser($row['contSigne']); ?> </td>
					<td style="background-color: #fff">
						<?php if (!isset($row['contSigne'])) {
							echo '<a href="contrat.php?Id=' . $id . '&Contrat=' . $row['contId'] . '" > Modifier </a>';
						}
						?>
						<?php echo
								'<a class="button fr" href="contratPrint.php?Id=' . $row['contId'] . '" target="_blank">Imprimer en français</a>' .
								'<a class="button all" href="contratPrintAcc.php?Id=' . $row['contId'] . '" target="_blank">Imprimer en allemand</a>';
						?>
					</td>
				</tr>
			<?php } ?>
			<form action="#" method="post">
				<tr>
					<td><input style="width: 25mm" name="annee"></td>
					<td><input class="input150" type="date" name="debut"></td>
					<td><input class="input150" type="date" name="fin"></td>
					<td><input type="submit" value="Crée" name="Cree" class="ValiderPetit"></td>
				</tr>
			</form>

		<?php } ?>


</table>
<?php include('../footer.php'); ?>




