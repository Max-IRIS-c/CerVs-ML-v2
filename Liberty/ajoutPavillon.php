<?php
include('../variables.php');

try {
    $bdd = new PDO($dsn, $user, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, // Active les exceptions
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ]);
} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}

// Récupération des listes déroulantes
$uniter  = $bdd->query("SELECT * FROM tblUnite");
$uniter2 = $bdd->query("SELECT * FROM tblUnite");
$uniter3 = $bdd->query("SELECT * FROM tblUnite");
$uniter4 = $bdd->query("SELECT * FROM tblUnite");
$tBien   = $bdd->query("SELECT * FROM tblTypeBien");

if(isset($_POST['Annuler'])){
    header("Location: planning.php");
}

if (isset($_POST['Valider'])) {
    try {
        $immatriculation = $_POST['immatriculation'] && $_POST['immatriculation'] !== '' ? $_POST['immatriculation'] : null;
        // Vérifier si tous les champs nécessaires existent
        /*$required = ['Nom','Nuite','uniteNuite','Entretien','unitepensionComplete',
                     'unitePriseEnCharge','nbrMax','nbrMin','pensionComplete',
                     'uniteEntretien','PriseEnCharge','logtype'];

        foreach ($required as $field) {
            if (!isset($_POST[$field]) || $_POST[$field] === '') {
                throw new Exception("Le champ $field est requis.");
            }
        }*/

        // Préparation de la requête
        $insert = $bdd->prepare('
            INSERT INTO tblLogement (
                logNom, logPrix1, logUniterPrix1, 
                logPrix2, logUniterPrix2, logUniterPrix3, 
                logPersonneMax, logPersonneMin, 
                logPrix3, logUniterPrix4, logPrix4, logtype, logCouleur, immatriculation
            )
            VALUES (
                :logNom, :logPrixNuitee, :logUniteNuitee,
                :logPrixEntretien, :logUniteEntretien, :logUnitePriseEnCharge,
                :logPersonneMax, :logPersonneMin,
                :logPrixPensionComplete, :logUnitePensionComplete, :logPrixPriseEnCharge, :logtype, :logCouleur, :immatriculation
            )
        ');

        $success = $insert->execute([
            ':logNom'               => $_POST['Nom'],
            ':logPrixNuitee'        => $_POST['Nuite'],
            ':logUniteNuitee'       => $_POST['uniteNuite'],
            ':logPrixEntretien'     => $_POST['Entretien'],
            ':logUniteEntretien'    => $_POST['uniteEntretien'],
            ':logUnitePriseEnCharge'=> $_POST['unitePriseEnCharge'],
            ':logPersonneMax'       => $_POST['nbrMax'],
            ':logPersonneMin'       => $_POST['nbrMin'],
            ':logPrixPensionComplete'=> $_POST['pensionComplete'],
            ':logUnitePensionComplete'=> $_POST['unitepensionComplete'],
            ':logPrixPriseEnCharge' => $_POST['PriseEnCharge'],
            ':logtype'              => $_POST['logtype'],
            ':logCouleur'           => $_POST['logCouleur'],
            ':immatriculation'      => $immatriculation
        ]);

        // Vérifier si l’insertion a bien ajouté une ligne
        if ($success && $insert->rowCount() > 0) {
            header("Location: liberty.php");
            exit;
        } else {
            echo "<p style='color:red;'>Erreur : aucune donnée insérée.</p>";
        }
    } catch (Exception $e) {
        echo "<p style='color:red;'>Erreur : " . htmlspecialchars($e->getMessage()) . "</p>";
    }
}

include('../heade.php'); 
?>
<nav></nav>

<h1><?= $mrp->getText("Nouveau logement") ?></h1>

<table>
    <form method="post">
        <tr>
            <th><?= $mrp->getText("Type de bien") ?></th>
            <td>
                <select name="logtype">
                    <?php ListeDeroulante($tBien,'tBienId','tBienNom')?>
                </select>
            </td>
        </tr>
        <tr>
            <th><?= $mrp->getText("Nom") ?></th>
            <td colspan="4"><input name="Nom"></td>
        </tr>
        <tr>
            <th><?= $mrp->getText("Nombre de personne") ?></th>
            <td>min</td>
            <td><input name="nbrMin"></td>
            <td>max</td>
            <td><input name="nbrMax"></td>
        </tr>
        <tr>
            <th>Prix nuitée</th>
            <td><input name="Nuite"></td>
            <td>
                <select name="uniteNuite">
                    <?php ListeDeroulante($uniter,'uniId','uniNom')?>
                </select>
            </td>
        </tr>
        <tr>
            <th>Prise en charge</th>
            <td><input name="PriseEnCharge"></td>
            <td>
                <select name="unitePriseEnCharge">
                    <?php ListeDeroulante($uniter2,'uniId','uniNom')?>
                </select>
            </td>
        </tr>
        <tr>
            <th>Entretien</th>
            <td><input name="Entretien"></td>
            <td>
                <select name="uniteEntretien">
                    <?php ListeDeroulante($uniter3,'uniId','uniNom')?>
                </select>
            </td>
        </tr>
        <tr>
            <th>Pension complète</th>
            <td><input name="pensionComplete"></td>
            <td>
                <select name="unitepensionComplete">
                    <?php ListeDeroulante($uniter4,'uniId','uniNom')?>
                </select>
            </td>
        </tr>
        <tr>
            <th><?= $mrp->getText("Couleur") ?></th>
            <td><input name="logCouleur" type="color"/>
        </tr>
        <tr>
            <th><?= $mrp->getText("Immatriculation") ?></th>
            <td><input name="immatriculation" type="text" />
        </tr>
        <tr>
            <td colspan="2">
                <input type="submit" value="Valider" name="Valider" class="valider">
            </td>
            <td colspan="2">
                <input type="submit" value="Annuler" name="Annuler" class="Annuler">
            </td>
        </tr>
    </form>
</table>

<?php include('../footer.php'); ?>
