<?php
include ('../variables.php');

$bdd = new PDO($dsn, $user, $password, [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
]);

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
$NewId = $bdd->query("SELECT max(conId)+1 FROM tblContact");
$NewId = $NewId ->fetch();
$NewId = $NewId[0];


if (isset($_POST['Annuler'])) {

    header("location: contact.php");
}

if (isset($_POST['Valider'])) {
    try{ 
    $insert = $bdd->prepare('INSERT INTO tblContact(conModif,tblCiviliter_civId,conNom,conPrenom,conComplement,
        conAdresse,conAdresse2,conNpa,conLocaliter,conTel1,conTel1T,conTel2T,conTel3T,conTel4T,conTel2,conTel3,conTel4,conMail,
        conDateNaissance,conCreation,conAvs,conLangues,conRegion,conCommentaire,conPermisB,conPermiD1,conEtatcivil,conNationalite,
        conPermisejour,conValidPermSejour,conIban,conBanque,conAgence,conParent,conHandicaper,conAdulte,conComiter,conInviAmi
        ,conClientPavillons,conEmploye,conAssociation,conAutresTypes,conIntervenant,conMedecin,conAccompagnant,conAssurance,
        conInstitution,conInviVip,conBenevole,conEntreprise,conMembre,conTypeMembre, conDonateur, conMembHoneur,conConnaitre,
        conCerebral,conProgramme,conGJ,conWK,conServiceReleve,conCAMP,conContribAssistant,conReleveScolaire,
        conGM,conUat,conLoto,conIntendant,conCoResponsable,conInfirmiere,conAccompagnantChauffeur,conResponsable,conCuisinier,
        conSecondaire,conSociete,conParenthese,conClieLaPar,conMembLaPar,conDonatLaPar,
        intervenantParenthese, accompagnantParenthese, intervenantReleve,accompagnantCerebral,intervenantCA,
        conTypeMembLaPar, conDelivrance
        )VALUES(
        :conModif, :tblCiviliter_civId, :conNom, :conPrenom, :conComplement,
        :conAdresse, :conAdresse2, :conNpa, :conLocaliter, :conTel1, :conTel1T, :conTel2T,:conTel3T,:conTel4T,:conTel2,:conTel3,
        :conTel4,:conMail, :conDateNaissance, :conCreation, :conAvs, :conLangues, :conRegion, :conCommentaire,:PermisB, 
        :PermisD, :EtatCivil, :Nationaliter,:PermisSejour, :Validiter, :Iban, :Banque, :Agence, :conParent,:conHandicaper,:conAdulte,
        :conComiter,:conInviAmi,:conClientPavillons,:conEmploye,:conAssociation,:conAutresTypes,:conIntervenant,:conMedecin,
        :conAccompagnant,:conAssurance,:conInstitution,:conInviVip,:conBenevole,:conEntreprise, :conMembre,:conTypeMembre, 
        :conDonateur,:conMembHoneur,:conConnaitre,:conCerebral,:conProgramme,:conGJ, :conWK,:conServiceReleve,
        :conCAMP,:conContribAssistant,:conReleveScolaire,:conGM,:conUat,:conLoto,:conIntendant,:conCoResponsable,:conInfirmiere
        ,:conAccompagnantChauffeur,:conResponsable,:conCuisinier, :conSecondaire, :conSociete, :conParenthese, :conClieLaPar, :conMembLaPar, :conDonatLaPar,
        :intervenantParenthese, :accompagnantParenthese, :intervenantReleve, :accompagnantCerebral, :intervenantCA,
        :conTypeMembLaPar, :conDelivrance)');
    /*$formatedData = array(
        'conModif' => date('Y-m-d'),
        'tblCiviliter_civId' => $_POST['Titre'] ,
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
        'conDateNaissance' => $_POST['Naissance'] === '' ? null : $_POST['Naissance'],
        'conCreation' => date('Y-m-d'),
        'conAvs' => $_POST['Avs'],
        'conLangues' => $_POST['Langues'],
        'conRegion' => $_POST['Region'],
        'conCommentaire' => $_POST['commentaire'],
        'PermisB' => $_POST['PermisB'] ?? null,
        'PermisD' => $_POST['PermisD'] ?? null,
        'EtatCivil' => $_POST['EtatCivil'],
        'Nationaliter' => $_POST['Nationaliter'],
        'PermisSejour' => $_POST['PermisSejour'],
        'Validiter' => $_POST['Validiter'] === '' ? null : $_POST['Validiter'],
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
        'conIntervenant' =>  $_POST['Intervenant'] === "" ? null : intval($_POST['Intervenant']),
        'conMedecin' => intval($_POST['Medecin']),
        'conAccompagnant' => $_POST['Accompagnant'] === "" ? null : intval($_POST['Accompagnant']),
        'conAssurance' => $_POST['Assurance'] ?? 0,
        'conInstitution' => $_POST['Institution'] ?? 0,
        'conInviVip' => $_POST['VIP'],
        'conBenevole' => $_POST['Benevole'] ?? 0,
        'conEntreprise'=>$_POST['Partenaire'],
        'conMembre' => $_POST['Membre'],
        'conTypeMembre' =>$_POST['TypeMembre'],
        'conDonateur' =>$_POST['Donnateur'] ?? 0,
        'conMembHoneur' =>$_POST['membreHonneure'],
        'conConnaitre' =>$_POST['Connaitre'],
        'conCerebral' =>$_POST['CerebralSuisse'],
        'conProgramme'=>$_POST['Programme'],
        'conGJ' =>$_POST['Tereifics'],
        /*'conAideDomicile' =>$_POST['AideDomicile'],*/
        /*
        'conWK' =>$_POST['WeekEnde'],
        'conServiceReleve' =>$_POST['ServicesReleve'],
        'conCAMP'=>$_POST['Camps'],
        'conContribAssistant' =>$_POST['ContribAssitance'],
        'conReleveScolaire' =>$_POST['Ecole'],
        'conGM' =>$_POST['GrpParent'],
        'conUat' =>$_POST['Uat'],
        'conLoto' =>$_POST['Loto'],
        'conIntendant' => $_POST['conIntendant'],
        'conInfirmiere' => $_POST['conInfirmiere'],
        'conAccompagnantChauffeur' => $_POST['conAccompagnantChauffeur'],
        'conCoResponsable' => $_POST['conCoResponsable'],
        'conResponsable' => $_POST['conResponsable'],
        'conCuisinier' => $_POST['conCuisinier'],
        'conSecondaire' =>$_POST['Secondaire'],
        'conSociete' =>$_POST['Societe'],
        'conParenthese' =>$_POST['parenthese'],
        'conClieLaPar' =>$_POST['CliPar'],
        'conMembLaPar' => $_POST['MemPar'] === "" ? null : intval($_POST['MemPar']),
        'conDonatLaPar' =>$_POST['DonPar'],
        'intervenantParenthese'=> $_POST['intervenantParenthese'] === "" ? null : intval($_POST['intervenantParenthese']),
        'accompagnantParenthese'=> $_POST['accompagnantParenthese'] === "" ? null : intval($_POST['accompagnantParenthese']),
        'intervenantReleve'=> $_POST['intervenantReleve'] === "" ? null : intval($_POST['intervenantReleve']),
        'accompagnantCerebral'=>  $_POST['accompagnantCerebral'] === "" ? null : intval($_POST['accompagnantCerebral']),        
        'intervenantCA'=> $_POST['intervenantCA'] === "" ? null : intval($_POST['intervenantCA']),
        'conTypeMembLaPar' => $_POST['TypeMemPar'] === "" ? null : intval($_POST['TypeMemPar']),
        'conDelivrance' => $_POST['conDelivrance'] === "" ? null : $_POST['conDelivrance']
        //'Marquage' => $_POST['Marquage'] === '' ? null : $_POST['Marquage']
    );*/
        $formatedData = array(
        'conModif' => date('Y-m-d'),
        'tblCiviliter_civId' => $_POST['Titre'] ?? null,
        'conNom' => $_POST['Nom'] ?? '',
        'conPrenom' => $_POST['Prenom'] ?? '',
        'conComplement' => $_POST['Complement'] ?? '',
        'conAdresse' => $_POST['Adresse'] ?? '',
        'conAdresse2' => $_POST['Adresse2'] ?? '',
        'conNpa' => $_POST['Npa'] ?? '',
        'conLocaliter' => $_POST['Localiter'] ?? '',
        'conTel1' => $_POST['tel1'] ?? '',
        'conTel1T' => $_POST['typeTel1'] ?? null,
        'conTel2T' => $_POST['typeTel2'] ?? null,
        'conTel3T' => $_POST['typeTel3'] ?? null,
        'conTel4T' => $_POST['typeTel4'] ?? null,
        'conTel2' => $_POST['tel2'] ?? '',
        'conTel3' => $_POST['tel3'] ?? '',
        'conTel4' => $_POST['tel4'] ?? '',
        'conMail' => $_POST['Mail'] ?? '',
        'conDateNaissance' => (!empty($_POST['Naissance'])) ? $_POST['Naissance'] : null,
        'conCreation' => date('Y-m-d'),
        'conAvs' => $_POST['Avs'] ?? '',
        'conLangues' => $_POST['Langues'] ?? null,
        'conRegion' => $_POST['Region'] ?? null,
        'conCommentaire' => $_POST['commentaire'] ?? '',
        'PermisB' => $_POST['PermisB'] ?? null,
        'PermisD' => $_POST['PermisD'] ?? null,
        'EtatCivil' => $_POST['EtatCivil'] ?? null,
        'Nationaliter' => $_POST['Nationaliter'] ?? null,
        'PermisSejour' => $_POST['PermisSejour'] ?? null,
        'Validiter' => (!empty($_POST['Validiter'])) ? $_POST['Validiter'] : null,
        'Iban' => $_POST['Iban'] ?? '',
        'Banque' => $_POST['Banque'] ?? '',
        'Agence' => $_POST['Agence'] ?? '',
        'conParent' => $_POST['Parent'] ?? null,
        'conHandicaper' => $_POST['Handicape'] ?? 0,
        'conAdulte' => $_POST['Adulte'] ?? 0,
        'conComiter' => $_POST['Comiter'] ?? 0,
        'conInviAmi' => $_POST['ami'] ?? 0,
        'conClientPavillons' => $_POST['Pavillon'] ?? 0,
        'conEmploye' => $_POST['EMployer'] ?? 0,
        'conAssociation' => $_POST['Association'] ?? 0,
        'conAutresTypes' => $_POST['Autres'] ?? 0,
        'conIntervenant' => (!empty($_POST['Intervenant'])) ? intval($_POST['Intervenant']) : null,
        
        // CORRECTION DES CHECKBOXES : Utilisation de ?? 0 pour forcer 0 si décoché
        'conMedecin' => $_POST['Medecin'] ?? 0,
        'conAccompagnant' => (!empty($_POST['Accompagnant'])) ? intval($_POST['Accompagnant']) : null,
        'conAssurance' => $_POST['Assurance'] ?? 0,
        'conInstitution' => $_POST['Institution'] ?? 0,
        'conInviVip' => $_POST['VIP'] ?? 0,
        'conBenevole' => $_POST['Benevole'] ?? 0,
        'conEntreprise' => $_POST['Partenaire'] ?? 0,
        'conMembre' => $_POST['Membre'] ?? 0,
        'conTypeMembre' => $_POST['TypeMembre'] ?? null,
        'conDonateur' => $_POST['Donnateur'] ?? 0,
        'conMembHoneur' => $_POST['membreHonneure'] ?? 0,
        'conConnaitre' => $_POST['Connaitre'] ?? 0,
        'conCerebral' => $_POST['CerebralSuisse'] ?? 0,
        'conProgramme' => $_POST['Programme'] ?? 0,
        'conGJ' => $_POST['Tereifics'] ?? 0,
        'conWK' => $_POST['WeekEnde'] ?? 0,
        'conServiceReleve' => $_POST['ServicesReleve'] ?? 0,
        'conCAMP' => $_POST['Camps'] ?? 0,
        'conContribAssistant' => $_POST['ContribAssitance'] ?? 0,
        'conReleveScolaire' => $_POST['Ecole'] ?? 0,
        'conGM' => $_POST['GrpParent'] ?? 0,
        'conUat' => $_POST['Uat'] ?? 0,
        'conLoto' => $_POST['Loto'] ?? 0,
        'conIntendant' => $_POST['conIntendant'] ?? 0,
        'conInfirmiere' => $_POST['conInfirmiere'] ?? 0,
        'conAccompagnantChauffeur' => $_POST['conAccompagnantChauffeur'] ?? 0,
        'conCoResponsable' => $_POST['conCoResponsable'] ?? 0,
        'conResponsable' => $_POST['conResponsable'] ?? 0,
        'conCuisinier' => $_POST['conCuisinier'] ?? 0,
        'conSecondaire' => $_POST['Secondaire'] ?? 0,
        'conSociete' => $_POST['Societe'] ?? 0, // Vérifiez que ce champ existe dans le formulaire
        'conParenthese' => $_POST['parenthese'] ?? 0,
        'conClieLaPar' => $_POST['CliPar'] ?? 0,
        'conMembLaPar' => (!empty($_POST['MemPar'])) ? intval($_POST['MemPar']) : null,
        'conDonatLaPar' => $_POST['DonPar'] ?? 0,
        'intervenantParenthese' => (!empty($_POST['intervenantParenthese'])) ? intval($_POST['intervenantParenthese']) : null,
        'accompagnantParenthese' => (!empty($_POST['accompagnantParenthese'])) ? intval($_POST['accompagnantParenthese']) : null,
        'intervenantReleve' => (!empty($_POST['intervenantReleve'])) ? intval($_POST['intervenantReleve']) : null,
        'accompagnantCerebral' => (!empty($_POST['accompagnantCerebral'])) ? intval($_POST['accompagnantCerebral']) : null,        
        'intervenantCA' => (!empty($_POST['intervenantCA'])) ? intval($_POST['intervenantCA']) : null,
        'conTypeMembLaPar' => (!empty($_POST['TypeMemPar'])) ? intval($_POST['TypeMemPar']) : null,
        'conDelivrance' => (!empty($_POST['conDelivrance'])) ? $_POST['conDelivrance'] : null
    );
    if(!$insert->execute($formatedData)) {
          $errorInfo = $insert->errorInfo();
        throw new Exception("Erreur PDO: " . $errorInfo[2]);
    }
    $createdId = $bdd->lastInsertId();
    header("location: detContacte.php?conId=".$createdId);
}catch(Exception $e){
    $res = $e->getMessage();
    echo "<pre style='color:red; font-weight:bold'>ERREUR : $res</pre>";
}
}
include('../heade.php');
include ('verif.php');
?>

