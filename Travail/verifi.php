<script>
$(function () {
    // 1) charge l'OFAS au démarrage selon la valeur actuelle de #statu
    $.get("ajax.ofas.php", {statu: $("#statu").val()}, function (data) {
        $("#ofas").html(data);
        if ($("#statu").val() === '2') $('#required-label').show();
        else $('#required-label').hide();
    });

    // 2) update on change
    $("#statu").on("change", function () {
        const val = $(this).val();
        $.get("ajax.ofas.php", {statu: val}, function (data) {
            $("#ofas").html(data);
            if (val === '2') $('#required-label').show();
            else $('#required-label').hide();
        });
    });

    // 3) validation sur submit du form
    $("form[name='addTime']").on("submit", function (e) {
        let valid = true;

        // reset messages
        $("#erreurVide").hide();
        $("#erreurType").hide();
        $("#erreurCode").hide();

        // date
        if (!$("#date").val()) {
            valid = false;
            $("#date").css("border-color", "#FF0000");
            $("#erreurVide").show();
        }

        // debut
        if (!$("#debut").val()) {
            valid = false;
            $("#debut").css("border-color", "#FF0000");
            $("#erreurVide").show();
        } else {
            if (!$("#debut").val().match(/^[0-9]{2}:[0-9]{2}$/)) {
                $("#debut").css("border-color", "#ff9025");
                valid = false;
                $("#erreurType").show();
            } else {
                $("#debut").css("border-color", "#00ff00");
            }
        }

        // fin
        if (!$("#fin").val()) {
            valid = false;
            $("#fin").css("border-color", "#FF0000");
            $("#erreurVide").show();
        } else {
            if (!$("#fin").val().match(/^[0-9]{2}:[0-9]{2}$/)) {
                $("#fin").css("border-color", "#ff9025");
                valid = false;
                $("#erreurType").show();
            } else {
                $("#fin").css("border-color", "#00ff00");
            }
        }

        // OFAS check: seulement si statut === '2'
        const statu = $("#statu").val();
        const hasOfas = $("#ofasList").length > 0;
        const ofasVal = hasOfas ? $("#ofasList").val() : '';

        if (statu === '2') {
            if (!hasOfas || !ofasVal) {
                $("#erreurCode").show();
                valid = false;
            }
        } else {
            // si statut != 2, on retire la valeur OFAS si présente
            if (hasOfas && ofasVal) {
                $("#ofasList").val('');
            }
        }

        if (!valid) {
            e.preventDefault();
            return false;
        }
        return true;
    });
});
</script>

<!--script type="text/javascript" src="../jquery-3.1.1.min.js"></script>
<script type="text/javascript">
    $(function () {
        $("form[name='addTime']").on("submit", function(e){
        //$("#submit").click(function (e) {
            let valid = true;
            if ($("#date").val() == "") {
                valid = false;
                $("#date").css("border-color", "#FF0000");
                $("#erreurVide").css ("display" , "block" );
            }
            if ($("#debut").val() == "") {
                valid = false;
                $("#debut").css("border-color", "#FF0000");
                $("#erreurVide").css ("display" , "block" );
            }else{
                if ($("#debut").val().match(/^[0-9]{2}[:][0-9]{2}/)) {
                    $("#debut").css("border-color", "#00ff00")
                }else {
                    $("#debut").css("border-color", "#ff9025");
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
                }else {
                    $("#fin").css("border-color", "#ff9025");
                    valid = false;
                    $("#erreurType").css ("display" , "block" );
                }    
                /*** si art. 74 selectionné : champs "code Ofas" obligatoire */
                console.log("STATUT :: ", $("#statu").val())
                console.log('OFAS :: ', $('#ofasList').val())
                if ($("#statu").val() !== 2 && (!$('#ofasList').val() || $('#ofasList').val() === '')){
                    $('#erreurCode').css({ 'display' : 'block' })
                    valid = false
                } 
                /*** si !art.74 est sel, il ne doit pas y avoir de code ofas */
                if ($("#statu").val("") !== '2' && $('#ofasList').val() !== ''){
                    $('#ofasList').val()
                }
            }
            return valid;
        })
    })
</script -->