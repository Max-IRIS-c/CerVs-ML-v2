<?php include('../header.php');
?>
<script type="text/javascript" src="../jquery-3.1.1.min.js"></script>
<script type="text/javascript">
    $(function () {
        $("#submit").click(function () {
            valid = true;


            if ($("#dateDebut").val() == "")
            {
                valid = false;
                $("#dateDebut").css("border-color", "#FF0000");
                $("#erreurVide").css ("display" , "block" );

            }
            else
            {

                if ($("#dateDebut").val().match(/^[0-9]{2}[.][0-9]{2}[.][0-9]{4}/)) {
                    $("#dateDebut").css("border-color", "#00ff00")

                }
                else
                {
                    $("#dateDebut").css("border-color", "#0000ff");
                    valid = false;
                    $("#erreurType").css ("display" , "block" );
                }
            }

            if ($("#dateFin").val() == "")
            {
                valid = false;
                $("#dateFin").css("border-color", "#FF0000");
                $("#erreurVide").css ("display" , "block" );

            }
            else
            {

                if ($("#dateFin").val().match(/^[0-9]{2}[.][0-9]{2}[.][0-9]{4}/)) {
                    $("#dateFin").css("border-color", "#00ff00")

                }
                else
                {
                    $("#dateFin").css("border-color", "#0000ff");
                    valid = false;
                    $("#erreurType").css ("display" , "block" );
                }
            }

            return valid;
        })

    });


</script>


<?php
$bdd = new PDO($dsn, $user, $password);
$_SESSION[traId] = $_GET['Id'];
$id = $_GET['Id'];
//liste déroulante
$stat = $bdd->query("SELECT * FROM tblTraCat3 WHERE cat3Id >=10 ");

$periode = $bdd->query("SELECT * FROM tblTravail WHERE traId = '$id'");
$periode = $periode->fetch();

$historique = $bdd->query("SELECT * FROM tblTravail
   LEFT JOIN tblTraCat3 on traCat3  = cat3Id 
    WHERE tblEmployer_empId = '$idUtilisateur' AND cat3Id >=10 AND traId != '$id'");

if ( isset ($_POST['submit']))
{
    $debut = dateToSql($_POST['dateDebut']);
    $fin = dateToSql($_POST['dateFin']);
    $total = JourOuvrable($_POST['dateDebut'],$_POST['dateFin'])*8.4;
    $statu = $_POST['statu'];


    $insert = $bdd->prepare("UPDATE tblTravail SET
traDate=:debut,
traDateFin=:fin,
traHeureTot=:total,
traCat3=:statu
 WHERE traId = '$id'");

    $insert->execute(array(
        'debut' => $debut,
        'fin' => $fin,
        'total' => $total,
        'statu' => $statu,

    ));


    header("location: travail.php");
}

?>
<nav id="menu2">
    <ul>
        <li class="textGauche"><?php echo '<a href="modifPeriode.php?Id='.$id.'"> Modifier</a>';?></li>
    </ul>
</nav>

<h1> Saisie des absences (1 ou plusieurs jours) </h1>
<h2 id="erreurVide" style="display: none"> Les champs en rouge doivent être remplis </h2>
<h2 id="erreurType" style="display: none"> Les champs en bleu ont un mauvais format, pour les date jj/mm/aaaa</h2>

<form method="post" name="addTime">
    <table>
        <tr>
            <th>Date début</th>
            <th>Date Fin y.c</th>
            <th>Nbr de jour</th>
            <th>Statu</th>

        </tr>
        <tr>
            <td><input disabled id="dateDebut" type="date" name="dateDebut" value="<?php echo ($periode['traDate']);?>"></td>
            <td><input disabled id="dateFin" type="date" name="dateFin" value="<?php echo ($periode['traDateFin']);?>"></td>
            <td><div  disabled id="total"></div> <!--div ajax.calculDateModif.php--> </td>

            <td><select disabled name="statu" style="width: <?php echo $Largeur ?>">
                    <?php ListeModif($stat, $periode['traCat3'], cat3Id, cat3Code) ?>
                </select></td>

        </tr>

    </table>
</form>

<h1>Historique des absences </h1>
<table>

    <tr>
        <th>Date début</th>
        <th>Date Fin </th>
        <th>Nbr de jour</th>
        <th>Statu</th>
        <th></th>

    </tr>
    <?php while ($row = $historique->fetch()){
        $heure = $row['traHeureTot']/8.4;
        ?>
        <tr>

            <td><?php echo dateToUser($row['traDate']);?></td>
            <td><?php echo dateToUser($row['traDateFin']);?></td>
            <td><?php echo round($heure,1)?></td>
            <td><?php echo $row['cat3Code'];?></td>
            <td><?php echo '<a href="supTravail.php?Id='.$id.'"> détail</a>';?> </td>


        </tr>
    <?php } ?>

</table>

<script>
    $.get("ajax.calculDateModif.php", function (data) {
        $("#total").html(data);
    });


    $("#dateFin").keyup(function () {
        $.get("ajax.calculDateModif.php", {idDebut: $("#dateDebut").val(), idFin: $("#dateFin").val()}, function (data) {
            $("#total").html(data);
        });

    });
</script>


<?php include('../footer.php'); ?>
