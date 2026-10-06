<?php
	include('../variables.php');
	$bdd = new PDO($dsn, $user, $password);
	$id = $_GET['Id'];
	$contrat = $bdd->query("SELECT * FROM tblContrat WHERE contId ='$id'");
	$contrat = $contrat->fetch();
	$employer = $contrat['cont_conId'];
	$contact = $bdd->query("SELECT conNpa, conLocaliter, conNom, conPrenom, conDateNaissance,natNom,conAdresse, conAvs,
 conBanque, conAgence, conIban FROM tblContact
  LEFT JOIN tblNationaliter on conNationalite = natId WHERE conId = '$employer'");
	$contact = $contact->fetch();
	
	
	use Spipu\Html2Pdf\Html2Pdf;
	
	ob_start();
?>
<style>
	* {
		
		margin: 0;
		padding: 0;
		color: #000;
		font-family: "helvetica", sans-serif;
		
	}
	
	table {
		
		
		width: 100%;
		color: #9A0000;
		
	}
	
	td {
		
		
		vertical-align: middle;
		text-align: left;
		
	}
	
	th {
		
		
		vertical-align: middle;
		text-align: left;
		color: #00AA33;
		font-size: 12px;
		font-style: normal;
		font-weight: normal !important;
	}
	
	.footer td {
		vertical-align: bottom
	}
	
	h1 {
		width: 100%;
		text-align: right;
		font-size: 20px;
		
		
	}
	
	h2 {
		
		color: #000;
		margin-top: 3mm;
		margin-bottom: 3mm;
		font-size: 14px;
		padding-left: 3mm;
	}
	
	p {
		text-align: justify;
		margin-bottom: 3mm;
		font-size: 15px;
	}
	.anneeInput{
		width: 55px;
		text-align: center;
		vertical-align: middle;
	}
	#priceInput{
		text-align: center;
		vertical-align: middle;
		width: 50px;
		color: black;box-sizing: border-box;
	}

