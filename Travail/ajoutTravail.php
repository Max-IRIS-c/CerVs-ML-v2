<?php
include('../variables.php');
include_once "../src/class/Db.class.php";
include_once "../src/class/Mrp.class.php";

$mrp = new Mrp();
$erreurCode = false;
$erreurCode2 = false;
$erreurTime = false;
$erreurStatus = false;
$erreurOther = false;
$givenError = '';

// helper pour recharger les valeurs postées
function old($name, $default = '') {
    return isset($_POST[$name]) ? htmlspecialchars($_POST[$name], ENT_QUOTES) : $default;
}
if(isset($_GET["lang"]) && !empty($_GET["lang"])){
    $mrp->setLanguage($_GET["lang"]);
}
$bdd = new PDO($dsn, $user, $password, [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES => false,
]);

$contact = $bdd->query("SELECT conNom,conPrenom,conId FROM tblContact WHERE conHandicaper = 1 AND conSecondaire is NULL ORDER BY conNom ASC");
$ofas = $bdd->query("SELECT * FROM tblTraCat1 WHERE catActiv = 1");
$stat = $bdd->query("SELECT * FROM tblTraCat3");
$dossier = $bdd->query("SELECT * FROM tblTraCat4 where cat4Statu = 1 ORDER BY cat4Code");

