<?php
//include ('verifi.php');
include('../header.php');

/* =======================
   INITIALISATION
======================= */

$erreurCode = false;
$erreurCode2 = false;
$erreurTime = false;
$erreurStatus = false;
$erreurOther = false;
$givenError = '';

$id = isset($_GET['Id']) ? (int)$_GET['Id'] : 0;
if ($id <= 0) {
    die('ID invalide');
}
$_SESSION['traId'] = $id;

/* =======================
   CONNEXION BDD
======================= */

$bdd = new PDO($dsn, $user, $password);
$bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

/* =======================
   DONNÉES
======================= */

$contact = $bdd->query("SELECT conNom, conPrenom, conId FROM tblContact WHERE conHandicaper = 1 AND conSecondaire IS NULL ORDER BY conNom ASC");
$ofas = $bdd->query("SELECT * FROM tblTraCat1 WHERE catActiv = 1");
$stat = $bdd->query("SELECT * FROM tblTraCat3 WHERE catActiv = 1");
$dossierList = $bdd->query("SELECT * FROM tblTraCat4 WHERE cat4Statu = 1 ORDER BY cat4Code");

$travailStmt = $bdd->prepare("SELECT * FROM tblTravail WHERE traId = :id");
$travailStmt->execute(['id' => $id]);
$travail = $travailStmt->fetch(PDO::FETCH_ASSOC);

if (!$travail) {
    die('Saisie introuvable');
}

/* =======================
   ANNULER
======================= */

if (isset($_POST['Anuller'])) {
    header("Location: travail.php");
    exit;
}

/* =======================
   VALIDATION
======================= */

if (isset($_POST['valider'])) {
    try {

        $dateDebut   = $_POST['dateTra'] ?? null;
        $debut       = $_POST['debut'] ?? null;
        $fin         = $_POST['fin'] ?? null;
        // Calcul du total d'heures
        $totalRaw = heureDiffDecimal($debut, $fin);
        if (is_string($totalRaw)) $totalRaw = str_replace(',', '.', $totalRaw);
        $total = (float) $totalRaw;
        //$total       = heureDiffDecimal($debut, $fin);

        $statu       = $_POST['statu'] ?? '';
        $ofasVal     = !empty($_POST['ofas']) ? (int)$_POST['ofas'] : null;
        $dossier     = $_POST['dossier'] ?? null;
        $client      = $_POST['beneficiare'] ?? null;
        $commentaire = $_POST['commentaire'] ?? '';

        $benevol     = isset($_POST['benevole']) ? 1 : 0;
        $honorefique = isset($_POST['honorefique']) ? 1 : 0;

        /* =======================
           CONTRÔLES
        ======================= */

        if (!$debut || !$fin) {
            throw new Exception('time');
        }

        if ($statu === '') {
            throw new Exception('status');
        }

        if ($statu === '2' && !$ofasVal) {
            throw new Exception('ofas');
        }

        if ($statu !== '2' && $ofasVal) {
            $erreurCode2 = true;
            $ofasVal = null;
        }

        /* =======================
           UPDATE
        ======================= */

        $update = $bdd->prepare("
            UPDATE tblTravail SET
                traDate = :date,
                traHeureTot = :total,
                traCat3 = :statu,
                traCat4 = :dossier,
                traCat1 = :ofas,
                traDebut = :debut,
                traFin = :fin,
                traCommentaire = :commentaire,
                traBenevole = :benevole,
                tblContact_conId = :beneficiaire,
                traHonorifique = :honorefique
            WHERE traId = :id
        ");

        $update->execute([
            'date'          => $dateDebut,
            'total'         => $total,
            'statu'         => $statu,
            'dossier'       => $dossier,
            'ofas'          => $ofasVal,
            'debut'         => $debut,
            'fin'           => $fin,
            'commentaire'   => $commentaire,
            'benevole'      => $benevol,
            'beneficiaire'  => $client,
            'honorefique'   => $honorefique,
            'id'            => $id
        ]);

        header("Location: travail.php");
        exit;

    } catch (Exception $e) {
        switch ($e->getMessage()) {
            case 'ofas':   $erreurCode = true; break;
            case 'time':   $erreurTime = true; break;
            case 'status': $erreurStatus = true; break;
            default:
                $erreurOther = true;
                $givenError = $e->getMessage();
        }
    }
}
?>

<style>
#required-label{
    display:none;
    background:red;
    color:white;
    text-align:center;
}
.required{
    border:2px solid red;
}
</style>

