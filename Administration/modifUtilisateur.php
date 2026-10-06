<script src="../jquery-3.1.1.min.js"></script>
<?php include('../header.php'); ?>

<script type="text/javascript">
    $(function () {
        $("#valider").click(function (event) {
            var mp1 = $("#mp1").val();
            var mp2 = $("#mp2").val();
            var valid = true;

            // Si les champs mots de passe sont remplis, ils doivent être identiques
            if (mp1 !== "" || mp2 !== "") {
                if (mp1 !== mp2) {
                    $("#erreur").css("display", "block");
                    valid = false;
                    event.preventDefault(); // Empêche l'envoi du formulaire si erreur
                } else {
                    $("#erreur").css("display", "none");
                }
            }
            
            // Si les champs sont vides, on laisse passer (le PHP gérera la mise à jour sans mdp)
            if (!valid) {
                event.preventDefault();
            }
        });
    });
</script>

<?php
// Récupération et sécurisation de l'ID
if (!isset($_GET["Id"]) || empty($_GET["Id"])) {
    die("ID utilisateur manquant.");
}
$id = (int)$_GET["Id"]; // Cast en entier pour sécurité immédiate

$bdd = new PDO($dsn, $user, $password);
$bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Correction de la requête : Parenthèses manquantes sur le OR/AND
$sql = 'SELECT * FROM tblEmployer
        LEFT JOIN tblAutorisation ON tblAutorisation_autId = autId
        LEFT JOIN tblFonction ON tblFonction_fonId = fonId
        WHERE empId = :id';

$req = $bdd->prepare($sql);
$req->execute(['id' => $id]);
$donnees = $req->fetch();

if (!$donnees) {
    die("Utilisateur introuvable.");
}

// Récupération des listes pour les menus déroulants
$autorisation = $bdd->query('SELECT * FROM tblAutorisation LIMIT 5');
$fonction = $bdd->query('SELECT * FROM tblFonction');
$lstEmployer = $bdd->query("SELECT conNom, conPrenom, conId FROM tblContact WHERE (conEmploye = 1 OR conComiter = 1) AND conStatu = 1 ORDER BY conNom");

// Traitement du formulaire
if (isset($_POST['annuler'])) {
    header("Location: detUtilisateur.php?Id=" . $id);
    exit;
}

// CORRECTION MAJEURE : Le name du bouton doit correspondre ici
if (isset($_POST['Valider'])) {
    $login = htmlspecialchars($_POST['Login']);
    $nom = htmlspecialchars($_POST['Nom']);
    $prenom = htmlspecialchars($_POST['Prenom']);
    $taux = $_POST['Taux'];
    $autorisationId = $_POST['Autorisation'];
    $fonctionId = $_POST['Fonction'];
    $contactId = $_POST['contact'];
    
    $password = $_POST['utiPassword'];
    $passwordConfirm = $_POST['utiPassword2'];

    // Vérification côté serveur (sécurité redondante avec le JS)
    if (!empty($password) && $password !== $passwordConfirm) {
        $erreur = "Les mots de passe ne correspondent pas.";
    } else {
        if (empty($password)) {
            // Mise à jour SANS changer le mot de passe
            $sqlUpdate = "UPDATE tblEmployer SET 
                          empNom = :nom,
                          empPrenom = :prenom,
                          empLogin = :login,
                          empTaux = :taux,
                          tblAutorisation_autId = :autorisation,
                          tblFonction_fonId = :fonction,
                          conId = :contact 
                          WHERE empId = :id";
            
            $insert = $bdd->prepare($sqlUpdate);
            $insert->execute([
                'nom' => $nom,
                'prenom' => $prenom,
                'login' => $login,
                'taux' => $taux,
                'autorisation' => $autorisationId,
                'fonction' => $fonctionId,
                'contact' => $contactId,
                'id' => $id
            ]);
        } else {
            // Mise à jour AVEC nouveau mot de passe
            // Utilisation de password_hash au lieu de md5 (Sécurité)
            $passwordHash = password_hash($password, PASSWORD_DEFAULT);
            
            $sqlUpdate = "UPDATE tblEmployer SET 
                          empNom = :nom,
                          empPrenom = :prenom,
                          empLogin = :login,
                          empPass = :passwordC,
                          empTaux = :taux,
                          tblAutorisation_autId = :autorisation,
                          tblFonction_fonId = :fonction,
                          conId = :contact 
                          WHERE empId = :id";
            
            $insert = $bdd->prepare($sqlUpdate);
            $insert->execute([
                'nom' => $nom,
                'prenom' => $prenom,
                'login' => $login,
                'passwordC' => $passwordHash,
                'taux' => $taux,
                'autorisation' => $autorisationId,
                'fonction' => $fonctionId,
                'contact' => $contactId,
                'id' => $id
            ]);
        }
        header("Location: detUtilisateur.php?Id=" . $id);
        exit;
    }
}
?>

