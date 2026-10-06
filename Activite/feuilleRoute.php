<?php
$pageNum = 24;
include('../header.php');

// Assurez-vous que la fonction formaterDateFr est disponible (copiez-la depuis le précédent message si nécessaire)
if (!function_exists('formaterDateFr')) {
    function formaterDateFr($dateString, $format = 'd F Y') {
        if (empty($dateString)) return '';
        try {
            $date = new DateTime($dateString);
            $dateFormatee = $date->format($format);
            $traduction = [
                'January' => 'janvier', 'February' => 'février', 'March' => 'mars',
                'April' => 'avril', 'May' => 'mai', 'June' => 'juin',
                'July' => 'juillet', 'August' => 'août', 'September' => 'septembre',
                'October' => 'octobre', 'November' => 'novembre', 'December' => 'décembre'
            ];
            return str_replace(array_keys($traduction), array_values($traduction), $dateFormatee);
        } catch (Exception $e) { return ''; }
    }
}

$bdd = new PDO($dsn, $user, $password);
// Sécurisation de l'ID
$actId = isset($_GET['Id']) ? (int)$_GET['Id'] : 0;
$id = $actId;

// Récupération des dates
$stmtDate = $bdd->prepare("SELECT actId, actDebut, actFin FROM tblActivites WHERE actId = ?");
$stmtDate->execute([$actId]);
$dateAct = $stmtDate->fetch();

if (!$dateAct) {
    echo "<p>Activité non trouvée.</p>";
    include('../footer.php');
    exit;
}

// --- ALLER ---
$aller1 = $bdd->query("SELECT * FROM tblAloFeuilleRout WHERE actId = $id AND busId = 1 AND feuAller = 0 ORDER BY feuOrdre ASC");
$aller2 = $bdd->query("SELECT * FROM tblAloFeuilleRout WHERE actId = $id AND busId = 2 AND feuAller = 0 ORDER BY feuOrdre ASC");
$aller3 = $bdd->query("SELECT * FROM tblAloFeuilleRout WHERE actId = $id AND busId = 3 AND feuAller = 0 ORDER BY feuOrdre ASC");
$aller4 = $bdd->query("SELECT * FROM tblAloFeuilleRout WHERE actId = $id AND busId = 4 AND feuAller = 0 ORDER BY feuOrdre ASC");

// Récupération des chauffeurs (ALLER)
$Chauffeuraller1 = $bdd->query("SELECT * FROM tblchauffeur WHERE actId = $id AND busId = 1 AND feuAller = 0")->fetch();
$Chauffeuraller2 = $bdd->query("SELECT * FROM tblchauffeur WHERE actId = $id AND busId = 2 AND feuAller = 0")->fetch();
$Chauffeuraller3 = $bdd->query("SELECT * FROM tblchauffeur WHERE actId = $id AND busId = 3 AND feuAller = 0")->fetch();
$Chauffeuraller4 = $bdd->query("SELECT * FROM tblchauffeur WHERE actId = $id AND busId = 4 AND feuAller = 0")->fetch();

// --- RETOUR ---
$retour1 = $bdd->query("SELECT * FROM tblAloFeuilleRout WHERE actId = $id AND busId = 1 AND feuAller = 1 ORDER BY feuOrdre ASC");
$retour2 = $bdd->query("SELECT * FROM tblAloFeuilleRout WHERE actId = $id AND busId = 2 AND feuAller = 1 ORDER BY feuOrdre ASC");
$retour3 = $bdd->query("SELECT * FROM tblAloFeuilleRout WHERE actId = $id AND busId = 3 AND feuAller = 1 ORDER BY feuOrdre ASC");
$retour4 = $bdd->query("SELECT * FROM tblAloFeuilleRout WHERE actId = $id AND busId = 4 AND feuAller = 1 ORDER BY feuOrdre ASC");

