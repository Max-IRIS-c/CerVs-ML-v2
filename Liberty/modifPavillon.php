<?php
	include('../variables.php');
	include('./class/locationObject.php');
	
	$id = $_GET['Id'];
	$error = false;
		// Création de la connexion à la base de données avec gestion des erreurs
	try {
		$bdd = new PDO($dsn, $user, $password);
		$bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	} catch (PDOException $e) {
		die('Erreur interne, rechergez la page'); // . $e->getMessage());
	}
	$pavillon = new LocationObject(intval($id));
	$tBien = $bdd->query("SELECT * FROM tblTypeBien");
	
	if(isset($_POST['Valider'])){
		try{
			$unity1 = intval($_POST['logUniterPrix1']) > 0 ? $_POST['logUniterPrix1'] : null;
			$unity2 = intval($_POST['logUniterPrix2']) > 0 ? $_POST['logUniterPrix2'] : null;
			$unity3 = intval($_POST['logUniterPrix3']) > 0 ? $_POST['logUniterPrix3'] : null;
			$unity4 = intval($_POST['logUniterPrix4']) > 0 ? $_POST['logUniterPrix4'] : null;
			$immatriculation = $_POST['immatriculation'] && $_POST['immatriculation'] !== '' ? $_POST['immatriculation'] : null;

			$query = "UPDATE tblLogement SET
				logNom = :logNom,
				logPrix1 = :logPrix1,
				logPrix2 = :logPrix2,
				logPrix3 = :logPrix3,
				logPrix4 = :logPrix4,
				logUniterPrix1 = :logUniterPrix1,
				logUniterPrix2 = :logUniterPrix2,
				logUniterPrix3 = :logUniterPrix3,
				logUniterPrix4 = :logUniterPrix4,
				logLibeller1 = :logLibeller1,
				logLibeller2 = :logLibeller2,
				logLibeller3 = :logLibeller3,
				logLibeller4 = :logLibeller4,
				logPersonneMax = :logPersonneMax,
				logPersonneMin = :logPersonneMin,
				logFontColor = :logFontColor,
				logtype = :logtype,
				logCouleur = :logCouleur,
				propriety = :propriety,
				immatriculation = :immatriculation
				WHERE logId = :logId";
				$stmt = $bdd->prepare($query);
				$stmt->bindParam(':logId', $id);
				$stmt->bindParam(':logNom', $_POST['Nom']);
				$stmt->bindParam(':logPrix1', $_POST['logPrix1']);
				$stmt->bindParam(':logPrix2', $_POST['logPrix2']);
				$stmt->bindParam(':logPrix3', $_POST['logPrix3']);
				$stmt->bindParam(':logPrix4', $_POST['logPrix4']);
				$stmt->bindParam(':logUniterPrix1', $unity1);
				$stmt->bindParam(':logUniterPrix2', $unity2);
				$stmt->bindParam(':logUniterPrix3', $unity3);
				$stmt->bindParam(':logUniterPrix4', $unity4);
				$stmt->bindParam(':logLibeller1', $_POST['logLibeller1']);
				$stmt->bindParam(':logLibeller2', $_POST['logLibeller2']);
				$stmt->bindParam(':logLibeller3', $_POST['logLibeller3']);
				$stmt->bindParam(':logLibeller4', $_POST['logLibeller4']);
				$stmt->bindParam(':logPersonneMax', $_POST['nbrMax']);
				$stmt->bindParam(':logPersonneMin', $_POST['nbrMin']);
				$stmt->bindParam(':logtype', $_POST['logtype']);
				$stmt->bindParam(':logCouleur', $_POST['logCouleur']);
				$stmt->bindParam(':logFontColor', $_POST['logFontColor']);
				$stmt->bindParam(':propriety', $_POST['propriety']);
				$stmt->bindParam(':immatriculation', $immatriculation);
				$stmt->execute();
				header("location: detPavillon.php?Id=" . $id);
				exit;
			}catch(Exception $e){
				$error = true;
			}
		}
		if (isset($_POST['Annuler'])) header("location: liberty.php");
		include('../heade.php'); ?>
	<nav>
		<ul><li><a href="./supObject.php?Id=<?php echo $id; ?>"><?= $mrp->getText("Supprimer l'objet") ?></a><li></ul>
	</nav>
	<h1><?= $mrp->getText("Modifier le bien") ?> <?php echo $pavillon->name ?></h1>
	<table>
		<form method="post">
			<tr>
				<th><?= $mrp->getText("Type de bien") ?></th>
				<td><select name="logtype">
						<?php ListeModif($tBien, $pavillon->type, 'tBienId', 'tBienNom') ?>
					</select></td>
			</tr>
			<tr>
				<th><?= $mrp->getText("Nom") ?></th>
				<td colspan="4"><input name="Nom" value="<?php echo $pavillon->name; ?> "></td>
			</tr>
			<tr>
				<th><?= $mrp->getText("Propriétaire") ?></th>
				<td>
					<select name="propriety">
						<option value="0" <?php if($pavillon->propriety === 'Cerebral') echo 'selected'; ?>>Cerebral</option>
						<option value="1" <?php if($pavillon->propriety === 'Parenthèse') echo 'selected'; ?>>Parenthèse</option>
					</select>
				</td>
			</tr>
			<tr>
				<th><?= $mrp->getText("Nombre de places") ?></th>	
            	<?php echo $pavillon->type === 2 ? '<td>Piétons</td>' : '<td>Min.</td>'; ?>
				<td><input name="nbrMin" value="<?php echo $pavillon->personMin; ?>"></td>
            	<?php echo $pavillon->type === 2 ? '<td>Chaises</td>' : '<td>Max.</td>'; ?>
				<td><input name="nbrMax" value="<?php echo $pavillon->personMax; ?>"></td>
			</tr>
			<?php
				
				foreach($pavillon->prices as $index => $price){ 
					$allUnite = $bdd->query("SELECT * FROM tblUnite");
					$allTypePresta = $bdd->query("SELECT * FROM tblTypePresta");				
					?>
					<tr>
						<th>
							<select 
								name="logLibeller<?php echo $index+1; ?>"
								>
								<?php ListeModif($allTypePresta, $price['idLabel'], 'TprestaId', 'TprestaNom'); ?>
							</select>
						</th>
						<td>	
							<input name="logPrix<?php echo $index+1; ?>" value="<?php echo $price['price'] ?>">
						</td>
						<td>
							<select name="logUniterPrix<?php echo $index+1; ?>">
								<option value=""></option>
								<?php ListeModif($allUnite, $price['idUnity'], 'uniId', 'uniNom') ?>
							</select>
						</td>
					</tr>
				<?php } ?>
				<tr>
					<th><?= $mrp->getText("Couleur") ?></th>
					<td>
						<input type="color" name="logCouleur" value="<?php echo $pavillon->color; ?>" />
					</td>
				</tr>
				<tr>
					<th><?= $mrp->getText("Couleur de la police") ?></th>
					<td>
						<select name="logFontColor">
							<option 
								value="black" 
								<?php echo $pavillon->fontColor === 'black' ? 'selected' : ''; ?>
								>Noir
							</option><option 
								value="white" 
								<?php echo $pavillon->fontColor === 'white' ? 'selected' : ''; ?>
								>Blanc
							</option>
						</select>
					</td>
				</tr>
				<tr>
					<th><?= $mrp->getText("Immatriculation") ?></th>
					<td><input type="text" name="immatriculation" value="<?= $pavillon->immatriculation ?>" /></td>
				</tr>
				<tr>
					<td colspan="2">
						<input type="submit" value="Valider" name="Valider" class="valider">
					</td>
					<td colspan="2">
						<input type="submit" value="Annuler" name="Annuler" class="Annuler">
					</td>
				</tr>
		</form>
	</table>
	<?php if($error){ ?>
		❌ Il y a eu un problème avec la modification
	<?php } ?>

<?php include('../footer.php');