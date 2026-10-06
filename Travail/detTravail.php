<!--<meta http-equiv="refresh" content="3">-->

<?php
//include ('verifi.php');
include('../header.php');
$id= $_GET['Id'];
$_SESSION['traId'] = $_GET['Id'];

$bdd = new PDO($dsn, $user, $password);
//liste déroulante
$contact = $bdd->query("SELECT conNom,conPrenom,conId FROM tblContact WHERE conHandicaper = 1 ORDER BY  conNom ASC");
$ofas = $bdd->query("SELECT * FROM tblTraCat1 ");
$stat = $bdd->query("SELECT * FROM tblTraCat3 ");
$dossier = $bdd->query("SELECT * FROM tblTraCat4 ");
$travail = $bdd ->query("SELECT * FROM tblTravail WHERE traId ='$id'");
$travail = $travail ->fetch();


if (isset($_POST['valider'])) {
    $dateDebut = dateToSql($_POST['dateTra']);
    $debut = $_POST['debut'];
    $fin = $_POST['fin'];
    $total = heureDiffDecimal($_POST['debut'], $_POST['fin']);
    $ofas = $_POST['ofas'];
    $client = $_POST['beneficiare'];
    $statu = $_POST['statu'];
    $dossier = $_POST['dossier'];
    $benevol = $_POST['benevole'];
    $commentaire = $_POST['commentaire'];
    $honorefique = $_POST['honorefique'];
    $insert = $bdd->prepare("UPDATE tblTravail SET
traDate=:Tdate,
traHeureTot=:total,
traCat3=:statu,
traCat4=:dossier,
traCat1=:ofas,
traDebut=:hDebut,
traFin=:hFin,
traCommentaire=:commentaire,
traBenevole=:benevole,
tblContact_conId=:beneficiaire,
traHonorifique =:honorefique
WHERE traId = '$id'");

    $insert->execute(array(
        'Tdate' => $dateDebut,
        'total' => $total,
        'statu' => $statu,
        'dossier' => $dossier,
        'ofas' => $ofas,
        'hDebut' => $debut,
        'hFin' => $fin,
        'commentaire' => $commentaire,
        'benevole' => $benevol,
        'beneficiaire' => $client,
        'honorefique' => $honorefique,

    ));

    header("location: travail.php");

}


?>


<nav id="menu2">
    <ul>

        <li class="textGauche"><?php echo '<a href="modifTravail.php?Id='.$id.'"> Modifier</a>';?>
        <li class="textGauche"><?php echo '<a href="ajoutTravail.php"> Nouvelle saisie</a>';?>

    </ul>
</nav>

<h1> saisie des heures </h1>
<h2 id="erreurVide" style="display: none"> Les champs en rouge doivent être remplis </h2>
<h2 id="erreurType" style="display: none"> Les champs en bleu ont un mauvais format, pour les date jj/mm/aaaa et pour les heures hh:mm</h2>
<?php if(intval($_GET['success']) === 1){ ?><h2>✅ La saisie a bien été enregistrée</h2><?php } ?>
<form method="post" name="addTime">
    <table>
        <tr>
            <th>Date</th>
            <th>Début</th>
            <th>Fin</th>
            <th>Nb. d'heures (en décimale)</th>
            <th>Commentaires, précisions</th>
            <th>Bénévole</th>
            <th>Honorifique</th>
        </tr>
        <tr>
            <td><input disabled id="date" class="input0" name="dateTra" value="<?php echo dateToUser($travail['traDate']);?>"></td>
            <td><input disabled id="debut" class="input0" name="debut" value="<?php echo HeureHhMm($travail['traDebut']);?>"></td>
            <td><input disabled id="fin" class="input0" name="fin" value="<?php echo HeureHhMm($travail['traFin']);?>"></td>
            <td><div id="calculeH"></div></td>
            <td><textarea disabled id="commentaire" cols="30" name="commentaire"><?php echo $travail['traCommentaire'];?></textarea></td>
            <td><?php echo CheckBox($travail['traBenevole'],'benevole')?></td>
            <td><?php echo CheckBox($travail['traHonorifique'],'honorefique')?></td>


        </tr>
        <tr>
            <th>Statut</th>
            <th colspan="2">Code Ofas</th>
            <th>Beneficiaire</th>
            <th>dossier</th>

            <th></th>
        </tr>
        <!-- Zone de saisie -->
        <?php
        $Largeur = "150px"// Largeur des liste déroulante?>
        <tr>
            <td><select disabled id="statu" name="statu" style="width: <?php echo $Largeur ?>">
                    <?php ListeModif2($stat, $travail['traCat3'],cat3Id, cat3Code,cat3Nom) ?>
                </select></td>
            <td colspan="2">
                <?php //echo '$travail[traCat1] : '.$travail['traCat1']; ?>
                <?php if($travail['traCat1']){ ?>
                    <select disabled name="ofas" style="width: <?php echo $Largeur ?>">
                        <?php ListeModif2($ofas, $travail['traCat1'], cat1Id, cat1Code, cat1Nom) ?>
                    </select></td>
                <?php } ?>
            <td>
                <select disabled name="beneficiare" style="width: <?php echo $Largeur ?>">
                    <?php ListeModif2($contact,$travail['tblContact_conId'], conId, conNom, conPrenom) ?>
                </select></td>

            <td><select disabled name="dossier" style="width: <?php echo $Largeur ?>">
                    <?php ListeModif2($dossier, $travail['traCat4'],cat4Id, cat4Code, cat4Nom) ?>
                </select></td>
        </tr>
    </table>
</form>

<div id="resum"></div><!-- tableau dans la page ajax.historiqueModif-->
<script src="../jquery-3.1.1.min.js"></script>
<script>

    // fonction qui sort l'historique par rapport a la date
    $.get("ajax.historiqueModif.php", function (data) {
        $("#resum").html(data);
    });

    $("#date").change(function () {
        $.get("ajax.historiqueModif.php", {date: $("#date").val()}, function (data) {
            $("#resum").html(data);
        });
    });

    //fonction qui calcule le temps

    $.get("ajax.calculModif.php", function (data) {
        $("#calculeH").html(data);
    });


    $("#fin").keyup(function () {
        $.get("ajax.calculModif.php", {idDebut: $("#debut").val(), idFin: $("#fin").val()}, function (data) {
            $("#calculeH").html(data);
        });

    });
</script>

<?php include('../footer.php'); ?>