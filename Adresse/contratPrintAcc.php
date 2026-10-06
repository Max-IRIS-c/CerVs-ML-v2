<?php
	session_start();
	ob_start();
	include_once "../src/class/Db.class.php";
	include_once "../src/class/Mrp.class.php";
	$mrp = new Mrp();
	include('../variables.php');
	$bdd = new PDO($dsn, $user, $password);
	$id = $_GET['Id'];
	$contrat = $bdd->query("SELECT * FROM tblContratAcc
LEFT JOIN tblTypeAcc on contType = tAccId WHERE contId ='$id'");
	$contrat = $contrat->fetch();
	$employer = $contrat['conId'];
	$contact = $bdd->query("SELECT conNpa, conLocaliter, conNom, conPrenom, conDateNaissance,conAdresse, conAvs,natNom,
 conBanque, conAgence, conIban FROM tblContact
  LEFT JOIN tblNationaliter on conNationalite = natId WHERE conId = '$employer'");
	$contact = $contact->fetch();
	
	use Spipu\Html2Pdf\Html2Pdf;


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
		#anneeInput{
			width: 55px;
			text-align: center;
			vertical-align: middle;
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
			<h1 style="margin-top:-4mm; font-size: 14px">Auxiliaire- <?php echo ($contrat['tAccNom'] ); ?> pour l'année <input id="anneeInput" type="text" value="<?php echo $contrat['contAnnee']; ?>" /><br><br></h1>


			<h1 style="text-align: center; margin-top: 15mm;">CONVENTION D'AUXILIAIRE : <?php echo strtoupper($contrat['tAccNom']); ?></h1>
			<p style="text-align: center; font-style: italic; font-size: 10px">
				Ce poste est ouvert aussi bien aux hommes qu’aux femmes.<br/>
				Pour des raisons de langage simplifié l’usage du masculin est utilisé.
			</p>
			<br/>

			<p>
				Cette convention se base sur les inscriptions reçues de votre part. La convention comprend le
				descriptif de fonction, le code de conduite ainsi que la déclaration d’engagement personnel signée.
				La convention est annuelle et sera valide pour tous les séjours (week-end ou camp) auxquels vous
				participerez de manière effective durant l’année. Les inscriptions, modifications et/ou annulations à
				des séjours en cours d’année vous seront confirmées par téléphone ou par courriel uniquement.
				Les tarifs ci-dessous font foi et seront appliqués sur la base de la liste des présences durant le
				séjour.
			</p>

			<p>
				Par votre signature, vous confirmez avoir pris connaissance de la présente convention, du
				descriptif de fonction ainsi que du code de conduite et vous vous engagez à les respecter.
			</p>
			<br/>
			<p>
				<strong>Mandante :</strong> L'Association Cerebral Valais, dénommée ci-après Cerebral Valais
				<br/>
				<strong>Et :</strong> L'auxiliaire <?php echo ($contrat['tAccNom'] ); ?>, dénommée ci-après <?php echo ($contrat['tAccNom'] ); ?> :
			</p>
			<table style="margin-bottom: ^5mm">
				<tr>
					<td> Prénom & Nom</td>
					<td><strong><?php echo($contact['conPrenom'] . ' ' . $contact['conNom']) ?></strong></td>
				</tr>
				<tr>
					<td>Date de naissance</td>
					<td><strong><?php echo dateToUser($contact['conDateNaissance']) ?></strong></td>
				</tr>
				<tr>
					<td>Lieu d'origine / Nationalité</td>
					<td><strong><?php echo $contact['natNom'] ?></strong></td>
				</tr>
				<tr>
					<td>Adresse</td>
					<td><strong><?php echo $contact['conAdresse'] . ' ' . $contact['conNpa'] . ' ' . $contact['conLocaliter'] ?></strong></td>
				</tr>
				<tr>
					<td>N° AVS</td>
					<td><strong><?php echo $contact['conAvs'] ?></strong></td>
				</tr>
				<tr>
					<td>Coordonnées bancaires (nom et lieu)&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
					<td><strong><?php echo $contact['conBanque'] . ' ' . $contact['conAgence'] ?></strong></td>
				</tr>
				<tr>
					<td>Compte IBAN</td>
					<td><strong><?php echo $contact['conIban'] ?></strong></td>
				</tr>
			</table>

			<h2>1. Fonction</h2>
			<p>
				La personne <?php echo ($contrat['tAccNom'] ); ?> dans le domaine social assure en principe une prise en charge individuelle
				de la personne en situation de handicap qui lui est confiée. Il aide et soutien la personne
				responsable du séjour dans la réalisation des activités. Les détails sont consignés dans le
				descriptif de fonction.
			</p>

			<h2>2. Défraiement</h2>
			<p>
				Le défraiement s’élève à CHF. 100.00 / jour comme <?php echo ($contrat['tAccNom'] ); ?> et à CHF. 130.00 / jour
				comme <?php echo ($contrat['tAccNom'] ); ?> chauffeur. Pour favoriser l’entrée dans le monde du travail, les
				cotisations aux charges sociales de base l’AVS/AI/APG/AC sont retenues sur le montant du
				défraiement. Les frais de transport du domicile au lieu de l'activité sont à la charge de l'auxiliaire.
			</p>

			<h2>3. Lieu d'activité :</h2>
			<p>En fonction du lieu fixé sur la convocation au séjour (feuille de route).</p>

			<h2>4. Assurances</h2>
			<p>
				La personne <?php echo ($contrat['tAccNom'] ); ?> est assuré par Cerebral Valais contre les accidents professionnels, y compris
				ceux survenant sur le chemin direct entre le domicile et le lieu de travail. Pour les accidents
				non professionnels, le <?php echo ($contrat['tAccNom'] ); ?> est assuré selon le contrat de base prévu par la LAA (loi
				sur l’assurance accident). En cas de transport de personnes, l’assurance propre du détenteur
				du véhicule intervient (RC occupants).
			</p>
		</div>
		<page_footer>
			<p style="color: #00AA00; font-size: 10px; text-align: center">
				Association Cerebral Valais&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;
				Tél. 027 346 70 44&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;
				www.cerebral-valais.ch&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;CH10 8057 2000 0099 9224 8
				___________________________________________________________________________________________________________________</p>
			<p style="font-size: 10px; text-align: center">En étroite collaboration avec l’association Cerebral Suisse
				et la
				Fondation suisse
				en faveur de l’enfant infirme moteur cérébral<br>
				In enger Zusammenarbeit mit der Vereinigung Cerebral Schweiz und der Schweizerischen Stiftung für das
				cerebral gelähmte Kind</p>
			<p style="text-align: right; margin-right: 15mm;font-size: 10px"> page <strong>1</strong> sur
				<strong>2</strong>
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
			<h2>5. Accord pour les photos</h2>
			<p>
				J'accepte que des photos de moi soient éventuellement utilisées pour la communication
				(flyers, brochures), sur le site internet et/ou les réseaux sociaux de l'Association Cerebral
				Valais.
			</p>
			<p>
				O oui O non O oui, uniquement sur demande
			</p>
			<br/>

			<p>
				Je suis conscient que des photos lors des activités peuvent être prises par des participants ou
				des accompagnants. Cas échéant, l'Association Cerebral Valais et ses collaborateurs ne sont
				pas responsable de leur diffusion.
			</p>
			<p>O oui</p>
			<br/>

			<p>
				Je m'engage à traiter les données et les photos de tous les participants avec bienveillance et
				à respecter leur sphère privée (pas de diffusion de photos et de numéros de téléphone sans
				l'accord de la personne concernée).
			</p>
			<p>O oui</p>
			<br/>

			<h2>6. Protection des données</h2>
			<p>
				Je suis conscient que mes données personnelles peuvent être transmises à des tiers
				(organisme de transport, hébergement, administration publique, assureurs, etc.) afin de
				pouvoir organiser la prestation et en assurer la qualité.
			</p>
			<p>O oui</p>
			<br/>

			<h2>7. En cas de faute grave</h2>
			<p>
				Cerebral Valais peut mettre un terme à la présente convention avec effet immédiat.
				<br/>
				<br/>
				Pour tout ce qui n’est pas prévu dans la présente convention, le code des obligations et les lois
				fédérales et cantonales sur le travail sont applicables.
				<br/>
				<br/>
				Le <?php echo ($contrat['tAccNom'] ); ?> s’engage à garder le secret sur toutes les informations auxquelles il a accès de par sa
				fonction.
			</p>
			<table id="tableTest">
				<tr>
					<th>Lieu et date :</th>
					<th>Lieu et date :</th> 
				</tr>
				<tr>
					<td colspan="1" style="width: 50%">L'auxiliaire - <?php echo ($contrat['tAccNom'] ); ?> :
						<br/><?php echo $contact['conPrenom'] . ' ' . $contact['conNom'] ?>
					</td>
					<td colspan="2">L'employeur : <br/>Association Cerebral Valais</td>
				</tr>
				<tr> <!-- afficher meme image blanche pour aligner les champs correctement -->
					<td><p>Signature : <img id="signature" src="../Adresse/signature_bruno_hidden.png" /></p></td>
					<td col="2"><p>Signature :<img id="signature" src="../Adresse/signature_bruno.png" /></p></td>
				</tr>
				<tr style="padding-top: 10px;">
					<td><p>Signature du parent :<br/> si mineur</p></td>
					<td><p>Signature :</p></td>
					<td></td>
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
					height: 5%;
				}
				#bruno{
					display: flex;
					align-items: center;
					justify-content: left;
				}
				#signatureH{
					background-color: white;
				}
			</style>

			<br/>
			<div>
				<p style="font-size: 11px">
					<strong>Référence en lien avec Cerebral Suisse :</strong>
					<br/>
					<span style="font-style: italic;">"Accompagnant-e de vacances pour personnes avec handicap
						<br/>
						Une tâche aux multiples facettes" </span>
					<br/>
					<br/>
					Sur le site de Cerebral Valais :
					<br/>
					<a style="color: blue" href="https://cerebral-vs.ch/wp-content/uploads/2024/01/Code-de-conduite_F.pdf">https://cerebral-vs.ch/wp-content/uploads/2024/01/Code-de-conduite_F.pdf</a>
				</p>
			</div>
		</div>
		<page_footer>
			<p style="color: #00AA00; font-size: 10px; text-align: center">
				Association Cerebral Valais&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;
				Tél. 027 346 70 44&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;
				www.cerebral-valais.ch&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;CH10 8057 2000 0099 9224 8
				___________________________________________________________________________________________________________________
			</p>
			<p style="font-size: 10px; text-align: center">En étroite collaboration avec l’association Cerebral Suisse
				et la
				Fondation suisse
				en faveur de l’enfant infirme moteur cérébral<br>
				In enger Zusammenarbeit mit der Vereinigung Cerebral Schweiz und der Schweizerischen Stiftung für das
				cerebral gelähmte Kind</p>
			<p style="text-align: right; margin-right: 15mm;font-size: 10px"> page <strong>2</strong> sur
				<strong>2</strong>
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