<?php
session_start();
include("../variables.php");

// --- Gestion exports ---
if (isset($_POST['Valider'])) {
    header("location: expotExcelAvance.php");
    exit;
}

if (isset($_POST['ValiderPDF'])) {
    header("location: exportPDFavance.php");
    exit;
}

$bdd = new PDO($dsn, $user, $password);
$bdd->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
$bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);


// nettoyage clefs foireuses du genre : "conAdulte = 1" devient "conAdulte"
$cleanPost = [];
foreach ($_POST as $key => $value) {
    $cleanKey = trim(preg_replace('/\s*=\s*\d+$/', '', $key));
    $cleanPost[$cleanKey] = $value;
}

// Liste des champs filtrables (clé POST => colonne SQL)
$champsAutorises = [
    // Qualité du contact
    'conParent'       => 'conParent',
    'conHandicaper'   => 'conHandicaper',
    'conAdulte'       => 'conAdulte',

    // Membre et type
    'conMembre'       => 'conMembre',
    'conTypeMembre'   => 'conTypeMembre',
    'conDonateur'     => 'conDonateur',

    // Activités / services
    'conGJ'               => 'conGJ',
    'conWK'               => 'conWK',
    'conCAMP'             => 'conCAMP',
    'conGM'               => 'conGM',
    'conLoto'             => 'conLoto',
    'conAideDomicile'     => 'conAideDomicile',
    'conServiceReleve'    => 'conServiceReleve',
    'conContribAssistant' => 'conContribAssistant',
    'conReleveScolaire'   => 'conReleveScolaire',
    'conUat'              => 'conUat',

    // Pour l’association
    'conComiter'  => 'conComiter',
    'conEmploye'  => 'conEmploye',
    'conBenevole' => 'conBenevole',

    // Disponible comme (selects)
    'conAccompagnant'        => 'conAccompagnant',
    'conIntervenant'         => 'conIntervenant',
    'accompagnantParenthese' => 'accompagnantParenthese',
    'intervenantParenthese'  => 'intervenantParenthese',
    'intervenantCA'          => 'intervenantCA',

    // Proche association
    'conEntreprise'   => 'conEntreprise',
    'conInstitution'  => 'conInstitution',
    'conAssociation'  => 'conAssociation',
    'conInviAmi'      => 'conInviAmi',
    'conInviVip'      => 'conInviVip',
    'conMedecin'      => 'conMedecin',
    'conAssurance'    => 'conAssurance',
    'conAutresTypes'  => 'conAutresTypes',
    'conClientPavillons' => 'conClientPavillons',

    // Abonnements
    'conProgramme' => 'conProgramme',
    'conConnaitre' => 'conConnaitre',
    'conCerebral'  => 'conCerebral',
];

// --- Construction dynamique du WHERE et params ---
$conditions = [];
$params = [];
// formatage params
foreach ($champsAutorises as $postKey => $colonneSQL) {
    if (isset($cleanPost[$postKey]) && $cleanPost[$postKey] !== '') {
        $conditions[] = "$colonneSQL = :$postKey";
        $params[$postKey] = $cleanPost[$postKey];
    }
}
// formatage WHERE
if (!empty($conditions)) {
    $recherche = implode(" AND ", $conditions);
} elseif (empty($conditions) && isset($_SESSION['recherche'])) {
    $recherche = $_SESSION['recherche'];
    $params = $_SESSION['params'] ?? [];
} else {
    $recherche = "1=1";
    $params = [];
}

$_SESSION['recherche'] = $recherche;
$_SESSION['params'] = $params;
$_SESSION['filtredContacts'] = getDataForCSV($bdd, $recherche, $params);

// --- Requête principale ---
$sql = "SELECT *  
        FROM tblContact
        LEFT JOIN tblCiviliter ON tblCiviliter_civId = civId 
        WHERE $recherche AND conStatu = 1 
        ORDER BY conNom, conPrenom";

$req = $bdd->prepare($sql);
$req->execute($params);