<nav id="menu2">
    <ul>
        <li class="textGauche">
            <a href="supTravail.php?Id=<?= $id ?>">
                <?= $mrp->getText("Supprimer") ?>
            </a>
        </li>
    </ul>
</nav>

<h1><?= $mrp->getText("Modifier saisie des heures") ?></h1>

<?php if($erreurTime): ?><h2>⚠️ Il faut mettre une heure de début et de fin</h2><?php endif; ?>
<?php if($erreurStatus): ?><h2>⚠️ Le statut est obligatoire</h2><?php endif; ?>
<?php if($erreurCode): ?><h2>⚠️ Code OFAS obligatoire pour Art. 74</h2><?php endif; ?>
<?php if($erreurCode2): ?><h2>⚠️ Code OFAS ignoré (statut ≠ Art. 74)</h2><?php endif; ?>
<?php if($erreurOther): ?><h2>⚠️ Erreur technique : <?= htmlspecialchars($givenError) ?></h2><?php endif; ?>

<form method="post">
<table>
<tr>
    <th>Date</th>
    <th>Début</th>
    <th>Fin</th>
    <th>Heures</th>
    <th>Commentaire</th>
    <th>Bénévole</th>
    <th>Honorifique</th>
</tr>
<tr>
    <td><input type="date" name="dateTra" value="<?= $travail['traDate'] ?>"></td>
    <td><input type="time" id="debut" name="debut" value="<?= HeureHhMm($travail['traDebut']) ?>"></td>
    <td><input type="time" id="fin" name="fin" value="<?= HeureHhMm($travail['traFin']) ?>"></td>
    <td><input type="text" id="total" disabled value="<?= $travail['traHeureTot'] ?>"></td>
    <td><textarea name="commentaire"><?= htmlspecialchars($travail['traCommentaire']) ?></textarea></td>
    <td><?= CheckBoxModif($travail['traBenevole'], 'benevole') ?></td>
    <td><?= CheckBoxModif($travail['traHonorifique'], 'honorefique') ?></td>
</tr>

<tr>
    <th>Statut</th>
    <th colspan="2">Code OFAS</th>
    <th>Bénéficiaire</th>
    <th>Dossier</th>
</tr>

<?php $Largeur = "150px"; ?>

<tr>
    <td>
        <select id="statu" name="statu" style="width:<?= $Largeur ?>">
            <?php ListeModif2($stat, $travail['traCat3'], 'cat3Id', 'cat3Code', 'cat3Nom'); ?>
        </select>
    </td>
    <td colspan="2">
        <p id="required-label">* Champ obligatoire</p>
        <div id="ofas"></div>
    </td>
    <td><div id="beneficiaire"></div></td>
    <td>
        <select name="dossier" style="width:<?= $Largeur ?>">
            <?php ListeModif2($dossierList, $travail['traCat4'], 'cat4Id', 'cat4Code', 'cat4Nom'); ?>
        </select>
    </td>
    <td><input id="submit" type="submit" name="valider" value="Valider"></td>
    <td><input id="submit" type="submit" name="Anuller" value="Annuler"></td>
</tr>
</table>
</form>

<script src="../jquery-3.1.1.min.js"></script>
<script>
const handleOfasCode = data => {
    $("#ofas").html(data);
    $("#statu").val() === '2' ? $("#required-label").show() : $("#required-label").hide();
};

$.get("ajax.ofas.php", handleOfasCode);
$("#statu").change(() => $.get("ajax.ofas.php", {statu: $("#statu").val()}, handleOfasCode));

$.get("ajax.beneficiaireModif.php", data => $("#beneficiaire").html(data));
$("#statu").change(() => $.get("ajax.beneficiaireModif.php", {statu: $("#statu").val()}, data => $("#beneficiaire").html(data)));

$('#debut, #fin').change(() => {
    const start = $('#debut').val();
    const end = $('#fin').val();
    if (!start || !end) return;
    const [sh, sm] = start.split(':').map(Number);
    const [eh, em] = end.split(':').map(Number);
    let diff = (eh * 60 + em) - (sh * 60 + sm);
    if (diff < 0) diff += 1440;
    $('#total').val((diff / 60).toFixed(2));
});
</script>

<?php include('../footer.php'); ?>
