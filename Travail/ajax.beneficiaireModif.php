<?php
include('../variables.php');

$bdd = new PDO($dsn, $user, $password);
$id = $_SESSION['traId'];
$travail = $bdd->query("SELECT traCat3, tblContact_conId  FROM tblTravail WHERE  traId = '$id'");
$travail = $travail->fetch();

$contact = $bdd->query("SELECT conNom,conPrenom,conId FROM tblContact WHERE conHandicaper = 1 AND conSecondaire is NULL ORDER BY  conNom ASC");

if (!empty($_GET['statu']) AND ($_GET['statu'] == 2)) { ?>
    <select name="beneficiare"  class="input150">
        <?php ListeModif2($contact, $travail['tblContact_conId'], 'conId', 'conNom', 'conPrenom') ?>
    </select>

    <?php
} else {
    if ($travail['traCat3'] == 1 OR $travail['traCat3'] == 2) { ?>
        <select name="beneficiare" class="input150" >
            <?php ListeModif2($contact, $travail['tblContact_conId'], 'conId', 'conNom', 'conPrenom') ?>
        </select>
    <?php }


}