$historique = $bdd->query("SELECT * FROM tblTravail
 LEFT JOIN tblTraCat1 on  traCat1 = cat1Id
   LEFT JOIN tblTraCat3 on  traCat3 = cat3Id
    LEFT JOIN tblTraCat4 on  traCat4 = cat4Id
    LEFT JOIN tblContact on  tblContact_conId = conId
    WHERE tblEmployer_empId = $idUtilisateur");

if (isset($_POST['Annuler'])) {header("location: travail.php"); exit;}

if (isset($_POST['valider'])) {
    try{
        $dateDebut = $_POST['dateTra'];
        $debut = $_POST['debut'];
        $fin = $_POST['fin'];
        $total = heureDiffDecimal($_POST['debut'], $_POST['fin']);
        $ofasVal = !empty($_POST['ofas']) ? intval($_POST['ofas']) : null;
        $client = $_POST['beneficiare'];
        $statu = $_POST['statu'];
        $dossierVal = $_POST['dossier'];
        $benevol = isset($_POST['benevole']) ? 1 : 0;
        $employer = $idUtilisateur;
        $commentaire = $_POST['commentaire'];
        $honorefique = isset($_POST['honorefique']) ? 1 : 0;

        if(!$debut || !$fin) throw new Exception('time');
        if(!$statu || $statu === '') throw new Exception('status');
        if($statu === '2' && (!$ofasVal || $ofasVal ==='')) throw new Exception('ofas');
        if($statu !== '2' && ($ofasVal && $ofasVal !== '')){
            $erreurCode2 = true;
            $ofasVal = null;
        }

        $insert = $bdd->prepare('INSERT INTO tblTravail
            (traCat1, traCat3, traCat4, traDebut, traFin, traHeureTot, tblContact_conId, tblEmployer_empId,
            traCommentaire, traBenevole,traDate,traHonorifique)
            VALUES(:traCat1, :traCat3, :traCat4, :traDebut, :traFin, :traHeureTot, :tblContact_conId,
            :tblEmployer_empId, :traCommentaire, :traBenevole, :traDate, :honorefique)');

        $insert->execute(array(
            'traCat1' => $ofasVal,
            'traCat3' => $statu,
            'traCat4' => $dossierVal,
            'traDebut' => $debut,
            'traFin' => $fin,
            'traHeureTot' => $total,
            'tblContact_conId' => $client,
            'tblEmployer_empId' => $employer,
            'traCommentaire' => $commentaire,
            'traBenevole' => $benevol,
            'traDate' => $dateDebut,
            'honorefique' => $honorefique,
        ));
        $newId = $bdd->lastInsertId();
        header("location: detTravail.php?Id=".$newId."&success=1");
        exit;
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

include('../heade.php');
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

<h1><?php echo $mrp->getText("Saisie des heures") ?> </h1>
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
            <th><?php echo $mrp->getText("Nb. d'heures (en décimal)") ?></th>
            <th><?php echo $mrp->getText("Commentaires, précisions") ?></th>
            <th><?php echo $mrp->getText("Bénévole") ?></th>
            <th><?php echo $mrp->getText("Honorifique") ?></th>
        </tr>
        <tr>
            <td><input onkeypress="refuserToucheEntree(event)" id="date"  name="dateTra" type="date" value="<?php echo old('dateTra', date("Y-m-d")); ?>"></td>
            <td><input onkeypress="refuserToucheEntree(event)" type="time" id="debut" class="input90" name="debut" value="<?php echo old('debut'); ?>"></td>
            <td><input onkeypress="refuserToucheEntree(event)" type="time" id="fin" class="input90" name="fin" value="<?php echo old('fin'); ?>"></td>
            <td>
                <div id="calculeH" name="totale">
                    <input type="text" id="total" class="input1" disabled name="total" />
                </div>
            </td>
            <td><textarea id="commentaire" cols="30" name="commentaire"><?php echo old('commentaire'); ?></textarea></td>
            <td><input class="input0" name="benevole" type="checkbox" value="1" <?php echo isset($_POST['benevole']) ? 'checked' : ''; ?>></td>
            <td><input class="input0" name="honorefique" type="checkbox" value="1" <?php echo isset($_POST['honorefique']) ? 'checked' : ''; ?>></td>
        </tr>
        <tr>
            <th><?= $mrp->getText("Statut") ?></th>
            <th colspan="2"><?= $mrp->getText("Code OFAS") ?></th>
            <th><?= $mrp->getText("Bénéficiaire (conseils)") ?></th>
            <th><?= $mrp->getText("Dossier") ?></th>
            <th colspan="2"></th>
        </tr>
        <tr>
            <td>
                <select name="statu" id="statu" style="width: 120px">
                    <?php
                        $stat = $bdd->query("SELECT * FROM tblTraCat3 WHERE catActiv = 1");
                        ListeDeroulante2($stat, 'cat3Id', 'cat3Code', 'cat3Nom', old('statu'));
                    ?>
                </select>
            </td>
            <td colspan="2">
                <p id="required-label">* Champ obligatoire</p>
                <div id="ofas"></div>
            </td>
            <td><div id="beneficiaire"></div></td>
            <td>
                <select name="dossier" style="width: 120px">
                    <?php
                    $dossier = $bdd->query("SELECT * FROM tblTraCat4 where cat4Statu = 1 ORDER BY cat4Code");
                    ListeDeroulante2($dossier, 'cat4Id', 'cat4Code', 'cat4Nom', old('dossier'));
                    ?>
                </select>
            </td>
            <td><input id="submit" type="submit" value="Valider" name="valider" class="ValiderPetit"></td>
        </tr>
    </table>
</form>

<div id="resum"></div>

<script src="../jquery-3.1.1.min.js"></script>
<script>
    addEventListener('load', () => {     
        $('#total').val(getTotalTime())
    })


    function refuserToucheEntree(event) {
        if (!event && window.event) event = window.event;
        if (event.keyCode == 13) {
            event.returnValue = false;
            event.cancelBubble = true;
        }
        if (event.which == 13) {
            event.preventDefault();
            event.stopPropagation();
        }
    }
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

    // bénéficiaires
    $.get("ajax.beneficiaire.php", function (data) {
        $("#beneficiaire").html(data);
    });

    $("#statu").change(function () {
        $.get("ajax.beneficiaire.php", {statu: $("#statu").val()}, function (data) {
            $("#beneficiaire").html(data);
        });
    });

    // historique
    $.get("ajax.historique.php", function (data) {
        $("#resum").html(data);
    });

    $("#date").change(function () {
        $.get("ajax.historique.php", {date: $("#date").val()}, function (data) {
            $("#resum").html(data);
        });
    });

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
    // calcul heures
    /*$.get("ajax.calcul.php", function (data) {
        $("#calculeH").html(data);
    });*/

    $("#fin").keyup(function () {
        $.get("ajax.calcul.php", { idDebut: $("#debut").val(), idFin: $("#fin").val() }, function (data) {
            $("#calculeH").html(data);
        });
    });
</script>

<?php include('../footer.php'); ?>
