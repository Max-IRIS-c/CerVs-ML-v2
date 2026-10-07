<?php include('../header.php');
$bdd = new PDO($dsn, $user, $password);
$debut = $_POST['debut'];
$fin = $_POST['fin'];
$id = $_SESSION['Id'];
$debutSql = dateToSql($debut);
$finSql = dateToSql($fin);

$contact = $bdd->query("SELECT conLocaliter,conComplement,conNpa,conAdresse2
,conPrenom,conMail,conNom,conAdresse,civNom FROM tblContact
LEFT JOIN tblCiviliter on tblCiviliter_civId = civId WHERE conId='$id' ");
$contact = $contact->fetch();

$intervention = $bdd->query("SELECT * FROM tblIntervention
LEFT JOIN tblContact on intBeneficiaire = conId
LEFT JOIN tblGenreServices on intGenre = genSerId
LEFT JOIN tblTypeServices on intType = tServicesId WHERE  (intDate BETWEEN '$debutSql' AND '$finSql') 
AND intIntervenant = '$id' order by intDate DESC ");
?>
    <nav id="menu2">
        <ul>
            <li class="textGauche"><?php echo '<a href="../Adresse/detContacte.php?conId='.$id.'"> Retour au contact</a>';?></li>
    </nav>

    <table>
        <tr>
            <td>Intervenant</td>
            <td>Période du :</td>
            <td><?php echo $debut; ?></td>
            <td>au</td>
            <td><?php echo $fin; ?></td>
        </tr>
        <tr>
            <td><?php echo $contact['civNom']; ?></td>
        </tr>
        <tr>
            <td><?php echo $contact['conNom'] . " " . $contact['conPrenom']; ?></td>
        </tr>
        <tr>
            <td>Adresse</td>
            <td><?php echo $contact['conAdresse']; ?></td>
            <td>Tel. Privé</td>
            <td><?php echo $contact['conTelPriver']; ?></td>
            <td>Tel. Prof</td>
            <td><?php echo $contact['conTelProf']; ?></td>
        </tr>
        <td></td>
        <td><?php echo $contact['conComplement']; ?></td>
        <td>Mobil</td>
        <td><?php echo $contact['conNathl']; ?></td>
        </tr>
        <td></td>
        <td><?php echo $contact['conNpa'] . ' ' . $contact['conLocaliter']; ?></td>
        <td>Mail</td>
        <td colspan="3"><?php echo $contact['conMail']; ?></td>
        </tr>
    </table>
    <h1> liste des intervention </h1>
    <table class="affichage">
        <tr>
            <th> Date</th>
            <th> Bénéficiaire</th>
            <th> Début</th>
            <th> Fin</th>
            <th>Type de besoin</th>
            <th> Temps de travail</th>

        </tr>

        <?php
        $Totale = 0;

        while ($row = $intervention->fetch()) { ?>

            <tr>
                <td><? echo DateToUser($row['intDate']); ?> </td>
                <td><? echo $row['conNom'] . " " . $row['conPrenom']; ?> </td>
                <td><? echo HeureHhMm($row['intDebut']); ?></td>
                <td><? echo HeureHhMm($row['intFin']); ?> </td>
                <td><? echo $row['genSerNom']; ?>  </td>
                <td class="input0"><? echo $row['intFacturable'];
                    $Totale = $Totale + $row['intFacturable']; ?> </td>
            </tr>
        <?php }
        ?>
        <tr>
            <td colspan="2"></td>
            <td colspan="3"> Total des heures de travail fournies:</td>
            <td><?php echo $Totale; ?></td>
    </table>


<?php include('../footer.php'); ?>