<?php
include('../variables.php');
$id = $_SESSION['traId'];
$bdd = new PDO($dsn, $user, $password);
$periode = $bdd->query("SELECT traHeureTot FROM tblTravail WHERE traId = '$id'");
$periode = $periode->fetch();

$debut = ($_GET['idDebut']);

$fin = ($_GET['idFin']);

$re = '/[0-9]{4}[-][0-9]{2}[-][0-9]{2}/';
$str = $fin;



if(isset($fin) AND isset($debut) )
{
    if(preg_match($re,$str))
    {

        $matches = JourOuvrable ($debut, $fin);
    }
}
else
{
    $matches = $periode[0]/8.4;
}

?>

<td><input class="input1" disabled name="total"  value="<?php echo $matches ;?>"></td>