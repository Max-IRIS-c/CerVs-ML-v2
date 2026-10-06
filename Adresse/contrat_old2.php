<?php 
	ob_start();
	include('../header.php');
	include('../Travail/verifi.php');
	/** classes */
	include('../src/class/contrats-gestion.php');
	include_once "../src/class/Db.class.php";
	include_once "../src/class/Mrp.class.php";
	$mrp = new Mrp();

	$bdd = new PDO($dsn, $user, $password);
	$id = $_GET['Id'];
	$searchedParam = $_GET['searchedParam'];
	$errorPDFload = false;
	$sucessPDFload = false;
	//include('../variables.php');

	/*** enregistrement d'un contrat signé dans le dossier ./contractsFiles */
	if(isset($_POST['submit']) && isset($_FILES['newPDF'])){
		try{
			if(!$_POST['idContrat']) throw new Exception();	
			$record = Contract::recordNewPDFfile('intervenant', $_POST['idContrat'], $_FILES['newPDF']);
			if(!$record) throw new Exception();
			else $sucessPDFload = true;
			//header('location: contrat.php?Id='.$id.'&searchedParam='.$searchedParam);
		}catch(Exception $e){
			$errorPDFload = true;
		}
	}
	
	if (isset($_GET['Contrat'])) {
		$contratId = $_GET['Contrat'];
		$contrat = $bdd->query("SELECT * FROM tblContrat where cont_conId = '$id' AND contId != $contratId");
		//$contrat = $bdd->query("SELECT * FROM tblContrat where cont_conId = '$id' AND contId != '$contratId'C");
		$contratModif = $bdd->query("SELECT * FROM tblContrat WHERE contId = '$contratId' ");
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
		header("location: contrat.php?Id=" . $id."&searchedParam=".$searchedParam);
		exit;
	}
	
	/* Supprimer l'entrée */
	if (isset($_POST['Supprimer']) and $_POST['Supprimer'] = "Supprimer") {
		$contId = $_POST['IdContrat'];

		$sql = "DELETE FROM tblContrat where contId= " . $contId;
		$stmt = $bdd->prepare($sql);
		$stmt->execute();
		header("location: contrat.php?Id=" . $id."&searchedParam=".$searchedParam);
		exit;
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
		header("location: contrat.php?Id=" . $id."&searchedParam=".$searchedParam);
		exit;
	}
	/*** suppression d'un contrat signé dans le dossier ./contractsFiles */
	$errorPDFDelete = false;
	$successPDFDelete = false;
	if(isset($_POST['deletePDF'])){
		try{   
			if(!$_POST['idContrat']) throw new Exception();	
			$delete = Contract::deletePDF('intervenant', $_POST['idContrat']);
			if(!$delete) throw new Exception();
			$successPDFDelete = true;
		}catch(Exception $e){
			$errorPDFDelete = true;
		}
	}
	// ----- Traitement de suppression -----
	if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['Supprimer'])) {
		$contId = intval($_POST['contId'] ?? 0);
		$stmt = $bdd->prepare("DELETE FROM tblContratAcc WHERE contId = ?");
		if ($stmt->execute([$contId])) {
			$message = "✅ Contrat supprimé avec succès.";
		} else {
			$message = "❌ Erreur lors de la suppression du contrat.";
		}
	}
	/*** suppression d'un contrat signé dans le dossier ./contractsFiles */
	$errorPDFDelete = false;
	$successPDFDelete = false;
	if(isset($_POST['deletePDF'])){
    try{   
        if(!$_POST['idContrat']) throw new Exception();	
        $delete = Contract::deletePDF('intervenant', $_POST['idContrat']);
        if(!$delete) throw new Exception();
        $successPDFDelete = true;
    }catch(Exception $e){
        $errorPDFDelete = true;
    }
}


?>
<nav id="menu2">
	<ul>
		<li><?php echo '<a href="detContacte.php?conId=' . $id .'&searchedParam='.$searchedParam.'"> Retour au contact</a>'; ?></li>

	</ul>
</nav>

<h1>Liste des contrats intervenant avec <?php echo $contact['conPrenom'] . ' ' . $contact['conNom'] ?> </h1>

<?php if($errorPDFload) { ?>
		<p>
			⚠️ Le fichier n a pas pu être enregistré pour quelques raisons: </br>
			1) Vérifiez que vous avez bien séléctionné un fichier pdf valide. </br>
			2) Si oui, cliquez sur "Retour au contact", puis revenez sur cette page afin de réessayer.</br>
			3) Si le problème persiste, contactez le développeur.
		</p>
<?php } ?>
<?php if($sucessPDFload) { ?>
		<p class="success"> ✅ Le fichier a bien été enregistré</p>
<?php } 
    if($errorPDFDelete){ ?>
        <p>⚠️ Il y a eu un soucis dans la suppression du pdf </p>
   <?php    }
    if($successPDFDelete){ ?>
        <p> ✅ Le fichier a bien été supprimé </p>
   <?php } ?>
