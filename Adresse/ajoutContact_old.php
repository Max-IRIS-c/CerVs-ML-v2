<?php
ini_set('display_errors', 1); 
error_reporting(E_ALL);
try {
    $bdd->query("SELECT 1");
} catch (Exception $e) {
    die("Erreur base de données : " . $e->getMessage());
}
$e = 1;

include ('../variables.php');


$bdd = new PDO($dsn, $user, $password);

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

print_r($_POST);
//echo '<pre>données ::: '; print_r($_POST); echo '</pre>';
if (isset($_POST['Annuler'])) header("location: contact.php");
if (isset($_POST['Valider']) && $_POST['Valider'] === 'Valider') createContact($_POST, $bdd);

include('../heade.php');
include ('verif.php');
?>

<h1><?=$mrp->getText('Ajout d\'un contact'); ?></h1>
<h2 id="erreurVide" style="display: none; color: #ff0000;"> <?=$mrp->getText("Les champs en rouge doivent être remplis",'CTREDR'); ?> </h2>
<h2 id="erreurType" style="display: none; color: #ff9025;"> <?=$mrp->getText("Les champs en orange ont un mauvais format",'CTREDF'); ?></h2>

<!-- Début colonne gauche-->
<form action="#" method="post">
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
                <th><?php echo $mrp->getText('Permis de conduire voiture (B)') ?></th>
                <td><input type="checkbox" value="1" name="PemisB" ></td>
                <th>Bus 8-16 pl (D1)</th>
                <td><input type="checkbox" value="1" name="PemiD1" ></td>
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
                <th>Accompagnant Parenthèse<?php //echo $mrp->getText('Accompagnant') ?></th>
                <td><input type="checkbox" value="1" name="accompagnantParenthese"></td> 
                <th>Accompagnant Cerebral<?php //echo $mrp->getText('Accompagnant') ?></th>
                <td><input type="checkbox" value="1" name="accompagnantCerebral"></td> 
                <th><?php echo $mrp->getText('Intervenant') ?></th>
                <?php //<td><input type="checkbox" value="1" name="Intervenant"></td> ?>
                <td>
                    <select name="intervenantValue" id="intervenantValue">
                        <option value="" selected>-</option>
                        <option value="0">Parenthèse</option>
                        <option value="1">Relève</option>
                        <option value="2">CA</option>
                    </select>
                </td>
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
                <td><input type="checkbox" value="1" name="MemPar"></td>
                <th><?php echo $mrp->getText('Donateur la parenthèse') ?></th>
                <td><input type="checkbox" value="1" name="DonPar"></td>
            </tr>
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

        </table>
        <h2><?php echo $mrp->getText('Intéressé(e) aux cours / activités / services et autre') ?></h2>
        <table class="noMargin" style="border: solid 1px;">
            <tr>
                <th colspan="2"><?php echo $mrp->getText('Activités') ?></th>
                <th colspan="2"><?php echo $mrp->getText('SERVICES') ?></th>
                <td></td>
            </tr>
            <tr>
                <?php /*<th><?php echo $mrp->getText('Terrifics') ?></th>
                <td><input type="checkbox" value="1" name="Tereifics"></td>*/ ?>
                <th><?php echo $mrp->getText('Aide à domicile') ?></th>
                <td><input type="checkbox" value="1" name="AideDomicile"></td>
            </tr>
            <tr>
                <?php /*<th><?php echo $mrp->getText('Week-ends') ?></th>
                <td><input type="checkbox" value="1" name="WeekEnde"></td>*/ ?>
                <th><?php echo $mrp->getText('Service de relève') ?></th>
                <td><input type="checkbox" value="1" name="ServicesReleve"></td>
            </tr>
            <tr>
                <?php /*<th><?php echo $mrp->getText('Camps') ?></th>
                <td><input type="checkbox" value="1" name="Camps"></td>*/ ?>
                <th><?php echo $mrp->getText('Contribution assistance') ?></th>
                <td><input type="checkbox" value="1" name="ContribAssitance"></td>
            </tr>
            <tr>
                <td></td>
                <td></td>
                <?php /*<th><?php echo $mrp->getText('École') ?></th>
                <td><input type="checkbox" value="1" name="Ecole"></td> */ ?>
            </tr>
            <tr>
                <th><?php echo $mrp->getText('Grp. Parents') ?></th>
                <td><input type="checkbox" value="1" name="GrpParent"></td>
                <th><?php echo $mrp->getText('UAT') ?></th>
                <td><input type="checkbox" value="1" name="Uat"></td>
            </tr>         
            <tr>
                <th><?php echo $mrp->getText("Terrifics, Week-ends et camps") ?></th>
                <td><?php CheckBoxModif(0, 'secondaryInfos'); ?></td>          
            </tr>
            <tr>
                <th colspan="4"><?php echo $mrp->getText('Autres intérêts') ?></th>
            </tr>

            <tr>
                <th><?php echo $mrp->getText('Client lotos') ?></th>
                <td><input type="checkbox" value="1" name="Loto"></td>
                <th><?php echo $mrp->getText('Marquage manuel') ?></th>

                <td><input style="width: 20mm" name="Marquage">
                </td>
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

