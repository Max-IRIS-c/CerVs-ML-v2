
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
<?php
include('../variables.php');

include_once "../src/class/Db.class.php";
include_once "../src/class/Mrp.class.php";

$mrp = new Mrp();
$errorMsg = '';
// we need to have the MRP object created.

if(isset($_GET["lang"]) && !empty($_GET["lang"])){
    $mrp->setLanguage($_GET["lang"]);
}


$bdd = new PDO($dsn, $user, $password);
//liste déroulante
$contact = $bdd->query("SELECT conNom,conPrenom,conId FROM tblContact WHERE conHandicaper = 1 AND conSecondaire is NULL ORDER BY  conNom ASC");
$ofas = $bdd->query("SELECT * FROM tblTraCat1 WHERE catActiv = 1");
$stat = $bdd->query("SELECT * FROM tblTraCat3 ");
$dossier = $bdd->query("SELECT * FROM tblTraCat4 where cat4Statu = 1 ORDER BY cat4Code");

$historique = $bdd->query("SELECT * FROM tblTravail
 LEFT JOIN tblTraCat1 on  traCat1 = cat1Id
   LEFT JOIN tblTraCat3 on  traCat3 = cat3Id
    LEFT JOIN tblTraCat4 on  traCat4 = cat4Id
    LEFT JOIN tblContact on  tblContact_conId = conId
    WHERE tblEmployer_empId = $idUtilisateur");

if (isset($_POST['Annuler'])) {header("location: travail.php");}

if (isset($_POST['valider'])) {
    try{
        $newId = $bdd->query("SELECT max(traId)+1 FROM tblTravail");
        $newId = $newId->fetch();
        $newId = $newId[0];
        $dateDebut = $_POST['dateTra'];
        $debut = $_POST['debut'];
        $fin = $_POST['fin'];
        $total = heureDiffDecimal($_POST['debut'], $_POST['fin']);
        $ofas = $_POST['ofas'];
        $client = $_POST['beneficiare'];
        $statu = $_POST['statu'];
        $dossier = $_POST['dossier'];
        $benevol = $_POST['benevole'];
        $employer = $idUtilisateur;
        $commentaire = $_POST['commentaire'];
        $honorefique = $_POST['honorefique'];

        if(intval($statu) === 2 && (!isset($ofas) || !$ofas)) throw new Exception();

        $insert = $bdd->prepare('INSERT INTO tblTravail
            (traCat1, traCat3, traCat4, traDebut, traFin, traHeureTot, tblContact_conId, tblEmployer_empId,
            traCommentaire, traBenevole,traDate,traHonorifique)
            VALUES(:traCat1, :traCat3, :traCat4, :traDebut, :traFin, :traHeureTot, :tblContact_conId,
            :tblEmployer_empId, :traCommentaire, :traBenevole, :traDate, :honorefique)');

        $insert->execute(array(
            'traCat1' => $ofas,
            'traCat3' => $statu,
            'traCat4' => $dossier,
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
            header("location: detTravail.php?Id=".$newId);
    }catch(Exception $e){
        $errorMsg = $e->getMessage();
    }

}
include('../heade.php');?>
<!--<meta http-equiv="refresh" content="3">-->
<script>
    function refuserToucheEntree(event) {
        // Compatibilité IE / Firefox
        if (!event && window.event) {
            event = window.event;
        }
        // IE
        if (event.keyCode == 13) {
            event.returnValue = false;
            event.cancelBubble = true;
        }
        // DOM
        if (event.which == 13) {
            event.preventDefault();
            event.stopPropagation();
        }
    }

</script>
<?php
include ('verifi.php');
?>




<h1><?php echo $mrp->getText("Saisie des heures") ?> </h1>
<h2 id="erreurVide" style="display: none"><?php echo $mrp->getText("Les champs en rouge doivent être remplis") ?></h2>
<h2 id="erreurType" style="display: none"><?php echo $mrp->getText(" Les champs en bleu ont un mauvais format, pour les date jj.mm.aaaa et pour les heures hh:mm") ?></h2>
<h2 id="erreurCode" style="display: none">⚠️Il faut séléctionner un code OFAS si 'Art. 74' séléctionné⚠️</h2>
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
            <td><input onkeypress="refuserToucheEntree(event)" id="date"  name="dateTra" type="date" value="<?php echo date("Y-m-d");?>"></td>
            <td><input  onkeypress="refuserToucheEntree(event)" id="debut" class="input90" name="debut"></td>
            <td><input onkeypress="refuserToucheEntree(event)" id="fin" class="input90" name="fin"></td>
            <td><div id="calculeH" name="totale"></div></td>
            <td><textarea id="commentaire" cols="30" name="commentaire"></textarea></td>
            <td><input class="input0" name="benevole" type="checkbox" value="1"></td>
            <td><input class="input0" name="honorefique" type="checkbox" value="1"></td>


        </tr>
        <tr>
            <th>Statut</th>
            <th colspan="2">
                <p>Code OFAS</p>
                <p id="required-label" >* Champ obligatoire</p>
            </th>
            <th>Bénéficiaire (conseils)</th>
            <th>Dossier</th>
            <th colspan="2"></th>
        </tr>
        <!-- Zone de saisie -->
        <?php
        $Largeur = "120px"// Largeur des liste déroulante?>
        <tr>
            <td><select name="statu" id="statu" style="width: <?php echo $Largeur ?>">
                    <?php ListeDeroulante2($stat, cat3Id, cat3Code, cat3Nom) ?>
                </select></td>
            <td colspan="2">
                <div id="ofas"></div>
            </td>
            <td><div id="beneficiaire"></div></td>

            <td><select name="dossier" style="width: <?php echo $Largeur ?>">
                    <?php ListeDeroulante2($dossier, cat4Id, cat4Code, cat4Nom) ?>
                </select></td>


            <td><input id="submit" type="submit" value="Valider" name="valider" class="ValiderPetit"></td>
            <td><input id="submit" type="submit" value="Annuler" name="Annuler" class="SuprimerrPetit"></td>
        </tr>
    </table>
</form>

<div id="resum"></div>
<script src="../jquery-3.1.1.min.js"></script>
<script>
    $(document).ready(function() {
        // fonction qui sort qui affiche les codes OFAS
        $.get("ajax.ofas.php", function (data) {
            $("#ofas").html(data);
        });
        $("#statu").change(function () {
            $.get("ajax.ofas.php", {
                statu: $("#statu").val()
            }, 
            function (data) {
                console.log("OFAS response:", data);
                console.log("change !! ::: ", typeof $("#statu").val())
                /****** champs 'code OFAS' obligatoire si statut === 'Art. 74' */
                if($("#statu").val() === '2'){
                    console.log("OFAS 74")
                    $("#ofas").html(data);
                    //$('#ofas select').addClass('required')
                    $('#required-label').show()
                } else{        
                    $("#ofas").html(data);        
                    //$('#ofas select').removeClass('required')
                    $('#required-label').hide()
                }
                });   
            $.get("ajax.beneficiaire.php", {
                statu: $("#statu").val()
            }, function (data) {
                $("#beneficiaire").html(data);
            });
        });
        // fonction qui sort qui affiche les bénéficiaire
        $.get("ajax.beneficiaire.php", function (data) {
            $("#beneficiaire").html(data);
        });

        /*$("#statu").change(function () {
            $.get("ajax.beneficiaire.php", {statu: $("#statu").val()}, function (data) {
                $("#beneficiaire").html(data);
            });
        });*/

        // fonction qui sort l'historique par rapport a la date
        $.get("ajax.historique.php", function (data) {
            $("#resum").html(data);
        });

        $("#date").change(function () {
            $.get("ajax.historique.php", {date: $("#date").val()}, function (data) {
                $("#resum").html(data);
            });
        });

        //fonction qui calcule le temps
        $.get("ajax.calcul.php", function (data) {
            $("#calculeH").html(data);
        });
        $("#fin").keyup(function () {
            $.get("ajax.calcul.php", {idDebut: $("#debut").val(), idFin: $("#fin").val()}, function (data) {
                $("#calculeH").html(data);
            });

        });
    })
</script>
<?php include('../footer.php'); ?>