// Récupération des chauffeurs (RETOUR)
$Chauffeurretou1 = $bdd->query("SELECT * FROM tblchauffeur WHERE actId = $id AND busId = 1 AND feuAller = 1")->fetch();
$Chauffeurretou2 = $bdd->query("SELECT * FROM tblchauffeur WHERE actId = $id AND busId = 2 AND feuAller = 1")->fetch();
$Chauffeurretou3 = $bdd->query("SELECT * FROM tblchauffeur WHERE actId = $id AND busId = 3 AND feuAller = 1")->fetch();
$Chauffeurretou4 = $bdd->query("SELECT * FROM tblchauffeur WHERE actId = $id AND busId = 4 AND feuAller = 1")->fetch();
?>

<nav>
    <ul>
        <li><?php echo '<a href="participants.php?Id='.$actId.'" >'.$mrp->getText("Retour").'</a>' ?></li>
        <li><?php echo '<a href="feuilleRouteEdition.php?Id='.$actId.'&trajet=0" >'.$mrp->getText("Modifier l'aller").'</a>' ?></li>
        <li><?php echo '<a href="feuilleRouteEdition.php?Id='.$actId.'&trajet=1" >'.$mrp->getText("Modifier le retour").'</a>' ?></li>
    </ul>
</nav>

<h1><?= $mrp->getText("Aller") ?> - <?php echo formaterDateFr($dateAct['actDebut']); ?></h1>

<!-- BUS 1 ALLER -->
<?php if ($Chauffeuraller1): ?>
    <h2><?= $bus[$Chauffeuraller1['busIdNom']] ?? 'Bus 1' ?></h2>
    <table style="margin-bottom: 0; padding-bottom: 0">
        <tr>
            <td><strong><?= $mrp->getText("Conducteur") ?></strong></td>
            <td><?php echo htmlspecialchars($Chauffeuraller1['chauPrinc'] ?? ''); ?></td>
            <td><?php echo htmlspecialchars($Chauffeuraller1['chauAide'] ?? ''); ?></td>
        </tr>
        <tr>
            <td><strong><?= $mrp->getText("Départ") ?></strong></td>
            <td><strong><?= $mrp->getText("Participants") ?></strong></td>
            <td><strong><?= $mrp->getText("Accompagnants") ?></strong></td>
        </tr>
        <?php while ($depA = $aller1->fetch()): ?>
            <tr>
                <td style="border-bottom:solid 1px"><?php echo htmlspecialchars($depA['feuLieu']); ?></td>
                <td style="border-bottom:solid 1px"><?php echo htmlspecialchars($depA['feuPart']); ?></td>
                <td style="border-bottom:solid 1px"><?php echo htmlspecialchars($depA['feuAcc']); ?></td>
            </tr>
        <?php endwhile; ?>
    </table>
<?php endif; ?>

<!-- BUS 2 ALLER -->
<?php if ($Chauffeuraller2): ?>
    <h2><?= $bus[$Chauffeuraller2['busIdNom']] ?? 'Bus 2' ?></h2>
    <table style="margin-bottom: 0; padding-bottom: 0">
        <tr>
            <td><strong><?= $mrp->getText("Conducteur") ?></strong></td>
            <td><?php echo htmlspecialchars($Chauffeuraller2['chauPrinc'] ?? ''); ?></td>
            <td><?php echo htmlspecialchars($Chauffeuraller2['chauAide'] ?? ''); ?></td>
        </tr>
        <tr>
            <td><strong><?= $mrp->getText("Départ") ?></strong></td>
            <td><strong><?= $mrp->getText("Participants") ?></strong></td>
            <td><strong><?= $mrp->getText("Accompagnants") ?></strong></td>
        </tr>
        <?php while ($depB = $aller2->fetch()): ?>
            <tr>
                <td style="border-bottom:solid 1px"><?php echo htmlspecialchars($depB['feuLieu']); ?></td>
                <td style="border-bottom:solid 1px"><?php echo htmlspecialchars($depB['feuPart']); ?></td>
                <td style="border-bottom:solid 1px"><?php echo htmlspecialchars($depB['feuAcc']); ?></td>
            </tr>
        <?php endwhile; ?>
    </table>
<?php endif; ?>