</style>
<page backtop="8mm" backbottom="25mm" backleft="15mm" backright="5mm">

	<page_header>
		<table>
			<tr>
				<td style=" vertical-align: top;  padding-left: 5mm"><img style="width: 205mm"
				                                                          src="../img/papier_logo.gif"></td>
			</tr>
		</table>
	</page_header>

	<div>
		<h1 style="margin-top:-4mm; font-size: 15px">Année <input class="anneeInput" type="text" value="<?php echo $contrat['contAnnee']; ?>"/><br><br></h1>


		<h1 style="text-align: center; margin-top: 15mm;">CONVENTION D'AUXILIAIRE INTERVENANTE</h1>
		<p style="text-align: center; font-style: italic; font-size: 10px">
			Ce poste est ouvert aussi bien aux hommes qu’aux femmes.<br/>
			Pour des raisons de langage simplifié l’usage du masculin est utilisé.
		</p>

		<p>
			Cette convention se base sur les disponibilités reçues de la part de la personne intervenante. La
			convention est annuelle et sera valide pour tous les séjours et/ou interventions à domicile effectives
			durant l’année. Les inscriptions, modifications ou annulations de séjours ou d'interventions à domicile
			en cours d’année seront confirmées par téléphone ou par courriel uniquement. Les heures seront
			rémunérées selon le montant brut, mentionné au point 4 et selon la liste des heures effectuées.
		</p>
		<p>
			Par votre signature, vous confirmez avoir pris connaissance de la présente convention, du descriptif de
			fonction ainsi que du code de conduite et vous vous engagez à les respecter.
		</p>
		<br/>
		<p>
			<strong>Mandante : L'Association Cerebral Valais,</strong> dénommée ci-après Cerebral Valais
			<br/>
			<strong>Et :</strong> La personne auxiliaire intervenante nommée ci-après l'intervenante :
		</p>
		<table style="margin-bottom: ^5mm">
			<tr>
				<td> Prénom & Nom</td>
				<td> : <strong><?php echo($contact['conPrenom'] . ' ' . $contact['conNom']) ?></strong></td>
			</tr>
			<tr>
				<td>Date de naissance</td>
				<td> : <strong><?php echo dateToUser($contact['conDateNaissance']) ?></strong></td>
			</tr>
			<tr>
				<td>Lieu d'origine / Nationalité</td>
				<td> : <strong><?php echo $contact['natNom'] ?></strong></td>
			</tr>
			<tr>
				<td>Adresse</td>
				<td> :
					<strong><?php echo $contact['conAdresse'] . ' ' . $contact['conNpa'] . ' ' . $contact['conLocaliter'] ?></strong>
				</td>
			</tr>
			<tr>
				<td>N° AVS</td>
				<td> : <strong><?php echo $contact['conAvs'] ?></strong></td>
			</tr>
			<tr>
				<td>Coordonnées bancaires (nom et lieu)&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
				<td> : <strong><?php echo $contact['conBanque'] . ' ' . $contact['conAgence'] ?></strong></td>
			</tr>
			<tr>
				<td>Compte IBAN</td>
				<td> : <strong><?php echo $contact['conIban'] ?></strong></td>
			</tr>
		</table>

		<p>
			<strong>1. Fonction / position</strong> L'intervenant dans le domaine social accompagne la personne en
			situation
			de
			handicap à son lieu de résidence ou lors de séjour. Les tâches sont stipulées sur le descriptif de
			fonction transmis et signé lors de l'entretien d'engagement.
		</p>
		<p>
			<strong>2. Lieu de travail</strong> La fiche d’intervention ou la feuille de route définit le lieu de
			travail.
		</p>
		<p>
			<strong>3. Durée de la convention </strong>Elle couvre toutes les interventions de l’année <input class="anneeInput" type="text" value="2024"/>.
			L'intervenant
			reste libre d'accepter ou de refuser une mission.
			Une fois la mission acceptée, l'intervenant s’engage à effectuer les heures stipulées sur la fiche
			d’intervention reçue et ne peut, sans raison valable, annuler les interventions prévues faute de quoi, un
			dédommagement minimum de Fr. 100.- peut être demandé afin de couvrir les frais engendrés par
			l'annulation.

			Après avoir accepté des interventions à domicile ou séjours et à des fins d'organisation, il est convenu
			d'annoncer l'arrêt des interventions dans les délais suivants :
		</p>
		<p>
			- 7 jours durant la période d’essai
			<br/>
			- 30 jours hors de la période d'essai
		</p>
		<p>
			En cas de faute grave : Cerebral Valais peut mettre un terme à la présente convention avec effet
			immédiat.
		</p>
		<p>
			<strong>4. Rémunération</strong> La rémunération brute s'élève à <strong>Fr. <input id="priceInput" type="number" value="19.60" /> de l'heure.</strong> Les
			vacances et jours
			fériés sont indemnisés mensuellement sous forme d’une indemnité ajoutée.
			<br/>
			Les cotisations légales pour l’AVS/AI/APG/AC/AF sont déduites du salaire brut. Pour les personnes
			détentrices de permis B/L, soumises à la retenue de l'impôt à la source communal, cantonal et fédéral,
			la retenue sera effectuée selon le barème en vigueur.
		</p>
	</div>
	<page_footer>
		<p style="color: #00AA00; font-size: 10px; text-align: center">
			Association Cerebral Valais&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;
			Tél. 027 346 70 44&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;
			www.cerebral-valais.ch&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;CH10 8057 2000 0099 9224 8
			___________________________________________________________________________________________________________________</p>
		<p style="font-size: 10px; text-align: center">En étroite collaboration avec l’association Cerebral Suisse et la
			Fondation suisse
			en faveur de l’enfant infirme moteur cérébral<br>
			In enger Zusammenarbeit mit der Vereinigung Cerebral Schweiz und der Schweizerischen Stiftung für das
			cerebral gelähmte Kind</p>
		<p style="text-align: right; margin-right: 15mm;font-size: 10px"> page <strong>1</strong> sur <strong>2</strong>
		</p>
	</page_footer>
</page>

