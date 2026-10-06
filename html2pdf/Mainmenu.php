<nav>
    <ul>
        <?php
        $Item1 ='CONTACTS';
        $Item2 ='ACTIVITES';
        $Item3 ='RELEVE';
        $Item4 ='LIBERTY';
        $Item5 ='TEMPS DE TRAVAIL';
        $Item6 ='ADMINISTRATION';

        if ($auth == 5) { // menu admin
            echo '<li><a href="../Adresse/contact.php">'.$Item1.' </a></li>';
            echo '<li><a href="../Activite/activite.php"> '.$Item2.'</a></li>';
            echo '<li><a href="../Releve/releveA.php"> '.$Item3.'</a></li>';
            echo '<li><a href="../Liberty/planning.php"> '.$Item4.'</a></li>';
            echo '<li><a href="../Travail/travail.php">'.$Item5.'</a></li>';
            echo '<li><a href="../Administration/administration.php"> '.$Item6.' </a></li>';
        }
        if ($auth == 4) { // menu Direction
            echo '<li><a href="../Adresse/contact.php">'.$Item1.' </a></li>';
            echo '<li><a href="../Activite/activite.php"> '.$Item2.'</a></li>';
            echo '<li><a href="../Releve/releveA.php"> '.$Item3.'</a></li>';
            echo '<li><a href="../Liberty/planning.php"> '.$Item4.'</a></li>';
            echo '<li><a href="../Travail/travail.php">'.$Item5.'</a></li>';
            echo '<li><a href="../Administration/administration.php"> '.$Item6.' </a></li>';
        }
        if ($auth == 3) { // menu Animation
            echo '<li><a href="../Adresse/contact.php">'.$Item1.' </a></li>';
            echo '<li><a href="../Activite/activite.php"> '.$Item2.'</a></li>';
            echo '<li><a href="../Releve/releveA.php"> '.$Item3.'</a></li>';
            echo '<li><a href="../Liberty/planning.php"> '.$Item4.'</a></li>';
            echo '<li><a href="../Travail/travail.php">'.$Item5.'</a></li>';

        }

        if ($auth == 2) { // menu Secretaria
            echo '<li><a href="../Adresse/contact.php">'.$Item1.' </a></li>';
            echo '<li><a href="../Activite/activite.php"> '.$Item2.'</a></li>';
            echo '<li><a href="../Travail/travail.php">'.$Item5.'</a></li>';

        }
        if ($auth == 1) { // menu Stagiaire
            echo '<li><a href="../Adresse/contact.php">'.$Item1.' </a></li>';
            echo '<li><a href="../Activite/activite.php"> '.$Item2.'</a></li>';

        }
        ?>

    </ul>
</nav>