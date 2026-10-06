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

elseif ($type == 4)
{
    include 'HorsOfas.php';
}

elseif ($type == 5)
{
    include 'ResumCentreDeCharges.php';
}
elseif ($type == 6)
{
    include 'ResumOfas.php';
}
elseif ($type == 7)
{
    include 'ResumHorsOfas.php';
}
