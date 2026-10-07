<?php include('../header.php');

$id = $_GET["conId"];
$_SESSION['contact'] = $id;
$searchedParam = $_GET['searchedParam'] ?? '';
$bdd = new PDO($dsn, $user, $password, [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
]);
$contact = $bdd->query("SELECT * FROM tblContact
LEFT JOIN tblCiviliter on tblCiviliter_civId = civId
LEFT JOIN tblMembre on conMembre = memId
LEFT JOIN tblMembreType on conTypeMembre = mTypId
LEFT JOIN tblangues on conLangues = lanId
LEFT JOIN tblRegionCon on conRegion = regConId
LEFT JOIN tblEtatCivile on conEtatcivil = etaCivId
LEFT JOIN tblPermisSejour on conPermisejour = pSejourId
LEFT JOIN tblNationaliter on conNationalite = natId WHERE
conId ='$id'");
$contact = $contact->fetch();

$infoMedical = $bdd->query("SELECT * from tblMedical 
LEFT JOIN tblHandicape on medOfasType = hanId
LEFT JOIN tblArt on medOfasReconnu = artId 
LEFT JOIN tblDegreImpot on medDegre = impId
LEFT JOIN tblOfasBesoin on ofasBesoin = OfasId
LEFT JOIN tblCanton on ofasCanton = cantId WHERE medConId = '$id'");
$infoMedical = $infoMedical->fetch();

$tel1 = $bdd->query("SELECT tTelNom FROM tblTypeTelephone WHERE tTelId ='$contact[conTel1T]'");
$tel1 = $tel1->fetch();
$tel2 = $bdd->query("SELECT tTelNom FROM tblTypeTelephone WHERE tTelId ='$contact[conTel2T]'");
$tel2 = $tel2->fetch();
$tel3 = $bdd->query("SELECT tTelNom FROM tblTypeTelephone WHERE tTelId ='$contact[conTel3T]'");
$tel3 = $tel3->fetch();
$tel4 = $bdd->query("SELECT tTelNom FROM tblTypeTelephone WHERE tTelId ='$contact[conTel4T]'");
$tel4 = $tel4->fetch();
$don = $bdd->query("SELECT max(cotiDate) FROM tblCotisation where tblContact_conId = $id");
$don = $don->fetch();


/* className attribué au menu "contributions" */
//$statusOfContributionsTitle = $contributionscouldAppear ? isContributeur($bdd, $id, $contact['conMembre']) : '';

if (isset($_POST['Valider'])) // Si le formulaire a été validé
{
    $marquage = $_POST['Marquage'];

    $insert = $bdd->prepare("UPDATE tblContact SET conMarquage=:marquage WHERE conId = '$id'");

    $insert->execute(array(
        'marquage' => $marquage,
    ));
    header("location: detContacte.php?conId=" . $id."&searchedParam=".$searchedParam);
}
    if(isset($_POST['validation_marquage']) && $_POST['validation_marquage'] === 'validated'){
        try{
            $newMarquage = $_POST['Marquage'];
            // if(!$newMarquage) throw new Exception();
            $updateMarquage = $bdd->prepare("UPDATE tblContact SET conMarquage = :conMarquage WHERE conId = '$id'");
            $params = ['conMarquage' => $newMarquage];
            $updateMarquage->execute($params);
    }catch(Exception $e){

        }
        header("location: detContacte.php?conId=" . $id."&searchedParam=" . $searchedParam);
    }
?>
<!-------------------------------------- menu ---------------------------------------------->

<nav id="menu2">
    <ul>
        <li class="backToContacts">
            <?php 
                echo '<a href="contact.php?conId=' . $id . '&searchedParam=' . $searchedParam . '">'.$mrp->getText("Retour aux contacts").'</a>'; 
            ?>
        </li>
        <li class="textGauche"><?php echo '<a href="contact.php?conId=' . $id . '">'.$mrp->getText('Tous les contacts').'</a>'; ?></li>
        <?php if ($auth >= 3) { ?>

            <li class="textGauche"><?php echo '<a href="modifContact.php?conId=' . $id . '&searchedParam=' . $searchedParam . '">'.$mrp->getText('Modifier').'</a>'; ?></li>      
        <?php }
        if ($auth >= 4)  { ?>
            <li class="textGauche"><?php echo '<a href="supContact.php?Id=' . $id . '"> '.$mrp->getText('Supprimer').'</a>'; ?></li>
           <!-- CONTIBUTIONS -->
            <?php
                $constributionsBtStatus = classOfContributionsBt($bdd, $id, $contact['conMembre'], $contact['conMembLaPar'], $contact['conDonatLaPar']);
                echo '<li><a class="'.$constributionsBtStatus.'" href="cotisation.php?Id=' . $id . '"> '.$mrp->getText('Contributions').'</a></li>'; 
            ?>
            <!-- champs "accompagnant" -->
            <?php 
                $intervenantStatus = FormatTypeOfContact($contact['conIntervenant']);
                $intervenantGriser = ($intervenantStatus === 'Inactif') ? 'grise' : null;
                $accompagnantStatus = FormatTypeOfContact($contact['conAccompagnant']);
                $accompagnantIsGrise = ($accompagnantStatus === 'Inactif') ? 'grise' : null;
            } 
            if($auth >= 3 && ($accompagnantStatus === 'Oui' || $accompagnantStatus === 'Inactif')) { ?>

            </ul>
        </nav><nav id="menu2">
            <ul>
                <li>
                    <?php echo '<a href="contratAcc.php?Id=' . $id . '&searchedParam='.$searchedParam.'" class="'.$accompagnantIsGrise.'">'.$mrp->getText('Contrat accompagnant').'</a>'; ?>
                </li>
                <?php } 
                    if (($contact['conHandicaper'] == 1) AND ($contact['conSecondaire'] == 0)) { ?>
                    <li class="textGauche"><?php echo '<a href="../social/social.php?Id=' . $id . '&searchedParam='.$searchedParam.'">'.$mrp->getText('Infos. sociales').'</a>'; ?></li>
                    <li class="textGauche"><?php echo '<a href="../medical/medical.php?Id=' . $id . '&searchedParam='.$searchedParam.'">'.$mrp->getText('Infos. médicales').'</a>'; ?></li>
                    <?php if ($auth >=3){
                        echo '<li><a href="../Releve/releve.php?Id=' . $id . '&searchedParam='.$searchedParam.'">'.$mrp->getText("Service de relève").'</a> </li>';
                    }?>

            <?php }
            // --------- champs intervenants -----------
            if($auth >=3 && ($intervenantStatus === 'Oui' || $intervenantStatus === 'Inactif')) { ?>
                <li class="textGauche"><?php echo '<a class="'.$intervenantGriser.'" href="../intervention/disponibilite.php?Id=' . $id . '&searchedParam='.$searchedParam.'">Profil</a>'; ?></li>
                <li class="textGauche"><?php echo '<a class="'.$intervenantGriser.'" href="../intervention/intervention.php?Id=' . $id .'&searchedParam='.$searchedParam.'"> '.$mrp->getText('Interventions').'</a>'; ?></li>
                <li class="textGauche"><?php echo '<a class="'.$intervenantGriser.'" href="contrat.php?Id=' . $id . '&searchedParam='.$searchedParam.'"> '.$mrp->getText('Contrat intervenant').'</a>'; ?></li>
            <?php } ?>
    </ul>
</nav>
<!-------------------------------------- contenu ---------------------------------------------->
<!-- Début colonne gauche-->
<div id="cordonee" style="width: 48%; float: left;">
    <h2><?php echo $mrp->getText("Coordonnées du contact") ?></h2>
    <table class="noMargin" style="border: solid 1px; width: 100%">
	    <tr>
		    <th><?php echo $mrp->getText("Société") ?></th>
		    <td colspan="3"><?php echo $contact['conSociete'] ?></td>
	    </tr>
        <tr>
            <th><?php echo $mrp->getText("Titre") ?></th>
            <td><?php echo $contact['civNom'] ?></td>
            <th><?php echo $mrp->getText("Adresse secondaire") ?></th>
            <td><?php CheckBox($contact['conSecondaire']); ?></td>
        </tr>
        <tr>
            <th><?php echo $mrp->getText("Nom") ?></th>
            <td><?php echo $contact['conNom'] ?></td>
            <th><?php echo $mrp->getText("Prénom") ?></th>
            <td><?php echo $contact['conPrenom'] ?></td>
        </tr>
        <tr>
            <th><?php echo $mrp->getText("Complément") ?></th>
            <td colspan="3"><?php echo $contact['conComplement'] ?></td>
        </tr>
        <tr>
            <th><?php echo $mrp->getText("Adresse") ?></th>
            <td colspan="3"><?php echo $contact['conAdresse'] ?></td>
        </tr>
        <tr>
            <th><?php echo $mrp->getText("Adresse2") ?></th>
            <td colspan="3"><?php echo $contact['conAdresse2'] ?></td>
        </tr>
        <tr>
            <th><?php echo $mrp->getText("NPA") ?></th>
            <td><?php echo $contact['conNpa'] ?></td>
            <th><?php echo $mrp->getText("Localité") ?></th>
            <td><?php echo $contact['conLocaliter'] ?></td>
        </tr>
        <tr>
            <th><?php echo $tel1[0] ?? '' ?></th>
            <td><?php echo $contact['conTel1'] ?? '' ?></td>
            <th><?php echo $tel2[0] ?? '' ?></th>
            <td><?php echo $contact['conTel2'] ?? '' ?></td>
        </tr>
        <tr>
            <th><?php echo $tel3[0] ?? '' ?></th>
            <td><?php echo $contact['conTel3'] ?? '' ?></td>
            <th><?php echo $tel4[0] ?? '' ?></th>
            <td><?php echo $contact['conTel4'] ?? '' ?></td>
        </tr>
        <tr>
            <th><?php echo $mrp->getText("e-mail") ?></th>
            <td colspan="3"><?php echo $contact['conMail'] ?? '' ?></td>
        </tr>
    </table>
    <h2><?php echo $mrp->getText("Infos diverses sur le contact") ?></h2>
    <table class="noMargin" style="border: solid 1px">
        <tr>
            <th><?php echo $mrp->getText("Date de naissance") ?></th>
            <td><?php echo dateToUser($contact['conDateNaissance']) ?></td>
            <th><?php echo $mrp->getText("Numéro AVS") ?></th>
            <td><?php echo $contact['conAvs']; ?></td>
        </tr>
        <tr>
            <th><?php echo $mrp->getText("Résident dans la région") ?></th>
            <td><?php echo $contact['regConNom']; ?></td>
            <th><?php echo $mrp->getText("Parlant (langue)") ?></th>
            <td><?php echo $contact['lanNom']; ?></td>
        </tr>
        <tr>
            <th><?php echo $mrp->getText("Création du contact") ?></th>
            <td><?php echo dateToUser($contact['conCreation']) ?></td>
            <th><?php echo $mrp->getText("Modifié le") ?></th>
            <td><?php echo dateToUser($contact['conModif']) ?></td>
        </tr>
    </table>
    <h2>Infos spécifiques accompagnant / intervenant</h2>
    <table class="noMargin" style="border: solid 1px; width:100%;">
        <tr>
            <td><?= $mrp->getText("Disponible comme") ?></td>
        <tr>
        <tr>
            <th><?= $mrp->getText("Accompagnant pour")?>:</th>
            <td></td>
            <th><?= $mrp->getText("Intervenant pour")?>:</th>
            <td></td>
        </tr>
        <tr> 
            <th><?php echo $mrp->getText("Cerebral") ?></th>
            <td><?php echo $accompagnantStatus ?? ''; ?></td>
            <th><?php echo $mrp->getText("Relève") ?></th>
            <td><?php echo $intervenantStatus ?? ''; ?></td>                  
        </tr>
        <tr>
            <th>Parenthèse</th>
            <td><?php echo FormatTypeOfContact($contact['accompagnantParenthese']) ?></td> 
            <th>Parenthèse</th>
            <td><?php echo FormatTypeOfContact($contact['intervenantParenthese']) ?></td> 
  
        </tr>
         <tr>             
            <th></th>
            <td></td>
            <th><?php echo $mrp->getText("Cont. Assist.") ?></th>
            <td><?php echo FormatTypeOfContact($contact['intervenantCA']) ?></td> 
        </tr>
        <tr>
            <td> <?php echo $mrp->getText("Autres infos") ?> </td>
        <tr>
            <th><?php echo $mrp->getText("Permis de conduire voiture (B)") ?></th>
            <td><?php CheckBox($contact['conPermisB']) ?></td>
            <th>Bus 8-16 pl (D1)</th>
            <td><?php CheckBox($contact['conPermiD1']) ?></td>
        </tr>
        <tr>
            <th><?php echo $mrp->getText("Nationalité") ?></th>
            <td><?php echo $contact['natNom'] ?></td>
            <th><?php echo $mrp->getText("Etat civil") ?></th>
            <td><?php echo $contact['eatCivNom'] ?></td>
        </tr>
    
        <tr>
            <th><?php echo $mrp->getText("Permis de séjour") ?></th>
            <td><?php echo $contact['pSejourNom'] ?></td>
            <th> <?php echo $mrp->getText("Validité") ?></th>
            <td> <?php echo dateToUser($contact['conValidPermSejour']); ?></td>
        </tr>
        <tr>
            <th><?php echo $mrp->getText("Banque") ?></th>
            <td><?php echo $contact['conBanque'] ?></td>
            <th><?php echo $mrp->getText("Délivrance") ?></th>
            <td><?php echo dateToUser($contact['conDelivrance']); ?></td>
        </tr>
        <tr>
            <th><?php echo $mrp->getText("Agence de") ?></th>
            <td colspan="5"><?php echo $contact['conAgence'] ?> </td>
        </tr>
        <tr>
            <th><?php echo $mrp->getText("IBAN/cpte") ?></th>
            <td colspan="5"><?php echo $contact['conIban'] ?></td>
        </tr>
        <tr>
            <th><?php echo $mrp->getText("Responsable") ?></th>
            <td><?php CheckBox($contact['conResponsable']) ?></td>
            <th><?php echo $mrp->getText("Co-responsable") ?></th>
            <td><?php CheckBox($contact['conCoResponsable']) ?></td>
        </tr>
        <tr>
            <th><?php echo $mrp->getText("Accompagnant chauffeur") ?></th>
            <td><?php CheckBox($contact['conAccompagnantChauffeur']) ?></td>
            <th><?php echo $mrp->getText("Resp. des soins") ?></th>
            <td><?php CheckBox($contact['conInfirmiere']) ?></td>
        </tr>
        <tr>
            <th><?php echo $mrp->getText("Cuisinier") ?></th>
            <td><?php CheckBox($contact['conCuisinier']) ?></td>
            <th><?php echo $mrp->getText("Intendant (e)") ?></th>
            <td><?php CheckBox($contact['conIntendant']) ?></td>
        </tr>
    </table>

    <table width="100%">
        <tr>
            <th> <?php echo $mrp->getText("Commentaire(s)") ?></th>
        </tr>
        <tr>
            <td colspan="3"><?php echo nl2br($contact['conCommentaire']) ?></td>
        </tr>

    </table>
</div>
<!-- Début colonne droite-->
<div id="infoDroit" style="width: 48%; margin-left: 52%;">
    <h2><?php echo $mrp->getText("Qualité de contact") ?></h2>
    <table class="noMargin" style="border: solid 1px;">
        <tr>
            <th><?php echo $mrp->getText("Parent/ tuteur") ?></th>
            <td><?php CheckBox($contact['conParent']); ?></td>
        </tr>
        <tr>
            <th><?php echo $mrp->getText("Personne en situation de handicap") ?></th>
            <td><?php CheckBox($contact['conHandicaper']); ?></td>
            <td><?php 
                if ($contact['conAdulte'] == 1) {
                    echo " ".$mrp->getText('Adulte');
                } else {
                    echo $mrp->getText('Enfant');
                } ?></td>
        </tr>
    </table>
    <table class="noMargin" style="border: solid 1px;">
        <tr>
            <th colspan="6"><?php echo $mrp->getText("Pour l'association") ?></th>
        </tr>
        <tr>
            <th><?php echo $mrp->getText("Comité") ?></th>
            <td><?php CheckBox($contact['conComiter']); ?></td>
            <th><?php echo $mrp->getText("Employé") ?></th>
            <td><?php CheckBox($contact['conEmploye']); ?></td>
            <th><?php echo $mrp->getText("Bénévole") ?></th>
            <td><?php CheckBox($contact['conBenevole']); ?></td>
        </tr>
        <tr>
            <th colspan="6"><?php echo $mrp->getText("Proche de l'association (partenaires et clients)") ?></th>
        </tr>
        <tr>
            <th><?php echo $mrp->getText("Partenaire") ?></th>
            <td><?php CheckBox($contact['conEntreprise']); ?></td>
            <th><?php echo $mrp->getText("Institution") ?></th>
            <td><?php CheckBox($contact['conInstitution']); ?></td>
            <th><?php echo $mrp->getText("Association") ?></th>
            <td><?php CheckBox($contact['conAssociation']); ?></td>
        <tr>
            <th><?php echo $mrp->getText("Ami, bienfaiteur") ?></th>
            <td><?php CheckBox($contact['conInviAmi']); ?></td>
            <th><?php echo $mrp->getText("VIP") ?></th>
            <td><?php CheckBox($contact['conInviVip']); ?></td>
        </tr>
        <tr>
            <th><?php echo $mrp->getText("Médecin") ?></th>
            <td><?php CheckBox($contact['conMedecin']); ?></td>
            <th><?php echo $mrp->getText("Assurance") ?></th>
            <td><?php CheckBox($contact['conAssurance']); ?></td>
            <th><?php echo $mrp->getText("Autre") ?></th>
            <td><?php CheckBox($contact['conAutresTypes']); ?></td>
        </tr>
        <tr>
            <th><?php echo $mrp->getText("Client pavillons") ?></th>
            <td><?php CheckBox($contact['conClientPavillons']); ?></td>
            <th>Client la parenthèse</th>
            <td><?php CheckBox($contact['conClieLaPar']) ?></td>
        </tr>

    </table>
    <h2><?php echo $mrp->getText("Qualité de contributeur") ?></h2>
    <table class="noMargin" style="border: solid 1px;">
        <tr>
            <th><?php echo $mrp->getText("Membre Cerebral") ?></th>
            <td><?php echo $contact['memNom'] ?></td>
            <th><?php echo $mrp->getText("Type") ?></th>
            <td><?php echo $contact['mTypNom'] ?></td>
        </tr>
        <tr>
            <th><?php echo $mrp->getText("Donateur Cerebral") ?></th>
            <td><?php CheckBox($contact['conDonateur']); ?></td>
            <th><?php echo $mrp->getText("Dernier don") ?></th>
            <td> <?php echo dateToUser($don[0]) ?></td>
        </tr>
        <tr>
            <th colspan="3"><?php echo $mrp->getText("Membre d'honneur de l'association") ?></th>
            <td><?php CheckBox($contact['conMembHoneur']); ?></td>
        </tr>
	    <tr>
		    <th><?php echo $mrp->getText("Membre la parenthèse") ?></th>
            <td><?php echo FormatTypeOfMember($contact['conMembLaPar']); ?></td>

		    <th><?php echo $mrp->getText("Donateur la parenthèse") ?> </th>
		    <td><?php CheckBox($contact['conDonatLaPar'], 'DonPar'); ?></td>
	    </tr>
        <?php /*<tr>
            <th>Type de membre</th>
            <td><?php echo FormatStatusOfMember($contact['conTypeMembLaPar']); ?></td>
        </tr>*/ ?>
    </table>
    <h2><?php echo $mrp->getText("Abonnement") ?></h2>
    <table class="noMargin" style="border: solid 1px;">
        <tr>
            <th><?php echo $mrp->getText("Programme des activités") ?></th>
            <td><?php CheckBox($contact['conProgramme']); ?></td>
        </tr>  
        <tr>
            <th><?php echo $mrp->getText("Bulletin CONNAITRE") ?></th>
            <td><?php CheckBox($contact['conConnaitre']); ?></td>
        </tr>
        <tr>
            <th><?php echo $mrp->getText("Journal CEREBRAL Suisse") ?></th>
            <td><?php CheckBox($contact['conCerebral']); ?></td>
        </tr>
        <tr>                
            <th><?php echo $mrp->getText("Grp. parents")?></th>
            <td><?php CheckBox($contact['conGM']); ?></td>
        </tr>
        <tr>
            <th><?php echo $mrp->getText("Client loto")?></th>
            <td><?php CheckBox($contact['conLoto']); ?></td>
        </tr>
    </table>
    <h2><?php echo $mrp->getText("Informations spécifiques personnes en situation de handicap") ?></h2>
    <table class="noMargin">
        <h2><?php $mrp->getText("OFAS (LAI)") ?></h2>
        <tr>
            <th><?php echo $mrp->getText("Canton de résidence") ?></th>
            <td><input style="width: 100%;" disabled
                        value="<?php echo $infoMedical['cantNom'] ?? ''; ?>"></td>
        </tr>
        <?php /*<tr>
            <td><?php  echo $mrp->getText("Statut") ?></td>
            <td><input style="width: 100%;" disabled
                        value="<?php if ($infoMedical['ofasProche'] == '1'){echo 'en situtation de handicape';}
                        elseif ($infoMedical['ofasProche'] == '0'){echo 'Proches';}else {echo'';}?>"></td>
        </tr>*/ ?>
        <tr>
            <th><?php  echo $mrp->getText("Types de handicap") ?></th>
            <td><input style="width: 100%;" disabled value="<?php echo $infoMedical['hanNom'] ?? ''; ?>"></td>
        </tr>
        <tr>
            <th><?php  echo $mrp->getText("dont plurihandicap") ?></th>
            <td><?php CheckBox($infoMedical['medOfasPluri'] ?? 0); ?></td>
        </tr>
        <tr>
            <th colspan="2"><?php  echo $mrp->getText("reconnu au titre de") ?></th>
        </tr>
        <tr>
            <td colspan="2"><input style="width: 100%;" disabled
                                    value="<?php echo $infoMedical['artNom'] ?? ''; ?>"></td>
        </tr>
        <tr>
            <th><?php  echo $mrp->getText("Nouveau en") ?></th>
            <td><input style="width: 100%;" disabled
                        value="<?php echo $infoMedical['ofasNouveau'] ?? ''; ?>"></td>
        </tr>
        <tr>
            <th><?php  echo $mrp->getText("Besoin") ?></th>
            <td><input style="width: 100%;" disabled
                        value="<?php echo $infoMedical['OfasNom'] ?? ''; ?>"></td>
        </tr>

    </table>
        <h2><?php echo $mrp->getText("Intéressé(e) aux cours / activités / services et autre") ?></h2>
    <table class="noMargin" style="border: solid 1px;">
        <tr>
            <th colspan="2"><?php echo $mrp->getText('Activités') ?></th>
            <th colspan="2"><?php echo $mrp->getText('SERVICES') ?></th>
            <td></td>
        </tr>
        <tr>
            <th><?php echo $mrp->getText('Terrifics') ?></th>
            <td><?php CheckBox($contact['conGJ']) ?></td>
            <th><?php echo $mrp->getText('Service de relève') ?></th>
            <td><?php CheckBox($contact['conServiceReleve']) ?></td>
            <?php /*th><?php echo $mrp->getText('Aide à domicile') ?></th>
            <td><?php CheckBox($contact['conAideDomicile']) ?></td */ ?>
        </tr>
        <tr>
            <th><?php echo $mrp->getText('Week-ends') ?></th>
            <td><?php CheckBox($contact['conWK']) ?></td>
            <th><?php echo $mrp->getText('Contribution assistance') ?></th>
            <td><?php CheckBox($contact['conContribAssistant']) ?></td>            
        </tr>
        <tr>
            <th><?php echo $mrp->getText('Camps') ?></th>
            <td><?php CheckBox($contact['conCAMP']) ?></td>
            <th><?php echo $mrp->getText('Aide au tiers') ?> <?php //echo $mrp->getText('École') ?></th>
            <td><?php CheckBox($contact['conReleveScolaire']) ?></td>
        </tr>
        <tr>
            <th>Court séjour</th>
            <td><?php CheckBox($contact['conUat']); ?></td>
        </tr>
    </table>
    <table class="noMargin" style="border: solid 1px;">
        <tr>
            <form method="POST" action="#">
                <th><?php echo $mrp->getText('Marquage manuel') ?></th>
                <td><input style="width: 20mm" name="Marquage" value="<?php echo $contact['conMarquage']; ?>">
                <td colspan="2"><button type="submit" id="marquage" name="validation_marquage" value="validated">Valider marquage</button></td> 
            </form>
        </tr>
    </table>
</div>
<style>
    .grise{
        color: grey;
    }
    .not{
        color: grey;
    }
    #marquage{
        background-color: #43a047; 
        border: none; 
        color: white;
    }
    #marquage:hover{
        cursor: pointer;
    }
