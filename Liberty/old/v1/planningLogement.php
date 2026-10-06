<?php
	$pageNum = 32;

	include('../variables.php');
	include('../heade.php');
	include('../fonctionReservation.php');


	setlocale(LC_TIME, 'fra_fra');

	if (empty($_POST)) {
		$start = date('Y-m-d');
		$end = date('Y-m-d', strtotime($start . "+10 weeks"));
	} else {
		$start = $_POST['debut'];
		$end = $_POST['fin'];
	}


	setlocale(LC_TIME, 'fr_FR.utf8', 'fra');
	$bdd = new PDO($dsn, $user, $password);
	$debut = new DateTime($start);
	$debutSting = strtotime($start);
	$finSting = strtotime($end);
	$fin = new DateTime($end);
	$interval = DateInterval::createFromDateString('1 day');
	$period = new DatePeriod($debut, $interval, $fin);

	// requête
	$pavillon = $bdd->query("
								SELECT logId,logNom
								FROM tblLogement
								WHERE logNom LIKE '%Logement%'");

	// requête
	$req = $bdd->query("SELECT datediff(locDateDep,locDateEnt), locStatu, locPavId, locDateEnt FROM tblLocation");


?>
<nav id="menu2">
	<ul>
		<li>
			<a id="testListeLocation" href="Location.php">
				<!-- Liste des locations // -->
				<?php echo $mrp->getText('Liste des locations') ?>
			</a>
		</li>
		<li class="textGauche"><?php echo '<a href="nouvelleLocation.php?Id=">Nouvelle location </a>'; ?></li>

		<li><a href="liberty.php">Objets en location </a></li>
	</ul>
</nav>

<h1 id="planOccupationTest"> <?php echo $mrp->getText('Plan d\'occupation') ?> </h1>
<div id="boutonBusLogement" style="display: flex; justify-content: center; font-weight: bold;">
	<a class="button button1" href="planningBus.php">Planning des Bus</a>
	<a class="button button2" href="planningLogement.php">Planning des Logement</a>
</div>


<table class="noMargin" style="margin-bottom: -10px;">
	<form method="POST">
		<td style="vertical-align: middle"> <?php echo $mrp->getText('Période du') ?></td>
		<td style="vertical-align: middle"><input type="date" name="debut" id="debute"></td>
		<td style="vertical-align: middle"> <?php echo $mrp->getText('au') ?></td>
		<td style="vertical-align: middle"><input type="date" name="fin" id="fin"></td>

		<td style="vertical-align: middle"><input type="submit" name="submit" value="Valider" class="Valider"</td>
	</form>
</table>


<table class="reservation" style='height: 3em; width: 100%'>
	<tr style='width: auto;'>
		
		<th id="reservation-th-date"><?php echo $mrp->getText('Date') ?></th>
		
		<?php
			$nbrLog = 1; // initialise le nombre de logement
			while ($log = $pavillon->fetch()) {
				?>

				<th style="width: 50px;">
					<?php echo $log['logNom'] ?>
				</th>
				<?php $nbrLog++;
			} ?>
	</tr>

	<?php
		$date = $debutSting;
		for ($i = $debut; $i <= $fin; $i->modify('+1 day')) {
			$jour = date('N', $date); // indique le jour ( 1 = Dimanche )
			$dateJour = date('d', $date);
			$semaine = date('W', $date);
			$mois = strftime('%B', $date);
			$colspan = $nbrLog;

			if ($jour == 1) {
				echo "<tr>
<td style='background-color: #a6c9ff; height: 2px'>" . $mrp->getText("semaine") . " $semaine</td>

<td style='background-color: #a6c9ff; height: 2px; width: 13%;'>Source & Oasis</td>
<td style='background-color: #a6c9ff; height: 2px; width: 10%;'>Source</td>
<td style='background-color: #a6c9ff; height: 2px; width: 10%;'>Oasis</td>

<!--
<td style='background-color: #a6c9ff; height: 2px; width: 12%;'>Pacifique</td>
<td style='background-color: #a6c9ff; height: 2px; width: 10%;'>Liberty</td>
<td style='background-color: #a6c9ff; height: 2px; width: 10%;'>Désiré</td>
<td style='background-color: #a6c9ff; height: 2px; width: 10%;'>Destiny</td>
<td style='background-color: #a6c9ff; height: 2px; width: 7%;'>Peugeot</td>
<td style='background-color: #a6c9ff; height: 2px; width: 7%;'>Mercedes</td>
-->

</tr>";
			}

			if ($dateJour == 1) {
				echo "<tr> <th colspan='$colspan' > $mois</th></tr>";
			}


			?>
			<tr>
				<td style="text-align: left"><?php echo(strftime(" %a %d.%m.%G", $date)) ?></td>
				<? for ($l = 1; $l < $nbrLog; $l++) {

					$logement = logement($bdd, $mrp, $date, $l);// apelle de fonction qui ce trouve dans fonctionReservation.php
					echo '<td style="background-color:' . $logement[3] . '; font-weight: ' . $logement[1] . '">' . $logement[0] . '</td>';
					if (($l == 1) and ($logement[2] == 1)) { // si le logement "source&oasis" est reserver ou en provisoir le 2 prochaine case
						// son afficher en non disponible  et la variable $l prend la valeur de 3

						echo '<td style="background-color:' . $logement[3] . ';font-weight: ' . $logement[1] . '">Non disponible</td>
                          <td style="background-color:' . $logement[3] . '; font-weight: ' . $logement[1] . '">Non disponible</td>';
						$l = 3;

					}
				} ?>
			</tr>
			<?
			$date = $date + 86400;
		} ?>


</table>