<script type="text/javascript" src="../jquery-3.1.1.min.js"></script>
<script type="text/javascript">
    $(function () {
        $("#submit").click(function () {
            valid = true;




            if ($("#debut").val() == "") {
                valid = false;
                $("#debut").css("border-color", "#FF0000");
                $("#erreurVide").css ("display" , "block" );
            }
            else
            {
                if ($("#debut").val().match(/^[0-9]{2}[:][0-9]{2}/)) {
                    $("#debut").css("border-color", "#00ff00")

                }
                else {
                    $("#debut").css("border-color", "#0000ff");
                    valid = false;
                    $("#erreurType").css ("display" , "block" );
                }
            }
            if ($("#fin").val() == "") {
                valid = false;
                $("#fin").css("border-color", "#FF0000");
                $("#erreurVide").css ("display" , "block" );


            }
            else {
                if ($("#fin").val().match(/^[0-9]{2}[:][0-9]{2}/)) {
                    $("#fin").css("border-color", "#00ff00")

                }
                else {
                    $("#fin").css("border-color", "#0000ff");
                    valid = false;
                    $("#erreurType").css ("display" , "block" );
                }
            }
            return valid;
        })

    });


</script>

