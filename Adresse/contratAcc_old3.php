<?php
include('../header.php');
include('../Travail/verifi.php');
include('../src/class/contrats-gestion.php');

$bdd = new PDO($dsn, $user, $password);
session_start();
$message = '';
$searchedParam = $_GET['searchedParam'];
$id = intval($_GET['Id'] ?? 0);
$contratId = isset($_GET['Contrat']) ? intval($_GET['Contrat']) : null;

// Liste des types de contrats
$tAccompagnant = $bdd->query("SELECT * FROM tblTypeAcc");


/*** enregistrement d'un contrat signé dans le dossier ./contractsFiles */
$errorPDFload = false;
$sucessPDFload = false;
if(isset($_POST['submit']) && isset($_FILES['newPDF'])){
    try{
        if(!$_POST['idContrat']) throw new Exception();	
        $record = Contract::recordNewPDFfile('accompagnant', $_POST['idContrat'], $_FILES['newPDF']);
        if(!$record) throw new Exception();
        else $sucessPDFload = true;
        //header('location: contrat.php?Id='.$id.'&searchedParam='.$searchedParam);
    }catch(Exception $e){
        $errorPDFload = true;
    }
}
/*** suppression d'un contrat signé dans le dossier ./contractsFiles */
$errorPDFDelete = false;
$successPDFDelete = false;
if(isset($_POST['deletePDF'])){
    try{   
        if(!$_POST['idContrat']) throw new Exception();	
        $delete = Contract::deletePDF('accompagnant', $_POST['idContrat']);
        if(!$delete) throw new Exception();
        $successPDFDelete = true;
    }catch(Exception $e){
        $errorPDFDelete = true;
    }
}
// ----- Traitement de suppression -----
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['Supprimer'])) {
    $contId = intval($_POST['contId'] ?? 0);
    $stmt = $bdd->prepare("DELETE FROM tblContratAcc WHERE contId = ?");
    if ($stmt->execute([$contId])) {
        $message = "✅ Contrat supprimé avec succès.";
    } else {
        $message = "❌ Erreur lors de la suppression du contrat.";
    }
}

