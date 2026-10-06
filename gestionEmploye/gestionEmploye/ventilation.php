<?php
include('../variables.php');


$type = $_POST['TypeDecompte'];
$debut = $_POST['debut'];
$fin = $_POST['fin'];
$id = $_POST['employer'];
$debutSql = ($debut);
$finSql = ($fin);


if
($type == 1){
    include "CentreDeCharges.php";
}
elseif ($type == 2)
{
    include 'CodeOfas.php';
}
elseif ($type == 3)
{
include 'Beneficiaire.php';
}



