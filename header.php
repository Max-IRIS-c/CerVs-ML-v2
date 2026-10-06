<script language="javascript" type='text/javascript'>
    /* function session(){
         window.location="deconnexion.php"; //page de déconnexion
     }
     setTimeout("session()",300000); //ça fait bien 5min*/
</script>

<?php
session_start(); // On démarre les sessions
// Adding the language pack on this page ///

include_once "../src/class/Db.class.php";
include_once "../src/class/Mrp.class.php";

$mrp = new Mrp();

// we need to have the MRP object created.

if(isset($_GET["lang"]) && !empty($_GET["lang"])){
    $mrp->setLanguage($_GET["lang"]);
}



/* on vérifie si la session est active, si elle ne l'est pas retour à la page index*/
if (!isset($_SESSION['id'])) {
         header("location: ../index.php");
   
}
else {
    include('../variables.php');
}

if (!isset($pageNum)) {
    $pageNum = 3;
}

?>


<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta charset="utf-8"/>


    <meta name="viewport" content="width=device-width"/>

    <!-- <link rel="stylesheet" type="text/css" media="screen" href="../style.css"/> -->
	<?php
		include ('link_CSS_and_JS.php');
	?>

    <title id="top" class="noprint">alunis-CerVs</title>

    <meta http-equiv="Content-Type" content="text/html;charset=ISO-8859"/>
</head>

<body>


<div id="contenu">

    <div id='logo'>
        <img src="../img/logo.jpg" alt="logo de l'entreprise" style="width: 30%">
    </div>


    <div id='connexion'>
        <?php
        echo $nom;
        echo '<a href="../deconnexion.php"> déconnexion</a>'; ?>
        <br>        
        <a style="border: 0px; padding-top: 50px" href="../doc/Manuel_alunis-CerVs.pdf#page=<?= $pageNum ?>" target="_blank"> <img style="width: 30px; height: 30px" src="../doc/picto_manuel"> </a>        


        <?php if ($formulaire == 1)
        {
        ?><!-- menu si il y a un formuulaire dans la page = popUp de validation -->

    </div>

    <?php
    }
    else
    {
    ?>
</div>
<div id="menu">

<?php 
include ("../src/language.php");
include ('Mainmenu.php');
?>
</div>
<?php 
}
?>