<!-- BUS 3 ALLER -->
<?php if ($Chauffeuraller3): ?>
    <h2><?= $bus[$Chauffeuraller3['busIdNom']] ?? 'Bus 3' ?></h2>
    <table style="margin-bottom: 0; padding-bottom: 0">
        <tr>
            <td><strong><?= $mrp->getText("Conducteur") ?></strong></td>
            <td><?php echo htmlspecialchars($Chauffeuraller3['chauPrinc'] ?? ''); ?></td>
            <td><?php echo htmlspecialchars($Chauffeuraller3['chauAide'] ?? ''); ?></td>
        </tr>
        <tr>
            <td><strong><?= $mrp->getText("Départ") ?></strong></td>
            <td><strong><?= $mrp->getText("Participants") ?></strong></td>
            <td><strong><?= $mrp->getText("Accompagnants") ?></strong></td>
        </tr>
        <?php while ($depC = $aller3->fetch()): ?>
            <tr>
                <td style="border-bottom:solid 1px"><?php echo htmlspecialchars($depC['feuLieu']); ?></td>
                <td style="border-bottom:solid 1px"><?php echo htmlspecialchars($depC['feuPart']); ?></td>
                <td style="border-bottom:solid 1px"><?php echo htmlspecialchars($depC['feuAcc']); ?></td>
            </tr>
        <?php endwhile; ?>
    </table>
<?php endif; ?>

<!-- BUS 4 ALLER -->
<?php if ($Chauffeuraller4): ?>
    <h2><?= $bus[$Chauffeuraller4['busIdNom']] ?? 'Bus 4' ?></h2>
    <table style="margin-bottom: 0; padding-bottom: 0">
        <tr>
            <td><strong><?= $mrp->getText("Conducteur") ?></strong></td>
            <td><?php echo htmlspecialchars($Chauffeuraller4['chauPrinc'] ?? ''); ?></td>
            <td><?php echo htmlspecialchars($Chauffeuraller4['chauAide'] ?? ''); ?></td>
        </tr>
        <tr>
            <td><strong><?= $mrp->getText("Départ") ?></strong></td>
            <td><strong><?= $mrp->getText("Participants") ?></strong></td>
            <td><strong><?= $mrp->getText("Accompagnants") ?></strong></td>
        </tr>
        <?php while ($depD = $aller4->fetch()): ?>
            <tr>
                <td style="border-bottom:solid 1px"><?php echo htmlspecialchars($depD['feuLieu']); ?></td>
                <td style="border-bottom:solid 1px"><?php echo htmlspecialchars($depD['feuPart']); ?></td>
                <td style="border-bottom:solid 1px"><?php echo htmlspecialchars($depD['feuAcc']); ?></td>
            </tr>
        <?php endwhile; ?>
    </table>
<?php endif; ?>


<h1><?= $mrp->getText("Retour") ?> - <?php echo formaterDateFr($dateAct['actFin']); ?></h1>

<!-- BUS 1 RETOUR -->
<?php if ($Chauffeurretou1): ?>
    <h2><?= $bus[$Chauffeurretou1['busIdNom']] ?? 'Bus 1' ?></h2>
    <table style="margin-bottom: 0; padding-bottom: 0">
        <tr>
            <td><strong><?= $mrp->getText("Conducteur") ?></strong></td>
            <td><?php echo htmlspecialchars($Chauffeurretou1['chauPrinc'] ?? ''); ?></td>
            <td><?php echo htmlspecialchars($Chauffeurretou1['chauAide'] ?? ''); ?></td>
        </tr>
        <tr>
            <td><strong><?= $mrp->getText("Départ") ?></strong></td>
            <td><strong><?= $mrp->getText("Participants") ?></strong></td>
            <td><strong><?= $mrp->getText("Accompagnants") ?></strong></td>
        </tr>
        <?php while ($retA = $retour1->fetch()): ?>
            <tr>
                <td style="border-bottom:solid 1px"><?php echo htmlspecialchars($retA['feuLieu']); ?></td>
                <td style="border-bottom:solid 1px"><?php echo htmlspecialchars($retA['feuPart']); ?></td>
                <td style="border-bottom:solid 1px"><?php echo htmlspecialchars($retA['feuAcc']); ?></td>
            </tr>
        <?php endwhile; ?>
    </table>
<?php endif; ?>

