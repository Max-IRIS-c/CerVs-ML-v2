<script src="../jquery-3.1.1.min.js"></script>
<?php include('../header.php'); ?>
<script type="text/javascript">
    $(function () {
        $("#valider").click(function () {
            valid = true;
            if ($("#mp1").val() == $("#mp2").val()) {
                           }
            else
            {
                $("#erreur").css ("display" , "block" );
                valid = false;
            }

            return valid;
        })
    });
</script>


<?php
$id = $_GET["Id"];
$bdd = new PDO($dsn, $user, $password);
$sql = 'SELECT * FROM tblEmployer
LEFT JOIN tblAutorisation ON tblAutorisation_autId = autId
LEFT JOIN  tblFonction ON tblFonction_fonId = fonId';
$req = $bdd->prepare($sql . ' WHERE empId =  :id');
$req->execute(['id' => $_GET['Id']]);
$donnees = $req->fetch();
// Menu Déroulant 
$autorisation = $bdd->query('SELECT * FROM tblAutorisation LIMIT 5');
$fonction = $bdd->query('SELECT * FROM tblFonction');
$lstEmployer = $bdd->query("SELECT conNom, conPrenom,conId FROM tblContact where conEmploye = 1 AND conStatu = 1 or conComiter = 1 ORDER BY conNom");

if (isset($_POST['annuler'])) // Si le formulaire a été validé
{
    header("location: detUtilisateur.php?Id=".$id);
}

if (isset($_POST['submit'])) // Si le formulaire a ? valid?
{
    $login = htmlspecialchars($_POST['Login']); // Son pseudo
    $password = ($_POST['utiPassword']);
    $password1 = ($_POST['utiPassword2']);
    $nom = ($_POST['Nom']);
    $prenom = ($_POST['Prenom']);
    $login = ($_POST['Login']);
    $taux = $_POST['Taux'];
    $autorisation = $_POST['Autorisation'];
    $fonction = $_POST['Fonction'];
    $contact = $_POST['contact'];

    if (empty($_POST['utiPassword'])) { // pas de mots de passe
        $insert = $bdd->prepare("UPDATE tblEmployer SET	 
			empNom = :nom,
			empPrenom  = :prenom,
			empLogin = :login,
			empTaux=:taux,
			tblAutorisation_autId=:autorisation,
			tblFonction_fonId=:fonction,
			conId = :contact 
			 

			 WHERE empId = '$id'");
        $insert->execute(array(
            'nom' => $nom,
            'prenom' => $prenom,
            'login' => $login,
            'taux' => $taux,
            'autorisation' => $autorisation,
            'fonction' => $fonction,
            'contact' =>$contact,

        ));


        header("location: detUtilisateur.php?Id=".$id);
    } else { // MAJ du mots de passe
        if ($password == $password1)
        {
            $passwordCry = md5($_POST['utiPassword']); // Son mot de passe, hashé grâce à la fonction md5()
            $password2 = $passwordCry;
            $insert = $bdd->prepare("UPDATE tblEmployer SET	
	             
			 empNom = :nom,
			 empPrenom = :prenom,
			 empLogin = :login,
			 empPass =:passwordC,
			 empTaux =:taux,
			 tblAutorisation_autId =:autorisation,
			 tblFonction_fonId =:fonction,	
			 conId = :contact 

			 WHERE empId = '$id'");
            $insert->execute(array(
                'nom' => $nom,
                'prenom' => $prenom,
                'login' => $login,
                'passwordC' => $password2,
                'taux' => $taux,
                'autorisation' => $autorisation,
                'fonction' => $fonction,
                'contact' =>$contact,
            ));


            header("location: detUtilisateur.php?Id=".$id);
        }

    }


}


?>


<div class="contenu">
    <h1> <?= $mrp->getText("Modifier l'utilisateur") ?> </h1>

    <h2 style="color: #9A0000; display: none" id="erreur"><?= $mrp->getText("Les mots des passe sont différents") ?></h2>
    <form name="add_user" method="post">


        <table>


            <tr>
                <td><label for="Nom"><?= $mrp->getText("Nom") ?> : </label></td>
                <td><input name="Nom" value="<?php echo($donnees['empNom']); ?>"/></td>
            </tr>
            <tr>
                <td><label for="Prenom"><?= $mrp->getText("Prénom") ?> : </label></td>
                <td><input name="Prenom" value="<?php echo($donnees['empPrenom']); ?> "/></td>
            </tr>
            <tr>
                <td><label for="Login"><?= $mrp->getText("Login") ?> : </label></td>
                <td><input name="Login" value="<?php echo($donnees['empLogin']); ?>"/></td>
            </tr>
            <tr>
                <td><label for="utiPassword"><?= $mrp->getText("Mot de passe") ?> : </label></td>
                <td><input type="password" id="mp1" name="utiPassword"/></td>
            </tr>
            <tr>
                <td><label for="utiPassword2"><?= $mrp->getText("Confirmation du mot de passe") ?> :* </label></td>
                <td><input type="password" id="mp2" name="utiPassword2"/></td>
            </tr>
            <tr>
                <td><label for="Autorisation"><?= $mrp->getText("Autorisation") ?> : </label></td>
                <td><select name="Autorisation">
                        <?php
                        while ($a = $autorisation->fetch()) {
                            if ($donnees['tblAutorisation_autId'] == $a['autId']) {
                                echo '<option value="' . $a['autId'] . '" selected>' . $a['autNom'] . '</option>';

                            } else {            /* afficher l'?ment de la liste comme ?nt selected */
                                echo '<option value="' . $a['autId'] . '">' . $a['autNom'] . '</option>';
                            }
                        }

                        ?>
                </td>
            </tr>
            <tr>
                <td><label for="Fonction"><?= $mrp->getText("Fonction") ?> : </label></td>
                <td><select name="Fonction">
                        <?php
                        while ($f = $fonction->fetch()) {
                            if ($donnees['tblFonction_fonId'] == $f['fonId']) {
                                echo '<option value="' . $f['fonId'] . '" selected>' . $f['fonNom'] . '</option>';

                            } else {            /* afficher l'?ment de la liste comme ?nt selected */
                                echo '<option value="' . $f['fonId'] . '">' . $f['fonNom'] . '</option>';
                            }

                        }
                        ?>
                    </select></td>
            </tr>
            <tr>
                <td><label for="Taux"><?= $mrp->getText("Taux d'activité") ?> : </label></td>
                <td><input class="input1" name="Taux" value="<?php echo($donnees['empTaux']); ?>"/>%</td>

            </tr>
            <tr>
                <td>
                    <?= $mrp->getText("Contact associé") ?>
                </td>
                <td>
                    <select name="contact" >
                        <option>-></option>
                        <?php ListeModif2($lstEmployer,$donnees['conId'],'conId','conNom','conPrenom')
                        ?></select>
                </td>
            </tr>
            <tr>
                <td>
                    <input type="submit" name="annuler" value="Annuler" class="Annuler"/>
                    <input type="submit" name="submit" id="valider" value="Valider" class="Valider"/>
                </td>
            </tr>
        </table>
    </form>
</div>


<?php include('../footer.php'); ?>

