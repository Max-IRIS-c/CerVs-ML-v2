<?php
ob_start();
require_once '../header.php';
require_once '../Travail/verifi.php';

/** Classes & Dépendances */
require_once '../src/class/contrats-gestion.php';
require_once "../src/class/Db.class.php";

// Gestion de Mrp (langue)
if (file_exists("../src/class/Mrp.class.php")) {
    require_once "../src/class/Mrp.class.php";
    $mrp = new Mrp();
} else {
    $mrp = new stdClass();
    $mrp->language = 'fr';
}

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
        $contId = filter_input(INPUT_POST, 'contId', FILTER_VALIDATE_INT);
        $annee = $_POST['annee'] ?? '';
        $type = $_POST['contType'] ?? '';
        $brut = $_POST['contSalaireBruit'] ?? '';
        $chauffeur = $_POST['contSalaireChauffeur'] ?? '';
        $signer = !empty($_POST['signer']) ? $_POST['signer'] : null;

        if ($contId) {
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
            // Redirection pour nettoyer le POST
            header("Location: contratAcc.php?Id=" . $id . "&searchedParam=" . $searchedParam);
            exit;
        }
    }

    // 3. Suppression Contrat (Base de données)
    if (isset($_POST['Supprimer']) && $_POST['Supprimer'] === "Supprimer") {
        $cId = filter_input(INPUT_POST, 'contId', FILTER_VALIDATE_INT);
        
        if ($cId) {
            $stmtDel = $bdd->prepare("DELETE FROM tblContratAcc WHERE contId = ?");
            if ($stmtDel->execute([$cId])) {
                $message = "✅ Contrat supprimé avec succès.";
            } else {
                $message = "❌ Erreur lors de la suppression du contrat.";
            }
            header("Location: contratAcc.php?Id=" . $id . "&searchedParam=" . $searchedParam);
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
        header("Location: contratAcc.php?Id=" . $id . "&searchedParam=" . $searchedParam);
        exit;
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
$stmtContact = $bdd->prepare("SELECT conNom, conPrenom FROM tblContact WHERE conId = ?");
$stmtContact->execute([$id]);
$contact = $stmtContact->fetch();

if (!$contact) {
    die("Contact introuvable.");
}

// Récupération de TOUS les types d'accompagnants (une seule fois)
$stmtTypes = $bdd->query("SELECT * FROM tblTypeAcc");
$typesAccompagnant = $stmtTypes->fetchAll(PDO::FETCH_ASSOC);

// Récupération contrats
if ($contratId) {
    // Mode Édition
    $stmtContratModif = $bdd->prepare("SELECT * FROM tblContratAcc WHERE contId = ?");
    $stmtContratModif->execute([$contratId]);
    $contratModif = $stmtContratModif->fetch();

    $stmtContrats = $bdd->prepare("SELECT * FROM tblContratAcc WHERE conId = ? AND contId != ?");
    $stmtContrats->execute([$id, $contratId]);
} else {
    // Mode Liste
    $stmtContrats = $bdd->prepare("SELECT * FROM tblContratAcc WHERE conId = ? ORDER BY contAnnee DESC");
    $stmtContrats->execute([$id]);
}

// Fonction helper pour l'affichage d'une ligne de contrat
function renderContractRow($row, $id, $searchedParam, $types, $isEditing = false) {
    ?>
    <tr>
        <td>
            <?php if ($isEditing): ?>
                <input style="width: 25mm" name="annee" value="<?= htmlspecialchars($row['contAnnee']) ?>" required>
            <?php else: ?>
                <?= htmlspecialchars($row['contAnnee']) ?>
            <?php endif; ?>
        </td>
        <td>
            <?php if ($isEditing): ?>
                <select name="contType">
                    <?php foreach ($types as $type): ?>
                        <option value="<?= $type['tAccId'] ?>" <?= ($type['tAccId'] == $row['contType']) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($type['tAccNom']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            <?php else: ?>
                <!-- Affichage du nom du type via une requête JOIN serait idéal, ici on affiche l'ID ou on pourrait le chercher -->
                <!-- Pour simplifier sans refaire toute la SQL, on affiche l'ID si pas de jointure, mais mieux vaut faire la jointure dans la requête principale -->
                <!-- Note: Dans la requête principale ci-dessus, il manque le JOIN pour le nom. On va l'ajouter juste après la fonction si besoin, 
                     mais pour l'instant, affichons l'ID ou cherchons le nom dans le tableau $types passé -->
                <?php 
                    $typeName = 'Inconnu';
                    foreach($types as $t) {
                        if($t['tAccId'] == $row['contType']) {
                            $typeName = $t['tAccNom'];
                            break;
                        }
                    }
                    echo htmlspecialchars($typeName);
                ?>
            <?php endif; ?>
        </td>
        <td>
            <?php if ($isEditing): ?>
                <input class="input150" name="contSalaireBruit" value="<?= htmlspecialchars($row['contSalaireBruit']) ?>">
            <?php else: ?>
                <?= htmlspecialchars($row['contSalaireBruit']) ?>
            <?php endif; ?>
        </td>
        <td>
            <?php if ($isEditing): ?>
                <input class="input150" name="contSalaireChauffeur" value="<?= htmlspecialchars($row['contSalaireChauffeur']) ?>">
            <?php else: ?>
                <?= htmlspecialchars($row['contSalaireChauffeur']) ?>
            <?php endif; ?>
        </td>
        <td>
            <?php if ($isEditing): ?>
                <input type="date" class="input150" name="signer" value="<?= htmlspecialchars($row['contSigner']) ?>">
            <?php else: ?>
                <?= $row['contSigner'] ? date('d.m.Y', strtotime($row['contSigner'])) : '' ?>
            <?php endif; ?>
        </td>
        
        <!-- Actions -->
        <td class="signed">
            <?php if ($isEditing): ?>
                <input type="hidden" name="contId" value="<?= $row['contId'] ?>">
                <button type="submit" name="Modifier" value="Valider" class="ValiderPetit">Valider</button>
                <button type="submit" name="Supprimer" value="Supprimer" class="SuprimerrPetit">Supprimer</button>
                <a href="contratAcc.php?Id=<?= $id ?>&searchedParam=<?= $searchedParam ?>" class="AnnulerPetit">Annuler</a>
            <?php else: ?>
                <div style="display:flex; flex-direction:column; gap:5px;">
                    <a class="bt blue" href="contratPrintAcc.php?Id=<?= $row['contId'] ?>" target="_blank">Imprimer</a>
                    <a href="contratAcc.php?Id=<?= $id ?>&Contrat=<?= $row['contId'] ?>&searchedParam=<?= $searchedParam ?>" class="bt green">Modifier</a>
                    <!-- Suppression directe dans la liste -->
                    <form method="post" style="display:inline;" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce contrat ?');">
                        <input type="hidden" name="contId" value="<?= $row['contId'] ?>">
                        <button type="submit" name="Supprimer" value="Supprimer" class="bt red">Supprimer</button>
                    </form>
                </div>
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
                    <input type="hidden" name="idContrat" value="<?= $row['contId'] ?>" />
                    <input id="newPDF" name="newPDF" type="file" accept=".pdf" />
                    <button class="sub" type="submit" name="submit" value="submit">Enregistrer</button>
                </form>
                <?php endif; ?>
            <?php else: ?>
                <div class="signed">
                    <a class="bt blue" onclick="window.open('./accompagnant_contractsFiles/<?= htmlspecialchars($contractPDF['url']); ?>', '_blank')">PDF</a>
                    <form method="post">
                        <input type="hidden" name="idContrat" value="<?= $row['contId'] ?>"/>
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

<h1>Contrats accompagnant pour <?= htmlspecialchars($contact['conPrenom'] . ' ' . $contact['conNom']) ?></h1>

<!-- Messages d'alerte -->
<?php if ($errorPDFload): ?>
    <div class="alert error">⚠️ Le fichier n'a pas pu être enregistré. Réessayez ou contactez le support.</div>
<?php endif; ?>
<?php if ($successPDFload): ?>
    <p class="success">✅ Le fichier a bien été enregistré.</p>
<?php endif; ?>
<?php if ($errorPDFDelete): ?>
    <p class="alert error">⚠️ Erreur lors de la suppression du PDF.</p>
<?php endif; ?>
<?php if ($successPDFDelete): ?>
    <p class="success">✅ Le fichier a bien été supprimé.</p>
<?php endif; ?>
<?php if (!empty($message)): ?>
    <p class="<?= strpos($message, '✅') !== false ? 'success' : 'error'; ?>"><?= htmlspecialchars($message) ?></p>
<?php endif; ?>

<style>
    .alert { padding: 15px; margin-bottom: 20px; border-radius: 4px; }
    .alert.error { background-color: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
    .success { width: 100%; padding: 15px; background-color: #c5e1a5; color: #2e7d32; box-sizing: border-box; }
    .error { width: 100%; padding: 15px; background-color: #f8d7da; color: #721c24; box-sizing: border-box; }
    
    table.affichage { width: 100%; border-collapse: collapse; margin-top: 20px; background: white; }
    table.affichage th, table.affichage td { border: 1px solid #ddd; padding: 8px; text-align: left; }
    table.affichage th { background-color: #4b86c1; color: white; }

    .ValiderPetit { background-color: #43a047; color: white; border: none; padding: 5px 10px; cursor: pointer; }
    .SuprimerrPetit { background-color: #e53935; color: white; border: none; padding: 5px 10px; cursor: pointer; margin-left: 5px;}
    .AnnulerPetit { background-color: #ccc; color: black; padding: 5px 10px; text-decoration: none; margin-left: 5px; display:inline-block;}
    
    .delete { background-color: #e53935; color: white; border: none; padding: 7px; cursor: pointer; width:100%; }
    .sub { background-color: #3384e5; color: white; border: none; padding: 5px; margin-top: 5px; cursor: pointer; }
    
    .bt { padding: 5px 10px; color: white; text-decoration: none; border-radius: 3px; display: inline-block; border:none; cursor:pointer; text-align:center;}
    .blue { background-color: #1e88e5; }
    .green { background-color: #43a047; }
    .red { background-color: #e53935; }
    
    .signed { display: flex; flex-direction: column; justify-content: center; align-items: start; gap: 6px; }
    .input150 { width: 130px; }
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
                <?php renderContractRow($contratModif, $id, $searchedParam, $typesAccompagnant, true); ?>
            </form>
            
            <?php 
            // Affichage des autres contrats en mode lecture
            while ($row = $stmtContrats->fetch()) {
                renderContractRow($row, $id, $searchedParam, $typesAccompagnant, false);
            }
        else:
            // MODE LISTE STANDARD
            while ($row = $stmtContrats->fetch()) {
                renderContractRow($row, $id, $searchedParam, $typesAccompagnant, false);
            }
            ?>
            <!-- Formulaire d'ajout harmonisé avec les colonnes de la table -->
            <form action="#" method="post">
                <tr>
                    <td><input style="width: 25mm" name="annee" placeholder="Année" required></td>
                    <td>
                        <select name="contType">
                            <?php foreach ($typesAccompagnant as $type): ?>
                                <option value="<?= $type['tAccId'] ?>"><?= htmlspecialchars($type['tAccNom']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </td>
                    <td><input class="input150" name="contSalaireBruit" placeholder="Brut"></td>
                    <td><input class="input150" name="contSalaireChauffeur" placeholder="Chauffeur"></td>
                    <td colspan="2">
                        <button type="submit" name="Cree" class="ValiderPetit">Ajouter</button>
                    </td>
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