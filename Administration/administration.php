<?php include('../header.php');
 ?>




<nav <nav id="menu2">
<ul>

    <?php
        if($auth >=3)
        {?>
      <li class="textGauche"><a href="utilisateur.php"><?php echo $mrp->getText("Utilisateurs") ?></a></li>
            <li class="textGauche"><a href="dossier.php"><?php echo $mrp->getText("Dossiers") ?></a></li>
            <li class="textGauche"><a href="marqueur.php"><?php echo $mrp->getText("Liste des marqueurs") ?></a></li>
        <?php }?>
</ul>
</nav>


 


<?php include('../footer.php'); ?>