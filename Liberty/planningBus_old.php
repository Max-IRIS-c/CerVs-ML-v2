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
	/* $pavillion - requête pour les Bus */


	$pavillon = $bdd->query("

SELECT logId, logNom
FROM tblLogement
WHERE logId between 4 AND 12
ORDER BY CASE WHEN logId BETWEEN 8 AND 9 THEN 12 ELSE 11 END
									");

	//$req = $bdd->query("SELECT datediff(locDateDep,locDateEnt), locStatu, locPavId, locDateEnt FROM tblLocation");


?>
<nav id="menu2">
	<ul>
		<li>
			<a href="Location.php">
				<!-- Liste des locations // -->
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
	</ul>
</nav>

<h1 id="planOccupationTest"> <?php echo $mrp->getText('Plan d\'occupation des bus') ?> </h1>
<div id="boutonBusLogement">
	<a class="button button1" href="planningBus.php">
		<?php echo ucfirst($mrp->getText('Planning des bus')) ?>
	</a>
	<a class="button button2" href="planningLogement.php">
		<?php echo ucfirst($mrp->getText('Planning des logements')) ?>
	</a>
</div>

<style>

	/*Modifie uniquement le contenu de "planning avec tout les bus "*/


	th {
		width: 10%;
	}

	td {
		height: 3em;
		text-align: center;
	}

	.reservation {
		margin-left: auto;
		margin-right: auto;
		width: 100%;
	}

	#debute {
		margin-right: 5em;
	}

	#boutonBusLogement {
		display: flex;
		flex-direction: row;
		justify-content: center;
		font-weight: bold;
	}

</style>

