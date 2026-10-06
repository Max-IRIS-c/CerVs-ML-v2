<?php
include '../variables.php';
$bdd = new PDO($dsn, $user, $password);

if (!empty($_GET['fin'])) {

    $Debut = $_GET['debut'];
    $Fin = $_GET['fin'];
    $DateDebut = substr($Debut, 0, 10);
    $DateFin = substr($Fin, 0, 10);
    $HeurDebut = substr($Debut, 11, 5);
    $HeurFin = substr($Fin, 11, 5);

    $LstPavillon = $bdd->query("SELECT logNom,logId FROM tblLogement
    where logId not in (SELECT locPavId FROM tblLocation
     where (locDateEnt <= '$DateDebut' AND locDateDep >= '$DateDebut') )
AND logId not in (SELECT locPavId FROM tblLocation where (locDateEnt <= '$DateFin' AND locDateDep >= '$DateFin') )
                      OR logId  in (SELECT locPavId FROM tblLocation where ( locDateDep = '$DateDebut') )");
}


?>
<select name="logId">
                            <option value="">-></option>
                            <?php ListeDeroulante($LstPavillon,'logId','logNom')?>
                        </select>