<?php 
    include('../variables.php');

    $debut = ($_GET['idDebut']);
    $fin = ($_GET['idFin']);
    $re = '/[0-9]{4}[-][0-9]{2}[-][0-9]{2}/';
    $str = $fin;
    if(isset($fin) AND isset($debut)) {
        if(preg_match($re,$str)){
            $matches = JourOuvrable($debut, $fin);
        } 
        else $matches = "";
    }
    else $matches = "";

    echo $matches;
?>

<td>
    <input class="input1" disabled name="total"  value="<?php echo $matches;?>">
</td>