<?php
include('../variables.php');
include_once "../src/class/Db.class.php";
include_once "../src/class/Mrp.class.php";

$db = new DB();
$mrp = new Mrp();
if(isset($_GET["lang"]) && !empty($_GET["lang"])){
    $mrp->setLanguage($_GET["lang"]);
}

$bdd = new PDO($dsn, $user, $password);

// Chargement des listes
$titre = $bdd->query("SELECT * FROM tblCiviliter WHERE civStatu =1");
$TypeTelephone = $bdd->query("SELECT * FROM tblTypeTelephone");
$ContRegion = $bdd->query("SELECT * FROM tblRegionCon");
$Langues = $bdd->query("SELECT * FROM tblangues ORDER BY lanId");
$EtatCivil = $bdd->query("SELECT * FROM tblEtatCivile");
$Nationaliter = $bdd->query("SELECT * FROM tblNationaliter");
$PermisSejour = $bdd->query("SELECT * FROM tblPermisSejour");
$Membre = $bdd->query("SELECT * FROM tblMembre");
$Type = $bdd->query("SELECT * FROM tblMembreType");

if (isset($_POST['Annuler'])) {
    header("location: contact.php");
    exit;
}

if (isset($_POST['Valider'])) {
    try {
        $bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $insert = $bdd->prepare("INSERT INTO tblContact (
            conCreation,
            tblCiviliter_civId,
            conNom,
            conPrenom,
            conComplement,
            conAdresse,
            conAdresse2,
            conNpa,
            conLocaliter,
            conTel1,
            conTel1T,
            conTel2T,
            conTel3T,
            conTel4T,
            conTel2,
            conTel3,
            conTel4,
            conMail,
            conDateNaissance,
            conAvs,
            conLangues,
            conRegion,
            conCommentaire,
            conPermisB,
            conPermiD1,
            conEtatcivil,
            conNationalite,
            conPermisejour,
            conValidPermSejour,
            conIban,
            conBanque,
            conAgence,
            conSociete,
            conSecondaire
        ) VALUES (
            :conCreation,
            :tblCiviliter_civId,
            :conNom,
            :conPrenom,
            :conComplement,
            :conAdresse,
            :conAdresse2,
            :conNpa,
            :conLocaliter,
            :conTel1,
            :conTel1T,
            :conTel2T,
            :conTel3T,
            :conTel4T,
            :conTel2,
            :conTel3,
            :conTel4,
            :conMail,
            :conDateNaissance,
            :conAvs,
            :conLangues,
            :conRegion,
            :conCommentaire,
            :conPermisB,
            :conPermiD1,
            :conEtatcivil,
            :conNationalite,
            :conPermisejour,
            :conValidPermSejour,
            :conIban,
            :conBanque,
            :conAgence,
            :conSociete,
            :conSecondaire
        )");

        $insert->execute([
            'conCreation' => date('Y-m-d'),
            'tblCiviliter_civId' => $_POST['Titre'] ?? null,
            'conNom' => $_POST['Nom'] ?? '',
            'conPrenom' => $_POST['Prenom'] ?? '',
            'conComplement' => $_POST['Complement'] ?? '',
            'conAdresse' => $_POST['Adresse'] ?? '',
            'conAdresse2' => $_POST['Adresse2'] ?? '',
            'conNpa' => $_POST['Npa'] ?? '',
            'conLocaliter' => $_POST['Localiter'] ?? '',
            'conTel1' => $_POST['tel1'] ?? '',
            'conTel1T' => $_POST['typeTel1'] ?? 0,
            'conTel2T' => $_POST['typeTel2'] ?? 0,
            'conTel3T' => $_POST['typeTel3'] ?? 0,
            'conTel4T' => $_POST['typeTel4'] ?? 0,
            'conTel2' => $_POST['tel2'] ?? '',
            'conTel3' => $_POST['tel3'] ?? '',
            'conTel4' => $_POST['tel4'] ?? '',
            'conMail' => $_POST['Mail'] ?? '',
            'conDateNaissance' => !empty($_POST['Naissance']) ? $_POST['Naissance'] : null,
            'conAvs' => $_POST['Avs'] ?? '',
            'conLangues' => $_POST['Langues'] ?? 0,
            'conRegion' => $_POST['Region'] ?? 0,
            'conCommentaire' => $_POST['commentaire'] ?? '',
            'conPermisB' => $_POST['PermisB'] ?? 0,
            'conPermiD1' => $_POST['PemiD1'] ?? 0,
            'conEtatcivil' => $_POST['EtatCivil'] ?? 0,
            'conNationalite' => $_POST['Nationaliter'] ?? 0,
            'conPermisejour' => $_POST['PermisSejour'] ?? 0,
            'conValidPermSejour' => !empty($_POST['Validiter']) ? $_POST['Validiter'] : null,
            'conIban' => $_POST['Iban'] ?? '',
            'conBanque' => $_POST['Banque'] ?? '',
            'conAgence' => $_POST['Agence'] ?? '',
            'conSociete' => $_POST['Societe'] ?? '',
            'conSecondaire' => isset($_POST['Secondaire']) ? 1 : 0,
        ]);

        header("location: contact.php");
        exit;
    } catch (Exception $e) {
        die('Erreur : ' . $e->getMessage());
    }
}

