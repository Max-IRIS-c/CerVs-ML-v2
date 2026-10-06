	<?php	
		$formatedStart = $start->format('Y-m-d');
		$formatedEnd = $end->format('Y-m-d');
	?>
	<nav id="menu2">
		<ul>
			<li>
				<a href="Location.php">
					<?php echo ucfirst($mrp->getText('Liste des locations')); ?>
				</a>
			</li>
			<li class="textGauche">
				<a href="nouvelleLocation.php?Id=">
					<?php echo ucfirst($mrp->getText('Nouvelle location')) ?>
				</a>
			</li>
			<li>
				<a href="liberty.php">
					<?php echo ucfirst($mrp->getText('Objets en location')) ?>
				</a>
			</li>
			<li>
				<a href="./ajoutPavillon.php">
					<?php echo ucfirst($mrp->getText('Nouvel objet en location')) ?></a>
			</li>
		</ul>
	</nav>
	<!--  -->
	<h1 id="planOccupationTest"> <?php echo $mrp->getText('Plan d\'occupation') ?> </h1>
	<div id="boutonBusLogement">
		<a class="button button3" href="planning.php?start=<?php echo $formatedStart; ?>&end=<?php echo $formatedEnd; ?>">
			<?php echo ucfirst($mrp->getText('Planning des bus et logements')) ?>
		</a>
		<a class="button button1" href="planningBus.php?start=<?php echo $formatedStart; ?>&end=<?php echo $formatedEnd; ?>">
			<?php echo ucfirst($mrp->getText('Planning des bus')) ?>
		</a>
		<a class="button button2" href="planningLogement.php?start=<?php echo $formatedStart; ?>&end=<?php echo $formatedEnd; ?>">
			<?php echo ucfirst($mrp->getText('Planning des logements')) ?>
		</a>
		<a class="button button2" style="background-color: orange;" href="planningParenthese.php">Parenthèse</a>
	</div> 
	<form method="POST">
		<fieldset>
			<legend><?php echo $mrp->getText('Choix de la période') ?></legend>
			<label for="debute"><?php echo $mrp->getText('Période de début') ?></label>
			<input type="date" value="<?php echo $formatedStart; ?>" name="debut" id="debute">

			<label for="fin"><?php echo $mrp->getText('Période de fin') ?> : </label>
			<input type="date" value="<?php echo $formatedEnd; ?>" name="fin" id="fin">

			<input type="submit" name="submit" class="Valider" value="Valider">
		</fieldset>
	</form>