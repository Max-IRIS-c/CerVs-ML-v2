<?php
include("../variables.php");

$bdd = new PDO($dsn, $user, $password);
$bdd->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
$bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// --- Construction dynamique du WHERE ---
$conditions = [];
$params = [];

// Liste des champs filtrables (clé POST => colonne SQL)
$champsAutorises = [    
    'Accompagnant' => 'conAccompagnant',
    'Intervenant'  => 'conIntervenant',
    'accompagnantParenthese' => 'accompagnantParenthese',
    'intervenantParenthese' => 'intervenantParenthese',
    'intervenantCA' => 'intervenantCA'
    //'accompagnantCerebral' => 'accompagnantCerebral',
    //'intervenantReleve' => 'intervenantReleve',
];

foreach ($champsAutorises as $postKey => $colonneSQL) {
    if (isset($_POST[$postKey]) && $_POST[$postKey] !== '') {
        $conditions[] = "$colonneSQL = :$postKey";
        $params[":$postKey"] = $_POST[$postKey];
    }
}

if(count($conditions) === 0 && $_SESSION['recherche'] !== "1=1") $recherche = $_SESSION['recherche'];
else{
    $recherche = $conditions ? implode(" AND ", $conditions) : "1=1";
    $_SESSION['recherche'] = $recherche;
    $_SESSION['params'] = $params;
} 
/*
if($recherche = "1=1" && $_SESSION['recherche'] !== "1=1")
$_SESSION['recherche'] = $recherche;
*/
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


// --- Gestion exports ---
if (isset($_POST['Valider'])) {
    $_SESSION['filtredContacts'] = getDataForCSV($bdd, $recherche, $params);
    header("location: expotExcelAvance.php");
    exit;
}

if (isset($_POST['ValiderPDF'])) {
    $requet = $recherche;
    header("location: exportPDFavance.php?Id=" . urlencode($requet));
    exit;
}

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
    try{
        $sql = "SELECT conSociete as 'Société', civNom as 'Titre', conNom as Nom,conPrenom as Prénom, conDateNaissance as date_naissance,conComplement as complément,
        conAdresse as Adresse, conAdresse2 as Adresse2, conNpa as Npa, conLocaliter as Localité, conTel1 as Téléphone1, conTel2 as Téléphone2, 
        conTel3 as Téléphone3, conMail as 'e-mail', mTypNom as 'Type' from tblContact 
        LEFT JOIN tblCiviliter on tblCiviliter_civId = civId 
        LEFT JOIN tblMembreType on conTypeMembre = mTypId
        WHERE $recherche AND conStatu = 1 
        ORDER BY conNom, conPrenom";
        $req = $bdd->prepare($sql);
        $req->execute($params); 
        return $req->fetchAll();
    }catch(Exception $e){
        return null;
    }
}

?>