function listeSelect($result, $name, $idField, $labelField) {
    $html = '<select name="' . $name . '"><option value="0">-></option>';
    foreach ($result as $row) {
        $html .= '<option value="' . $row[$idField] . '">' . $row[$labelField] . '</option>';
    }
    $html .= '</select>';
    return $html;
}
include('../heade.php');
?>


<h1><?= $mrp->getText("Création d'un nouveau contact") ?></h1>

<form method="post" action="ajoutContact.php">
    <table>
        <tr><th>Société</th><td colspan="3"><input name="Societe" size="50"></td></tr>
        <tr><th>Titre</th><td><?= listeSelect($titre, "Titre", "civId", "civNom") ?></td>
            <th>Adresse secondaire</th><td><?= checkBox("Secondaire") ?></td></tr>
        <tr><th>Nom</th><td><input name="Nom" size="15"></td><th>Prénom</th><td><input name="Prenom"></td></tr>
        <tr><th>Complément</th><td colspan="3"><input name="Complement" size="50"></td></tr>
        <tr><th>Adresse</th><td colspan="3"><input name="Adresse" size="50"></td></tr>
        <tr><th>Adresse2</th><td colspan="3"><input name="Adresse2" size="50"></td></tr>
        <tr><th>NPA</th><td><input name="Npa" size="10"></td><th>Localité</th><td><input name="Localiter" size="30"></td></tr>

        <tr><th>Type Tel 1</th><td><?= listeSelect($TypeTelephone, "typeTel1", "tTelId", "tTelNom") ?><br><input name="tel1" size="20"></td>
            <th>Type Tel 2</th><td><?= listeSelect($TypeTelephone, "typeTel2", "tTelId", "tTelNom") ?><br><input name="tel2" size="20"></td></tr>
        <tr><th>Type Tel 3</th><td><?= listeSelect($TypeTelephone, "typeTel3", "tTelId", "tTelNom") ?><br><input name="tel3" size="20"></td>
            <th>Type Tel 4</th><td><?= listeSelect($TypeTelephone, "typeTel4", "tTelId", "tTelNom") ?><br><input name="tel4" size="20"></td></tr>

        <tr><th>Email</th><td colspan="3"><input name="Mail" size="50"></td></tr>
        <tr><th>Date de naissance</th><td><input name="Naissance" type="date"></td>
            <th>Numéro AVS</th><td><input name="Avs"></td></tr>

        <tr><th>Région</th><td><?= listeSelect($ContRegion, "Region", "regConId", "regConNom") ?></td>
            <th>Langue</th><td><?= listeSelect($Langues, "Langues", "lanId", "lanNom") ?></td></tr>

        <tr><th>État Civil</th><td><?= listeSelect($EtatCivil, "EtatCivil", "etCivId", "etCivNom") ?></td>
            <th>Nationalité</th><td><?= listeSelect($Nationaliter, "Nationaliter", "natId", "natNom") ?></td></tr>

        <tr><th>Permis de séjour</th><td><?= listeSelect($PermisSejour, "PermisSejour", "perId", "perNom") ?></td>
            <th>Validité</th><td><input name="Validiter" type="date"></td></tr>

        <tr><th>Banque</th><td><input name="Banque"></td>
            <th>Agence</th><td><input name="Agence"></td></tr>
        <tr><th>IBAN</th><td colspan="3"><input name="Iban" size="50"></td></tr>

        <tr><th>Commentaire</th><td colspan="3"><textarea name="commentaire" rows="4" cols="70"></textarea></td></tr>

        <tr><th>Adulte</th><td><?= checkBox("Adulte") ?></td>
            <th>Membre</th><td><?= checkBox("Membre") ?></td></tr>
        <tr><th>Type Membre</th><td><?= listeSelect($Type, "TypeMembre", "typMemId", "typMemNom") ?></td></tr>

        <tr><th>Intervenant lié</th><td colspan="3"><input name="intervenantValue"></td></tr>
        <tr><th>Handicapé</th><td><?= checkBox("Handicaper") ?></td>
            <th>Parenthèse</th><td><?= checkBox("Parent") ?></td></tr>
        <tr><th>Invité par ami</th><td><?= checkBox("InviAmi") ?></td>
            <th>Permis B</th><td><?= checkBox("PemisB") ?></td></tr>
        <tr><th>Permis C</th><td><?= checkBox("PemisC") ?></td>
            <th>Travailleur</th><td><?= checkBox("Travailleur") ?></td></tr>

        <tr><th>Secondaire infos visibles</th><td><?= checkBox("secondaryInfos") ?></td>
            <th>Marquage</th><td><input name="Marquage"></td></tr>
    </table>

    <br>
    <input type="submit" name="Valider" value="Valider">
    <input type="submit" name="Annuler" value="Annuler">
</form>