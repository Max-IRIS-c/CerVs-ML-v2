<? echo '<h1>'.$mrp->getText('Période du').' ' . dateToUser($_SESSION['DebutOfas']) . ' '.$mrp->getText('au').' ' . dateToUser($_SESSION['finOfas']) . '</h1>'; ?>


<nav>
<table class="noMargin">

        <tr>
            <td><a  href="conseilSupport.php"><?=$mrp->getText('Conseil et support') ?></a></td>
            <td><a  href="StatistiquesClients.php"><?=$mrp->getText('Statistiques Clients') ?></a></td>
            <td><a  href="typeCours.php"><?=$mrp->getText('Types de cours') ?>///</a></td>
            <td><a  href=""><?=$mrp->getText('Catégories de cours') ?>///</a></td>
            <td><a  href="VieAccompagne.php"><?=$mrp->getText('Vie accompagnée') ?>///</a></td>
            <td><a  href="LufebProgamme.php"><?=$mrp->getText('Programme de travail réalisé') ?>. LUFEB</a></td>
        </tr>
</table>
</nav>

