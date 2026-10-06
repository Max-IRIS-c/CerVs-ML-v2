<?php include('../header.php');
$bdd = new PDO($dsn,$user,$password);
?>
<nav id="menu2">
    <ul>
        <li class="textGauche"><a href="beneficiaire.php"><?php echo $mrp->getText('Nouvelle intervention'); ?></a>
        <li class="textGauche"><a href="statistique.php"><?php echo $mrp->getText('Statistiques') ?></a>
        <li class="textGauche"><a href="interOuverte.php"><?php echo $mrp->getText('Interventions ouvertes') ?></a>
    </ul>
</nav>





<?php include('../footer.php'); ?>