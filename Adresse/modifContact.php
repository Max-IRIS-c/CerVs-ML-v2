<?php
include('../variables.php');

// Create the db instance
include_once "../src/class/Db.class.php";
include_once "../src/class/Mrp.class.php";

$searchedParam = $_GET['searchedParam'] ?? null;
// Create the db instance
$db = new DB();
$mrp = new Mrp();

//$mrp->pageAccess();
if(isset($_GET["lang"]) && !empty($_GET["lang"])){
    $mrp->setLanguage($_GET["lang"]);
}

//$mrp->pageAccess();
if(isset($_GET["lang"]) && !empty($_GET["lang"])){
    $mrp->setLanguage($_GET["lang"]);
}

$bdd = new PDO($dsn, $user, $password);

$id = $_GET['conId'];
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

$titre = $bdd->query("SELECT * FROM tblCiviliter WHERE civStatu =1");
$TypeTelephone1 = $bdd->query("SELECT * FROM tblTypeTelephone");
$TypeTelephone2 = $bdd->query("SELECT * FROM tblTypeTelephone");
$TypeTelephone3 = $bdd->query("SELECT * FROM tblTypeTelephone");
$TypeTelephone4 = $bdd->query("SELECT * FROM tblTypeTelephone");
$ContRegion = $bdd->query("SELECT * FROM tblRegionCon");
$Langues = $bdd->query("SELECT * FROM tblangues ORDER BY lanId");
$EtatCivil = $bdd->query("SELECT * FROM tblEtatCivile");
$Nationaliter = $bdd->query("SELECT * FROM tblNationaliter");
$PermisSejour = $bdd->query("SELECT * FROM tblPermisSejour");
$Membre = $bdd->query("SELECT * FROM tblMembre");
$Type = $bdd->query("SELECT * FROM tblMembreType");
$don = $bdd->query("SELECT max(cotiDate) FROM tblCotisation where tblContact_conId = $id");
$don = $don->fetch();


if (isset($_POST['Annuler'])) {

header("location: contact.php");
}

