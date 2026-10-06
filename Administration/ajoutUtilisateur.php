<script src="jquery-3.1.1.min.js"></script>
<?php include('../header.php'); 

//session_start(); // On démarre les sessions

$bdd = new PDO($dsn, $user, $password);
$autorisation = $bdd->query('SELECT * FROM tblAutorisation LIMIT 5'); // On récupère tout le contenu de la table tblAutorisation
$fonction  = $bdd->query('SELECT * FROM tblFonction'); // On récupère tout le contenu de la table tblTaux
$date = date("Y-m-d");
    
if(isset($_POST['annuler'])) // Si le formulaire a été validé
{
	header("location: utilisateur.php");
}


if(isset($_POST['submit'])) // Si le formulaire a été validé
{
	$login = htmlspecialchars($_POST['Login']); // Son pseudo
    $password = ($_POST['utiPassword']);
    $password1 = ($_POST['utiPassword2']);

       if ($password == $password1) // je controle que les 2 mots de passe sont identique
	     {
	     	$passwordCry = md5($_POST['utiPassword']); // Son mot de passe, hashé grâce à la fonction md5()
	     	$password2 = $passwordCry;
	        $nom = ($_POST['Nom']);
	        $prenom = ($_POST['Prenom']);
	        $login = ($_POST['Login']);
	        $taux =$_POST['Taux'];
	        $autorisation = $_POST['Autorisation'];
	        $fonction = $_POST['Fonction'];
	        $entree = date("Y-m-d");

	        $statu = 1;

	        $insert = $bdd->prepare('INSERT INTO tblEmployer (empNom, empPrenom, empLogin, empPass, empTaux, tblAutorisation_autId, tblFonction_fonId, empStatu, empEntree)
	         						 VALUES(:nom, :prenom, :login, :password, :taux, :autorisation, :fonction, :statu, :entree)');
	         $insert->execute(array(
	         	'nom' => $nom,
	         	'prenom' => $prenom,
	         	'login' => $login,
	         	'password' => $password2,
	         	'taux' => $taux,	         	
	         	'autorisation' => $autorisation,
	         	'fonction' => $fonction,
	         	'statu' => $statu,
	         	'entree' => $entree,

	         	 ));

	         header("location: utilisateur.php");

	     }
	     else
	     {
	     	?>
	      <script>
	      jQuery(document).ready(function(){
	        
	      alert("Les mots de passe sont différant");
	      });
	      </script>
	      <?php 
	      
		}
	
}


?>
<h1>Ajouter un utilisateur </h1><br />
<p> les champs avec une * sont obligatoire </p>
<form name="add_user" method="post">
<table>


<tr>
    <td><label for="Nom">Nom :* </label></td>
    <td><input  name="Nom" /></td>
</tr>
<tr>
    <td><label for="Prenom">Prénom :* </label></td>
    <td><input name="Prenom"  /></td>
</tr>
<tr>
    <td><label for="Login">login :* </label></td>
    <td><input  name="Login" /></td>
</tr>
<tr>
    <td><label for="utiPassword">Mot de passe :* </label></td>
    <td><input type="password" name="utiPassword"  /></td>
</tr>
<tr>
    <td><label for="utiPassword2">Confirmation du Mot de passe :* </label></td>
    <td><input type="password" name="utiPassword2"  /></td>
</tr>
<tr>
    <td><label for="Autorisation">Autorisation : </label></td>
    <td><select name="Autorisation">
	<?php
        while ($a = $autorisation->fetch())
        {
            ?>
            <option value="<?php echo $a['autId']; ?>"> <?php echo $a['autNom']; ?></option>

            <?php
        }
        ?>
    </select></td>
</tr>
<tr>
    <td><label for="Fonction">Fonction : </label></td>
    <td><select name="Fonction">
	<?php
        while ($f = $fonction->fetch())
        {
            ?>
            <option value="<?php echo $f['fonId']; ?>"> <?php echo $f['fonNom']; ?></option>

            <?php
        }
        ?>
    </select></td>
</tr>
<tr>
    <td><label for="Taux">Taux d'activité : </label></td>
    <td><input  class="input1" name="Taux" />%</td>
</tr>
<tr>
    <td>
        <input type="submit" name="annuler"  value="Annuler" class="Annuler" />
        <input type="submit" name="submit" value="Valider" class="Valider" />
    </td>
</tr>
</table>

</form>


<?php include('../footer.php'); ?>