<h1><?=$mrp->getText('Ajout d\'un contact'); ?></h1>
<h2 id="erreurVide" style="display: none; color: #ff0000;"> <?=$mrp->getText("Les champs en rouge doivent être remplis",'CTREDR'); ?> </h2>
<h2 id="erreurType" style="display: none; color: #ff9025;"> <?=$mrp->getText("Les champs en orange ont un mauvais format",'CTREDF'); ?></h2>

<!-- Début colonne gauche-->
<form action="" method="post">
    <div id="cordonee" style="width: 48%; float: left;">
        <h2><?php echo $mrp->getText('Coordonnées du contact') ?></h2>
        <table class="noMargin" style="border: solid 1px; width: 100%">
            <tr>
                <th><?php echo $mrp->getText('Société') ?></th>
                <td colspan="3"><input size="50" id="Societe" name="Societe" ></td>
            </tr>
            <tr>
                <th><?php echo $mrp->getText('Titre') ?></th>
                <td><select id="Titre" class="input100" name="Titre">
                        <option value="0">-></option><?php ListeDeroulante($titre, 'civId', 'civNom') ?></select>
                </td>
                <th><?php echo $mrp->getText('Adresse secondaire') ?></th>
                <td><input type="checkbox" value="1" name="Secondaire"></td>

            </tr>
            <tr>
                <th><?php echo $mrp->getText('Nom') ?></th>
                <td colspan=""><input size="15" id="Nom" name="Nom"></td>
                <th><?php echo $mrp->getText('Prénom') ?></th>
                <td><input class="input100" name="Prenom"></td>
            </tr>
            <tr>
                <th><?php echo $mrp->getText('Complément') ?></th>
                <td colspan="3"><input class="input350" name="Complement"></td>
            </tr>
            <tr>
                <th><?php echo $mrp->getText('Adresse') ?></th>
                <td colspan="3"><input class="input350" name="Adresse"></td>
            </tr>
            <tr>
                <th><?php echo $mrp->getText('Adresse2') ?></th>
                <td colspan="3"><input class="input350" name="Adresse2"></td>
            </tr>
            <tr>
                <th>NPA</th>
                <td><input class="input100" id="NPA" name="Npa"></td>
                <th><?php echo $mrp->getText('Localité') ?></th>
                <td><input class="input100" id="Localiter" name="Localiter"></td>
            </tr>
            <tr>
                <th><select id="Ttel1" name="typeTel1">
                        <option value="0">-></option>
                        <?php ListeDeroulante($TypeTelephone1, 'tTelId', 'tTelNom') ?></select></th>
                <td><input class="input100" name="tel1" id="tel1" value="+41 "></td>
                <th><select name="typeTel2">
                        <option value="0">-></option>
                        <?php ListeDeroulante($TypeTelephone2, 'tTelId', 'tTelNom') ?></select></th>
                <td><input class="input100" name="tel2" id="tel2" value=""></td>
            </tr>
            <tr>
                <th><select name="typeTel3">
                        <option value="0">-></option>
                        <?php ListeDeroulante($TypeTelephone3, 'tTelId', 'tTelNom') ?></select></th>
                <td><input class="input100" name="tel3" id="tel3" value=""></td>
                <th><select name="typeTel4">
                        <option value="0">-></option>
                        <?php ListeDeroulante($TypeTelephone4, 'tTelId', 'tTelNom') ?></select></th>
                <td><input class="input100" name="tel4" id="tel4" value=""></td>
            </tr>
            <tr>
                <th><?php echo $mrp->getText('e-mail') ?></th>
                <td colspan="3"><input id="Mail" class="input350" name="Mail"></td>
            </tr>
        </table>
        <h2><?php echo $mrp->getText('Infos diverses sur le contact') ?></h2>
        <table class="noMargin" style="border: solid 1px">
            <tr>
                <td colspan="2"></td>
                <th><?php echo $mrp->getText('Adresse la parenthèse') ?></th>
                <td><input type="checkbox" value="1" name="parenthese" ></td>
            </tr>
            <tr>
                <th><?php echo $mrp->getText('Date de naissance') ?></th>
                <td><input  style="width:130px" name="Naissance" type="date" value=""></td>
                <th>Numéro AVS</th>
                <td><input class="input100" name="Avs" id="Avs" value=""></td>
            </tr>
            <tr>
                <th><?php echo $mrp->getText('Résident dans la région') ?></th>
                <td><select name="Region" id="Region">
                        <option value="0">-></option>
                        <?php ListeDeroulante($ContRegion, 'regConId', 'regConNom') ?></select></td>
                <th><?php echo $mrp->getText('Parlant (langue)') ?></th>
                <td><select name="Langues" id="Langue">
                        <option value="0">-></option>
                        <?php ListeDeroulante($Langues, 'lanId', 'lanNom') ?></select></td>
            </tr>
            <tr>
                <th><?php echo $mrp->getText('Création du contac') ?>t</th>
                <td><input disabled class="input100" value="<?php echo date('d.m.y') ?> "></td>
                <th><?php echo $mrp->getText('Modifié le') ?></th>
                <td><input disabled class="input100" value="<?php echo date('d.m.y') ?> "></td>
            </tr>
        </table>
        <h2><?php echo $mrp->getText('Infos spécifiques accompagnant') ?></h2>
        <table class="noMargin" style="border: solid 1px; width:100%;">
            <tr>
                <td> <?php echo $mrp->getText('Disponible comme')?> </td>
            <tr>
            <tr>
                <th> <?php echo $mrp->getText('Accompagnant pour') ?>: </th>
                <td></td>
                <th><?php echo $mrp->getText('Intervenant pour') ?>:</th>
                <td></td>
            </tr>
            <tr>
                <th><?php echo $mrp->getText("Cerebral") ?></th>
                <td>
                    <select name="Accompagnant">
                        <option value="" selected>Non</option>
                        <option value="1">Oui</option>
                        <option value="0">Inactif</option>
                    </select>
                </td>
                <th><?php echo $mrp->getText("Relève") ?></th>
                <td>
                    <select name="Intervenant">
                        <option value="">Non</option>
                        <option value="1">Oui</option>
                        <option value="0" >Inactif</option>
                    </select>
                </td>
            </tr>
            <tr>
                <th>Parenthèse</th>
                <td>
                    <select name="accompagnantParenthese">
                        <option value="">Non</option>
                        <option value="1">Oui</option>
                        <option value="0">Inactif</option>
                    </select>
                </td>
                <th>Parenthèse</th>
                <td>
                    <select name="intervenantParenthese">
                        <option value="">Non</option>
                        <option value="1">Oui</option>
                        <option value="0">Inactif</option>
                    </select>
                </td>
            </tr>
            <tr>             
                <th></th>
                <td></td>
                <th><?php echo $mrp->getText('Cont. Assist.') ?></th>
                <td>
                    <select name="intervenantCA">
                        <option value="">Non</option>
                        <option value="1">Oui</option>
                        <option value="0">Inactif</option>
                    </select>
                </td>
            </tr>
            <tr>
                <th><?php echo $mrp->getText('Permis de conduire voiture (B)') ?></th>
                <td><input type="checkbox" value="1" name="PermisB" ></td>
                <th>Bus 8-16 pl (D1)</th>
                <td><input type="checkbox" value="1" name="PermisD" ></td>
            </tr>
            <tr>
                <th><?php echo $mrp->getText('État civil') ?></th>
                <td><select class="input100" name="EtatCivil">
                        <option value="0">-></option><?php ListeDeroulante($EtatCivil, 'etaCivId', 'eatCivNom') ?>
                    </select>
                </td>

            </tr>
            <tr>
                <th><?php echo $mrp->getText('Nationalité') ?></th>
                <td><select class="input100" name="Nationaliter">
                        <option value="0">-></option><?php ListeDeroulante($Nationaliter, 'natId', 'natNom') ?></select>
                </td>       
                <th><?php echo $mrp->getText('Délivrance') ?></th>
                <td><input name="conDelivrance" type="date" value="<?php echo $contact['conDelivrance']; ?>"></td>
            </tr>
            <tr>
                <th><?php echo $mrp->getText('Permis de séjour') ?></th>
                <td><select class="input100" name="PermisSejour">
                        <option value="0">-></option><?php ListeDeroulante($PermisSejour, 'pSejourId', 'pSejourNom') ?>
                    </select>
                </td>
                <th> <?php echo $mrp->getText('Validité') ?></th>
                <td><input type="date"  style="width:130px" name="Validiter" ></td>
            </tr>
            <tr>
                <th><?php echo $mrp->getText('Banque') ?></th>
                <td colspan="3"><input class="input350" name="Banque"></td>
            </tr>
            <tr>
                <th><?php echo $mrp->getText('Agence de') ?></th>
                <td colspan="3"><input class="input350" name="Agence"></td>
            </tr>
            <tr>
                <th><?php echo $mrp->getText('IBAN/cpte') ?></th>
                <td colspan="3"><input class="input350" name="Iban" id="IBAN"></td>
            </tr>
            <tr>
                <th><?php echo $mrp->getText('Responsable') ?></th>
                <td><input type="checkbox" value="1" name="conResponsable"></td>
                <th><?php echo $mrp->getText('Co-responsable') ?></th>
                <td><input type="checkbox" value="1" name="conCoResponsable"></td>
            </tr>
            <tr>
                <th><?php echo $mrp->getText('Accompagnant chauffeur') ?></th>
                <td><input type="checkbox" value="1" name="conAccompagnantChauffeur"></td>
                <th><?php echo $mrp->getText('Résp. des soins') ?></th>
                <td><input type="checkbox" value="1" name="conInfirmiere"></>
            </tr>
            <tr>
                <th><?php echo $mrp->getText('Cuisinier') ?></th>
                <td><input type="checkbox" value="1" name="conCuisinier"></td>
                <th><?php echo $mrp->getText('Intendant (e)') ?></th>
                <td><input type="checkbox" value="1" name="conIntendant"></td>
            </tr>
        </table>
        <table class="noMargin" style="border: solid 1px;">
            <tr>
                <th><?php echo $mrp->getText(' Commentaire(s)') ?></th>
            </tr>
            <tr>
                <td><textarea cols="67" rows="5" name="commentaire"></textarea></td>
            </tr>

        </table>
    </div>
    <!-- Début colonne droite-->
    <div id="infoDroit" style="width: 48%; margin-left: 52%;">
        <h2><?php echo $mrp->getText('Qualité de contact') ?></h2>
        <table class="noMargin" style="border: solid 1px;">
            <tr>
                <th><?php echo $mrp->getText('Parent/ tuteur') ?></th>
                <td><input type="checkbox" value="1" name="Parent"></td>
            </tr>
            <tr>
                <th><?php echo $mrp->getText('Bénéficiaire(LAI)') ?></th>
                <td><input type="checkbox" value="1" name="Handicape"></td>
                <td><select name="Adulte">
                        <option value="0">Adulte</option>
                        <option value="1">Enfant</option>
                    </select>
        </table>
        <table class="noMargin" style="border: solid 1px;">
            <tr>
                <th colspan="6"><?php echo $mrp->getText('Pour l\'association') ?></th>
            </tr>
            <tr>
                <th><?php echo $mrp->getText('Comité') ?></th>
                <td><input type="checkbox" value="1" name="Comiter"></td>
                <th><?php echo $mrp->getText('Employé') ?></th>
                <td><input type="checkbox" value="1" name="EMployer"></td>
                <th><?php echo $mrp->getText('Bénévole') ?></th>
                <td><input type="checkbox" value="1" name="Benevole"></td>
            </tr>
            <tr>
            <tr>
                <th colspan="6"><?php echo $mrp->getText('Proche de l\'association (partenaires et clients)') ?></th>
            </tr>
            <tr>
                <th><?php echo $mrp->getText('Partenaire') ?></th>
                <td><input type="checkbox" value="1" name="Partenaire"></td>
                <th><?php echo $mrp->getText('Institution') ?></th>
                <td><input type="checkbox" value="1" name="Institution"></td>
                <th><?php echo $mrp->getText('Association') ?></th>
                <td><input type="checkbox" value="1" name="Association"></td>
            <tr>
                <th><?php echo $mrp->getText('Ami, bienfaiteur') ?></th>
                <td><input type="checkbox" value="1" name="ami"></td>
                <th>VIP></th>
                <td><input type="checkbox" value="1" name="VIP"></td>
            </tr>
            <tr>
                <th><?php echo $mrp->getText('Médecin') ?></th>
                <td><input type="checkbox" value="1" name="Medecin"></td>
                <th><?php echo $mrp->getText('Assurance') ?></th>
                <td><input type="checkbox" value="1" name="Assurance"></td>
                <th><?php echo $mrp->getText('Autre') ?></th>
                <td><input type="checkbox" value="1" name="Autres"></td>
            </tr>
            <tr>
                <th><?php echo $mrp->getText('Client Pavillons') ?></th>
                <td><input type="checkbox" value="1" name="Pavillon"></td>
                <th><?php echo $mrp->getText('Client la parenthèse') ?></th>
                <td><input type="checkbox" value="1" name="CliPar"></td>
            </tr>

        </table>
        <h2><?php echo $mrp->getText('Qualité de contributeur') ?></h2>
        <table class="noMargin" style="border: solid 1px;">
            <tr>
                <th><?php echo $mrp->getText('Membre') ?></th>
                <td><select class="input100" name="Membre">
                        <option value="0">-></option><?php ListeDeroulante($Membre, 'memId', 'memNom') ?>
                    </select>
                </td>
                <th><?php echo $mrp->getText('Type') ?></th>
                <td><select class="input100" name="TypeMembre">
                        <option value="0">-></option><?php ListeDeroulante($Type, 'mTypId', 'mTypNom') ?>
                    </select>
                </td>
            </tr>
            <tr>
                <th><?php echo $mrp->getText('Donateur Cerebral') ?></th>
                <td><input type="checkbox" value="1" name="Donnateur"></td>
                <th><?php echo $mrp->getText('Dernier don') ?></th>
                <td><input class="input100" disabled></td>
            </tr>
            <tr>
                <th colspan="3"><?php echo $mrp->getText('Membre d\'honneur de l\'association') ?></th>
                <td><input type="checkbox" value="1" name="membreHonneure"></td>
            </tr>
            <tr>
                <th><?php echo $mrp->getText('Membre la parenthèse') ?></th>
                <td>
                    <select name="MemPar">                
                        <option value="0"></option>
                        <option value="" selected>Non</option>
                        <option value="1">Interessé</option>
                        <option value="2">Actif</option>
                    </select>
                </td>
                <th><?php echo $mrp->getText('Donateur la parenthèse') ?></th>
                <td><input type="checkbox" value="1" name="DonPar"></td>
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
            </tr> */ ?>
        </table>
        <h2><?php echo $mrp->getText('Abonnement') ?></h2>
        <table class="noMargin" style="border: solid 1px;">
            <tr>
                <th><?php echo $mrp->getText('Programme des activités') ?></th>
                <td><input type="checkbox" value="1" name="Programme"></td>
            </tr>
            <tr>
                <th><?php echo $mrp->getText('Bulletin CONNAITRE') ?></th>
                <td><input type="checkbox" value="1" name="Connaitre"></td>
            </tr>
            <tr>
                <th><?php echo $mrp->getText('Journal CEREBRAL Suisse') ?></th>
                <td><input type="checkbox" value="1" name="CerebralSuisse"></td>
            </tr>
            <tr>
                <th><?php echo $mrp->getText('Grp. Parents') ?></th>
                <td><input type="checkbox" value="1" name="GrpParent"></td>
            </tr>
            <tr>                
                <th><?php echo $mrp->getText('Client lotos') ?></th>
                <td><input type="checkbox" value="1" name="Loto"></td>
            </tr>
        </table>
        <h2><?php echo $mrp->getText('Intéressé(e) aux cours / activités / services et autre') ?></h2>
        <table class="noMargin" style="border: solid 1px;">
            <tr>
                <th colspan="2"><?php echo $mrp->getText('Activités') ?></th>
                <th colspan="2"><?php echo $mrp->getText('SERVICES') ?></th>
                <td></td>
            </tr>
            <tr>
                <th><?php echo $mrp->getText('Terrifics') ?></th>
                <td><input type="checkbox" value="1" name="Tereifics"></td>
                <?php /* th><?php echo $mrp->getText('Aide à domicile') ?></th>
                <td><input type="checkbox" value="1" name="AideDomicile"></td */?>
                <th><?php echo $mrp->getText('Service de relève') ?></th>
                <td><input type="checkbox" value="1" name="ServicesReleve"></td>
            </tr>
            <tr>
                <th><?php echo $mrp->getText('Week-ends') ?></th>
                <td><input type="checkbox" value="1" name="WeekEnde"></td>
                <th><?php echo $mrp->getText('Contribution assistance') ?></th>
                <td><input type="checkbox" value="1" name="ContribAssitance"></td>
            </tr>
            <tr>
                <th><?php echo $mrp->getText('Camps') ?></th>
                <td><input type="checkbox" value="1" name="Camps"></td>
                <th><?php echo $mrp->getText('Aide au tiers') ?> <?php //echo $mrp->getText('École') ?></th>
                <td><input type="checkbox" value="1" name="Ecole"></td>
            </tr>
            <tr>
                <th><?php echo $mrp->getText('Court séjour') ?></th>
                <td><input type="checkbox" value="1" name="Uat"></td>
            </tr>
            <tr>
                <th><?php echo $mrp->getText('Marquage manuel') ?></th>
                <td><input type="text" style="width: 20mm" name="Marquage" value="<?php echo $contact['conMarquage'] ?? ''; ?>"></td>
            </tr>
        </table>
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


<?php include '../footer.php'; 

?>