<style>
	.delete{
        background-color: #e53935;
        color: white;
        border: none;
        padding: 7px;
    }
    .delete:hover{
        cursor: pointer;
    }
	.success{
		width: 100%;
		padding: 15px;
		background-color: #c5e1a5;
		color: #2e7d32;
	}
	.button
	{
		height: 1em;
		width: auto;
		font-size: small;
		padding: 5px;
		color: black;
		background-color: lightgray;
		border-radius: 0px;
		font-weight: bold;
		margin-top: 0px;
		margin-bottom: 0px;
	
		
	}
	
	.button:hover
	{
		background-color: lightgreen;
		border: 2px solid blue;
	}
	.signed{
		width: 100%;
		display: flex;
		justify-content: space-around;
        align-items: center;
	}
	.signed input{
		height: 100%;
	}
	.sub{
		background-color: #3384e5;
		color: white;
		border: none;
		padding: 5px;
		margin-top: 5px;
	}
	.bt{
        padding: 5px 10px;
        color: white;
    }
    .blue{
        background-color: #1e88e5;
    }
    .green{
        background-color: #43a047;
    }
    .red{
        background-color: #e53935 ;
    }
</style>

<table class="affichage">
	<tr>
		<th>Année</th>
		<th>Date du début</th>
		<th>Date de fin</th>
		<th>Signé le</th>
		<th>Contrat non-signé</th>
		<th>Contrat signé</th>
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
						<?php if ($mrp->language == "fr")
						{
							echo '<a class="bt blue" href="contratPrint.php?Id=' . $row['contId'] . '" target="_blank">Imprimer</a>';
						}elseif ($mrp->language == "de"){
							echo '<a class="bt blue" href="contratPrintAcc.php?Id=' . $row['contId'] . '" target="_blank">Drucken</a>';
						} ?>
					</td>					
					<td><a href="contrat.php?Id='<?= $id.'&Contrat='.$row['contId'].'&searchedParam='.$searchedParam; ?>" class="bt green"> Modifier </a>
					<?php 
						$contractPDF = Contract::signedContrat('intervenant', intval($row['contId']));
						if(!$contractPDF['exist']) { ?>
							<td>
								<form method="post" enctype="multipart/form-data">
									<input type="hidden" name="idContrat" value="<?php echo $row['contId']; ?>" />
									<input id="newPDF" name="newPDF" type="file" accept=".pdf" />
									<button class="sub" type="submit" name="submit" value="submit">Enregistrer</button>
								</form>
							</td>
							<?php }elseif($contractPDF['exist']) { ?> 
								<td class="signed">
									<a class="button all" onclick="window.open('<?php echo './intervenant_contractsFiles/'.$contractPDF['url']; ?>', '_blank')">PDF</a>
									<form method="post">
										<input type="hidden" name="idContrat" value="<?php echo $row['contId']; ?>"/>
										<button class="delete" name="deletePDF" value="1">Supprimer</button>
									</form>
								</td>
						<?php } 
					?>
				</tr>
			<?php }

		} else {

			while ($row = $contrat->fetch()) { ?>

				<tr>
					<td><? echo($row['contAnnee']); ?> </td>
					<td><? echo DateToUser($row['contDebut']); ?> </td>
					<td><? echo DateToUser($row['contFin']); ?> </td>
					<td><? echo DateToUser($row['contSigne']); ?> </td>
					<td><?php 
							echo '<a href="contrat.php?Id=' . $id . '&Contrat=' . $row['contId'] . '&searchedParam='.$searchedParam.'" > Modifier </a>';
						if ($mrp->language == "fr"){
							echo '<a class="button fr" href="contratPrint.php?Id=' . $row['contId'] . '" target="_blank">Imprimer</a>';								
						}
						elseif ($mrp->language == "de"){
							echo '<a class="button all" href="contratPrintAcc.php?Id=' . $row['contId'] . '" target="_blank">Drucken</a>';
						}
					?></td>
					<?php 
						$contractPDF = Contract::signedContrat('intervenant', intval($row['contId']));
						if(!$contractPDF['exist']) { ?>
							<td>
								<form method="post" enctype="multipart/form-data">
									<input type="hidden" name="idContrat" value="<?php echo $row['contId']; ?>" />
									<input id="newPDF" name="newPDF" type="file" accept=".pdf" />
									<button class="sub" type="submit" name="submit" value="submit">Enregistrer</button>
								</form>
							</td>
							<?php }elseif($contractPDF['exist']) { ?> 
								<td class="signed">
									<a class="bt blue" onclick="window.open('<?php echo './intervenant_contractsFiles/'.$contractPDF['url']; ?>', '_blank')">PDF</a>
									<form method="post">
										<input type="hidden" name="idContrat" value="<?php echo $row['contId']; ?>"/>
										<button class="delete" name="deletePDF" value="1">Supprimer</button>
									</form>
								</td>
						<?php } ?>
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
<?php 
	include('../footer.php'); 
	ob_end_flush();
?>
<script>
	addEventListener("load", (event) => { 
		const newPDF = document.getElementById('newPDF')
	 })
</script>




