<?php
include_once "../src/class/Db.class.php";
include_once "../src/class/Mrp.class.php";

include_once('../src/functions.inc.php');

// Create the db instance
$db = new DB();
$mrp = new Mrp();

//$mrp->pageAccess();
if(isset($_GET["lang"]) && !empty($_GET["lang"])){
    $mrp->setLanguage($_GET["lang"]);
}

if (!isset($pageNum)) {
    $pageNum = 3;
}



?>

<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width"/>
    
	<link rel="stylesheet" type="text/css" media="screen" href="../src/css/style.css"/>
	
	<?php
		// include ('link_CSS_and_JS.php');
	?>
	
    <title id="top" class="noprint">alunis-CerVs</title>
    <meta http-equiv="Content-Type" content="text/html;charset=ISO-8859"/>
    <script src="../src/js/jquery-3.1.1.min.js"></script>
</head>

<body>
<div id="contenu">

    <div id="logo">
        <a href="../Accueil/accueil.php"><img src="../img/logo_admin.jpg" alt="logo de l'entreprise"></a>
    </div>
    <!--
    <h3 style="color:#FF0000;">Attention cette administration n'est que pour les traductions. Aucune modification autre que les traductions ne sera enregistrée Merci</h3>
-->

    <div id='connexion'>
        <?php
        echo $nom;
        echo '<a href="../index.php?s=deconnexion"> ' .$mrp->getText('déconnexion').'</a>'; ?>
        <br>
        <a style="border: 0px; padding-top: 50px" href="../doc/Manuel_alunis-CerVs.pdf#page=<?= $pageNum ?>" target="_blank"> <img style="width: 30px; height: 30px" src="../doc/picto_manuel"> </a>


        <?php if ($formulaire == 1)
        {
        ?><!-- menu si il y a un formulaire dans la page = popUp de validation -->

    </div>

    <?php
    }
    else
    {
    ?>
</div>
<div>
<?php
include ("../src/language.php");

?>
</div>
<div id="menu">
    <?php include('../src/menu.php');?>
</div>

<?php }
?>