// --- Requête compteur ---
$sqlCount = "SELECT COUNT(conId) AS nombre  
             FROM tblContact
             WHERE $recherche AND conStatu = 1";

$nbrReq = $bdd->prepare($sqlCount);
$nbrReq->execute($params);
$nbr = $nbrReq->fetchColumn();

include('../heade.php');
?>

<nav id="menu2">
    <ul>
        <li class="textGauche"><a href="contact.php"><?php echo $mrp->getText("Tous les contacts") ?></a></li>
        <li class="textGauche"><a href="filtMarContact.php"><?php echo $mrp->getText(" Filtre recherche marquage manuel"); ?></a></li>
        <li class="textGauche"><a href="filtContact.php"><?php echo $mrp->getText(" Filtre recherche avancée"); ?></a></li>
    </ul>
</nav>

<form method="post">
    <input type="hidden" name="Recherche" value="<?php echo htmlspecialchars($recherche); ?>">
    <input type="submit" name="Valider" value="Export CSV" class="valider">
    <?php if ($nbr < 26) { ?>
        <input type="submit" name="ValiderPDF" value="Export PDF" class="valider">
    <?php } ?>
</form>

<table class="affichage">
    <tr>
        <th><?php echo $mrp->getText("Société") ?></th>
        <th><?php echo $mrp->getText("Nom") ?></th>
        <th><?php echo $mrp->getText("Prénom") ?></th>
        <th><?php echo $mrp->getText("Npa") ?></th>
        <th><?php echo $mrp->getText("Localité") ?></th>
        <th><?php echo $mrp->getText("Tel1 or Tel 2 or Tel 3 or Tel 4") ?></th>
        <th><?php echo $mrp->getText("Marqueur") ?></th>
        <th></th>
    </tr>

    <?php
    $i = 0;
    while ($row = $req->fetch()) { ?>
        <tr>
            <td><?php echo htmlspecialchars($row['conSociete']); ?></td>
            <td><?php echo htmlspecialchars($row['conNom']); ?></td>
            <td><?php echo htmlspecialchars($row['conPrenom']); ?></td>
            <td><?php echo htmlspecialchars($row['conNpa']); ?></td>
            <td><?php echo htmlspecialchars($row['conLocaliter']); ?></td>
            <td>
                <?php 
                echo htmlspecialchars($row['conTel1']) . " <br/> " .
                     htmlspecialchars($row['conTel2']) . " <br/> " .
                     htmlspecialchars($row['conTel3']) . " <br/> " .
                     htmlspecialchars($row['conTel4']); 
                ?>
            </td>
            <td><?php echo htmlspecialchars($row['conMarquage']); ?></td>
            <td>
                <a href="detContacte.php?conId=<?php echo urlencode($row['conId']); ?>">
                    <?php echo $mrp->getText("Détail"); ?>
                </a>
            </td>
        </tr>
        <?php $i++; 
    }
    $req->closeCursor();
    ?>
</table>

<?php echo 'Nombre de contacts trouvés : ' . $i; ?>

<?php include('../footer.php'); 

function getDataForCSV($bdd, $recherche, $params){
    try {
        $sql = "SELECT conSociete as 'Société', civNom as 'Titre', conNom as Nom, conPrenom as Prénom, 
                       conDateNaissance as date_naissance, conComplement as complément,
                       conAdresse as Adresse, conAdresse2 as Adresse2, conNpa as Npa, 
                       conLocaliter as Localité, conTel1 as Téléphone1, conTel2 as Téléphone2, 
                       conTel3 as Téléphone3, conMail as 'e-mail', mTypNom as 'Type' 
                FROM tblContact 
                LEFT JOIN tblCiviliter on tblCiviliter_civId = civId 
                LEFT JOIN tblMembreType on conTypeMembre = mTypId
                WHERE $recherche AND conStatu = 1 
                ORDER BY conNom, conPrenom";
        $req = $bdd->prepare($sql);
        if (empty($params)) {
            $req->execute();
        } else {
            $req->execute($params);
        }
        return $req->fetchAll();
    } catch (Exception $e) {
        return null;
    }
}
?>


