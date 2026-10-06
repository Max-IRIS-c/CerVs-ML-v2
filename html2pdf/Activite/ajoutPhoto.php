<?php
$nom = $_GET['genre'];
$id = $_GET['Id'];


if (isset($_POST['validerPhoto'])) {
    $extensions_valides = array('jpg');
    $extension_upload = strtolower(  substr(  strrchr($_FILES['Portrait']['name'], '.')  ,1)  );
    if ($_FILES['Portrait']['error'] > 0) {
        $erreur = "Erreur lors du transfert";
        echo $erreur;
    }
    elseif (in_array($extension_upload,$extensions_valides) ){
        $photo = "img/$id-$nom.jpg";
        var_dump($photo);

        $resultat = move_uploaded_file($_FILES['Portrait']['tmp_name'],$photo);
if ($_GET['src']=='Modif'){
    header("location: modifActivite.php?Id=".$id);}
    else
        {
            header("location: modifActivite.php?Id=".$id);}


    }
    else
    {
        echo "Extension incorrect";
    }
}

?>








<form method="post" enctype="multipart/form-data">
    <label for="Portrait">Photo  (JPG| max. 15 Ko) :</label><br/>
    <input type="file" name="Portrait" id="Portrait"/><br/>
    <input type="submit" name="validerPhoto" value="valider" class="valider"/>
</form>
