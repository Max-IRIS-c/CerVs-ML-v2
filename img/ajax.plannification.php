

<?php

if(!empty($_GET['id']))
{
    $id = $_GET['id'];
}
else
{
    $id = 1;
}

$bdd = new PDO('mysql:host=localhost;dbname=alunis_time', 'Lionel', 'jvsmpdg88'); // Vos identifiants
$reponse = $bdd->query('SELECT * FROM tblFonction');
$sql = "SELECT * FROM tblAlocTravail 
left join tblTache on tblAlocTravail.tblTache_tacId=tblTache.tacId
left join tblFonction on tblAlocTravail.tblFonction_fonId=tblFonction.fonId
left join tblCode on tblAlocTravail.tblCode_codId=tblCode.codId
WHERE tblAlocTravail.tblFonction_fonId = ".$id;
 
$req = $bdd->query($sql); // On récupère tout le contenu de la table tblFonction

?>


    </form>

    <table border="0">
        <tr>
            
            
            <th>Tâches </th>
            <th>Code OFAS </th>
            <th> </th>
        </tr>


        <?php while($row = $req->fetch()) { ?>
            <tr>
                
               
                <td><?php echo $row['tacCode']. " / ".$row['tacDescription']; ?></td>
                <td><?php echo $row['codCode']; ?></td>
                <td><?php echo '<a href="modificationCorrelations.php?AloId='.$row['AloId'].'"> Modifier</a>';?>





            </tr>
        <?php }
        $req->closeCursor();
        ?>
    </table>
</div>




