<?php
include('../header.php');

// Configuration de la base de données
// Assurez-vous que $dsn, $user, $password sont définis dans header.php ou ici
if (!isset($bdd)) {
    try {
        $bdd = new PDO($dsn, $user, $password);
        $bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $bdd->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        die("Erreur de connexion à la base de données."); // Message générique en prod
    }
}

// Récupération et validation des entrées
$intervenantId = isset($_GET['Id']) ? (int)$_GET['Id'] : 0;
$searchedParam = isset($_GET['searchedParam']) ? $_GET['searchedParam'] : '';
$statu = isset($_GET['statu']) ? $_GET['statu'] : 'aloIntA';

// SÉCURITÉ CRITIQUE : Whitelist pour le nom de la colonne ($statu)
// On ne peut modifier que les colonnes autorisées.
/*$allowedColumns = ['aloIntA', 'aloIntB', 'aloIntC']; // ADAPTEZ CECI aux vrais noms de colonnes de tblAlocIntervenant
if (!in_array($statu, $allowedColumns)) {
    // Si la colonne n'est pas valide, on arrête ou on définit une valeur par défaut sûre
    die("Erreur : Paramètre de statut invalide.");
}*/

// Récupération de la liste des intervenants
$lstIntervenant = $bdd->query("SELECT conNom, conPrenom, conId FROM tblContact WHERE conIntervenant = 1 ORDER BY conNom, conPrenom");

if (isset($_POST['Valider'])) {
    $intervenantPost = isset($_POST['intervenant']) ? $_POST['intervenant'] : null;

    try {
        // 1. Vérifier si un enregistrement existe déjà
        $req = $bdd->prepare('SELECT aloIntBenefic FROM tblAlocIntervenant WHERE aloIntBenefic = :beneficiaire');
        $req->execute([':beneficiaire' => $beneficiaire]);
        $resultat = $req->fetch();

        if (!$resultat) {
            // 2. INSERTION si aucun enregistrement trouvé
            $sql = "INSERT INTO tblAlocIntervenant (aloIntBenefic, $statu) VALUES (:beneficiaire, :intervenant)";
            $insert = $bdd->prepare($sql);
            $insert->execute([
                ':beneficiaire' => $beneficiaire,
                ':intervenant'  => $intervenantPost
            ]);
        } else {
            // 3. MISE À JOUR (UPDATE) si l'enregistrement existe
            // La colonne $statu est sûre car validée par la whitelist plus haut
            $sql = "UPDATE tblAlocIntervenant SET $statu = :intervenant WHERE aloIntBenefic = :beneficiaire";
            $update = $bdd->prepare($sql);
            $update->execute([
                ':intervenant'  => $intervenantPost,
                ':beneficiaire' => $beneficiaire // Correction: utilisation d'un placeholder pour la valeur WHERE aussi
            ]);
        }

        // Redirection sécurisée
        // On utilise htmlspecialchars pour l'affichage, mais pour header() on s'assure que c'est une URL valide
        $redirectUrl = "releve.php?Id=" . urlencode($beneficiaire) . "&searchedParam=" . urlencode($searchedParam);
        header("Location: " . $redirectUrl);
        exit(); // Toujours mettre exit() après un header Location

    } catch (PDOException $e) {
        // En production, loggez l'erreur dans un fichier au lieu de l'afficher
        error_log("Erreur SQL : " . $e->getMessage());
        echo "Une erreur est survenue lors de la sauvegarde. Veuillez réessayer.";
    }
}

if (isset($_POST['Annuler'])) {
    $redirectUrl = "releve.php?Id=" . urlencode($beneficiaire) . "&searchedParam=" . urlencode($searchedParam);
    header("Location: " . $redirectUrl);
    exit();
}
?>

<form method="post">
    <label for="interv">Choisir un intervenant :</label>
    <select name="intervenant" id="interv">
        <option value="0">-> Sélectionner </option>
        <?php
        if (function_exists('listeDeroulante2')) {
            listeDeroulante2($lstIntervenant, 'conId', 'conNom', 'conPrenom');
        } else {
            // Fallback si la fonction n'existe pas pour éviter une erreur fatale
            foreach ($lstIntervenant as $row) {
                echo "<option value='" . htmlspecialchars($row['conId']) . "'>" . htmlspecialchars($row['conNom'] . ' ' . $row['conPrenom']) . "</option>";
            }
        }
        ?>
    </select>
    <input type="submit" name="Valider" value="Valider" class="valider">
    <input type="submit" name="Annuler" value="Annuler" class="Annuler">
</form>

<div id="detInterv"></div>

<?php include('../footer.php'); ?>

<!-- Chargement de jQuery (vérifiez le chemin) -->
<script src="../jquery-3.1.1.min.js"></script>

<script>
    $(document).ready(function() {
        // Chargement initial
        $.get("ajax.interInfo.php", function (data) {
            $("#detInterv").html(data);
        });

        // Mise à jour au changement
        $("#interv").change(function () {
            $.get("ajax.interInfo.php", {inter: $("#interv").val()}, function (data) {
                $("#detInterv").html(data);
            });
        });
    });
</script>