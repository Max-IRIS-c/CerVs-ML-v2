<nav>
    <ul>
        <?php
        $Item1 =$mrp->getText('CONTACTS');
        $Item2 =$mrp->getText('Activités');
        $Item3 =$mrp->getText('RELEVE');
        $Item4 =$mrp->getText('LIBERTY');
        $Item5 =$mrp->getText('TEMPS DE TRAVAIL');
        $Item6 =$mrp->getText('ADMINISTRATION');
	$Item7 =$mrp->getText('traduction');
	
	if (!(isset($auth)))
	{
		$auth=5;
	}

        if ($auth == 5) { // menu admin
            echo '<li><a href="../Adresse/contact.php">'.$Item1.' </a></li>';
            echo '<li><a href="../Activite/activite.php"> '.$Item2.'</a></li>';
            echo '<li><a href="../Releve/releveA.php"> '.$Item3.'</a></li>';
            echo '<li><a href="../Liberty/planning.php"> '.$Item4.'</a></li>';
            echo '<li><a href="../Travail/travail.php">'.$Item5.'</a></li>';
            echo '<li><a href="../Administration/administration.php"> '.$Item6.' </a></li>';
            echo '<li><a href="../Administration/newTraductions.php"> '.$Item7.' </a></li>';

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