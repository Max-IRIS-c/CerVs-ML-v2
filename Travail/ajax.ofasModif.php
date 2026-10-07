
<?php
include('../variables.php');

$bdd = new PDO($dsn, $user, $password);
$id = $_SESSION['traId'];
$travail = $bdd->query("SELECT traCat1, traCat3 FROM tblTravail WHERE  traId = '$id'");
$travail = $travail->fetch();

if($_GET['statu']==2)
{
    $ofas = $bdd->query("SELECT * FROM tblTraCat1 WHERE cat1Id < 100 AND catActiv =1 ");
}
if($_GET['statu']==3)
{
    $ofas = $bdd->query("SELECT * FROM tblTraCat1 WHERE cat1Id > 100 AND catActiv =1 ");
}

$ofas2 = $bdd->query("SELECT * FROM tblTraCat1 WHERE catActiv =1");

if (!empty($_GET['statu']) AND ($_GET['statu']==2) OR ($_GET['statu']==3)  ){ ?>
    <select name="ofas" style="width:150px">
        <?php ListeModif2($ofas, $travail['traCat1'], 'cat1Id', 'cat1Code', 'cat1Nom') ?>
    </select>

<?php
}
else {
    if ($travail['traCat3'] == 1 OR $travail['traCat3'] == 2 OR  $travail['traCat3'] == 3  ) { ?>
        <select name="ofas" style="width: 150px">
            <?php ListeModif2($ofas2, $travail['traCat1'], 'cat1Id', 'cat1Code', 'cat1Nom') ?>
        </select>
    <?php }


}
?>