if (isset($_POST['Valider'])) {
    try{
        $bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $insert = $bdd->prepare("UPDATE tblContact SET
        conModif = :conModif,
        tblCiviliter_civId = :tblCiviliter_civId,
        conNom = :conNom,
        conPrenom = :conPrenom,
        conComplement = :conComplement,
        conAdresse = :conAdresse,
        conAdresse2 = :conAdresse2,
        conNpa = :conNpa,
        conLocaliter = :conLocaliter,
        conTel1 = :conTel1,
        conTel1T = :conTel1T,
        conTel2T = :conTel2T,
        conTel3T = :conTel3T,
        conTel4T = :conTel4T,
        conTel2 = :conTel2,
        conTel3 = :conTel3,
        conTel4 = :conTel4,
        conMail = :conMail,
        conDateNaissance = :conDateNaissance,
        conAvs =  :conAvs,
        conLangues = :conLangues,
        conRegion = :conRegion,
        conCommentaire = :conCommentaire,
        conPermisB = :PermisB,
        conPermiD1 = :PermisD,
        conEtatcivil = :EtatCivil,
        conNationalite = :Nationaliter,
        conPermisejour =  :PermisSejour,
        conValidPermSejour = :Validiter,
        conDelivrance = :conDelivrance,
        conIban = :Iban,
        conBanque = :Banque,
        conAgence = :Agence,
        conParent = :conParent,
        conHandicaper = :conHandicaper,
        conAdulte = :conAdulte,
        conComiter = :conComiter,
        conInviAmi = :conInviAmi,
        conClientPavillons = :conClientPavillons,
        conEmploye = :conEmploye,
        conAssociation = :conAssociation,
        conAutresTypes = :conAutresTypes,
        conIntervenant = :conIntervenant,
        conMedecin = :conMedecin,
        conAccompagnant = :conAccompagnant,
        conAssurance = :conAssurance,
        conInstitution = :conInstitution,
        conInviVip = :conInviVip,
        conBenevole = :conBenevole,
        conEntreprise = :conEntreprise,
        conMembre = :conMembre,
        conTypeMembre = :conTypeMembre,
        conDonateur = :conDonateur,
        conMembHoneur = :conMembHoneur,
        conConnaitre = :conConnaitre,
        conCerebral = :conCerebral,
        conProgramme = :conProgramme,
        conGJ = :conGJ,
        conWK = :conWK,
        conServiceReleve = :conServiceReleve,
        conCAMP = :conCAMP,
        conContribAssistant = :conContribAssistant,
        conReleveScolaire = :conReleveScolaire,
        conGM = :conGM,
        conUat = :conUat,
        conLoto =:conLoto,
        conIntendant=:conIntendant,
        conInfirmiere=:conInfirmiere,
        conAccompagnantChauffeur=:conAccompagnantChauffeur,
        conCoResponsable=:conCoResponsable,
        conResponsable=:conResponsable,
        conCuisinier=:conCuisinier,
        conSecondaire =:conSecondaire,
        conSociete = :conSociete,
        conParenthese = :conParenthese,
        conClieLaPar = :conClieLaPar,
        conMembLaPar = :conMembLaPar,
        conTypeMembLaPar = :conTypeMembLaPar,
        conMarquage = :conMarquage,
        secondaryInfos = :secondaryInfos,
        intervenantParenthese = :intervenantParenthese,
        accompagnantParenthese = :accompagnantParenthese,
        intervenantReleve = :intervenantReleve,
        accompagnantCerebral = :accompagnantCerebral,
        intervenantCA = :intervenantCA,
        conDonatLaPar = :conDonatLaPar
        /*conAideDomicile = :conAideDomicile*/
        WHERE conId = '$id'");

        $insert->execute(array(
        'conModif' => date('Y-m-d'),
        'conSociete' => $_POST['Societe'] ?? '',
        'tblCiviliter_civId' => $_POST['Titre'],
        'conNom' => $_POST['Nom'],
        'conPrenom' => $_POST['Prenom'],
        'conComplement' => $_POST['Complement'],
        'conAdresse' => $_POST['Adresse'],
        'conAdresse2' => $_POST['Adresse2'],
        'conNpa' => $_POST['Npa'],
        'conLocaliter' => $_POST['Localiter'],
        'conTel1' => $_POST['tel1'],
        'conTel1T' => $_POST['typeTel1'],
        'conTel2T' => $_POST['typeTel2'],
        'conTel3T' => $_POST['typeTel3'],
        'conTel4T' => $_POST['typeTel4'],
        'conTel2' => $_POST['tel2'],
        'conTel3' => $_POST['tel3'],
        'conTel4' => $_POST['tel4'],
        'conMail' => $_POST['Mail'],
        'conDateNaissance' => !empty($_POST['Naissance']) ? $_POST['Naissance'] : null,
        'conAvs' => $_POST['Avs'],
        'conLangues' => $_POST['Langues'],
        'conRegion' => $_POST['Region'],
        'conCommentaire' => $_POST['commentaire'],
        'PermisB' => $_POST['PermisB'] ?? null,
        'PermisD' => $_POST['PemiD1'] ?? null,
        'EtatCivil' => $_POST['EtatCivil'],
        'Nationaliter' => $_POST['Nationaliter'],
        'PermisSejour' => $_POST['PermisSejour'],
        'Validiter' => !empty($_POST['Validiter']) ? $_POST['Validiter'] : null,
        'conDelivrance' =>  !empty($_POST['conDelivrance']) ? $_POST['conDelivrance'] : null,
        'Iban' => $_POST['Iban'],
        'Banque' => $_POST['Banque'],
        'Agence' => $_POST['Agence'],
        'conParent' => $_POST['Parent'] ?? null,
        'conHandicaper' => $_POST['Handicape'] ?? 0,
        'conAdulte' => $_POST['Adulte'],
        'conComiter' => $_POST['Comiter'] ?? 0,
        'conInviAmi' => $_POST['ami'] ?? 0,
        'conClientPavillons' => $_POST['Pavillon'] ?? 0,
        'conEmploye' => $_POST['EMployer'] ?? 0,
        'conAssociation' => $_POST['Association'] ?? 0,
        'conAutresTypes' => $_POST['Autres'] ?? 0,
        'conIntervenant' => $_POST['Intervenant'] === "" ? null : intval($_POST['Intervenant']),
        'conMedecin' => $_POST['Medecin'] ?? 0,
        'conAccompagnant' => $_POST['Accompagnant'] === "" ? null : intval($_POST['Accompagnant']),
        'conAssurance' => $_POST['Assurance'] ?? 0,
        'conInstitution' => $_POST['Institution'] ?? 0,
        'conInviVip' => $_POST['VIP'],
        'conBenevole' => $_POST['Benevole'] ?? 0,
        'conEntreprise' => $_POST['Partenaire'],
        'conMembre' => $_POST['Membre'],
        'conTypeMembre' => $_POST['TypeMembre'],
        'conDonateur' => $_POST['Donnateur'] ?? 0,
        'conMembHoneur' => $_POST['membreHonneure'],
        'conConnaitre' => $_POST['Connaitre'],
        'conCerebral' => $_POST['CerebralSuisse'],
        'conProgramme' => $_POST['Programme'],
        'conGJ' => $_POST['Tereifics'],
        'conWK' => $_POST['WeekEnde'],
        'conServiceReleve' => $_POST['ServicesReleve'],
        'conCAMP' => $_POST['Camps'],
        'conContribAssistant' => $_POST['ContribAssitance'],
        'conReleveScolaire' => $_POST['Ecole'],
        'conGM' => $_POST['GrpParent'] ?? 0,
        'conUat' => $_POST['Uat'],
        'conLoto' => $_POST['Loto'],
        'conIntendant' => $_POST['conIntendant'],
        'conInfirmiere' => $_POST['conInfirmiere'],
        'conAccompagnantChauffeur' => $_POST['conAccompagnantChauffeur'],
        'conCoResponsable' => $_POST['conCoResponsable'],
        'conResponsable' => $_POST['conResponsable'],
        'conCuisinier' => $_POST['conCuisinier'],
        'conSecondaire' =>$_POST['Secondaire'],
        'conParenthese' =>$_POST['parenthese'],
        'conClieLaPar' =>$_POST['CliePar'],
        'conMembLaPar' =>$_POST['MemPar'] === "" ? null : intval($_POST['MemPar']),
        'conTypeMembLaPar' => $_POST['TypeMemPar'] === "" ? null : intval($_POST['TypeMemPar']),
        'conDonatLaPar' =>$_POST['DonPar'],
        'conMarquage' =>$_POST['Marquage'],
        'secondaryInfos'=> !empty($_POST['secondaryInfos']) ? intval($_POST['secondaryInfos']) : 0,
        'intervenantParenthese'=> $_POST['intervenantParenthese'] === "" ? null : intval($_POST['intervenantParenthese']),
        'accompagnantParenthese'=> $_POST['accompagnantParenthese'] === "" ? null : intval($_POST['accompagnantParenthese']),
        'intervenantReleve'=> $_POST['intervenantReleve'] === "" ? null : intval($_POST['intervenantReleve']),
        'accompagnantCerebral'=>  $_POST['accompagnantCerebral'] === "" ? null : intval($_POST['accompagnantCerebral']),        
        'intervenantCA'=> $_POST['intervenantCA'] === "" ? null : intval($_POST['intervenantCA'])
        /*'conAideDomicile'=>$_POST['AideDomicile']*/
    ));

        }
        catch(Exception $e)
        {
            //var_dump($_POST);
            die('Erreur : '.$e->getMessage());
        }
        header("location: detContacte.php?conId=" . $id."&searchedParam=" . $searchedParam);
}
include('../heade.php');
include('verif.php');
?>

<h1><?php echo $mrp->getText("Modification d'un contact") ?></h1>
<h2 id="erreurVide" style="display: none; color: #ff0000;"> <?php echo $mrp->getText("Les champs en rouge doivent être remplis") ?> </h2>
<h2 id="erreurType" style="display: none; color: #ff9025;"><?php echo $mrp->getText("Les champs en orange ont un mauvais format") ?></h2>

<!-- Début colonne gauche-->
<form method="post">
    <div id="cordonee" style="width: 48%; float: left;">
        <h2><?php echo $mrp->getText("Coordonnées du contact") ?></h2>
        <table class="noMargin" style="border: solid 1px; width: 100%">         
            <tr>
                <th><?php echo $mrp->getText("Société") ?></th>
                <td colspan="3"><input id="Societe" size="50" name="Societe" value="<?php echo $contact['conSociete'] ?>"></td>
            </tr>

            <tr>
                <th><?php echo $mrp->getText("Titre") ?></th>
                <td><select id="Titre" class="input100" name="Titre">
                        <option value="0">->
                        </option><?php ListeModif($titre, $contact['tblCiviliter_civId'], 'civId', 'civNom') ?></select>
                </td>
                <th><?php echo $mrp->getText("Adresse secondaire") ?></th>
                <td> <?php CheckBoxModif($contact['conSecondaire'], 'Secondaire') ?></td>
            </tr>
            <tr>
                <th><?php echo $mrp->getText("Nom") ?></th>
                <td colspan=""><input id="Nom" size="15" name="Nom" value="<?php echo $contact['conNom'] ?>"></td>
                <th><?php echo $mrp->getText("Prénom") ?></th>
                <td><input class="input100" name="Prenom" value="<?php echo $contact['conPrenom'] ?>"></td>
            </tr>
            <tr>
                <th><?php echo $mrp->getText("Complément") ?></th>
                <td colspan="3"><input class="input350" name="Complement"
                                       value="<?php echo $contact['conComplement'] ?>"></td>
            </tr>
            <tr>
                <th><?php echo $mrp->getText("Adresse") ?></th>
                <td colspan="3"><input class="input350" name="Adresse" value="<?php echo $contact['conAdresse'] ?>">
                </td>
            </tr>
            <tr>
                <th><?php echo $mrp->getText("Adresse 2") ?></th>
                <td colspan="3"><input class="input350" name="Adresse2" value="<?php echo $contact['conAdresse2'] ?>">
                </td>
            </tr>
            <tr>
                <th><?php echo $mrp->getText("NPA") ?></th>
                <td><input class="input100" name="Npa" id="NPA" value="<?php echo $contact['conNpa'] ?>"></td>
                <th><?php echo $mrp->getText("Localité") ?></th>
                <td><input class="input100" name="Localiter" id="Localiter"
                           value="<?php echo $contact['conLocaliter'] ?>"></td>
            </tr>
            <tr>
                <th><select id="Ttel1" name="typeTel1">
                        <option value="0">-></option>
                        <?php ListeModif($TypeTelephone1, $contact['conTel1T'], 'tTelId', 'tTelNom') ?></select></th>
                <td><input class="input100" name="tel1" id="tel1" value="<?php echo $contact['conTel1'] ?>"></td>
                <th><select name="typeTel2">
                        <option value="0">-></option>
                        <?php ListeModif($TypeTelephone2, $contact['conTel2T'], 'tTelId', 'tTelNom') ?></select></th>
                <td><input class="input100" name="tel2" id="tel2" value="<?php echo $contact['conTel2'] ?>"></td>
            </tr>
            <tr>
                <th><select name="typeTel3">
                        <option value="0">-></option>
                        <?php ListeModif($TypeTelephone3, $contact['conTel3T'], 'tTelId', 'tTelNom') ?></select></th>
                <td><input class="input100" name="tel3" id="tel3" value="<?php echo $contact['conTel3'] ?>"></td>
                <th><select name="typeTel4">
                        <option value="0">-></option>
                        <?php ListeModif($TypeTelephone4, $contact['conTel4T'], 'tTelId', 'tTelNom') ?></select></th>
                <td><input class="input100" name="tel4" id="tel4" value="<?php echo $contact['conTel4'] ?>"></td>
            </tr>
            <tr>
                <th><?php echo $mrp->getText("e-mail") ?></th>
                <td colspan="3"><input id="Mail" class="input350" name="Mail" value="<?php echo $contact['conMail'] ?>"></td>
            </tr>
        </table>
        <h2><?php echo $mrp->getText("Infos diverses sur le contact") ?></h2>
        <table class="noMargin" style="border: solid 1px">
            <tr>
                <td colspan="2"></td>
                <th><?php echo $mrp->getText("Adresse la parenthèse") ?></th>
                <td> <?php 
		
		if (isset($contact['conParenthese']))
		{
		CheckBoxModif($contact['conParenthese'], 'parenthese');
			}
		?>
	
		</td>
            </tr>
            <tr>
                <th><?php echo $mrp->getText("Date de naissance") ?></th>
                <td><input style="width:130px" name="Naissance" type="date"
                           value="<?php echo ($contact['conDateNaissance']) ?>"></td>
                <th><?php echo $mrp->getText("Numéro AVS") ?></th>
                <td><input class="input100" name="Avs" id="Avs" value="<?php echo $contact['conAvs'] ?>"></td>
            </tr>
            <tr>
                <th><?php echo $mrp->getText("Résident dans la région") ?></th>
                <td><select id="Region" name="Region">
                        <option value="0">-></option>
                        <?php ListeModif($ContRegion, $contact['conRegion'], 'regConId', 'regConNom') ?></select></td>
                <th><?php echo $mrp->getText("Parlant (langue)") ?></th>
                <td><select id="Langue" name="Langues">
                        <option value="0">-></option>
                        <?php ListeModif($Langues, $contact['conLangues'], 'lanId', 'lanNom') ?></select></td>
            </tr>
            <tr>
                <th><?php echo $mrp->getText("Création du contact") ?></th>
                <td><input disabled class="input100" value="<?php echo dateToUser($contact['conCreation']) ?> "></td>
                <th><?php echo $mrp->getText("Modifié le") ?></th>
                <td><input disabled class="input100" value="<?php echo dateToUser($contact['conModif']) ?> "></td>
            </tr>
        </table>
        <h2><?php echo $mrp->getText("Infos spécifiques accompagnant</h2") ?>>
        <table class="noMargin" style="border: solid 1px; width:100%;">  
            <tr>
                <td><?php echo $mrp->getText("Disponible comme") ?> </td>
            <tr>
            <tr>
                <th><?php echo $mrp->getText("Accompagnant pour") ?>: </th>
                <td></td>
                <th><?php echo $mrp->getText("Intervenant pour") ?>:</th>
                <td></td>
            </tr>
            <tr>
                <th><?php echo $mrp->getText("Cerebral") ?></th>
                <td>
                    <select name="Accompagnant">
                        <option value="" <?php if($contact['conAccompagnant'] === null) echo "selected"; ?>>Non</option>
                        <option value="1" <?php if(intval($contact['conAccompagnant'])===1) echo "selected"; ?>>Oui</option>
                        <option value="0" <?php if($contact['conAccompagnant'] !== null && intval($contact['conAccompagnant'])===0) echo "selected"; ?>>Inactif</option>
                    </select>
                </td>
                <th><?php echo $mrp->getText("Relève") ?></th>
                <td>
                    <select name="Intervenant">
                        <option value="" <?php if($contact['conIntervenant'] === null) echo "selected"; ?>>Non</option>
                        <option value="1" <?php if(intval($contact['conIntervenant'])===1) echo "selected"; ?>>Oui</option>
                        <option value="0" <?php if($contact['conIntervenant'] !== null && intval($contact['conIntervenant'])===0) echo "selected"; ?>>Inactif</option>
                    </select>
                </td>
            </tr>
            <tr>
                <th>Parenthèse</th>
                <td>
                    <select name="accompagnantParenthese">
                        <option value="" <?php if($contact['accompagnantParenthese'] === null) echo "selected"; ?>>Non</option>
                        <option value="1" <?php if(intval($contact['accompagnantParenthese'])===1) echo "selected"; ?>>Oui</option>
                        <option value="0" <?php if($contact['accompagnantParenthese'] !== null && intval($contact['accompagnantParenthese'])===0) echo "selected"; ?>>Inactif</option>
                    </select>
                </td>
                <th>Parenthèse</th>
                <td>
                    <select name="intervenantParenthese">
                        <option value="" <?php if($contact['intervenantParenthese'] === null) echo "selected"; ?>>Non</option>
                        <option value="1" <?php if(intval($contact['intervenantParenthese'])===1) echo "selected"; ?>>Oui</option>
                        <option value="0" <?php if($contact['intervenantParenthese'] !== null && intval($contact['intervenantParenthese'])===0) echo "selected"; ?>>Inactif</option>
                    </select>
                </td>
            </tr>
            <tr>             
                <th></th>
                <td></td>
                <th><?php echo $mrp->getText("Cont. Assist.") ?></th>
                <td>
                    <select name="intervenantCA">
                        <option value="" <?php if($contact['intervenantCA'] === null) echo "selected"; ?>>Non</option>
                        <option value="1" <?php if(intval($contact['intervenantCA'])===1) echo "selected"; ?>>Oui</option>
                        <option value="0" <?php if($contact['intervenantCA'] !== null && intval($contact['intervenantCA'])===0) echo "selected"; ?>>Inactif</option>
                    </select>
                </td>
            </tr>
           <?php /* <tr> 
                <th><?php echo $mrp->getText("Intervenant") ?></th>
                <td><?php echo $intervenantStatus; ?></td>
                <th>Parenthèse</th>
                <td><?php Checkbox($contact['intervenantParenthese']) ?></td>                       
                <th>Relève</th>
                <td><?php Checkbox($contact['intervenantReleve']) ?></td>         
                <th>CA</th>
                <td><?php Checkbox($contact['intervenantCA']) ?></td>
            </tr>
            <tr>         
                <th><?php echo $mrp->getText("Accompagnant") ?></th>
                <td><?php echo $accompagnantStatus; ?></td>
                <th>Cerebral</th>
                <td><?php Checkbox($contact['accompagnantCerebral']) ?></td>            
                <th>Parenthèse</th>
                <td><?php Checkbox($contact['accompagnantParenthese']) ?></td>
            </tr>  
          */  ?>        
            <tr>
                <th><?php echo $mrp->getText("Permis de conduire voiture (B)") ?></th>
                <td> <?php CheckBoxModif($contact['conPermisB'], 'PermisB') ?></td>
                <th>Bus 8-16 pl (D1)</th>
                <td> <?php CheckBoxModif($contact['conPermiD1'], 'PemiD1') ?></td>
            </tr>
            <tr>
                <th><?php echo $mrp->getText("Etat civil") ?></th>
                <td><select class="input100" name="EtatCivil">
                        <option value="0">->
                        </option><?php ListeModif($EtatCivil, $contact['conEtatcivil'], 'etaCivId', 'eatCivNom') ?>
                    </select>
                </td>       
                <th><?php echo $mrp->getText("Délivrance") ?></th>
                <td><input name="conDelivrance" type="date" value="<?php echo $contact['conDelivrance']; ?>"></td>
            </tr>
            <tr>
                <th><?php echo $mrp->getText("Nationalité") ?></th>
                <td><select class="input100" name="Nationaliter">
                        <option value="0">->
                        </option><?php ListeModif($Nationaliter, $contact['conNationalite'], 'natId', 'natNom') ?>
                    </select>
                </td>                
                <th> <?php echo $mrp->getText("Validité") ?></th>
                <td><input style="width:130px" name="Validiter" type="date" VALUE="<?php echo ($contact['conValidPermSejour']); ?>"></td></td>
            </tr>
            <tr>
                <th><?php echo $mrp->getText("Permis de séjour") ?></th>
                <td><select class="input100" name="PermisSejour">
                        <option value="0">->
                        </option><?php ListeModif($PermisSejour, $contact['conPermisejour'], 'pSejourId', 'pSejourNom') ?>
                    </select>
                </td>
            </tr>
            <tr>
                <th><?php echo $mrp->getText("Banque") ?></th>
                <td colspan="3"><input class="input350" name="Banque" VALUE="<?php echo $contact['conBanque'] ?>"></td>
            </tr>
            <tr>
                <th><?php echo $mrp->getText("Agence de") ?></th>
                <td colspan="3"><input class="input350" name="Agence" VALUE="<?php echo $contact['conAgence'] ?>"></td>
            </tr>
            <tr>
                <th><?php echo $mrp->getText("IBAN/cpte") ?></th>
                <td colspan="3"><input class="input350" name="Iban" id="IBAN" VALUE="<?php echo $contact['conIban'] ?>">
                </td>
            </tr>
            <tr>
                <th><?php echo $mrp->getText("Responsable") ?></th>
                <td> <?php CheckBoxModif($contact['conResponsable'], 'conResponsable') ?></td>
                <th><?php echo $mrp->getText("Co-responsable") ?></th>
                <td> <?php CheckBoxModif($contact['conCoResponsable'], 'conCoResponsable') ?></td>
            </tr>
            <tr>
                <th><?php echo $mrp->getText("Accompagnant chauffeur") ?></th>
                <td> <?php CheckBoxModif($contact['conAccompagnantChauffeur'], 'conAccompagnantChauffeur') ?></td>
                <th><?php echo $mrp->getText("Resp. des soins") ?></th>
                <td> <?php CheckBoxModif($contact['conInfirmiere'], 'conInfirmiere') ?></td>
            </tr>
            <tr>
                <th><?php echo $mrp->getText("Cuisinier") ?></th>
                <td> <?php CheckBoxModif($contact['conCuisinier'], 'conCuisinier') ?></td>
                <th><?php echo $mrp->getText("Intendant (e)") ?></th>
                <td> <?php CheckBoxModif($contact['conIntendant'], 'conIntendant') ?></td>
            </tr>
        </table>
        <table class="noMargin" style="border: solid 1px;">
            <tr>
                <th><?php echo $mrp->getText("Commentaire(s)") ?></th>
            </tr>
            <tr>
                <td><textarea cols="67" rows="5" name="commentaire"><?php echo $contact['conCommentaire'] ?></textarea>
                </td>
            </tr>

        </table>
    </div>
    <!-- Début colonne droite-->
    <div id="infoDroit" style="width: 48%; margin-left: 52%;">
        <h2><?php echo $mrp->getText("Qualité de contact") ?></h2>
        <table class="noMargin" style="border: solid 1px;">
            <tr>
                <th><?php echo $mrp->getText("Parent/ tuteur") ?></th>
                <td><?php CheckBoxModif($contact['conParent'], 'Parent') ?></td>
            </tr>
            <tr>
                <th><?php echo $mrp->getText("Bénéficiaire(LAI)") ?></th>
                <td><?php CheckBoxModif($contact['conHandicaper'], 'Handicape') ?></td>

                <td><select name="Adulte">
                        <?php if ($contact['conAdulte'] == 1) { ?>
                            <option value="1" SELECTED>Adulte</option>
                            <option value="0"><?php echo $mrp->getText("Enfant") ?></option>

                        <?php } else { ?>
                            <option value="1"><?php echo $mrp->getText("Adulte") ?></option>
                            <option value="0" selected><?php echo $mrp->getText("Enfant") ?></option>
                        <?php } ?>
                    </select>
        </table>
        <table class="noMargin" style="border: solid 1px;">
            <tr>
                <th colspan="6"><?php echo $mrp->getText("Pour l'association") ?></th>
            </tr>
            <tr>
                <th><?php echo $mrp->getText("Comité") ?></th>
                <td><?php CheckBoxModif($contact['conComiter'], 'Comiter'); ?></td>
                <th><?php echo $mrp->getText("Employé") ?></th>
                <td><?php CheckBoxModif($contact['conEmploye'], 'EMployer'); ?></td>
                <th><?php echo $mrp->getText("Bénévole") ?></th>
                <td><?php CheckBoxModif($contact['conBenevole'], 'Benevole'); ?></td>
            </tr>
            <tr>
                <th colspan="6"><?php $mrp->getText("Proche de l'association (partenaires et clients)") ?></th>
            </tr>
            <tr>
                <th><?php echo $mrp->getText("Partenaire") ?></th>
                <td><?php CheckBoxModif($contact['conEntreprise'], 'Partenaire'); ?></td>
                <th><?php echo $mrp->getText("Institution") ?></th>
                <td><?php CheckBoxModif($contact['conInstitution'], 'Institution'); ?></td>
                <th><?php echo $mrp->getText("Association") ?></th>
                <td><?php CheckBoxModif($contact['conAssociation'], 'Association'); ?></td>
            <tr>
                <th><?php echo $mrp->getText("Ami, bienfaiteur") ?></th>
                <td><?php CheckBoxModif($contact['conInviAmi'], 'ami'); ?></td>
                <th><?php echo $mrp->getText("VIP") ?></th>
                <td><?php CheckBoxModif($contact['conInviVip'], 'VIP'); ?></td>
            </tr>
            <tr>
                <th><?php echo $mrp->getText("Médecin") ?></th>
                <td><?php CheckBoxModif($contact['conMedecin'], 'Medecin'); ?></td>
                <th><?php echo $mrp->getText("Assurance") ?></th>
                <td><?php CheckBoxModif($contact['conAssurance'], 'Assurance'); ?></td>
                <th><?php echo $mrp->getText("Autre") ?></th>
                <td><?php CheckBoxModif($contact['conAutresTypes'], 'Autres'); ?></td>
            </tr>
            <tr>
                <th><?php echo $mrp->getText("Client Pavillons") ?></th>
                <td><?php CheckBoxModif($contact['conClientPavillons'], 'Pavillon'); ?></td>
                 <th><?php echo $mrp->getText("Client la parenthèse") ?></th>
                <td><?php CheckBoxModif($contact['conClieLaPar'], 'CliePar'); ?></td>
            </tr>

        </table>
        <h2><?php echo $mrp->getText("Qualité de contributeur") ?></h2>
        <table class="noMargin" style="border: solid 1px;">
            <tr>
                <th><?php echo $mrp->getText("Membre Cerebral") ?></th>
                <td><select class="input100" name="Membre">
                        <option value="0">->
                        </option><?php ListeModif($Membre, $contact['conMembre'], 'memId', 'memNom') ?>
                    </select>
                </td>
                <th><?php echo $mrp->getText("Type") ?></th>
                <td><select class="input100" name="TypeMembre">
                        <option value="0">->
                        </option><?php ListeModif($Type, $contact['conTypeMembre'], 'mTypId', 'mTypNom') ?>
                    </select>
                </td>
            </tr>
            <tr>
                <th><?php echo $mrp->getText("Donateur Cerebral") ?></th>
                <td><?php CheckBoxModif($contact['conDonateur'], 'Donnateur'); ?></td>

                <th><?php echo $mrp->getText("Dernier don") ?></th>
                <td> <?php echo dateToUser($don[0]) ?></td>
            </tr>
            <tr>
                <th colspan="3"><?php echo $mrp->getText("Membre d'honneur de l'association") ?></th>
                <td><?php CheckBoxModif($contact['conMembHoneur'], 'membreHonneure'); ?></td>
            </tr>
            <tr>
                <th><?php echo $mrp->getText("Membre la parenthèse") ?></th>
                <td>
                    <select name="MemPar">                
                        <option value="0" <?php if($contact['conMembLaPar'] !== null && intval($contact['conMembLaPar'])===0) echo "selected"; ?>>NC</option>
                        <option value="" <?php if($contact['conMembLaPar'] === null) echo "selected"; ?>>Non</option>
                        <option value="1" <?php if(intval($contact['conMembLaPar'])===1) echo "selected"; ?>>Interessé</option>
                        <option value="2" <?php if(intval($contact['conMembLaPar'])===2) echo "selected"; ?>>Actif</option>
                    </select>
                </td>	            
                <th><?php echo $mrp->getText("Donateur la parenthèse") ?> </th>
                <td><?php CheckBoxModif($contact['conDonatLaPar'], 'DonPar'); ?></td>
            </tr>
            <?php /*<tr>
                <th>Type de membre</th>
                <td>
                    <select name="TypeMemPar">                
                        <option value="" <?php if($contact['conTypeMembLaPar'] === null) echo "selected"; ?>>-</option>
                        <option value="0" <?php if($contact['conTypeMembLaPar'] !== null && intval($contact['conTypeMembLaPar'])===0) echo "selected"; ?>>NC</option>
                        <option value="1" <?php if(intval($contact['conTypeMembLaPar'])===1) echo "selected"; ?>>Interessé</option>
                        <option value="2" <?php if(intval($contact['conTypeMembLaPar'])===2) echo "selected"; ?>>Payant</option>
                    </select>
                </td>
            </tr> */
            ?>
        </table>
        <h2><?php echo $mrp->getText("Abonnement") ?></h2>
        <table class="noMargin" style="border: solid 1px;">
            <tr>
                <th><?php echo $mrp->getText("Programme des activités") ?></th>
                <td><?php CheckBoxModif($contact['conProgramme'], 'Programme'); ?></td>

            </tr>
            <tr>
                <th><?php echo $mrp->getText("Bulletin CONNAITRE") ?></th>
                <td><?php CheckBoxModif($contact['conConnaitre'], 'Connaitre'); ?></td>

            </tr>
            <tr>
                <th><?php echo $mrp->getText("Journal CEREBRAL Suisse") ?></th>
                <td><?php CheckBoxModif($contact['conCerebral'], 'CerebralSuisse'); ?></td>
            </tr>
            <tr>                
                <th><?php echo $mrp->getText("Grp. parents") ?></th>
                <td><?php CheckBoxModif($contact['conGM'], 'GrpParent'); ?></td>
            </tr>
            <tr>    
                <th><?php echo $mrp->getText("Client loto") ?></th>
                <td><?php CheckBoxModif($contact['conLoto'], 'Loto'); ?></td>
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
                <td><?php CheckBoxModif($contact['conGJ'], 'Tereifics') ?></td>
                <th><?php echo $mrp->getText('Service de relève') ?></th>
                <td><?php CheckBoxModif($contact['conServiceReleve'], 'ServicesReleve') ?></td>
                <?php /*th><?php echo $mrp->getText('Aide à domicile') ?></th>
                <td><?php CheckBoxModif($contact['conAideDomicile'], 'AideDomicile') ?></td*/ ?>
            </tr>
            <tr>
                <th><?php echo $mrp->getText('Week-ends') ?></th>
                <td><?php CheckBoxModif($contact['conWK'], 'WeekEnde') ?></td>
                <th><?php echo $mrp->getText('Contribution assistance') ?></th>
                <td><?php CheckBoxModif($contact['conContribAssistant'], 'ContribAssitance') ?></td>
            </tr>
            <tr>
                <th><?php echo $mrp->getText('Camps') ?></th>
                <td><?php CheckBoxModif($contact['conCAMP'], 'Camps') ?></td>
                <th>Aide au tiers <?php //echo $mrp->getText('École') ?></th>
                <td><?php CheckBoxModif($contact['conReleveScolaire'], 'Ecole') ?></td>
            </tr>
            <tr>
                <th>Court séjour</th>
                <td><?php CheckBoxModif($contact['conUat'], 'Uat'); ?></td>
            </tr>
            <?php /*tr>
                <th colspan="4"><?php echo $mrp->getText('Autres intérêts') ?></th>
            </tr */  ?>
        </table>
        <table class="noMargin" style="border: solid 1px;">
        <tr>
            <th><?php echo $mrp->getText('Marquage manuel') ?></th>
            <td><input style="width: 20mm" name="Marquage" value="<?php echo $contact['conMarquage']; ?>">
        </tr>
    </table>
</div>
        <table>
            <tr>
                <td style="vertical-align: top; background-color: transparent">
                    <input type="submit" id="submit" value="Valider" name="Valider" class="valider"></td>
                <td style="vertical-align: top; background-color: transparent">
                    <input type="submit" value="Annuler" name="Annuler" class="Annuler"></td>
            </tr>

        </table>
    </div>
</form>


<?php include '../footer.php'; ?>