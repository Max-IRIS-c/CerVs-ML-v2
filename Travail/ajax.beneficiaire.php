
<?php
include('../variables.php');

$bdd = new PDO($dsn, $user, $password);


$contact = $bdd->query("SELECT conNom,conPrenom,conId FROM tblContact WHERE conHandicaper = 1 AND conSecondaire is NULL ORDER BY  conNom ASC");

if (!empty($_GET['statu']) AND ($_GET['statu']==2) ){ ?>
    <select name="beneficiare" class="input150">
        <?php ListeDeroulante2($contact, 'conId', 'conNom', 'conPrenom') ?>
    </select>

<?php
}
?>
