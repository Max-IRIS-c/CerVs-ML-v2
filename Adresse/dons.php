<?php
	$pageNum = 9;
	include('../header.php');


	$bdd = new PDO($dsn, $user, $password);
	$lstGenre = $bdd->query("SELECT gerCotiId, concat(gerCotiNom, ' ', gerCotiDescription) as 'Genre' FROM tblGenreCoti"); // selection par genre de cotisation
	
	//$variable = $bdd->query("SELECT gerCotiNom as 'Genre', gerCotId INTO @variable FROM tblGenreCoti");
?>


	<h1><?php echo $mrp->getText("Export des dons") ?></h1>
	<form action="exportDons.php">
		<label> <?php echo $mrp->getText("Du") ?></label>
		<input type="date" name="DateDebut">
		<label><?php echo $mrp->getText("au") ?> </label>
		<input type="date" name="DateFin">
		<label><?php echo $mrp->getText("Genre") ?></label>
		
		<select name="Genre" id="">
			<?
				ListeDeroulante($lstGenre, 'gerCotiId','Genre')
			?>
		</select>
		
		<input type="submit" value="<?php echo $mrp->getText("Valider") ?>" name="Valider" class="ValiderPetit">
	</form>
<?php include('../footer.php');