// ----- Traitement de modification -----
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['Modifier'])) {
    $contId = intval($_POST['contId'] ?? 0);
    $annee = $_POST['annee'] ?? '';
    $type = $_POST['type'] ?? '';
    $brut = $_POST['salairBrut'] ?? '';
    $chauffeur = $_POST['salairChauffeur'] ?? '';
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

// ----- Traitement d'ajout -----
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['Ajouter'])) {
    $annee = $_POST['annee'] ?? '';
    $type = $_POST['type'] ?? '';
    $brut = $_POST['salairBrut'] ?? '';
    $chauffeur = $_POST['salairChauffeur'] ?? '';

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
?>
<?php
// Récupérer les contrats existants
$queryContrat = $bdd->prepare("
    SELECT * FROM tblContratAcc
    LEFT JOIN tblTypeAcc ON contType = tAccId
    WHERE conId = ?
    ORDER BY contAnnee DESC
");
$queryContrat->execute([$id]);
$contrats = $queryContrat->fetchAll(PDO::FETCH_ASSOC);

// Récupération du contact
$contact = $bdd->prepare("
    SELECT conNom, conPrenom FROM tblContact WHERE conId = ?
");
$contact->execute([$id]);
$contactInfo = $contact->fetch();
?>

<nav id="menu2">
    <ul>
        <li><a href="detContacte.php?conId=<?= $id ?>&searchedParam=<?= $searchedParam ?>">Retour au contact</a></li>
    </ul>
</nav>

<h1>Contrats accompagnant pour <?= htmlspecialchars($contactInfo['conPrenom'] . ' ' . $contactInfo['conNom']) ?></h1>

<!-- ✅ Message utilisateur -->
<?php if (!empty($message)): ?>
    <div style="margin: 1em 0; padding: 1em; border: 1px solid #ccc; background-color: #f0f8ff; font-weight: bold;">
        <?= htmlspecialchars($message) ?>
    </div>
<?php endif; ?>

<?php if($errorPDFload) { ?>
		<p>
			⚠️ Le fichier n a pas pu être enregistré pour quelques raisons: </br>
			<strong>Cliquez sur "Retour au contact", puis revenez sur cette page afin de réessayer.</strong></br>
			Si le problème persiste, contactez le développeur.'</p>
<?php } ?>
<?php if($sucessPDFload) { ?>
		<p class="success"> ✅ Le fichier a bien été enregistré</p>
<?php } 
    if($errorPDFDelete){ ?>
        <p>⚠️ Il y a eu un soucis dans la suppression du pdf </p>
   <?php    }
    if($successPDFDelete){ ?>
        <p> ✅ Le fichier a bien été supprimé </p>
   <?php } ?>
<table class="affichage">
    <tr>
        <th>Année</th>
        <th>Type</th>
        <th>Salaire brut</th>
        <th>Salaire chauffeur</th>
        <th>Signé le</th>
        <th>Actions</th>
        <th>Contrat signé</th>
    </tr>

    <?php foreach ($contrats as $contrat): ?>
        <?php if ($contratId && $contrat['contId'] == $contratId): ?>
            <!-- ✅ Ligne modification -->
            <form method="post" action="contratAcc.php?Id=<?= $id ?>&searchedParam=<?= $searchedParam ?>">
                <tr>
                    <td><input name="annee" value="<?= htmlspecialchars($contrat['contAnnee']) ?>" required></td>
                    <td>
                        <select name="type">
                            <?php
                            $tAccompagnant->execute(); // rewind le curseur
                            while ($type = $tAccompagnant->fetch()) {
                                $selected = ($type['tAccId'] == $contrat['contType']) ? 'selected' : '';
                                echo "<option value='{$type['tAccId']}' $selected>{$type['tAccNom']}</option>";
                            }
                            ?>
                        </select>
                    </td>
                    <td><input name="salairBrut" value="<?= htmlspecialchars($contrat['contSalaireBruit']) ?>"></td>
                    <td><input name="salairChauffeur" value="<?= htmlspecialchars($contrat['contSalaireChauffeur']) ?>"></td>
                    <td><input type="date" name="signer" value="<?= htmlspecialchars($contrat['contSigner']) ?>"></td>
                    <td>
                        <input type="hidden" name="contId" value="<?= $contrat['contId'] ?>">
                        <button type="submit" name="Modifier" class="ValiderNew">Valider</button>
                        <button type="submit" name="Supprimer" class="Supprimer">Supprimer</button>
                        <a href="contratAcc.php?Id=<?= $id ?>&searchedParam=<?= $searchedParam ?>" class="Annuler">Annuler</a>
                    </td>
                </tr>
            </form>
        <?php else: ?>
            <!-- ✅ Ligne affichage simple -->
            <tr>
                <td><?= htmlspecialchars($contrat['contAnnee']) ?></td>
                <td><?= htmlspecialchars($contrat['tAccNom']) ?></td>
                <td><?= htmlspecialchars($contrat['contSalaireBruit']) ?> / jour</td>
                <td><?= htmlspecialchars($contrat['contSalaireChauffeur']) ?> / jour</td>
                <td><?= $contrat['contSigner'] ? date('d.m.Y', strtotime($contrat['contSigner'])) : '' ?></td>
                <td> <!-- action -->
                    <div id="btZone" style="display:flex; flex-direction:column; align-items: start;">
                        <a class="bt blue" href="contratPrintAcc.php?Id=<?= $contrat['contId'] ?>" target="_blank">Imprimer</a>
                        <a class="bt green" href="contratAcc.php?Id=<?= $id ?>&Contrat=<?= $contrat['contId'] ?>&searchedParam=<?= $searchedParam ?>">Modifier</a>
                        <form method="post" style="display:inline;">
                            <input type="hidden" name="contId" value="<?= $contrat['contId'] ?>">
                            <button type="submit" name="Supprimer" class="Supprimer bt red">Supprimer</button>
                        </form>
                    </div>
                </td>
                <?php 
                    $contractPDF = Contract::signedContrat('accompagnant', $contrat['contId']);
                    if(!$contractPDF['exist']) { ?>
                        <td>
                            <form method="post" enctype="multipart/form-data">
                                <input type="hidden" name="idContrat" value="<?php echo $contrat['contId']; ?>" />
                                <input id="newPDF" name="newPDF" type="file" accept=".pdf" />
                                <button class="sub" type="submit" name="submit" value="submit">Enregistrer</button>
                            </form>
                        </td>
                        <?php }elseif($contractPDF['exist']) { ?> 
                            <td class="signed">
                                <a class="bt blue" onclick="window.open('<?php echo './accompagnant_contractsFiles/'.$contractPDF['url']; ?>', '_blank')">PDF</a>
                                <form method="post">
                                    <input type="hidden" name="idContrat" value="<?php echo $contrat['contId']; ?>"/>
                                    <button class="delete" name="deletePDF" value="1">Supprimer le PDF</button>
                                </form>
                            </td>
                    <?php } ?>
            </tr>
        <?php endif; ?>
    <?php endforeach; ?>

    <!-- ✅ Formulaire d’ajout -->
    <form method="post" action="contratAcc.php?Id=<?= $id ?>&searchedParam=<?= $searchedParam ?>">
        <tr>
            <td><input name="annee" required></td>
            <td>
                <select name="type">
                    <?php
                    $tAccompagnant->execute(); // rewind
                    while ($type = $tAccompagnant->fetch()) {
                        echo "<option value='{$type['tAccId']}'>{$type['tAccNom']}</option>";
                    }
                    ?>
                </select>
            </td>
            <td><input name="salairBrut"></td>
            <td><input name="salairChauffeur"></td>
            <td colspan="2">
                <button type="submit" name="Ajouter" class="ValiderNew">Ajouter</button>
            </td>
        </tr>
    </form>
</table>
<style>
    .delete{
        background-color: #e53935;
        color: white;
        border: none;
        padding: 7px;
    }
    .delete:hover{
        cursor: pointer;
    }
    #btZone {
        display: flex;
        gap: 5px;
        align-items: center;
    }
    .signed{
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: start;
        gap: 6px;
        padding: 5px;
    }
    .Supprimer {
        background-color: #e53935 ;
        color: white;
        border: none;
        padding: 5px 10px;
        cursor: pointer;
    }

    .ValiderNew,
    .Imprimer,
    .Modifier {
        background-color: #43a047;
        color: white;
        padding: 5px 10px;
        border: none;
        text-decoration: none;
    }
    .affichage{
        border-radius: 6px;
        background-color: white;
        padding: 15px;
        margin: 0;
    }
    .Annuler {
        border: 1px solid #555;
        background-color: lightgray;
        padding: 5px 10px;
        text-decoration: none;
    }
    .bt{
        background-color: blue;
        padding: 5px 10px;
        color: white;
    }
    .blue{
        background-color: #1e88e5;
    }
    .green{
        background-color: #43a047;
    }
    .red{
        background-color: #e53935 ;
    }
	.success{
		width: 100%;
		padding: 15px;
		background-color: #c5e1a5;
		color: #2e7d32;
	}
	.sub{
		background-color: #3384e5;
		color: white;
		border: none;
		padding: 5px;
		margin-top: 5px;
	}
</style>