<script type="text/javascript">
        function verif ()
        {
            var etat = document.getElementById('check').checked;
             
            if(etat)
            {
                document.getElementById('1').className = 'off';
                 
                document.getElementById('2').className = 'on';
            }
            else
            {
                document.getElementById('1').className = 'on';
                 
                document.getElementById('2').className = 'off';
            }
        }
</script>
<style type="text/css">
.on {
    display: block;
}
 
.off {
    display: none;
}
</style>
<input id="check" type="checkbox" onChange="verif();" /><label> Case à cocher</label>
<nav id="1">
    <ul>
    <li><a href="../Adresse/contact.php"> Adresses ///</a></li>
    <?php if($auth >0){?>
    <li><a href="installation.php"> Gestion temps de travail/// </a></li>
     <?php }?>
    <li><a href="listeSDossier.php"> Activités///</a></li>
    <li><a href="listePlannifiactaion.php"> Services de relève/// </a></li>
    <li><a href="tacheObjet.php?Id=7"> Fondation Liberty/// </a></li> 
    <?php if($auth > 1)
    {?>      
    <li><a href="Travail.php"> UAT/// </a></li>    
    <?php }?>
     <?php if($auth >= 4)
    {?>
    <li><a href="../Administration/administration.php"> Administration </a></li> 
    <?php }?>
    </ul></nav>
<nav id="2">
    <ul>
    <li><a href="../Adresse/contact.php"> Adresses ///</a></li>
    <?php if($auth >0){?>
    <li><a href="installation.php"> Gestion temps de travail/// </a></li>
     <?php }?>
    <li><a href="listeSDossier.php"> Activités///</a></li>
  
    </ul></nav>
    