<page backtop="8mm" backbottom="10mm" backleft="15mm" backright="5mm">
	<page_header>
		<table>
			<tr>
				<td style=" vertical-align: top;  padding-left: 5mm"><img style="width: 205mm"
				                                                          src="../img/papier_logo.gif"></td>
			</tr>
		</table>
	</page_header>
	<div style="margin-top: 20mm">
		<p>
			LPP (prévoyance professionnelle) : Il n’a pas été prévu de cotisation. Toutefois, en cas de volume de
			travail important et si le salaire annuel brut soumis est susceptible d’atteindre le montant de CHF.
			22’050.- la cotisation à la LPP sera retenue et déduite du salaire brut.
		</p>

		<p>
			<strong>5. Assurances </strong>L'intervenant est assuré par l'employeur contre les accidents professionnels,
			y compris ceux survenant sur le chemin direct entre le domicile et le lieu de travail.
			Pour les accidents non professionnels, l’intervenant est assuré selon le contrat de base prévu par la LAA
			(loi
			sur l’assurance accident).
			La perte de gain maladie n'est pas assurée.
		</p>
		<p>
			<strong>6. Frais de déplacement </strong>Le déplacement sur le lieu de travail est à la charge de
			l'intervenant.
			Le transport de personnes ne fait pas partie du descriptif de fonction.
			Un tel transport peut être toléré dans les seuls cas où le transport est effectué sur mandat de la personne
			ou
			son représentant légal qui en supporte les frais.
			Cas échéant la responsabilité de Cerebral est exclue.
		</p>

		<p><strong>7. Dispositions générales complémentaires </strong>Font partie intégrante du présent contrat :</p>
		<ul>
			<li>Le descriptif de fonction signé.</li>
			<li>Le code de conduite ainsi que la déclaration d’engagement personnel signée.</li>
			<li>Un extrait récent du casier judiciaire est à joindre au présent contrat.</li>
		</ul>
		<p>
			Des modifications et adjonctions au présent contrat n’ont validité que si elles sont formulées par écrit et
			acceptées mutuellement.
			L'intervenant s'engage à garder le secret sur toutes les informations auxquelles il a accès de par sa
			fonction.
			Pour tout ce qui n’est pas prévu dans le présent contrat, les parties s’en remettent aux prescriptions
			légales
			applicables en la matière.
		</p>

		<p>
			<strong>8. Loi sur la protection des données (LPD) : Accord pour les photos</strong>
			J'accepte que des photos de moi soient éventuellement utilisées pour la communication (flyers,
			brochures), sur le site internet et/ou les réseaux sociaux de l'Association Cerebral Valais.
			<br/>
			<strong>O oui</strong>, <strong>O non</strong>, <strong>O oui, uniquement sur demande</strong>
		</p>
		<br/>
		<p>
			Je suis conscient que des photos lors des activités peuvent être prises par des bénéficiaires ou des
			accompagnants.
			Cas échéant, l'Association Cerebral Valais et ses collaborateurs ne sont pas responsables de leurs
			diffusions.
			<br/>
			<strong>O oui</strong>
		</p>
		<br/>
		<p>
			Je m'engage à traiter les données et les photos de tous les participants avec bienveillance et à respecter
			leur
			sphère privée
			(pas de diffusion de photos et de numéros de téléphone sans l'accord de la personne concernée).
			<br/>
			<strong>O oui</strong>
		</p>
		<br/>
		<p><strong>Protection des données</strong></p>
		<p>
			Je suis conscient que mes données personnelles peuvent être transmises à des tiers (organisme de transport,
			hébergement, administration publique, assureurs, etc.)
			afin de pouvoir organiser la prestation et en assurer la qualité.
			<br/>
			<strong>O oui</strong>
		</p>
		<table id="tableTest">
			<tr>
				<td>Lieu et date :</td>
				<td colspan="2">Lieu et date :</td>
			</tr>
			<tr>
				<td colspan="1" style="width: 40%;">L'auxiliaire
					: <?php echo $contact['conPrenom'] . ' ' . $contact['conNom'] ?>
				</td>
				<td colspan="2">L'employeur : Association Cerebral Valais</td>
			</tr>
			<tr>
				<td>Signature : </td>
				<td>Signature : </td>
				<td>Signature : </td>				
				<img id="signature" src="../Adresse/signature_bruno.png" />
			</tr>
		</table>
		<style>
			#signature{
				width: 100px;
				height: auto;
			}
			#tableTest {
				/* border des cellules */
				text-align: left;
				
			}
			
			#tableTest td {
				/* bordure des cellules */
				vertical-align: top;
				width: 30%;
				height: 3%;
			}
		</style>
	</div>
	<page_footer>
		<p style="color: #00AA00; font-size: 10px; text-align: center">
			Association Cerebral Valais&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;
			Tél. 027 346 70 44&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;
			www.cerebral-valais.ch&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;CH10 8057 2000 0099 9224 8
			___________________________________________________________________________________________________________________
		</p>
		<p style="font-size: 10px; text-align: center">En étroite collaboration avec l’association Cerebral Suisse et la
			Fondation suisse
			en faveur de l’enfant infirme moteur cérébral<br>
			In enger Zusammenarbeit mit der Vereinigung Cerebral Schweiz und der Schweizerischen Stiftung für das
			cerebral gelähmte Kind</p>
		<p style="text-align: right; margin-right: 15mm;font-size: 10px"> page <strong>2</strong> sur <strong>2</strong>
		</p>
	</page_footer>
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
		$pdf->output('contratIntervenant.pdf');
	} catch (\Spipu\Html2Pdf\Exception\Html2PdfException $e) {
		die($e);
	};

?>