<div class="contenu">
    <h1><?php echo $mrp->getText("Modifier l'utilisateur") ?> </h1>

    <?php if (isset($erreur)): ?>
        <h2 style="color: #9A0000; display: block" id="erreur"><?php echo $erreur; ?></h2>
    <?php else: ?>
        <h2 style="color: #9A0000; display: none" id="erreur">Les mots des passe sont différents</h2>
    <?php endif; ?>

    <form name="add_user" method="post">
        <table>
            <tr>
                <td><label for="Nom"><?php echo $mrp->getText("Nom") ?> : </label></td>
                <td><input name="Nom" value="<?php echo htmlspecialchars($donnees['empNom']); ?>"/></td>
            </tr>
            <tr>
                <td><label for="Prenom"><?php echo $mrp->getText("Prénom") ?> : </label></td>
                <td><input name="Prenom" value="<?php echo htmlspecialchars($donnees['empPrenom']); ?>"/></td>
            </tr>
            <tr>
                <td><label for="Login"><?php echo $mrp->getText("Login") ?> : </label></td>
                <td><input name="Login" value="<?php echo htmlspecialchars($donnees['empLogin']); ?>"/></td>
            </tr>
            <tr>
                <td><label for="utiPassword"><?php echo $mrp->getText("Mot de passe") ?> : </label></td>
                <td><input type="password" id="mp1" name="utiPassword" placeholder="Laisser vide pour ne pas changer"/></td>
            </tr>
            <tr>
                <td><label for="utiPassword2"><?php echo $mrp->getText("Confirmation du mot de passe") ?> : </label></td>
                <td><input type="password" id="mp2" name="utiPassword2"/></td>
            </tr>
            <tr>
                <td><label for="Autorisation"><?php echo $mrp->getText("Autorisation") ?> : </label></td>
                <td>
                    <select name="Autorisation">
                        <?php while ($a = $autorisation->fetch()): ?>
                            <option value="<?php echo $a['autId']; ?>" <?php if ($donnees['tblAutorisation_autId'] == $a['autId']) echo 'selected'; ?>>
                                <?php echo htmlspecialchars($a['autNom']); ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                </td>
            </tr>
            <tr>
                <td><label for="Fonction">Fonction : </label></td>
                <td>
                    <select name="Fonction">
                        <?php while ($f = $fonction->fetch()): ?>
                            <option value="<?php echo $f['fonId']; ?>" <?php if ($donnees['tblFonction_fonId'] == $f['fonId']) echo 'selected'; ?>>
                                <?php echo htmlspecialchars($f['fonNom']); ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                </td>
            </tr>
            <tr>
                <td><label for="Taux"><?php echo $mrp->getText("Taux d'activité") ?> : </label></td>
                <td><input class="input1" name="Taux" value="<?php echo htmlspecialchars($donnees['empTaux']); ?>"/>%</td>
            </tr>
            <tr>
                <td><?php echo $mrp->getText(" Contact associé"); ?></td>
                <td>
                    <select name="contact">
                        <option>-></option>
                        <?php 
                        // Assurez-vous que la fonction ListeModif2 existe et est définie ailleurs
                        if(function_exists('ListeModif2')) {
                            ListeModif2($lstEmployer, $donnees['conId'], 'conId', 'conNom', 'conPrenom');
                        }
                        ?>
                    </select>
                </td>
            </tr>
            <tr>
                <td>
                    <input type="submit" name="annuler" value="<?php echo $mrp->getText("Annuler") ?>" class="Annuler"/>
                    <input type="submit" name="Valider" id="valider" value="<?php echo $mrp->getText("Valider") ?>" class="Valider"/>
                </td>
            </tr>
        </table>
    </form>
</div>

<?php include('../footer.php'); ?>