
<?php
    include('../variables.php');

    $bdd = new PDO($dsn, $user, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ]);

    $statu = isset($_GET['statu']) ? (int) $_GET['statu'] : null;

    if ($statu === 2) {
        $ofas = $bdd->query("SELECT * FROM tblTraCat1 WHERE cat1Id < 150 AND catActiv = 1");
    } elseif ($statu === 3) {
        $ofas = $bdd->query("SELECT * FROM tblTraCat1 WHERE cat1Id > 150 AND catActiv = 1");
    }

    if ($statu === 2) {
        echo '<select id="ofasList" class="required" name="ofas" style="width:150px">';
        echo '<option value="">--</option>';
        ListeDeroulante2($ofas, cat1Id, cat1Code, cat1Nom);
        echo '</select>';
    }

/*
include('../variables.php');

$bdd = new PDO($dsn, $user, $password);

if($_GET['statu']==2)
{
    $ofas = $bdd->query("SELECT * FROM tblTraCat1 WHERE cat1Id < 150 AND catActiv = 1 ");
}
if($_GET['statu']==3)
{
    $ofas = $bdd->query("SELECT * FROM tblTraCat1 WHERE cat1Id > 150 AND catActiv = 1 ");
}

//if (!empty($_GET['statu']) AND ($_GET['statu']==2) OR ($_GET['statu']==3) ){ 
if (!empty($_GET['statu']) && ($_GET['statu']==2 || $_GET['statu']==3)) { ?>
    <select id="ofasList" class="required" name="ofas" style="width: 150px">
        <?php ListeDeroulante2($ofas, cat1Id, cat1Code, cat1Nom) ?>
    </select>
<?php
}*/
?>
