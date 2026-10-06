<!--<meta http-equiv="refresh" content="3">-->

<?php
//include ('verifi.php');
include('../header.php');

$erreurCode = false;
$erreurCode2 = false;
$erreurTime = false;
$erreurStatus = false;
$erreurOther = false;
$givenError = '';


$id= $_GET['Id'];
$_SESSION['traId'] = $_GET['Id'];

$bdd = new PDO($dsn, $user, $password);
//liste déroulante
$contact = $bdd->query("SELECT conNom,conPrenom,conId FROM tblContact WHERE conHandicaper = 1 AND conSecondaire is NULL ORDER BY  conNom ASC");
$ofas = $bdd->query("SELECT * FROM tblTraCat1 WHERE catActive = 1");
$stat = $bdd->query("SELECT * FROM tblTraCat3 ");
$dossier = $bdd->query("SELECT * FROM tblTraCat4 where cat4Statu = 1 ORDER BY cat4Code");
$travail = $bdd ->query("SELECT * FROM tblTravail WHERE traId ='$id'");
$travail = $travail ->fetch();

if (isset($_POST['Anuller'])) {header("location: travail.php");}
if (isset($_POST['valider'])) {
    try{
        $dateDebut = $_POST['dateTra'];
        $debut = $_POST['debut'];
        $fin = $_POST['fin'];
        $total = heureDiffDecimal($_POST['debut'], $_POST['fin']);    
        $ofasVal = !empty($_POST['ofas']) ? intval($_POST['ofas']) : null;
        $client = $_POST['beneficiare'];
        $statu = $_POST['statu'];
        $dossier = $_POST['dossier'];
        $benevol = $_POST['benevole'];
        $commentaire = $_POST['commentaire'];
        $honorefique = $_POST['honorefique'];

        if(!$debut || !$fin) throw new Exception('time');
        if(!$statu || $statu === '') throw new Exception('status');
        if($statu === '2' && (!$ofasVal || $ofasVal ==='')) throw new Exception('ofas');
        if($statu !== '2' && ($ofasVal && $ofasVal !== '')){
            $erreurCode2 = true;
            $ofasVal = null;
        }

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
            'ofas' => $ofasVal,
            'hDebut' => $debut,
            'hFin' => $fin,
            'commentaire' => $commentaire,
            'benevole' => $benevol,
            'beneficiaire' => $client,
            'honorefique' => $honorefique,

        ));

        header("location: travail.php");
    }catch(Exception $e){
        $errMsg = $e->getMessage();
        if($errMsg === 'ofas') $erreurCode = true;
        else if($errMsg === 'time') $erreurTime = true;
        else if($errMsg === 'status') $erreurStatus = true;
        else {
            $givenError = $errMsg;
            $erreurOther = true;
        }
    }
}


?>

<style>
    #required-label{
        display: none;
        background-color: red;
        color: white;
        width: 100%;
        text-align: center;
    }
    .required{
        border: 2px solid red;
    }
</style>

<nav id="menu2">
    <ul>

        <li class="textGauche"><?php echo '<a href="supTravail.php?Id='.$id.'">'.$mrp->getText("Supprimer").'</a>';?>
    </ul>
</nav>

