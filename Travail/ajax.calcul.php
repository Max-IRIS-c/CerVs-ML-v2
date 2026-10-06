<?php
include('../variables.php');

$debut = ($_GET['idDebut']);

$fin = ($_GET['idFin']);

$re = '/[0-9]{2}[:][0-9]{2}/';
$str = $fin;



if(isset($fin))
{
    if(preg_match($re,$str))
    {

       $matches = heureDiffDecimal ($debut, $fin);
    }
    else
    {
        $matches = "";
    }


}
else
{
    $matches = "";
}

?>

<td><input class="input1" disabled name="total"  value="<?php echo $matches ;?>"></td>