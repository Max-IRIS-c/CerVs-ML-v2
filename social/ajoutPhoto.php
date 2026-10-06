<?php
// récupération data
$nom = isset($_POST['genre']) ? $_POST['genre'] : (isset($_GET['genre']) ? $_GET['genre'] : '');
$id = isset($_POST['Id']) ? $_POST['Id'] : (isset($_GET['Id']) ? $_GET['Id'] : '');
$src = isset($_POST['src']) ? $_POST['src'] : (isset($_GET['src']) ? $_GET['src'] : '');

if ($id === '' || $nom === '') die("Erreur : Paramètres manquants (ID ou Genre).");


if (isset($_POST['validerPhoto'])) {
    // Création du dossier si nécessaire
    $dossier_dest = "../imgParticipant/$id";
    if (!is_dir($dossier_dest)) {
        mkdir($dossier_dest, 0777, true);
    }

    $extensions_valides = array('jpg', 'jpeg'); // Ajout de jpeg par sécurité
    $extension_upload = strtolower(substr(strrchr($_FILES['Portrait']['name'], '.'), 1));
    
    if ($_FILES['Portrait']['error'] > 0) {
        $erreur = "Erreur lors du transfert : code " . $_FILES['Portrait']['error'];
        echo $erreur;
    }
    elseif (in_array($extension_upload, $extensions_valides)) {
        $photo = "$dossier_dest/$nom.jpg";
        
        if (move_uploaded_file($_FILES['Portrait']['tmp_name'], $photo)) {
            // Redirection
            /*if ($src == 'Modif') {
                header("location: modifSocial.php?Id=" . $id);
            } else {
                header("location: ajouterSocial.php?Id=" . $id);
            }*/
            header("location: social.php?Id=" . $id);
            exit(); // Important pour arrêter le script après le header
        } else {
            echo "Échec du déplacement du fichier.";
        }
    }
    else {
        echo "Extension incorrecte (.jpg attendu).";
    }
}
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////7
?>
<?php
$nom = isset($_POST['genre']) ? $_POST['genre'] : (isset($_GET['genre']) ? $_GET['genre'] : '');
$id = isset($_POST['Id']) ? $_POST['Id'] : (isset($_GET['Id']) ? $_GET['Id'] : '');
$src = isset($_POST['src']) ? $_POST['src'] : (isset($_GET['src']) ? $_GET['src'] : '');


if (isset($_POST['validerPhoto'])) {

    if (!is_dir($id))
    {

        mkdir("../imgParticipant/$id",777);
    }


    $extensions_valides = array('jpg');
    $extension_upload = strtolower(  substr(  strrchr($_FILES['Portrait']['name'], '.')  ,1)  );
    if ($_FILES['Portrait']['error'] > 0) {
        $erreur = "Erreur lors du transfert";
        echo $erreur;
    }
    elseif (in_array($extension_upload,$extensions_valides) ){
        $photo = "../imgParticipant/$id/$nom.jpg";
        $resultat = move_uploaded_file($_FILES['Portrait']['tmp_name'],$photo);
        if ($_GET['src']=='Modif'){
            header("location: modifSocial.php?Id=".$id);
            var_dump($photo);
        } else{
            header("location: ajouterSocial.php?Id=".$id);
        }
    }
    else echo "Extension incorrect"; 
}

?>

<form method="post" enctype="multipart/form-data">
    <input type="hidden" name="genre" value="<?php echo htmlspecialchars($nom); ?>">
    <input type="hidden" name="Id" value="<?php echo htmlspecialchars($id); ?>">
    <input type="hidden" name="src" value="<?php echo htmlspecialchars($src); ?>">

    <label for="Portrait">Photo (JPG| max. 15 Ko) :</label><br/>
    <input type="file" name="Portrait" id="Portrait" required/><br/>
    <input type="submit" name="validerPhoto" value="valider" class="valider"/>
</form> 