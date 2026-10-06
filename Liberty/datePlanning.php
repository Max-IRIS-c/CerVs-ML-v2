<?php include('../header.php');

$bdd = new PDO($dsn, $user, $password); ?>


<h1> Période du décompte </h1>
<table>
    <form method="POST" action="decompteHeure.php">


        <td> Période du</td>
        <td><input type="date" name="debut" id="debute"></td>
        <td> au</td>
        <td><input type="date" name="fin" id="fin"></td>

        <td><input type="submit" name="submit" value="Valider" class="Valider"</td>
    </form>
</table>


<?php include('../footer.php'); ?>