<div class="containerOrigine-test">
	<form method="POST">
		<fieldset>
			<legend><?php echo $mrp->getText('Choix de la période') ?></legend>

			<label for="debute"><?php echo $mrp->getText('Période de début :') ?></label>
			<input type="date" name="debut" id="debute">

			<label for="fin"><?php echo $mrp->getText('Période de début :') ?></label>
			<input type="date" name="fin" id="fin">

			<input type="submit" name="submit" class="Valider" value="Valider">
		</fieldset>
	</form>
	
	<style>

		/*Modifie uniquement le contenu de "planning avec tout les bus "*/

		#contenu
		{
			/*width: 75%;*/
		}

		th
		{
			width: 10%;
			font-weight: bold;
		}

		td
		{
			height: 3em;
			text-align: center;
		}

		.reservation
		{
			margin-left: auto;
			margin-right: auto;
			width: 100%;
		}

		#debute
		{
			margin-right: 5em;
		}

		#boutonBusLogement
		{
			display: flex;
			flex-direction: row;
			justify-content: center;
			font-weight: bold;
		}

		.reservation th:nth-child(1), th:nth-child(2), th:nth-child(3), th:nth-child(4), th:nth-child(5), th:nth-child(6), th:nth-child(7), th:nth-child(8), th:nth-child(9), th:nth-child(10)
		{
			width: 100px;
			
		}

		.reservation th:nth-child(11), th:nth-child(12), th:nth-child(13), th:nth-child(14), th:nth-child(15), th:nth-child(16), th:nth-child(17), th:nth-child(18), th:nth-child(19), th:nth-child(20), th:nth-child(21), th:nth-child(22), th:nth-child(23), th:nth-child(24), th:nth-child(25)
		{
			display: none;
			width: 0px;
		}

		.reservation th:nth-child(7), th:nth-child(8)
		{
			background-color: orange;
		}

		.peugeotMercedes
		{
			background-color:  #ffe4b3;
		}
		
		.autresBus
		{
			background-color: lightgreen;
		}
		

	</style>
	
	
	<table class="reservation" style='height: 3em; width: 100%'>
		<tr style='width: auto;'>
			<th id="reservation-th-date"><?php echo $mrp->getText('Date') ?></th>

			<th>Bus <br/>Pacifique II</th>
			<th>Bus <br/> Liberty II</th>
			<th>Bus <br/> Désiré</th>
			<th>Bus <br/> Destiny</th>
			<th>Bus <br/> Pénalty</th>
			<th>Bus (par) <br/>Peugeot</th>
			<th>Bus (par) <br/>Mercedes</th>
			<th>Bus <br/> Spitex</th>
			<th>Bus <br/> Colibri</th>
			
			
			<?php $nbrLog = 4; // initialise le nombre de logement
				// TEST FC modif à 4

				while ($log = $pavillon->fetch()) { ?>
					<th style="width: 50px;"><?php echo $log['logNom'] ?></th>
					<?php $nbrLog++;
				} ?>
		</tr>
		<?php
			$date = $debutSting;
			for ($i = $debut;
			$i <= $fin;
			$i->modify('+1 day')) {
			$jour = date('N', $date); // indique le jour ( 1 = Dimanche )
			$dateJour = date('d', $date);
			$semaine = date('W', $date);
			$mois = strftime('%B', $date);
			$colspan = $nbrLog;

			if ($jour == 1) {
				echo "<tr>
<td style='background-color: #a6c9ff; height: 2px'>" . $mrp->getText("semaine") . " $semaine</td>
<!--
<td style='background-color: #a6c9ff; height: 2px; width: 13%;'>Source & Oasis</td>
<td style='background-color: #a6c9ff; height: 2px; width: 10%;'>Source</td>
<td style='background-color: #a6c9ff; height: 2px; width: 10%;'>Oasis</td>
-->

<td class='td-Titre'>Pacifique</td>
<td class='td-Titre'>Liberty</td>
<td class='td-Titre'>Désiré</td>
<td class='td-Titre'>Destiny</td>

<td class='td-Titre'>Pénalty</td>
<td class='td-Titre'>Peugeot</td>

<td class='td-Titre'>Mercedes</td>
<td class='td-Titre'>Spitex</td>
<td class='td-Titre'>Colibri</td>

<style>
	.td-Titre
	{
	background-color: #a6c9ff;
	height: 2px;
	width: auto;
	}
</style>


</tr>";
			}
			if ($dateJour == 1) {
				echo "<tr> <th colspan='$colspan' > $mois .</th></tr>";
			}


		?>

		<!-- Afichage des données des bus : 4 à 7 -->
		<tr>
			<td style="text-align: left"><?php echo(strftime(" %a %d.%m.%G", $date)) ?></td>

			<? for ($l = 4; $l <= 7; $l++) // TEST MODIF FC
			{

				$logement = logement($bdd, $mrp, $date, $l);// apelle de fonction qui ce trouve dans fonctionReservation.php
				/* Code d'origine */

				//echo "<td>$logement[0]</td>";
				echo '<td style="background-color:' . $logement[3] . '; font-weight: ' . $logement[1] . '">' . $logement[0] . '</td>';
				if (($l == 1) and ($logement[2] == 1)) {
					// si le logement "source&oasis" est reserver ou en provisoir le 2 prochaine case
					// son afficher en non disponible  et la variable $l prend la valeur de 3

					echo '<td style="background-color:' . $logement[3] . ';font-weight: ' . $logement[1] . '">Non disponible</td>
                          <td style="background-color:' . $logement[3] . '; font-weight: ' . $logement[1] . '">Non disponible</td>';
					$l = 3;
				}
			}
			?>
			
			<!-- Affichage du bus : 10 -->
			<? for ($l = 10; $l <= 10; $l++) // TEST MODIF FC
			{

				$logement = logement($bdd, $mrp, $date, $l);// apelle de fonction qui ce trouve dans fonctionReservation.php
				/* Code d'origine */

				//echo "<td>$logement[0]</td>";
				echo '<td style="background-color:' . $logement[3] . '; font-weight: ' . $logement[1] . '">' . $logement[0] . '</td>';
				if (($l == 1) and ($logement[2] == 1)) {
					// si le logement "source&oasis" est reserver ou en provisoir le 2 prochaine case
					// son afficher en non disponible  et la variable $l prend la valeur de 3

					echo '<td style="background-color:' . $logement[3] . ';font-weight: ' . $logement[1] . '">Non disponible</td>
                          <td style="background-color:' . $logement[3] . '; font-weight: ' . $logement[1] . '">Non disponible</td>';
					$l = 3;
				}
			}
			?>

			<!-- Affichage des bus : 8 et 9-->
			<? for ($l = 8; $l <= 9; $l++) // TEST MODIF FC
			{

				$logement = logement($bdd, $mrp, $date, $l);// apelle de fonction qui ce trouve dans fonctionReservation.php
				/* Code d'origine */

				//echo "<td>$logement[0]</td>";
				echo '<td class="peugeotMercedes" style="font-weight: ' . $logement[1] . '">' . $logement[0] . '</td>';
				if (($l == 1) and ($logement[2] == 1)) {
					// si le logement "source&oasis" est reserver ou en provisoir le 2 prochaine case
					// son afficher en non disponible  et la variable $l prend la valeur de 3

					echo '<td style="background-color:' . $logement[3] . ';font-weight: ' . $logement[1] . '">Non disponible</td>
                          <td style="background-color:' . $logement[3] . '; font-weight: ' . $logement[1] . '">Non disponible</td>';
					$l = 3;
				}
			}
			?>

			<!-- Affichage des bus : 11 et 12-->
			<? for ($l = 11; $l <= 12; $l++) // TEST MODIF FC
			{

				$logement = logement($bdd, $mrp, $date, $l);// apelle de fonction qui ce trouve dans fonctionReservation.php
				/* Code d'origine */

				//echo "<td>$logement[0]</td>";
				echo '<td style="background-color:' . $logement[3] . '; font-weight: ' . $logement[1] . '">' . $logement[0] . '</td>';
				if (($l == 1) and ($logement[2] == 1)) {
					// si le logement "source&oasis" est reserver ou en provisoir le 2 prochaine case
					// son afficher en non disponible  et la variable $l prend la valeur de 3

					echo '<td style="background-color:' . $logement[3] . ';font-weight: ' . $logement[1] . '">Non disponible</td>
                          <td style="background-color:' . $logement[3] . '; font-weight: ' . $logement[1] . '">Non disponible</td>';
					$l = 3;
				}
			}
			?>
			
		</tr>
			<?
				$date = $date + 86400;
				} ?>
	</table>
</div>
