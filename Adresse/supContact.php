<?php

include ('../variables.php');
include ('../heade.php');
$bdd = new PDO($dsn, $user, $password);
$id = $_GET['Id'];
$contact = $bdd->query("SELECT conNom, conPrenom FROM tblContact WHERE conId ='$id'");
$contact = $contact->fetch();

if (isset($_POST['annuler']))
{
    header("location: detContacte.php?conId=".$id);
}
if (isset($_POST['valider']))
{
    $insert = $bdd->prepare("UPDATE tblContact SET    
conModif = :conModif,
conStatu = :Statu
  WHERE conId = '$id'");



    $insert->execute(array(
        'conModif' => date('Y-m-d'),
        'Statu' => 0

    ));
    header("location: contact.php");
}

?>
<nav>

</nav>
    <div class="contenu">
    <form action="#" method="post">
    <h1 class="rouge"><?php echo $mrp->getText("Veuillez confirmer la suppression du contact")?> <?php echo $contact['conNom']. ' ' .$contact['conPrenom'];?>  </h1>
    <input type="submit" name="annuler"  value="Annuler" class="Annuler" />
    <input type="submit" name="valider" value="Valider" class="Valider" />
</form>
    </div>

<?php
include ('../footer.php');
?>