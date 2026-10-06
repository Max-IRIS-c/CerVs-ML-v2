<?php
/**
 * Created by PhpStorm.
 * User: Maïté
 * Date: 07.07.2020
 * Time: 15:04
 */
include_once "../src/class/Db.class.php";
include_once "../src/class/Mrp.class.php";
include_once('../src/functions.inc.php');

// Create the db instance
$db = new DB();
$mrp = new Mrp();

$mrp->pageAccess();

$id=intval($_GET['id']);
$type=$_GET['type'];


if($type=='trad'){
    $db->bindInt('tradsId', $id);
    $sqlQuery = 'SELECT * 
              FROM admTradSourceXL
              WHERE tradsId=:tradsId';
    $aLine = $db->single($sqlQuery);
    if($aLine){
        echo $aLine["tradsTexte"];
    }
}
