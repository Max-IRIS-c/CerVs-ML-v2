<?php 

    $searchedParam = $_GET['searchedParam'] ?? null;
    include('../header.php');

?>


    <nav id="menu2"></nav>
    <p> <?php echo $mrp->getText('Chercher') ?> </p> <input name="Date" type="text" id="liste" value="<?= $searchedParam ?>">
    <h1> <?php echo $mrp->getText('Liste des adresses') ?></h1>
    <div class="liste" id="table"></div>
    <script src="../jquery-3.1.1.min.js"></script>
    <script>
        $.get( "ajax.Contact.php", 
        { searchedParam: "<?= htmlspecialchars($searchedParam ?? '', ENT_QUOTES) ?>" },
        function( data ) {
            $( "#table" ).html( data );
        });

        $("#liste").change(function () {
            $.get( "ajax.Contact.php", { 
                searchedParam: $('#liste').val()
            }, function( data ) {
                $( "#table" ).html( data );
            });
        });

    </script>





<?php include('../footer.php'); ?>