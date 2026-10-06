<?php
ob_start();
require_once '../header.php';
require_once '../Travail/verifi.php';

/** Classes & Dépendances */
require_once '../src/class/contrats-gestion.php';
require_once "../src/class/Db.class.php";
require_once "../src/class/Mrp.class.php";

$mrp = new Mrp();
// Assurez-vous que $dsn, $user, $password sont définis dans verifi.php ou variables.php
// Si non, décommentez la ligne ci-dessous :
// require_once '../variables.php'; 

try {
    $bdd = new PDO($dsn, $user, $password);
    $bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion à la base de données.");
}

// --- Récupération et Validation des Entrées ---
$id = filter_input(INPUT_GET, 'Id', FILTER_VALIDATE_INT);
$searchedParam = filter_input(INPUT_GET, 'searchedParam', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$contratId = filter_input(INPUT_GET, 'Contrat', FILTER_VALIDATE_INT);

if (!$id) {
    die("ID de contact invalide.");
}

// --- Variables d'état pour les messages ---
$errorPDFload = false;
$successPDFload = false;
$errorPDFDelete = false;
$successPDFDelete = false;
$message = "";

// --- TRAITEMENT DES FORMULAIRES (POST) ---

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    // 1. Upload PDF
    if (isset($_POST['submit']) && isset($_FILES['newPDF'])) {
        $idContrat = filter_input(INPUT_POST, 'idContrat', FILTER_VALIDATE_INT);
        if ($idContrat) {
            try {
                $record = Contract::recordNewPDFfile('accompagnant', $idContrat, $_FILES['newPDF']);
                if ($record) {
                    $successPDFload = true;
                } else {
                    throw new Exception("Échec de l'enregistrement.");
                }
            } catch (Exception $e) {
                $errorPDFload = true;
            }
        }
    }

    // 2. Modification Contrat
    if (isset($_POST['Modifier']) && $_POST['Modifier'] === "Valider") {
        $contId = intval($_POST['contId'] ?? 0);
        $annee = $_POST['annee'] ?? '';
        $type = $_POST['contType'] ?? '';
        $brut = $_POST['contSalaireBruit'] ?? '';
        $chauffeur = $_POST['contSalaireChauffeur'] ?? '';
        $signer = $_POST['signer'] ?: null;       
        $update = $bdd->prepare("
            UPDATE tblContratAcc
            SET contType = ?, contSalaireBruit = ?, contSalaireChauffeur = ?, contAnnee = ?, contSigner = ?, contModification = NOW()
            WHERE contId = ?
        ");
        if ($update->execute([$type, $brut, $chauffeur, $annee, $signer, $contId])) {
            $message = "✅ Contrat modifié avec succès.";
        } else {
            $message = "❌ Erreur lors de la modification du contrat.";
        }
    }

    // 3. Suppression Contrat (Base de données)
    if (isset($_POST['Supprimer']) && $_POST['Supprimer'] === "Supprimer") {
        $cId = filter_input(INPUT_POST, 'IdContrat', FILTER_VALIDATE_INT) ?? filter_input(INPUT_POST, 'contId', FILTER_VALIDATE_INT);
        
        if ($cId) {
            // Suppression du contrat principal
            $stmtDel = $bdd->prepare("DELETE FROM tblContratAcc WHERE contId = ?");
            if ($stmtDel->execute([$cId])) {
                $message = "✅ Contrat supprimé avec succès.";
            } else {
                $message = "❌ Erreur lors de la suppression du contrat.";
            }
            header("Location: contrat.php?Id=" . $id . "&searchedParam=" . $searchedParam);
            exit;
        }
    }

    // 4. Création Nouveau Contrat
    if (isset($_POST['Cree'])) {
            $annee = $_POST['annee'] ?? '';
        $type = $_POST['contType'] ?? '';
        $brut = $_POST['contSalaireBruit'] ?? '';
        $chauffeur = $_POST['contSalaireChauffeur'] ?? '';

        $insert = $bdd->prepare("
            INSERT INTO tblContratAcc (conId, contCreation, contType, contSalaireBruit, contSalaireChauffeur, contAnnee)
            VALUES (?, NOW(), ?, ?, ?, ?)
        ");
        if ($insert->execute([$id, $type, $brut, $chauffeur, $annee])) {
            $message = "✅ Contrat ajouté avec succès.";
        } else {
            $message = "❌ Erreur lors de l'ajout du contrat.";
        }
    }

    // 5. Suppression PDF
    if (isset($_POST['deletePDF'])) {
        $idContrat = filter_input(INPUT_POST, 'idContrat', FILTER_VALIDATE_INT);
        if ($idContrat) {
            try {
                $delete = Contract::deletePDF('accompagnant', $idContrat);
                if ($delete) {
                    $successPDFDelete = true;
                } else {
                    throw new Exception("Échec de la suppression.");
                }
            } catch (Exception $e) {
                $errorPDFDelete = true;
            }
        }
    }
}

// --- CHARGEMENT DES DONNÉES (GET) ---

// Récupération infos contact
$stmtContact = $bdd->prepare("SELECT conNom, conPrenom, conDateNaissance, conNationalite, conAdresse, conNpa, conLocaliter, conAvs, conBanque, conAgence, conIban FROM tblContact WHERE conId = ?");
$stmtContact->execute([$id]);
$contact = $stmtContact->fetch();
if (!$contact) {
    die("Contact introuvable.");
}
// récupération type d'accompagnant 
$stmtAccompagnant = $bdd->query("SELECT * FROM tblTypeAcc");
$stmtAccompagnant->execute();
$tAccompagnant = $stmtAccompagnant->fetch();
    
function selectTaccompagnant() {
    while ($type = $tAccompagnant->fetch()) {
        $selected = ($type['tAccId'] == $contrat['contType']) ? 'selected' : '';
        echo "<option value='{$type['tAccId']}' $selected>{$type['tAccNom']}</option>";
    }
}
// Récupération contrats
if ($contratId) {
    // Mode Édition : On récupère le contrat spécifique à éditer ET la liste des autres
    $stmtContratModif = $bdd->prepare("SELECT * FROM tblContratAcc WHERE contId = ?");
    $stmtContratModif->execute([$contratId]);
    $contratModif = $stmtContratModif->fetch();

    $stmtContrats = $bdd->prepare("SELECT * FROM tblContratAcc WHERE conId = ? AND contId != ?");
    $stmtContrats->execute([$id, $contratId]);
} else {
    // Mode Liste : On récupère tous les contrats
    $stmtContrats = $bdd->prepare("SELECT * FROM tblContratAcc WHERE conId = ?");
    $stmtContrats->execute([$id]);
}

// Fonction helper pour l'affichage d'une ligne de contrat (évite la duplication de code HTML)
/* conId, contCreation, contType, contSalaireBruit, contSalaireChauffeur, contAnnee  */
function renderContractRow($row, $id, $searchedParam, $mrp, $isEditing = false) {
    ?>
    <tr>
        <td><?php echo $isEditing ? '<input style="width: 25mm" name="annee" value="' . htmlspecialchars($row['contAnnee']) . '">' : htmlspecialchars($row['contAnnee']); ?></td>
        <td><?php  
                if($isEditing){
                    echo '<select name="type">';
                    selectTaccompagnant();
                    echo '</select>';
                }else{
                    echo '<input class="input150" name="contType" value="' . htmlspecialchars($row['contType']) . '">';
                } 
            ?></td>
        <td><?php echo $isEditing ? '<input class="input150" name="contSalaireBruit" value="' . htmlspecialchars($row['contSalaireBruit']) . '">' :$row['contSalaireBruit']; ?></td>
        <td><?php echo $isEditing ? '<input class="input150" name="contSalaireChauffeur" value="' . htmlspecialchars($row['contSalaireChauffeur']) . '">' :$row['contSalaireChauffeur']; ?></td>
        <td><?php echo $isEditing ? '<input class="input150" name="signer" value="' . htmlspecialchars($row['contSigne']) . '">' : DateToUser($row['contSigne']); ?></td>
        <!-- Actions -->
        <td class="signed">
            <?php if ($isEditing): ?>
                <input type="submit" value="Valider" name="Modifier" class="ValiderPetit">
                <input type="hidden" name="IdContrat" value="<?php echo $row['contId']; ?>">
                <input type="submit" value="Supprimer" name="Supprimer" class="SuprimerrPetit">
            <?php else: ?>
                    <a class="bt blue" href="contratPrintAcc.php?Id=<?= $contrat['contId'] ?>" target="_blank">Imprimer</a>              
                    <a  href="contratAcc.php?Id=<?= $id ?>&Contrat=<?= $contrat['contId'] ?>&searchedParam=<?= $searchedParam ?>" class="bt green">Modifier</a>
            <?php endif; ?>
        </td>
        <!-- Contrat signé -->
        <td>
            <?php 
            $contractPDF = Contract::signedContrat('accompagnant', intval($row['contId']));
            if (!$contractPDF['exist']): 
            ?>
                <?php if (!$isEditing): ?>
                <form method="post" enctype="multipart/form-data">
                    <input type="hidden" name="idContrat" value="<?php echo $row['contId']; ?>" />
                    <input id="newPDF" name="newPDF" type="file" accept=".pdf" />
                    <button class="sub" type="submit" name="submit" value="submit">Enregistrer</button>
                </form>
                <?php endif; ?>
            <?php else: ?>
                <div class="signed">
                    <a class="bt blue" onclick="window.open('./intervenant_contractsFiles/<?php echo htmlspecialchars($contractPDF['url']); ?>', '_blank')">PDF</a>
                    <form method="post">
                        <input type="hidden" name="idContrat" value="<?php echo $row['contId']; ?>"/>
                        <button class="delete" name="deletePDF" value="1">Supprimer pdf</button>
                    </form>
                </div>
            <?php endif; ?>
        </td>
    </tr>
    <?php
}
?>

<nav id="menu2">
    <ul>
        <li><a href="detContacte.php?conId=<?= $id ?>&searchedParam=<?= $searchedParam ?>">Retour au contact</a></li>
    </ul>
</nav>

<h1>Liste des contrats intervenant avec <?php echo htmlspecialchars($contact['conPrenom'] . ' ' . $contact['conNom']) ?></h1>

<!-- Messages d'alerte -->
<?php if ($errorPDFload): ?>
    <div class="alert error">
        ⚠️ Le fichier n'a pas pu être enregistré.<br>
        1) Vérifiez que vous avez bien sélectionné un fichier PDF valide.<br>
        2) Réessayez après un rafraîchissement de la page.<br>
        3) Si le problème persiste, contactez le développeur.
    </div>
