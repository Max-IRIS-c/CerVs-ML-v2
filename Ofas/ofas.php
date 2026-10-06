<?php
include "../src/header.inc.php";

if (isset($_POST['Valider']))
{
    $_SESSION['DebutOfas'] = $_POST['debut'];
    $_SESSION['finOfas'] = $_POST['fin'];
}

if (isset($_SESSION['finOfas']))
{
    include  'menu.php';
}

if (!isset($_SESSION['finOfas']))
{
    echo '<h1>'.$mrp->getText('Sélectioner la période du décompte OFAS').'</h1>
<form method="post">
<label>Du</label>
<input type="date" name="debut">
<label>au</label>
<input type="date" name="fin">
<input type="submit" value="'.$mrp->getText('Valider').'" name="Valider" class="ValiderPetit">
</form>';
}




include "../src/footer.inc.php";?>
