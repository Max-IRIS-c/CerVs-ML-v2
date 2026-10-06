<?php
	include('../variables.php');
	
	setlocale(LC_TIME, 'fr_FR.utf8', 'fra');
	$bdd = new PDO($dsn, $user, $password);
	$id = $_GET['Id'];
	$activite = $bdd->query("SELECT tblActivites.actNom,tblActivites.actLieu,tblActivites.actTheme,
  Responsable.conNom as responsableN ,  Responsable.conPrenom as responsableP ,
  CoResponsable.conNom as  coResponsableN,CoResponsable.conPrenom as  coResponsableP,
  infirmier.conNom as infirmierN, infirmier.conPrenom as infirmierP,
  Cuisinier.conNom as cuisinierN, Cuisinier.conPrenom as cuisinierP,
   actDebut, actDec, Responsable.conTel1 as telephone, actType
FROM tblActivites
  LEFT JOIN tblContact as Responsable on actResponsable = Responsable.conId
  LEFT JOIN tblContact as CoResponsable on actCoResponsable = CoResponsable.conId
  LEFT JOIN tblContact as Cuisinier on actCuisiniere = Cuisinier.conId
  LEFT JOIN tblContact as infirmier on actInfirmier = infirmier.conId
WHERE actId = '$id' ");
	$activite = $activite->fetch();
	
	
	$desActivite = $bdd->query("SELECT * FROM tblActivites
 LEFT JOIN tblTypeActivite on actType = tActId WHERE actId = $id");
	$desActivite = $desActivite->fetch();
	
	$participant = $bdd->query("SELECT
acc.conNom as accN, acc.conPrenom as accP,
part.conNom as partN, part.conPrenom as partP,
doub.conNom as doubN, doub.conPrenom as doubP FROM tblParticipants
  LEFT JOIN tblContact as acc on conIdA = acc.conId
  LEFT JOIN tblContact as part on conIdP = part.conId
  LEFT JOIN tblContact as doub on conIdD = doub.conId where actId =$id");
	
	
	$aller1 = $bdd->query("SELECT * FROM tblAloFeuilleRout where actId = $id and busId = 1 and feuAller = 0  ");
	$aller2 = $bdd->query("SELECT * FROM tblAloFeuilleRout where actId = $id and busId = 2 and feuAller = 0  ");
	$aller3 = $bdd->query("SELECT * FROM tblAloFeuilleRout where  actId = $id and busId = 3 and feuAller = 0 ");
	$aller4 = $bdd->query("SELECT * FROM tblAloFeuilleRout where  actId = $id and busId = 4 and feuAller = 0 ");
	
	$retour1 = $bdd->query("SELECT * FROM tblAloFeuilleRout where actId = $id and busId = 1 and feuAller = 1   ");
	$retour2 = $bdd->query("SELECT * FROM tblAloFeuilleRout where actId = $id and busId = 2 and feuAller = 1 ");
	$retour3 = $bdd->query("SELECT * FROM tblAloFeuilleRout where actId = $id and busId = 3 and feuAller = 1 ");
	$retour4 = $bdd->query("SELECT * FROM tblAloFeuilleRout where actId = $id and busId = 4 and feuAller = 1 ");
	
	$Chauffeuraller1 = $bdd->query("SELECT * FROM tblchauffeur where actId = $id and busId = 1 and feuAller = 0  ");
	$Chauffeuraller1 = $Chauffeuraller1->fetch();
	$Chauffeuraller2 = $bdd->query("SELECT * FROM tblchauffeur where actId = $id and busId = 2 and feuAller = 0  ");
	$Chauffeuraller2 = $Chauffeuraller2->fetch();
	$Chauffeuraller3 = $bdd->query("SELECT * FROM tblchauffeur where  actId = $id and busId = 3 and feuAller = 0 ");
	$Chauffeuraller3 = $Chauffeuraller3->fetch();
	$Chauffeuraller4 = $bdd->query("SELECT * FROM tblchauffeur where  actId = $id and busId = 4 and feuAller = 0 ");
	$Chauffeuraller4 = $Chauffeuraller4->fetch();
	
	
	$Chauffeurretou1 = $bdd->query("SELECT * FROM tblchauffeur where actId = $id and busId = 1 and feuAller = 1   ");
	$Chauffeurretou1 = $Chauffeurretou1->fetch();
	$Chauffeurretou2 = $bdd->query("SELECT * FROM tblchauffeur where actId = $id and busId = 2 and feuAller = 1 ");
	$Chauffeurretou2 = $Chauffeurretou2->fetch();
	$Chauffeurretou3 = $bdd->query("SELECT * FROM tblchauffeur where actId = $id and busId = 3 and feuAller = 1 ");
	$Chauffeurretou3 = $Chauffeurretou3->fetch();
	$Chauffeurretou4 = $bdd->query("SELECT * FROM tblchauffeur where actId = $id and busId = 4 and feuAller = 1 ");
	$Chauffeurretou4 = $Chauffeurretou4->fetch();
	
	use Spipu\Html2Pdf\Html2Pdf;
	
	ob_start();
?>
	<style>
		* {
			
			margin: 0;
			padding: 0;
			color: #000;
			font-family: "helvetica", sans-serif;
			font-size: 14px;
		}
		
		table {
			
			margin-top: 10px;
			width: 100%;
			color: #9A0000;
			
		}
		
		td {
			
			padding-left: 1mm;
			vertical-align: top;
			text-align: left;
			
		}
		
		th {
			
			padding-left: 2mm;
			vertical-align: top;
			text-align: left;
			font-style: normal;
			font-weight: bold;
			
		}
		
		.footer td {
			vertical-align: bottom
		}
		
		h2 {
			background-color: #00AA00;
			color: #fff;
			margin-top: 10mm;
			width: 25mm;
			font-size: 14px;
		}
		
		h1 {
			
			
			font-size: 28px;
		}
		
		.footer td {
			border: 0;
		}
		
		.paire {
			
			background-color: #FFE4C4;
		}
		
		.impaire {
			
			background-color: #DEB887;
		}
		
		.affichage {
			margin-left: 2mm;
			width: 100%;
			
		}
		
		.affichage td {
			padding-left: 2mm;
		}
		
		.affichage th {
			padding-left: 2mm;
		}
		
		.footer {
			border-top: solid 1px;
		}
		
		p {
			padding-top: -3mm;
			padding-left: 2mm;
			padding-bottom: -3mm;
			margin: 0;
		}

	</style>

	<page backtop="10mm" backleft="5mm" backright="10mm">
		<page_header>
			<table style="margin-top: -2mm">
				<tr>
					<td rowspan="2" style=" width:90mm; margin-left: 10mm; border: 0; vertical-align: top;  padding: 0">
						<?php
							if ($desActivite['tActId'] == 4) // logo La Parantese
							{
								?>
								<img style="height:20mm;margin-left: 2mm" src="../img/logoParentheseAdresse.jpg" alt="">
							<?php } else // logo Cerebral
							{ ?>
								<img style="height:20mm;margin-left: 2mm" src="../img/logoAdresse.jpg" alt="">
							<?php }
						
						?>

					</td>
					<td style="text-align: right;width:95mm;  border: 0; vertical-align: middle"> <?php
							
							$Date1 = strtotime($desActivite['actDebut']);
							$Date2 = strtotime($desActivite['actFin']);
							$format1 = ("%d");
							$format2 = ("%d %B %G");
							
							if ($Date1 != $Date2) {
								
								echo '<strong>' . $desActivite['tActNom'] . ' ' . $desActivite['actNom'] . ' du ' . (strftime($format1, $Date1)) . ' au ' . (strftime($format2, $Date2)) . '</strong>';
							} else {
								echo '<strong>' . $desActivite['tActNom'] . ' ' . $desActivite['actNom'] . ' du ' . (strftime($format2, $Date1)) . '</strong>';
								
							} ?> </td>
				</tr>
				<tr>
					<td style="border-top:solid 1px; text-align: right; vertical-align: middle ">
						Sion, le <?php echo(strftime("%d %B %G"));; ?>
					</td>

				</tr>
			</table>
		</page_header>

		<div style="margin-top: 55mm; text-align: justify">
			<?php if (file_exists("img/$id-photo1.jpg")) {
				?>
				<img style="max-height: 55mm; max-width:  80mm; float: left; margin-right: 3mm;margin-bottom: 3mm; margin-left: 2mm"
				     src="img/<? echo $id ?>-photo1.jpg">
			<?php } ?>
			
			<?php echo nl2br($activite['actDec']) ?>
			<p style="text-align: right; margin-top: 5mm">
				
				<?php if ($desActivite['tActId'] == 4) // signature La Parantese
				{ ?>
					Amicalement, <?php echo $activite['responsableP'] ?>
				<?php } else // signature Cerebral
				{ ?>
					Amicalement, <?php echo $activite['responsableP'] . ' et ' . $activite['coResponsableP'] ?>
				<?php }
				
				?>
			</p>
			<p style="padding-top: 20px;">
				<?php
					if ((!empty($activite['infirmierN']) && ($activite['actType'] == 1)) || ($activite['actType'] == 3)) {
						echo '<strong>Responsable de soins ' . $activite['infirmierP'] . ' ' . $activite['infirmierN'] . '</strong>';
					}
					
					if ((!empty($activite['cuisinierN']) && ($activite['actType'] == 1)) || ($activite['actType'] == 3)) {
						echo "<br>";
						echo '<strong>Responsable de cuisine ' . $activite['cuisinierP'] . ' ' . $activite['cuisinierN'] . '</strong>';
					}
				?>

			</p>

		</div>

		<table style="margin-top: 10mm">
			<tr>
				<th style=" padding-bottom: 3mm; "><strong style="font-size: 4mm">Participants</strong>
				</th>
				<th style=" padding-bottom: 3mm; font-size: 25mm ;">
					<strong style="font-size: 4mm">Accompagnants</strong></th>
				<th style=" padding-bottom: 3mm; font-size: 25mm ;">
					<strong style="font-size: 4mm">Doublure(s)</strong></th>
			</tr>
			<?php
				$numero = 1;
				while ($row = $participant->fetch()) {
					?>
					<tr>
						<td style="width: 57mm; border-bottom: dotted #00AA33; padding-bottom: 1mm; padding-top: 1mm"><?php echo $numero . '. ' . $row['partN'] . ' ' . $row['partP'] ?></td>
						<td style="width: 58mm; border-bottom: dotted #00AA33; padding-bottom: 1mm; padding-top: 1mm"><?php echo $numero . '. ' . $row['accN'] . ' ' . $row['accP'] ?></td>
						<td style="width: 57mm; border-bottom: dotted #00AA33; padding-bottom: 1mm; padding-top: 1mm"><?php echo $row['doubN'] . ' ' . $row['doubP'] ?></td>
					</tr>
					<?php $numero++;
				} ?>
		</table>
		<page_footer>
			
			<?php
				if ($desActivite['tActId'] == 4) // footer la parantese
				{
					include '../footerPrintPar.php';
				} else // footer cerebrale
				{
					
					include '../footerPrint.php';
				}
			
			?>

		</page_footer>


	</page>
	<page backtop="0mm" backleft="5mm" backright="10mm">
		<h1 style="text-align: center">Feuille de route </h1>
		<p style="margin-top: 5mm; text-align: center"><strong>Afin de faciliter le respect des horaires,
				nous vous prions de bien vouloir être prêts sur le lieu de rendez-vous.
				Merci à tous de votre compréhension. </strong></p>


		<h1 style="font-size: 19px; margin-top: 5mm">Aller - <?php echo strftime("%d %B %G", $Date1) ?></h1>

		<table>
			<tr>
				<td><strong style="font-size: 4mm;"><?= $bus[$Chauffeuraller1['busIdNom']] ?></strong></td>
				<td colspan="2"><strong>Chauffeur
						: <?php echo $Chauffeuraller1['chauPrinc'] ?>  </strong>
				</td>
				<td style="text-align: right"><strong><?php echo $Chauffeuraller1['chauAide'] ?></strong></td>
			</tr>
			<tr>
				<th style="background-color:#999999; border: solid; width: 56mm" colspan="2">Rendez-vous</th>
				<th style="background-color:#999999; border: solid; width: 56mm">Participants</th>
				<th style="background-color:#999999; border:solid; width: 56mm">Accompagnants</th>
			</tr>
			<?php while ($depA = $aller1->fetch()) { ?>

				<tr>
					<td style="border-bottom: dotted; border-left: solid; width: 56mm"
					    colspan="2"><?php echo $depA['feuLieu'] ?></td>
					<td style="border-bottom: dotted; border-left: solid; width: 56mm"><?php echo $depA['feuPart'] ?></td>
					<td style="border-bottom: dotted; border-left: solid; border-right: solid; width: 56mm"><?php echo $depA['feuAcc'] ?></td>
				</tr>
			<? } ?>
			<tr>
				<td style="height: 2mm"></td>
			</tr>

			<tr>
				<td><strong style="font-size: 4mm;"><?= $bus[$Chauffeuraller2['busIdNom']] ?></strong></td>
				<td colspan="2"><strong>Chauffeur
						: <?php echo $Chauffeuraller2['chauPrinc'] ?>  </strong>
				</td>
				<td style="text-align: right"><strong><?php echo $Chauffeuraller2['chauAide'] ?></strong></td>
			</tr>

			<tr>
				<th style="background-color:#999999; border: solid; width: 56mm" colspan="2">Rendez-vous</th>
				<th style="background-color:#999999; border: solid; width: 56mm">Participants</th>
				<th style="background-color:#999999; border:solid; width: 56mm">Accompagnants</th>
			</tr>
			<?php while ($depB = $aller2->fetch()) { ?>

				<tr>
					<td style="border-bottom: dotted; border-left: solid; width: 56mm"
					    colspan="2"><?php echo $depB['feuLieu'] ?></td>
					<td style="border-bottom: dotted; border-left: solid; width: 56mm"><?php echo $depB['feuPart'] ?></td>
					<td style="border-bottom: dotted; border-left: solid; border-right: solid; width: 56mm"><?php echo $depB['feuAcc'] ?></td>
				</tr>
			<? } ?>
			<tr>
				<td colspan="3" style="height: 2mm"></td>
			</tr>

			<tr>
				<td><strong style="font-size: 4mm;"><?= $bus[$Chauffeuraller3['busIdNom']] ?></strong></td>
				<td colspan="2"><strong>Chauffeur
						: <?php echo $Chauffeuraller3['chauPrinc'] ?>  </strong>
				</td>
				<td style="text-align: right"><strong><?php echo $Chauffeuraller3['chauAide'] ?></strong></td>
			</tr>

			<tr>
				<th style="background-color:#999999; border: solid; width: 56mm" colspan="2">Rendez-vous</th>
				<th style="background-color:#999999; border: solid; width: 56mm">Participants</th>
				<th style="background-color:#999999; border:solid; width: 56mm">Accompagnants</th>
			</tr>
			
			<?php while ($depC = $aller3->fetch()) { ?>

				<tr>
					<td style="border-bottom: dotted; border-left: solid; width: 56mm"
					    colspan="2"><?php echo $depC['feuLieu'] ?></td>
					<td style="border-bottom: dotted; border-left: solid; width: 56mm"><?php echo $depC['feuPart'] ?></td>
					<td style="border-bottom: dotted; border-left: solid; border-right: solid; width: 56mm"><?php echo $depC['feuAcc'] ?></td>
				</tr>
			<? } ?>
			<tr>
				<td colspan="4" style="height: 2mm"></td>
			</tr>

			<tr>
				<td><strong style="font-size: 4mm;"><?= $bus[$Chauffeuraller4['busIdNom']] ?></strong></td>
				<td colspan="2"><strong>Chauffeur
						: <?php echo $Chauffeuraller4['chauPrinc'] ?>  </strong>
				</td>
				<td style="text-align: right"><strong><?php echo $Chauffeuraller4['chauAide'] ?></strong></td>
			</tr>
			<tr>
				<th style="background-color:#999999; border: solid; width: 56mm" colspan="2">Rendez-vous</th>
				<th style="background-color:#999999; border: solid; width: 56mm">Participants</th>
				<th style="background-color:#999999; border:solid; width: 56mm">Accompagnants</th>
			</tr>
			<tr>
				<td><strong></strong></td>

			</tr>
			<?php while ($depD = $aller4->fetch()) { ?>
				<tr>
					<td style="border-bottom: dotted; border-left: solid; width: 58mm"
					    colspan="2"><?php echo $depD['feuLieu'] ?></td>
					<td style="border-bottom: dotted; border-left: solid; width: 57mm"><?php echo $depD['feuPart'] ?></td>
					<td style="border-bottom: dotted; border-left: solid; border-right: solid; width: 57mm"><?php echo $depD['feuAcc'] ?></td>
				</tr>
			<? } ?>
		</table>
		<h1 style="font-size: 19px; margin-top: 1mm">Retour - <?php echo strftime("%d %B %G", $Date2) ?></h1>
		<table>

			<tr>
				<td><strong style="font-size: 4mm;"><?= $bus[$Chauffeurretou1['busIdNom']] ?></strong></td>
				<td colspan="2"><strong>Chauffeur
						: <?php echo $Chauffeurretou1['chauPrinc'] ?>  </strong>
				</td>
				<td style="text-align: right">
					<strong><?php echo $Chauffeurretou1['chauAide'] ?></strong>
				</td>
			</tr>
			<tr>
				<th style="background-color:#999999; border: solid; width: 56mm" colspan="2">Rendez-vous</th>
				<th style="background-color:#999999; border: solid; width: 56mm">Participants</th>
				<th style="background-color:#999999; border:solid; width: 56mm">Accompagnants</th>
			</tr>
			
			<?php while ($retA = $retour1->fetch()) { ?>

				<tr>
					<td style="border-bottom: dotted; border-left: solid; width: 56mm"
					    colspan="2"><?php echo $retA['feuLieu'] ?></td>
					<td style="border-bottom: dotted; border-left: solid; width: 56mm"><?php echo $retA['feuPart'] ?></td>
					<td style="border-bottom: dotted; border-left: solid; border-right: solid; width: 56mm"><?php echo $retA['feuAcc'] ?></td>
				</tr>
			<? } ?>
			<tr>
				<td style="height: 2mm"></td>
			</tr>

			<tr>
				<td><strong style="font-size: 4mm;"><?= $bus[$Chauffeurretou2['busIdNom']] ?></strong></td>
				<td colspan="2"><strong>Chauffeur
						: <?php echo $Chauffeurretou2['chauPrinc'] ?>  </strong>
				</td>
				<td style="text-align: right">
					<strong><?php echo $Chauffeurretou2['chauAide'] ?></strong>
				</td>
			</tr>
			<tr>
				<th style="background-color:#999999; border: solid; width: 56mm" colspan="2">Rendez-vous</th>
				<th style="background-color:#999999; border: solid; width: 56mm">Participants</th>
				<th style="background-color:#999999; border:solid; width: 56mm">Accompagnants</th>
			</tr>
			
			<?php while ($retB = $retour2->fetch()) { ?>

				<tr>
					<td style="border-bottom: dotted; border-left: solid; width: 56mm"
					    colspan="2"><?php echo $retB['feuLieu'] ?></td>
					<td style="border-bottom: dotted; border-left: solid; width: 56mm"><?php echo $retB['feuPart'] ?></td>
					<td style="border-bottom: dotted; border-left: solid; border-right: solid; width: 56mm"><?php echo $retB['feuAcc'] ?></td>
				</tr>
			<? } ?>
			<tr>
				<td style="height: 2mm"></td>
			</tr>

			<tr>
				<td><strong style="font-size: 4mm;"><?= $bus[$Chauffeurretou3['busIdNom']] ?></strong></td>
				<td colspan="2"><strong>Chauffeur
						: <?php echo $Chauffeurretou3['chauPrinc'] ?>  </strong>
				</td>
				<td style="text-align: right">
					<strong><?php echo $Chauffeurretou3['chauAide'] ?></strong>
				</td>
			</tr>
			<tr>
				<th style="background-color:#999999; border: solid; width: 56mm" colspan="2">Rendez-vous</th>
				<th style="background-color:#999999; border: solid; width: 56mm">Participants</th>
				<th style="background-color:#999999; border:solid; width: 56mm">Accompagnants</th>
			</tr>
			
			<?php while ($retC = $retour3->fetch()) { ?>

				<tr>
					<td style="border-bottom: dotted; border-left: solid; width: 56mm"
					    colspan="2"><?php echo $retC['feuLieu'] ?></td>
					<td style="border-bottom: dotted; border-left: solid; width: 56mm"><?php echo $retC['feuPart'] ?></td>
					<td style="border-bottom: dotted; border-left: solid; border-right: solid; width: 56mm"><?php echo $retC['feuAcc'] ?></td>
				</tr>
			<? } ?>
			<tr>
				<td style="height: 2mm"></td>
			</tr>
			<tr>
				<td><strong style="font-size: 4mm;"><?= $bus[$Chauffeurretou4['busIdNom']] ?></strong></td>
				<td colspan="2"><strong>Chauffeur
						: <?php echo $Chauffeurretou4['chauPrinc'] ?>  </strong>
				</td>
				<td style="text-align: right">
					<strong><?php echo $Chauffeurretou4['chauAide'] ?></strong>
				</td>
			</tr>
			<tr>
				<th style="background-color:#999999; border: solid; width: 56mm" colspan="2">Rendez-vous</th>
				<th style="background-color:#999999; border: solid; width: 56mm">Participants</th>
				<th style="background-color:#999999; border:solid; width: 56mm">Accompagnants</th>
			</tr>
			<tr>
				<td><strong></strong></td>
			</tr>
			<?php while ($retD = $retour4->fetch()) { ?>
				<tr>
					<td style="border-bottom: dotted; border-left: solid; width: 56mm"
					    colspan="2"><?php echo $retD['feuLieu'] ?></td>
					<td style="border-bottom: dotted; border-left: solid; width: 56mm"><?php echo $retD['feuPart'] ?></td>
					<td style="border-bottom: dotted; border-left: solid; border-right: solid; width: 56mm"><?php echo $retD['feuAcc'] ?></td>
				</tr>
			<?php } ?>

		</table>
		<p style="text-align: center; margin-top: 5mm">Si un rendez-vous ne vous convient pas,<br> n'hésitez pas à
			m'appeler
			<?
				if ($desActivite['tActId'] == 4) // numero La Parantese
				{
					
					echo " au +41 79 296 93 97";
				} else // numero Cerebral
				{
					
					echo "au bureau +41 27 346 70 44 ou au " . ' ' . $activite['telephone'];
				}
			
			?>
		</p>


	</page>


<?php
	
	try {
		$content = ob_get_clean();
		require _('../vendor/autoload.php');
		$family = 'coucou';
		$style = 'regular';
		$file = '../vendor/tecnickcom/tcpdf/fonts/helvetica.php';
		$pdf = new HTML2PDF('P', 'A4', 'fr');
		$pdf->pdf->SetDisplayMode('fullwidth', 'tworight');
		
		$pdf->writeHTML($content);
		
		$pdf->addFont($family, $style, $file);
		ob_get_clean();
		$pdf->output('feuilleDeRoute.pdf');
	} catch (\Spipu\Html2Pdf\Exception\Html2PdfException $e) {
		die($e);
	};
