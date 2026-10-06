<?php
// Récupération de l'ID depuis l'URL
$idLocation = isset($_GET['Id']) ? intval($_GET['Id']) : 0;

// Si le formulaire est soumis
if (isset($_GET['submit-contact-number'])) {
    $number_contact = $_GET['contact_number'];      
    if ($idLocation > 0) { 
        header('Location: printLocation_bus_parenthese.php?Id=' . $idLocation . '&number_contact=' . urlencode($number_contact));
        exit;
    } else {
        $error = "Veuillez remplir le numéro et vérifier l'ID.";
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Numéro de contact - Bus</title>
    <style>
        body { font-family: Arial, sans-serif; padding: 50px; text-align: center; }
        input[type="text"] { padding: 10px; width: 200px; font-size: 16px; }
        input[type="submit"] { padding: 10px 20px; font-size: 16px; cursor: pointer; background: #007bff; color: white; border: none; }
        .error { color: red; margin-bottom: 10px; }
    </style>
</head>
<body>
    <h1>Finalisation du contrat Bus - Réservation n°<?= $idLocation ?></h1>
    <p>Veuillez insérer le numéro de téléphone à afficher sur le contrat :.</p>
    <p style="text-decoration: italic">Section 6 - "Les éventuels dysfonctionnements liés au véhicule devront être communiqués par téléphone au
    .......................... ou par e-mail info@laparenthese.ch"</p>
    
    <?php if(isset($error)) echo '<p class="error">'.$error.'</p>'; ?>
    <form method="get" action="">
        <input type="hidden" name="Id" value="<?php echo $idLocation; ?>" />
        <input type="text" name="contact_number" placeholder="Ex: 079 123 45 67" />
        <input type="submit" name="submit-contact-number" value="Générer le PDF" />
    </form>
</body>
</html>