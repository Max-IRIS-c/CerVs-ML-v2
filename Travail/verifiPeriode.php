<script type="text/javascript" src="../jquery-3.1.1.min.js"></script>
<script type="text/javascript">
    $(function () {
        $("#submit").click(function () {
            valid = true;


            if ($("#dateDebut").val() == "") {
                valid = false;
                $("#date").css("border-color", "#FF0000");
                $("#erreurVide").css ("display" , "block" );

            }
            else
            {

                if ($("#dateDebut").val().match(/^[0-9]{2}[\/][0-9]{2}[\/][0-9]{4}/)) {
                    $("#date").css("border-color", "#00ff00")

                }
                else
                {
                    $("#dateDebut").css("border-color", "#0000ff");
                    valid = false;
                    $("#erreurType").css ("display" , "block" );
                }
            }


        })

    });


</script>