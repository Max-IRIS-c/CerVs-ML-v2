<?php include('header.php');
$id  = $_GET["conId"] ;
$bdd = new PDO($dsn2,$user,$password); 
$sql = 'SELECT * FROM gescon_basecontacts ';
$req = $bdd->prepare($sql . ' WHERE conId =  :id');
$req->execute(['id' => $_GET['conId']]);

if(isset($_POST['retour'])) // Si le formulaire a été validé
{
	
	 header("location: listeContacte.php");
}
{
	
}

 ?>
<form name="add_user" method="post">
<?php 
		while ($donnees = $req->fetch()) 
		{ 
		?> 
	<div id="cordonee">
	<h2 class="titre1"> Coordonnées du contact  </h2>
	<table>

	<tr>
		<td> Titre  </td>		
		<td><input type="text" name="TITRE" value="<?php echo($donnees[TITRE ]) ;?>"></td>
	</tr>
	<tr>
		<td> Nom  </td>		
		<td><input type="text" name="NOM" value="<?php echo($donnees[NOM ]) ;?>"></td>
	</tr>
	<tr>
		<td>Prénom </td>
		<td><input type="text" name="PRENOM" value="<?php echo($donnees[PRENOM ]) ;?>"></td>
	</tr>
	<tr>
		<td>Complément </td>
		<td><input type="text" name="Complément" value="<?php echo($donnees[Complément ]) ;?>"></td>
	</tr>
	<tr>
		<td>ADRESSE  </td>
		<td><input type="text" name="ADRESSE" value="<?php echo($donnees[ADRESSE ]) ;?>"></td>
	</tr>
	<tr>
		<td>NPA   </td>
		<td><input type="text"  name="NPA" value="<?php echo($donnees[NPA]) ;?>"></td>
	</tr>
	<tr>
		<td>LOCALITE   </td>
		<td><input type="text" name="LOCALITE" value="<?php echo($donnees[LOCALITE]) ;?>"></td>
	</tr>
	<tr>
		<td>TELPRIVE   </td>
		<td><input type="tel" name="TELPRIVE" value="<?php echo($donnees[TELPRIVE]) ;?>"></td>
	</tr>
	<tr>
		<td>TELPROF   </td>
		<td><input type="tel" name="TELPROF" value="<?php echo($donnees[TELPROF]) ;?>"></td>
	</tr>
	<tr>
		<td>Natel   </td>
		<td><input type="tel" name="Natel" value="<?php echo($donnees[Natel]) ;?>"></td>
	</tr>
	<tr>
		<td>FAX   </td>
		<td><input type="tel" name="FAX" value="<?php echo($donnees[FAX]) ;?>"></td>
	</tr>
	<tr>
		<td>e-mail   </td>
		<td><input id="Mail" type="text" name="Email" value="<?php echo($donnees[Email]) ;?>"></td>
	</tr>
	<tr>
		<td>Contact inactif   </td>
		<td><?php 
		if ($donnees['ContactInactif'] == 1)
		{echo '<INPUT type="checkbox" name="ContactInactif" value="1" checked>';}
		else
		{echo '<INPUT type="checkbox" name="ContactInactif" value="0" >';}
		?>
		</td>
	</tr>
	</table>
	</div>
	<!------------------------------------------------------------------------------------------------------------------------------------- Membre---------------------------------------------------------->
	<div id="membres">
	<h2>Membre</h2>
	<table>
		<tr>
		<td> Membre  </td>		
		<td><input type="text" name="Membre" value="<?php echo($donnees[Membre ]) ;?>"></td>
	</tr>
	<tr>
		<td> Type  </td>		
		<td><input type="text" name="TypeMembre" value="<?php echo($donnees[TypeMembre ]) ;?>"></td>
	</tr>
	<tr>
		<td>Donnateur </td>
		<td><?php 
		if ($donnees['Donateur'] == 1)
		{echo '<INPUT type="checkbox" name="Donateur" value="1" checked>';}
		else
		{echo '<INPUT type="checkbox" name="Donateur" value="0" >';}
		?>
		</td>
	</tr>
	</table>
	</div>
	<!------------------------------------------------------------------------------------------------------------------------------------- Info Divers sur le contacte-------------------------------------->
	<div id="infoDivers">
	<h2>Infos diverses</h2>
	<table>
	<tr>
		<td> Langue  </td>		
		<td><input type="text" size="1" name="Langue" value="<?php echo($donnees[Langue ]);?>"></td>
	</tr>
	<tr>
		<td> Région  </td>		
		<td><input type="text" name="Région" value="<?php echo($donnees[Région]) ;?>"></td>
	</tr>
	<tr>
		<td> ENTREE  </td>		
		<td><input type="text"  size="7" name="ENTREE" value="<?php echo($donnees[ENTREE]) ;?>"></td>
	</tr>
	<tr>
		<td> NAISSANCE  </td>		
		<td><input type="text" size="7" name="NAISSANCE" value="<?php echo($donnees[NAISSANCE]) ;?>"></td>
	</tr>
	<tr>
		<td> noAVS  </td>		
		<td><input type="text" name="noAVS" value="<?php echo($donnees[noAVS]) ;?>"></td>
	</tr>
		
	</table>
	</div>
	<!------------------------------------------------------------------------------------------------------------------------------------Type de contacte------------------------------------------------>
	<div id="typeContacte">
	<h2>Membre en qualité de</h2>
	<table>
	<tr>
		<td> Parent  </td>		
		<td><?php 
		if ($donnees['Parent'] == 1)
		{echo '<INPUT type="checkbox" name="Parent" value="1" checked>';}
		else
		{echo '<INPUT type="checkbox" name="Parent" value="0" >';}
		?></td>
		<td> Handicapé</td>		
		<td><?php 
		if ($donnees['Handicape'] == 1)
		{echo '<INPUT type="checkbox" name="Handicape" value="1" checked>';}
		else
		{echo '<INPUT type="checkbox" name="Handicape" value="0" >';}
		?></td>

	</tr>
	<tr>
		<td> Adulte  </td>		
		<td><?php 
		if ($donnees['Adulte'] == 1)
		{echo '<INPUT type="checkbox" name="Adulte" value="1" checked>';}
		else
		{echo '<INPUT type="checkbox" name="Adulte" value="0" >';}
		?></td>
		<td> Ado  </td>		
		<td><?php 
		if ($donnees['Ado'] == 1)
		{echo '<INPUT type="checkbox" name="Ado" value="1" checked>';}
		else
		{echo '<INPUT type="checkbox" name="Ado" value="0" >';}
		?></td>
		<td> Enfant  </td>		
		<td><?php 
		if ($donnees['Enfant'] == 1)
		{echo '<INPUT type="checkbox" name="Enfant" value="1" checked>';}
		else
		{echo '<INPUT type="checkbox" name="Enfant" value="0" >';}
		?></td>
	</tr>
	
	</table>
	<h2>Type de contact</h2>
	<table>
	<tr>
		<td> Comité  </td>		
		<td><?php 
		if ($donnees['COMITE'] == 1)
		{echo '<INPUT type="checkbox" name="COMITE" value="1" checked>';}
		else
		{echo '<INPUT type="checkbox" name="COMITE" value="0" >';}
		?></td>
		<td> Employé  </td>		
		<td><?php 
		if ($donnees['Employe'] == 1)
		{echo '<INPUT type="checkbox" name="Employe" value="1" checked>';}
		else
		{echo '<INPUT type="checkbox" name="Employe" value="0" >';}
		?></td>
		<td> Partenaire  </td>		
		<td><?php 
		if ($donnees['Entreprise'] == 1)
		{echo '<INPUT type="checkbox" name="Entreprise" value="1" checked>';}
		else
		{echo '<INPUT type="checkbox" name="Entreprise" value="0" >';}
		?></td>
	</tr>
	<tr>
		<td> Bénévole  </td>		
		<td><?php 
		if ($donnees['Benevole'] == 1)
		{echo '<INPUT type="checkbox" name="Benevole" value="1" checked>';}
		else
		{echo '<INPUT type="checkbox" name="Benevole" value="0" >';}
		?></td>
		<td> Accompagnant  </td>		
		<td><?php 
		if ($donnees['Accompagnant'] == 1)
		{echo '<INPUT type="checkbox" name="Accompagnant" value="1" checked>';}
		else
		{echo '<INPUT type="checkbox" name="Accompagnant" value="0" >';}
		?></td>
		<td> Institution  </td>		
		<td><?php 
		if ($donnees['INSTITUTION'] == 1)
		{echo '<INPUT type="checkbox" name="INSTITUTION" value="1" checked>';}
		else
		{echo '<INPUT type="checkbox" name="INSTITUTION" value="0" >';}
		?></td>
	</tr>
	<tr>
		<td> Ami, bienfaiteur  </td>		
		<td><?php 
		if ($donnees['Invi_ami'] == 1)
		{echo '<INPUT type="checkbox" name="Invi_ami" value="1" checked>';}
		else
		{echo '<INPUT type="checkbox" name="Invi_ami" value="0" >';}
		?></td>
		<td> Intervenant  </td>		
		<td><?php 
		if ($donnees['Intervenant'] == 1)
		{echo '<INPUT type="checkbox" name="Intervenant" value="1" checked>';}
		else
		{echo '<INPUT type="checkbox" name="Intervenant" value="0" >';}
		?></td>
		<td> Medecin  </td>		
		<td><?php 
		if ($donnees['Medecin'] == 1)
		{echo '<INPUT type="checkbox" name="Medecin" value="1" checked>';}
		else
		{echo '<INPUT type="checkbox" name="Medecin" value="0" >';}
		?></td>
	</tr>
	<tr>
		<td> VIP  </td>		
		<td><?php 
		if ($donnees['Invi_vip'] == 1)
		{echo '<INPUT type="checkbox" name="Invi_vip" value="1" checked>';}
		else
		{echo '<INPUT type="checkbox" name="Invi_vip" value="0" >';}
		?></td>
		<td> Association  </td>		
		<td><?php 
		if ($donnees['ASSOCIATION'] == 1)
		{echo '<INPUT type="checkbox" name="ASSOCIATION" value="1" checked>';}
		else
		{echo '<INPUT type="checkbox" name="ASSOCIATION" value="0" >';}
		?></td>
		<td> Assurance  </td>		
		<td><?php 
		if ($donnees['Assurance'] == 1)
		{echo '<INPUT type="checkbox" name="Assurance" value="1" checked>';}
		else
		{echo '<INPUT type="checkbox" name="Assurance" value="0" >';}
		?></td>
	</tr>
	<tr>
		<td> Membre d'honneur  </td>		
		<td><?php 
		if ($donnees['memb_honeur'] == 1)
		{echo '<INPUT type="checkbox" name="memb_honeur" value="1" checked>';}
		else
		{echo '<INPUT type="checkbox" name="memb_honeur" value="0" >';}
		?></td>
		<td> Client Pavillon  </td>		
		<td><?php 
		if ($donnees['ClientPavillons'] == 1)
		{echo '<INPUT type="checkbox" name="ClientPavillons" value="1" checked>';}
		else
		{echo '<INPUT type="checkbox" name="ClientPavillons" value="0" >';}
		?></td>
		<td> Autres  </td>		
		<td><?php 
		if ($donnees['Autres'] == 1)
		{echo '<INPUT type="checkbox" name="Autres" value="1" checked>';}
		else
		{echo '<INPUT type="checkbox" name="Autres" value="0" >';}
		?></td>
	</tr>

	</table>
	</div>
	<!------------------------------------------------------------------------------------------------------------------------------------abonner---------------------------------------------------------->
	<div id="abonner">
		

<h2>Abonné</h2>
	<table>
	<tr>
		<td> Programme des activités  </td>		
		<td><?php 
		if ($donnees['Programme'] == 1)
		{echo '<INPUT type="checkbox" name="Programme" value="1" checked>';}
		else
		{echo '<INPUT type="checkbox" name="Programme" value="0" >';}
		?></td>
	</tr>
	<tr>
		<td> Bulletin CONNAITRE  </td>		
		<td><?php 
		if ($donnees['CONNAITRE'] == 1)
		{echo '<INPUT type="checkbox" name="CONNAITRE" value="1" checked>';}
		else
		{echo '<INPUT type="checkbox" name="CONNAITRE" value="0" >';}
		?></td>
		
	</tr>
	<tr>
		<td> Journal CEREBRAL Suisse  </td>		
		<td><?php 
		if ($donnees['CEREBRAL'] == 1)
		{echo '<INPUT type="checkbox" name="CEREBRAL" value="1" checked>';}
		else
		{echo '<INPUT type="checkbox" name="CEREBRAL" value="0" >';}
		?></td>
		
	</tr>
	
	</table>

	</div>
	<!--------------------------------------------------------------------------------------------------------------------------------------Interesser---------------------------------------------->
	<div id="intersser">
	<h2> Interessé par  </h2>
	<table>
	<tr>
		<td> Grp. ENFANTS  </td>		
		<td><?php 
		if ($donnees['ACTIFeNF'] == 1)
		{echo '<INPUT type="checkbox" name="ACTIFeNF" value="1" checked>';}
		else
		{echo '<INPUT type="checkbox" name="ACTIFeNF" value="0" >';}
		?></td>
		<td> LotoLeytron  </td>		
		<td><?php 
		if ($donnees['LotoLeytron'] == 1)
		{echo '<INPUT type="checkbox" name="LotoLeytron" value="1" checked>';}
		else
		{echo '<INPUT type="checkbox" name="LotoLeytron" value="0" >';}
		?></td>
	</tr>
	<tr>
		<td> Grp. ADOS  </td>		
		<td><?php 
		if ($donnees['ACTIFrELEVE'] == 1)
		{echo '<INPUT type="checkbox" name="ACTIFrELEVE" value="1" checked>';}
		else
		{echo '<INPUT type="checkbox" name="ACTIFrELEVE" value="0" >';}
		?></td>
		<td> LotoSion  </td>		
		<td><?php 
		if ($donnees['LotoSion'] == 1)
		{echo '<INPUT type="checkbox" name="LotoSion" value="1" checked>';}
		else
		{echo '<INPUT type="checkbox" name="LotoSion" value="0" >';}
		?></td>
	</tr>
	<tr>
		<td> Grp. TERIFICS  </td>		
		<td><?php 
		if ($donnees['ACTIFgJ'] == 1)
		{echo '<INPUT type="checkbox" name="ACTIFgJ" value="1" checked>';}
		else
		{echo '<INPUT type="checkbox" name="ACTIFgJ" value="0" >';}
		?></td>
		<td></td>		
		<td></td>
	</tr>
	<tr>
		<td> Grp. ADULTES  </td>		
		<td><?php 
		if ($donnees['ACTIFAdultes'] == 1)
		{echo '<INPUT type="checkbox" name="ACTIFAdultes" value="1" checked>';}
		else
		{echo '<INPUT type="checkbox" name="ACTIFAdultes" value="0" >';}
		?></td>
		<td> Services d'aides à domicile     </td>		
		<td><?php 
		if ($donnees['aidedomicil'] == 1)
		{echo '<INPUT type="checkbox" name="aidedomicil" value="1" checked>';}
		else
		{echo '<INPUT type="checkbox" name="aidedomicil" value="0" >';}
		?></td>
	</tr>
	<tr>
		<td> Grp. PARENTS  </td>		
		<td><?php 
		if ($donnees['ACTIFgM'] == 1)
		{echo '<INPUT type="checkbox" name="ACTIFgM" value="1" checked>';}
		else
		{echo '<INPUT type="checkbox" name="ACTIFgM" value="0" >';}
		?></td>
		<td>Service relève  </td>		
		<td><?php 
		if ($donnees['aidedomicil'] == 1)
		{echo '<INPUT type="checkbox" name="aidedomicil" value="1" checked>';}
		else
		{echo '<INPUT type="checkbox" name="aidedomicil" value="0" >';}
		?></td>
	</tr>
	<tr>
		<td> Week-end  </td>		
		<td><?php 
		if ($donnees['ACTIFwK'] == 1)
		{echo '<INPUT type="checkbox" name="ACTIFwK" value="1" checked>';}
		else
		{echo '<INPUT type="checkbox" name="ACTIFwK" value="0" >';}
		?></td>
		<td> Contribution d'assistance </td>		
		<td><?php 
		if ($donnees['contribassistant'] == 1)
		{echo '<INPUT type="checkbox" name="contribassistant" value="1" checked>';}
		else
		{echo '<INPUT type="checkbox" name="contribassistant" value="0" >';}
		?></td>
	</tr>
	<tr>
		<td> Camp  </td>		
		<td><?php 
		if ($donnees['ACTIFcAMP'] == 1)
		{echo '<INPUT type="checkbox" name="ACTIFcAMP" value="1" checked>';}
		else
		{echo '<INPUT type="checkbox" name="ACTIFcAMP" value="0" >';}
		?></td>
		<td> Ecole  </td>		
		<td><?php 
		if ($donnees['relevescolaire'] == 1)
		{echo '<INPUT type="checkbox" name="relevescolaire" value="1" checked>';}
		else
		{echo '<INPUT type="checkbox" name="relevescolaire" value="0" >';}
		?></td>
	</tr>
	</table>
	</div>

	<div id="commentaire">
	<h2> commentaire </h2>
	<textarea rows="6" cols="47">
		<?php echo($donnees[COMMENTAIRES]) ;?>
		</textarea>
		
	</div>
<?php
 if ($donnees['Accompagnant'] == 1)
 {
 ?>
	



	<div id="InfoSpesifique"> 
	<h2> Infos spécifiques accompagnant </h2>
	<table>
	<tr>
		<td> Permis Voiture  </td>		
		<td><?php 
		if ($donnees['permiscond_B'] == 1)
		{echo '<INPUT type="text" name="permiscond_B" value="Oui" size=2 >';}
		else
		{echo '<INPUT type="text" name="permiscond_B" value="Non" size=2 >';}
		?></td>
		<td> Permis D1  </td>		
		<td><?php 
		if ($donnees['permiscond_D1'] == 1)
		{echo '<INPUT type="text" name="permiscond_D1" value="Oui" size=2 >';}
		else
		{echo '<INPUT type="text" name="permiscond_D1" value="Non" size=2 >';}
		?></td><td> Autocar (D) </td>		
		<td><?php 
		if ($donnees['permiscond_D'] == 1)
		{echo '<INPUT type="text" name="permiscond_D" value="Oui"size=2 >';}
		else
		{echo '<INPUT type="text" name="permiscond_D" value="Non"size=2 >';}
		?></td>
	</tr>
	<tr>
		<td> Etat civil  </td>		
		<td><input type="text" name="etatcivil" value="<?php echo($donnees[etatcivil ]) ;?>"></td>
	</tr>
	<tr>
		<td> Nationalité  </td>		
		<td><input type="text" name="nationalite" value="<?php echo($donnees[nationalite ]) ;?>"></td>
		<td> Permis de séjour  </td>		
		<td><input type="text"  name="permisejour" value="<?php echo($donnees[permisejour ]) ;?>"></td>
		<td> Validité  </td>		
		<td><input type="text"   name="validpermsejour" value="<?php echo($donnees[validpermsejour ]) ;?>"></td>
	</tr>
	<tr>
		<td> Banque  </td>		
		<td><input type="text" name="banque" value="<?php echo($donnees[banque ]) ;?>"></td>
	</tr>
	<tr>
		<td> Agence de </td>		
		<td><input type="text" name="agence" value="<?php echo($donnees[agence ]) ;?>"></td>
		<td>
		</td>
		<td>
		</td>
		<td> IBAN/cpte: </td>		
		<td><input type="text" size="26" name="banque" value="<?php echo($donnees[IBAN ]) ;?>"></td>
	</tr>


	</table>
	</div>
<?php
}




		
		?><table>
				<tr> marqueur</tr>
				<tr> <input type="text" size="26" name="banque" value="<?php echo($donnees[Marquage ]) ;?>"></tr>
		</table>
		 
		 <?php
		} 
		
		?> 
		 
<input type="submit" name="retour"  value="Retour" class="agrandirAnuller" />


</div>


</form>

<?php include('footer.php'); ?>