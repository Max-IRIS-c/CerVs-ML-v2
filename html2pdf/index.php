
<script src="jquery-3.1.1.min.js"></script>
<?php



session_start(); //  cette fonction propre à PHP servira à maintenir la $_SESSION
if(isset($_POST['connexion'])) { // si le bouton "valider" est appuyé
    // on vérifie que le champ "Pseudo" n'est pas vide
    
    if(empty($_POST['login'])) 
  {
      ?>
      <script>
      jQuery(document).ready(function(){
        
      alert("Le champ Login est vide!");
      });
      </script>
      <?php       
  } 
  else
  
  {
        // on vérifie maintenant si le champ "Mot de passe" n'est pas vide"
        if(empty($_POST['password'])) 
        {
            ?>
      <script>
      jQuery(document).ready(function(){
        
      alert("Le champ password est vide!");
      });
      </script>
      <?php 
        } else {
            // les champs sont bien posté et pas vide, on sécurise les données entrées par le membre:
            $Pseudo = htmlentities($_POST['login'], ENT_QUOTES, "ISO-8859-1"); // le htmlentities() passera les guillemets en entités HTML, ce qui empêchera les injections SQL
            $MotDePasse = md5($_POST['password']);
            
            //on se connecte à la base de données:
           $bdd = new PDO('mysql:host=localhost:3307;dbname=alunis-CerVs17', 'adminInternet', 'X99Sk3hsP86iBa');
            //on vérifie que la connexion s'effectue correctement:
            if(!$bdd){
              ?>
      <script>
      jQuery(document).ready(function(){
        
      alert("Erreur de connexion à la base de données.");
      });
      </script>
      <?php
                
            } else {
                // on fait maintenant la requête dans la base de données pour rechercher si ces données existe et correspondent:
                $req = $bdd->prepare('SELECT * FROM tblEmployer WHERE empLogin= :login AND empPass= :password');
            $req->execute(array(
              'login' => $Pseudo,
              'password' => $MotDePasse));
              
            $resultat = $req->fetch();
            
            if (!$resultat)
{
  ?>
   <script>
       jQuery(document).ready(function(){
           // Du code en jQuery va pouvoir être tapé ici !
       alert("Mauvais login ou mots de passe  !");
       });
   </script>
   <?php

  

}else
{

  session_start(); //enregistrement des valeurs de session
  $_SESSION['id'] = $resultat['empId'];
  $_SESSION['auth'] = $resultat['tblAutorisation_autId'];
  $_SESSION['Nom'] = $resultat['empNom'];
  $_SESSION['Prenom'] = $resultat['empPrenom'];


    
  header("location: Accueil/accueil.php");
   
}
               
                }
            }
        }
    }

?>
<html>
<head>
<meta name="viewport" content="width=device-width"/>

	<!-- <link rel="stylesheet" type="text/css" media="screen" href="style.css"/> -->
	<?php
		include ('link_CSS_and_JS.php');
	?>
        <title> Alunis CerVs</title>

</head>
<body>
<div id="contenu">
<div id="logoGrand"> <img src="img/logo.jpg" alt="logo de l'entreprise" style="width: 20%"></div>

<h2> Bienvenue sur alunis-CerVs.
</br>
 Pour continuer, merci de vous enregistrer
</h2>



<form action="#" method="post">
    Login: <input type="text" name="login" value="" />
     
    Password: <input type="password" name="password" value="" /> 
     
    <input type="submit" name="connexion" value="valider" class="valider" />
</form>

<p> <?php // echo $text; ?></p>


<div id="footer">
  <div id="gauche"> 
  <a href="http://www.iris-c.ch" target=_blank ><img src="img/alunis.gif" class="imgGauche">  une application IRIS-c </a>
  </div>
  <div id="droit">
  <a href="http://www.iris-c.ch" target=_blank><img src="img/logoIris.jpg" alt="logo d'iris-c" class="imgDroit"></a>
  </div>

</div>

</body>

</html>