</style>

<?php include '../footer.php'; 

/** champs à checker pour l'apparition du menu "contributions : 
 * - conMembre (1, 2, 3)
 * - conMembLaPar (2)
*/  

function classOfContributionsBt($bdd, $id, $cerebralStatus, $parentheseStatus, $parentheseDonateurStatus){
    try{
        $membreCerebral = intval($cerebralStatus);
        $membreParenthese = intval($parentheseStatus);
        $donateurParenthese = intval($parentheseDonateurStatus);
        if(($membreCerebral !== 0 && $membreCerebral !== 4) || $membreParenthese !== 0 || $donateurParenthese === 1) return 'yes';
        $nbrOfContributions = getContributionsOfUser($bdd, $id);
        if($nbrOfContributions === 0) return 'not';
        return 'grise';
    }catch(Exception $e){
        return 'grise'; // en cas de doute
    }
}
// cette fonction retourn la class html à attribuer au menu "Contributions"
// si champs "Membre cerebral" est à non ou non-connu, 
// on check si il a des contributions à son actif et si oui menu "contributions" est grisé 

/*function isContributeur($bdd, $id, $cerebralStatus){
    switch(intval($cerebralStatus)){
        case 0:
        case 4: 
            $nbrOfContributions = getContributionsOfUser($bdd, $id);
            if($nbrOfContributions > 0) return 'grise';
            else return 'not';
            break;
        default : 
            return 'yes';
            break;
    }
}*/

function getContributionsOfUser($bdd, $idUser){
    $req = $bdd->query("SELECT cotiId FROM tblCotisation WHERE tblContact_conId = '$idUser'");
    $contributions = $req->fetch();
    if(!$contributions) return 0;
    return count($contributions);
}


function FormatTypeOfContact($givenType){
    if($givenType === null) return "Non";
    if(intval($givenType) === 1) return "Oui";
    else if(intval($givenType) === 0) return "Inactif";
}
function FormatTypeOfMember($givenMember){
    if($givenMember === null) return "Non"; 
    if($givenMember !== null && intval($givenMember) === 0) return "NC";
    if($givenMember !== null && intval($givenMember) === 1) return "Interessé";
    if($givenMember !== null && intval($givenMember) === 2) return "Actif";
    else return '?';
}
/*
function FormatStatusOfMember($givenMember){
    if($givenMember === null) return "-"; 
    if($givenMember !== null && intval($givenMember) === 0) return "NC";
    if($givenMember !== null && intval($givenMember) === 1) return "Interessé";
    if($givenMember !== null && intval($givenMember) === 2) return "Payant";
    else return '?';
}*/
 ?>