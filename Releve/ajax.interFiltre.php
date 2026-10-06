<?php
include "../variables.php";

$bdd = new PDO($dsn, $user, $password);
if (isset($_GET['lieu']) AND ($_GET['lieu']) !="N/A")  {
    $lieu = $_GET['lieu'];
    $intervenent = $bdd->query("SELECT * FROM tblDiponibiliter
LEFT JOIN tblContact on tblContact_conId = conId 
 WHERE (dispRegion1 = '$lieu' OR dispRegion2 = '$lieu' OR dispRegion3 = '$lieu') AND conIntervenant = 1 ORDER BY conNom ASC ");
    }
    else
    {

        $intervenent = $bdd->query("SELECT * FROM tblDiponibiliter
LEFT JOIN tblContact on tblContact_conId = conId AND conIntervenant = 1 ORDER BY conNom ASC");
    }
    ?>
<select name="" id="interventant">
    <option value="N/A">-></option>
    <?php while ($a=$intervenent->fetch()){ ?>

        <option value="<?php echo $a['conId'];?>"> <?php echo $a['conNom'].' '.$a['conPrenom'];?></option> <?php

    }?>
</select>

<div id="infoInter"></div> <!-- les informations ce trouvent dans le fichier ajax.interInfo.php-->

<script src="../jquery-3.1.1.min.js"></script>

<script>

    $.get("ajax.interInfo.php", function (data) {
        $("#infoInter").html(data);
    });

    $("#interventant").change(function () {
        $.get("ajax.interInfo.php", {inter: $("#interventant").val()}, function (data) {
            $("#infoInter").html(data);
        });
    });
</script>