<h1><?php echo $mrp->getText("Modifier saisie des heures") ?></h1>
<?php if($erreurTime){ ?><h2 id="erreurVide">⚠️ Il faut mettre une heure de début et de fin</h2><?php } ?>
<?php if($erreurStatus){ ?><h2 id="erreurType">⚠️ La selection du Statut est obligatoire</h2><?php } ?>
<?php if($erreurCode){ ?><h2 id="erreurCode">⚠️ Il faut sélectionner un code OFAS si <strong>'Art. 74'</strong> sélectionné ⚠️</h2><?php } ?>
<?php if($erreurCode2){ ?><h2 id="erreurCode">⚠️ La séquence est enregistrée mais le code OFAS a été ignoré car ce n'était pas 'Art.74'</h2><?php } ?>
<?php if($erreurOther) echo '⚠️ Autre erreur : '.$givenError; ?>
<form method="post" name="addTime">
    <table>
        <tr>
            <th><?php echo $mrp->getText("Date") ?></th>
            <th><?php echo $mrp->getText("Début") ?></th>
            <th><?php echo $mrp->getText("Fin") ?></th>
            <th><?php echo $mrp->getText("Nb. d'heures (en décimale)") ?></th>
            <th><?php echo $mrp->getText("Commentaires, précisions") ?></th>
            <th><?php echo $mrp->getText("Bénévole") ?></th>
            <th><?php echo $mrp->getText("Honorifique") ?></th>
        </tr>
        <tr>
            <td><input id="date"  name="dateTra" type="date" value="<?php echo $travail['traDate'];?>"></td>
            <td><input type="time" id="debut" class="input90" name="debut" value="<?php echo HeureHhMm($travail['traDebut']);?>"></td>
            <td><input type="time" id="fin" class="input90" name="fin" value="<?php echo HeureHhMm($travail['traFin']);?>"></td>
            <td>
                <div id="calculeH">           
                    <input type="text" id="total" class="input1" disabled name="total" value="<?php echo $travail['traHeureTot']; ?>" />
                </div>
            </td>
            <td><textarea id="commentaire" cols="30" name="commentaire"><?php echo $travail['traCommentaire'];?></textarea></td>
            <td><?php echo CheckBoxModif($travail['traBenevole'],benevole)?></td>
            <td><?php echo CheckBoxModif($travail['traHonorifique'],honorefique)?></td>


        </tr>
        <tr>
            <th><?php echo $mrp->getText("Statut") ?></th>
            <th colspan="2"><?php echo $mrp->getText("Code Ofas") ?></th>
            <th><?php echo $mrp->getText("Bénéficiaire") ?></th>

            <th><?php echo $mrp->getText("dossier") ?></th>

            <th></th>
        </tr>
        <!-- Zone de saisie -->
        <?php
        $Largeur = "150px"// Largeur des liste déroulante?>
        <tr>
            <td><select  id="statu" name="statu" style="width: <?php echo $Largeur ?>">
                    <?php ListeModif2($stat, $travail['traCat3'],cat3Id, cat3Code,cat3Nom) ?>
                </select></td>
            <td colspan="2">                
                <p id="required-label">* Champ obligatoire</p>
                <div id="ofas"></div>
            </td>
            <td><div id="beneficiaire"></div></td>

            <td><select name="dossier" style="width: <?php echo $Largeur ?>">
                    <?php ListeModif2($dossier, $travail['traCat4'],cat4Id, cat4Code, cat4Nom) ?>
                </select></td>
            <td><input id="submit" type="submit" value="Valider" name="valider" class="ValiderPetit"></td>
            <td><input id="submit" type="submit" value="Annuler" name="Anuller" class="SuprimerrPetit"></td>
        </tr>
    </table>
</form>

<div id="resum"></div>
<script src="../jquery-3.1.1.min.js"></script>
<script>
    // fonction qui sort qui affiche les codes OFAS
    const handleOfasCode = (data) => {
        $("#ofas").html(data);
        if($("#statu").val() === '2') $('#required-label').show()
        else $('#required-label').hide()
    }
    // chargement OFAS
    $.get("ajax.ofas.php", handleOfasCode);

    $("#statu").change(function () {
        $.get("ajax.ofas.php", {  statu: $("#statu").val() }, handleOfasCode);
    });
    /*$.get("ajax.ofasModif.php", function (data) {
        $("#ofas").html(data);
    });

    $("#statu").change(function () {
        $.get("ajax.ofasModif.php", {statu: $("#statu").val()}, function (data) {
            $("#ofas").html(data);
        });
    });*/
    // fonction qui sort qui affiche les bénéficiaire

    $.get("ajax.beneficiaireModif.php", function (data) {
        $("#beneficiaire").html(data);
    });

    $("#statu").change(function () {
        $.get("ajax.beneficiaireModif.php", {statu: $("#statu").val()}, function (data) {
            $("#beneficiaire").html(data);
        });
    });
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
/*
    $.get("ajax.calculModif.php", function (data) {
        $("#calculeH").html(data);
    });


    $("#fin").keyup(function () {
        $.get("ajax.calculModif.php", {idDebut: $("#debut").val(), idFin: $("#fin").val()}, function (data) {
            $("#calculeH").html(data);
        });

    });*/

    $('#debut').change(function(){
        $('#total').val(getTotalTime())
    })
    $('#fin').change(function(){
        $('#total').val(getTotalTime())
    })

    function getTotalTime(){
        const start = $('#debut').val()
        const fin = $('#fin').val()
        if(!start || !fin) return 0
        const [sh, sm] = start.split(':').map(Number);
        const [eh, em] = fin.split(':').map(Number);
        // conversion en minutes
        const startMinutes = sh * 60 + sm;
        const endMinutes = eh * 60 + em;
        let diff = endMinutes - startMinutes;
        // si l'heure de fin est le lendemain
        if(diff < 0) diff += 24 * 60;
        const totalHours = (diff / 60).toFixed(2);
        return totalHours
    }
</script>

<?php include('../footer.php'); ?>