<!-- BUS 2 RETOUR -->
<?php if ($Chauffeurretou2): ?>
    <h2><?= $bus[$Chauffeurretou2['busIdNom']] ?? 'Bus 2' ?></h2>
    <table style="margin-bottom: 0; padding-bottom: 0">
        <tr>
            <td><strong><?= $mrp->getText("Conducteur") ?></strong></td>
            <td><?php echo htmlspecialchars($Chauffeurretou2['chauPrinc'] ?? ''); ?></td>
            <td><?php echo htmlspecialchars($Chauffeurretou2['chauAide'] ?? ''); ?></td>
        </tr>
        <tr>
            <td><strong><?= $mrp->getText("Départ") ?></strong></td>
            <td><strong><?= $mrp->getText("Participants") ?></strong></td>
            <td><strong><?= $mrp->getText("Accompagnants") ?></strong></td>
        </tr>
        <?php while ($retB = $retour2->fetch()): ?>
            <tr>
                <td style="border-bottom:solid 1px"><?php echo htmlspecialchars($retB['feuLieu']); ?></td>
                <td style="border-bottom:solid 1px"><?php echo htmlspecialchars($retB['feuPart']); ?></td>
                <td style="border-bottom:solid 1px"><?php echo htmlspecialchars($retB['feuAcc']); ?></td>
            </tr>
        <?php endwhile; ?>
    </table>
<?php endif; ?>

<!-- BUS 3 RETOUR -->
<?php if ($Chauffeurretou3): ?>
    <h2><?= $bus[$Chauffeurretou3['busIdNom']] ?? 'Bus 3' ?></h2>
    <table style="margin-bottom: 0; padding-bottom: 0">
        <tr>
            <td><strong><?= $mrp->getText("Conducteur") ?></strong></td>
            <td><?php echo htmlspecialchars($Chauffeurretou3['chauPrinc'] ?? ''); ?></td>
            <td><?php echo htmlspecialchars($Chauffeurretou3['chauAide'] ?? ''); ?></td>
        </tr>
        <tr>
            <td><strong><?= $mrp->getText("Départ") ?></strong></td>
            <td><strong><?= $mrp->getText("Participants") ?></strong></td>
            <td><strong><?= $mrp->getText("Accompagnants") ?></strong></td>
        </tr>
        <?php while ($retC = $retour3->fetch()): ?>
            <tr>
                <td style="border-bottom:solid 1px"><?php echo htmlspecialchars($retC['feuLieu']); ?></td>
                <td style="border-bottom:solid 1px"><?php echo htmlspecialchars($retC['feuPart']); ?></td>
                <td style="border-bottom:solid 1px"><?php echo htmlspecialchars($retC['feuAcc']); ?></td>
            </tr>
        <?php endwhile; ?>
    </table>
<?php endif; ?>

<!-- BUS 4 RETOUR -->
<?php if ($Chauffeurretou4): ?>
    <h2><?= $bus[$Chauffeurretou4['busIdNom']] ?? 'Bus 4' ?></h2>
    <table style="margin-bottom: 0; padding-bottom: 0">
        <tr>
            <td><strong><?= $mrp->getText("Conducteur") ?></strong></td>
            <td><?php echo htmlspecialchars($Chauffeurretou4['chauPrinc'] ?? ''); ?></td>
            <td><?php echo htmlspecialchars($Chauffeurretou4['chauAide'] ?? ''); ?></td>
        </tr>
        <tr>
            <td><strong><?= $mrp->getText("Départ") ?></strong></td>
            <td><strong><?= $mrp->getText("Participants") ?></strong></td>
            <td><strong><?= $mrp->getText("Accompagnants") ?></strong></td>
        </tr>
        <?php while ($retD = $retour4->fetch()): ?>
            <tr>
                <td style="border-bottom:solid 1px"><?php echo htmlspecialchars($retD['feuLieu']); ?></td>
                <td style="border-bottom:solid 1px"><?php echo htmlspecialchars($retD['feuPart']); ?></td>
                <td style="border-bottom:solid 1px"><?php echo htmlspecialchars($retD['feuAcc']); ?></td>
            </tr>
        <?php endwhile; ?>
    </table>
<?php endif; ?>

<?php include('../footer.php');?>