<?php endif; ?>

<?php if ($successPDFload): ?>
    <p class="success">✅ Le fichier a bien été enregistré.</p>
<?php endif; ?>

<?php if ($errorPDFDelete): ?>
    <p class="alert error">⚠️ Une erreur est survenue lors de la suppression du PDF.</p>
<?php endif; ?>

<?php if ($successPDFDelete): ?>
    <p class="success">✅ Le fichier a bien été supprimé.</p>
<?php endif; ?>

<?php if (!empty($message)): ?>
    <p class="<?php echo strpos($message, '✅') !== false ? 'success' : 'error'; ?>"><?php echo $message; ?></p>
<?php endif; ?>

<style>
    .alert { padding: 15px; margin-bottom: 20px; border-radius: 4px; }
    .alert.error { background-color: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
    .success { width: 100%; padding: 15px; background-color: #c5e1a5; color: #2e7d32; box-sizing: border-box; }
    
    table.affichage { width: 100%; border-collapse: collapse; margin-top: 20px; }
    table.affichage th, table.affichage td { border: 1px solid #ddd; padding: 8px; text-align: left; }
    table.affichage th { background-color: #4b86c1; }

    .delete { background-color: #e53935; color: white; border: none; padding: 7px; cursor: pointer; }
    .sub { background-color: #3384e5; color: white; border: none; padding: 5px; margin-top: 5px; cursor: pointer; }
    
    .bt { padding: 5px 10px; color: white; text-decoration: none; border-radius: 3px; display: inline-block; margin: 2px; }
    .blue { background-color: #1e88e5; }
    .green { background-color: #43a047; }
    .red { background-color: #e53935; }
    
    .button { font-size: small; padding: 5px; color: black; background-color: lightgray; font-weight: bold; }
    .button:hover { background-color: lightgreen; border: 2px solid blue; }
    
    .signed { display: flex; flex-direction: column; justify-content: center; align-items: start; gap: 10px; }
    .input150 { width: 150px; }
</style>

<table class="affichage">
    <thead>
        <tr>
            <th>Année</th>
            <th>Type</th>
            <th>Salaire brut</th>
            <th>Salaire chauffeur</th>
            <th>Signé le</th>
            <th>Actions</th>
            <th>Contrat signé</th>
        </tr>
    </thead>
    <tbody>
        <?php
        if (isset($contratId) && $contratModif):
            // MODE ÉDITION
            ?>
            <form action="#" method="post">
                <?php renderContractRow($contratModif, $id, $searchedParam, $mrp, true); ?>
            </form>
            
            <?php 
            // Affichage des autres contrats en mode lecture
            while ($row = $stmtContrats->fetch()) {
                renderContractRow($row, $id, $searchedParam, $mrp, false);
            }
        else:
            // MODE LISTE STANDARD
            while ($row = $stmtContrats->fetch()) {
                renderContractRow($row, $id, $searchedParam, $mrp, false);
            }
            ?>
            <form action="#" method="post">
                <tr>
                    <td><input style="width: 25mm" name="annee" placeholder="Année"></td>
                    <td><input class="input150" type="date" name="debut"></td>
                    <td><input class="input150" type="date" name="fin"></td>
                    <td colspan="2"><input type="submit" value="Créer" name="Cree" class="ValiderPetit"></td>
                    <td></td>
                </tr>
            </form>
        <?php endif; ?>
    </tbody>
</table>

<?php 
include('../footer.php'); 
ob_end_flush();
?>