function createContact(array $postData, PDO $bdd) {
    try{
        $sql = "INSERT INTO tblContact (
            conModif, tblCiviliter_civId, conNom, conPrenom, conComplement,
            conAdresse, conAdresse2, conNpa, conLocaliter, conTel1, conTel1T,
            conTel2T, conTel3T, conTel4T, conTel2, conTel3, conTel4, conMail,
            conDateNaissance, conAvs, conLangues, conRegion, conCommentaire,
            conPermisB, conPermiD1, conEtatcivil, conNationalite, conPermisejour,
            conValidPermSejour, conIban, conBanque, conAgence, conParent, conHandicaper,
            conAdulte, conComiter, conInviAmi, conClientPavillons, conEmploye, conAssociation,
            conAutresTypes, conIntervenant, conMedecin, conAccompagnant, conAssurance,
            conInstitution, conInviVip, conBenevole, conEntreprise, conMembre, conTypeMembre,
            conDonateur, conMembHoneur, conConnaitre, conCerebral, conProgramme, conServiceReleve,
            conContribAssistant, conGM, conUat, conLoto, conIntendant, conInfirmiere,
            conAccompagnantChauffeur, conCoResponsable, conResponsable, conCuisinier,
            conSecondaire, conSociete, conParenthese, conClieLaPar, conMembLaPar,
            conMarquage, secondaryInfos, conDonatLaPar
        ) VALUES (
            :conModif, :tblCiviliter_civId, :conNom, :conPrenom, :conComplement,
            :conAdresse, :conAdresse2, :conNpa, :conLocaliter, :conTel1, :conTel1T,
            :conTel2T, :conTel3T, :conTel4T, :conTel2, :conTel3, :conTel4, :conMail,
            :conDateNaissance, :conAvs, :conLangues, :conRegion, :conCommentaire,
            :PermisB, :PermisD, :EtatCivil, :Nationaliter, :PermisSejour,
            :Validiter, :Iban, :Banque, :Agence, :conParent, :conHandicaper,
            :conAdulte, :conComiter, :conInviAmi, :conClientPavillons, :conEmploye, :conAssociation,
            :conAutresTypes, :conIntervenant, :conMedecin, :conAccompagnant, :conAssurance,
            :conInstitution, :conInviVip, :conBenevole, :conEntreprise, :conMembre, :conTypeMembre,
            :conDonateur, :conMembHoneur, :conConnaitre, :conCerebral, :conProgramme, :conServiceReleve,
            :conContribAssistant, :conGM, :conUat, :conLoto, :conIntendant, :conInfirmiere,
            :conAccompagnantChauffeur, :conCoResponsable, :conResponsable, :conCuisinier,
            :conSecondaire, :conSociete, :conParenthese, :conClieLaPar, :conMembLaPar,
            :conMarquage, :secondaryInfos, :conDonatLaPar
        )";

        $stmt = $bdd->prepare($sql);

        // Bind params - à adapter selon type attendu (string, int, etc.)
        $stmt->bindParam(':conModif', $conModif);
        $stmt->bindParam(':tblCiviliter_civId', $tblCiviliter_civId, PDO::PARAM_INT);
        $stmt->bindParam(':conNom', $conNom);
        $stmt->bindParam(':conPrenom', $conPrenom);
        $stmt->bindParam(':conComplement', $conComplement);
        $stmt->bindParam(':conAdresse', $conAdresse);
        $stmt->bindParam(':conAdresse2', $conAdresse2);
        $stmt->bindParam(':conNpa', $conNpa);
        $stmt->bindParam(':conLocaliter', $conLocaliter);
        $stmt->bindParam(':conTel1', $conTel1);
        $stmt->bindParam(':conTel1T', $conTel1T);
        $stmt->bindParam(':conTel2T', $conTel2T);
        $stmt->bindParam(':conTel3T', $conTel3T);
        $stmt->bindParam(':conTel4T', $conTel4T);
        $stmt->bindParam(':conTel2', $conTel2);
        $stmt->bindParam(':conTel3', $conTel3);
        $stmt->bindParam(':conTel4', $conTel4);
        $stmt->bindParam(':conMail', $conMail);
        $stmt->bindParam(':conDateNaissance', $conDateNaissance);
        $stmt->bindParam(':conAvs', $conAvs);
        $stmt->bindParam(':conLangues', $conLangues, PDO::PARAM_INT);
        $stmt->bindParam(':conRegion', $conRegion, PDO::PARAM_INT);
        $stmt->bindParam(':conCommentaire', $conCommentaire);
        $stmt->bindParam(':PermisB', $PermisB);
        $stmt->bindParam(':PermisD', $PermisD);
        $stmt->bindParam(':EtatCivil', $EtatCivil, PDO::PARAM_INT);
        $stmt->bindParam(':Nationaliter', $Nationaliter, PDO::PARAM_INT);
        $stmt->bindParam(':PermisSejour', $PermisSejour, PDO::PARAM_INT);
        $stmt->bindParam(':Validiter', $Validiter);
        $stmt->bindParam(':Iban', $Iban);
        $stmt->bindParam(':Banque', $Banque);
        $stmt->bindParam(':Agence', $Agence);
        $stmt->bindParam(':conParent', $conParent);
        $stmt->bindParam(':conHandicaper', $conHandicaper);
        $stmt->bindParam(':conAdulte', $conAdulte);
        $stmt->bindParam(':conComiter', $conComiter);
        $stmt->bindParam(':conInviAmi', $conInviAmi);
        $stmt->bindParam(':conClientPavillons', $conClientPavillons);
        $stmt->bindParam(':conEmploye', $conEmploye);
        $stmt->bindParam(':conAssociation', $conAssociation);
        $stmt->bindParam(':conAutresTypes', $conAutresTypes);
        $stmt->bindParam(':conIntervenant', $conIntervenant);
        $stmt->bindParam(':conMedecin', $conMedecin);
        $stmt->bindParam(':conAccompagnant', $conAccompagnant);
        $stmt->bindParam(':conAssurance', $conAssurance);
        $stmt->bindParam(':conInstitution', $conInstitution);
        $stmt->bindParam(':conInviVip', $conInviVip);
        $stmt->bindParam(':conBenevole', $conBenevole);
        $stmt->bindParam(':conEntreprise', $conEntreprise);
        $stmt->bindParam(':conMembre', $conMembre);
        $stmt->bindParam(':conTypeMembre', $conTypeMembre);
        $stmt->bindParam(':conDonateur', $conDonateur);
        $stmt->bindParam(':conMembHoneur', $conMembHoneur);
        $stmt->bindParam(':conConnaitre', $conConnaitre);
        $stmt->bindParam(':conCerebral', $conCerebral);
        $stmt->bindParam(':conProgramme', $conProgramme);
        $stmt->bindParam(':conServiceReleve', $conServiceReleve);
        $stmt->bindParam(':conContribAssistant', $conContribAssistant);
        $stmt->bindParam(':conGM', $conGM);
        $stmt->bindParam(':conUat', $conUat);
        $stmt->bindParam(':conLoto', $conLoto);
        $stmt->bindParam(':conIntendant', $conIntendant);
        $stmt->bindParam(':conInfirmiere', $conInfirmiere);
        $stmt->bindParam(':conAccompagnantChauffeur', $conAccompagnantChauffeur);
        $stmt->bindParam(':conCoResponsable', $conCoResponsable);
        $stmt->bindParam(':conResponsable', $conResponsable);
        $stmt->bindParam(':conCuisinier', $conCuisinier);
        $stmt->bindParam(':conSecondaire', $conSecondaire);
        $stmt->bindParam(':conSociete', $conSociete);
        $stmt->bindParam(':conParenthese', $conParenthese);
        $stmt->bindParam(':conClieLaPar', $conClieLaPar);
        $stmt->bindParam(':conMembLaPar', $conMembLaPar);
        $stmt->bindParam(':conMarquage', $conMarquage);
        $stmt->bindParam(':secondaryInfos', $secondaryInfos);
        $stmt->bindParam(':conDonatLaPar', $conDonatLaPar);

        // Affectation des variables aux valeurs $_POST (ou valeurs par défaut)
        $conModif = date('Y-m-d');
        $tblCiviliter_civId = !empty($postData['Titre']) ? (int)$postData['Titre'] : null;
        $conNom = $postData['Nom'] ?? null;
        $conPrenom = $postData['Prenom'] ?? null;
        $conComplement = $postData['Complement'] ?? null;
        $conAdresse = $postData['Adresse'] ?? null;
        $conAdresse2 = $postData['Adresse2'] ?? null;
        $conNpa = $postData['Npa'] ?? null;
        $conLocaliter = $postData['Localiter'] ?? null;
        $conTel1 = $postData['tel1'] ?? null;
        $conTel1T = $postData['typeTel1'] ?? null;
        $conTel2T = $postData['typeTel2'] ?? null;
        $conTel3T = $postData['typeTel3'] ?? null;
        $conTel4T = $postData['typeTel4'] ?? null;
        $conTel2 = $postData['tel2'] ?? null;
        $conTel3 = $postData['tel3'] ?? null;
        $conTel4 = $postData['tel4'] ?? null;
        $conMail = $postData['Mail'] ?? null;
        $conDateNaissance = $postData['Naissance'] ?? null;
        $conAvs = $postData['Avs'] ?? null;
        $conLangues = !empty($postData['Langues']) ? (int)$postData['Langues'] : null;
        $conRegion = !empty($postData['Region']) ? (int)$postData['Region'] : null;
        $conCommentaire = $postData['commentaire'] ?? null;
        $PermisB = $postData['PermisB'] ?? null;
        $PermisD = $postData['PemiD1'] ?? null;
        $EtatCivil = !empty($postData['EtatCivil']) ? (int)$postData['EtatCivil'] : null;
        $Nationaliter = !empty($postData['Nationaliter']) ? (int)$postData['Nationaliter'] : null;
        $PermisSejour = !empty($postData['PermisSejour']) ? (int)$postData['PermisSejour'] : null;
        $Validiter = !empty($postData['Validiter']) ? $postData['Validiter'] : null;
        $Iban = $postData['Iban'] ?? null;
        $Banque = $postData['Banque'] ?? null;
        $Agence = $postData['Agence'] ?? null;
        $conParent = $postData['Parent'] ?? null;
        $conHandicaper = $postData['Handicape'] ?? null;
        $conAdulte = $postData['Adulte'] ?? null;
        $conComiter = $postData['Comiter'] ?? null;
        $conInviAmi = $postData['ami'] ?? null;
        $conClientPavillons = $postData['Pavillon'] ?? null;
        $conEmploye = $postData['EMployer'] ?? null;
        $conAssociation = $postData['Association'] ?? null;
        $conAutresTypes = $postData['Autres'] ?? null;
        $conIntervenant = $postData['Intervenant'] ?? null;
        $conMedecin = $postData['Medecin'] ?? null;
        $conAccompagnant = $postData['Accompagnant'] ?? null;
        $conAssurance = $postData['Assurance'] ?? null;
        $conInstitution = $postData['Institution'] ?? null;
        $conInviVip = $postData['VIP'] ?? null;
        $conBenevole = $postData['Benevole'] ?? null;
        $conEntreprise = $postData['Partenaire'] ?? null;
        $conMembre = $postData['Membre'] ?? null;
        $conTypeMembre = $postData['TypeMembre'] ?? null;
        $conDonateur = $postData['Donnateur'] ?? null;
        $conMembHoneur = $postData['membreHonneure'] ?? null;
        $conConnaitre = $postData['Connaitre'] ?? null;
        $conCerebral = $postData['CerebralSuisse'] ?? null;
        $conProgramme = $postData['Programme'] ?? null;
        $conServiceReleve = $postData['ServicesReleve'] ?? null;
        $conContribAssistant = $postData['ContribAssitance'] ?? null;
        $conGM = $postData['GrpParent'] ?? null;
        $conUat = $postData['Uat'] ?? null;
        $conLoto = $postData['Loto'] ?? null;
        $conIntendant = $postData['Intendant'] ?? null;
        $conInfirmiere = $postData['Infirmier'] ?? null;
        $conAccompagnantChauffeur = $postData['AccompChauffeur'] ?? null;
        $conCoResponsable = $postData['CoResponsable'] ?? null;
        $conResponsable = $postData['Responsable'] ?? null;
        $conCuisinier = $postData['Cuisinier'] ?? null;
        $conSecondaire = $postData['Secondaire'] ?? null;
        $conSociete = $postData['Societe'] ?? null;
        $conParenthese = $postData['Parenthese'] ?? null;
        $conClieLaPar = $postData['CliLaPar'] ?? null;
        $conMembLaPar = $postData['MembLaPar'] ?? null;
        $conMarquage = $postData['Marquage'] ?? null;
        $secondaryInfos = $postData['secondaryInfos'] ?? null;
        $conDonatLaPar = $postData['DonatLaPar'] ?? null;

        // Exécute l'insertion
        if ($stmt->execute()) {
            $newContact = $bdd->lastInsertId(); // Retourne l'ID du nouvel enregistrement     
            header("location: detContacte.php?conId=".$newContact);
        } 
        else return $stmt->errorInfo();
    }catch(Exception $e){
        echo $e->getMessage();
    }
}


?>
