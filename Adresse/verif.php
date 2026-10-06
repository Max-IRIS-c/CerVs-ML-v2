<script type="text/javascript" src="../jquery-3.1.1.min.js"></script>
<script type="text/javascript">
    $(function () {
        $("#submit").click(function () {
            valid = true;
            // Teste si le champ titre est remplis
            if ($("#Titre").val() == "0") {
                valid = false;
                $("#Titre").css("border-color", "#FF0000");
                $("#erreurVide").css("display", "block");
            }
            else {
                $("#Titre").css("border-color", "#00FF00");
            }
            // Teste si le champ nom est remplis
            if ($("#Nom").val() == "") {
                valid = false;
                $("#Nom").css("border-color", "#FF0000");
                $("#erreurVide").css("display", "block");
            }
            else {
                $("#Nom").css("border-color", "#00FF00");
            }
            // Teste si le champ NPA est remplis
            if ($("#NPA").val() == "")  {
                valid = false;
                $("#NPA").css("border-color", "#FF0000");
                $("#erreurVide").css("display", "block");
            }
            else {   // et au bon format
                if ($("#NPA").val().match(/^[0-9]{4,}/)) {
                    $("#NPA").css("border-color", "")
                }
                else {
                    valid = false;
                    $("#NPA").css("border-color", "#ff9025");
                    $("#erreurType").css("display", "block");
                }
            }

            // Teste si le champ Localiter est remplis
            if ($("#Localiter").val() == "")  {
                valid = false;
                $("#Localiter").css("border-color", "#FF0000");
                $("#erreurVide").css("display", "block");

            }
            else {   // et au bon format
                if ($("#Localiter").val().match(/^[a-zA-Z]{2,}/)) {
                    $("#Localiter").css("border-color", "")
                }
                else {
                    valid = false;
                    $("#Localiter").css("border-color", "#ff9025");
                    $("#erreurType").css("display", "block");
                }
            }

            // Teste si le champ  Telephone1 est remplis
            if ($("#tel1").val() == "")  {
                $("#tel1").css("border-color", "#00ff00")
            }
            else {   // et au bon format
                if ($("#tel1").val().match(/^[+]{1}[0-9]{2}[ ]{1}[0-9]{2}[ ]{1}[0-9]{3}[ ]{1}[0-9]{2}[ ]{1}[0-9]{2}/)) {
                    $("#tel1").css("border-color", "#00ff00")
                }
                else {
                    valid = false;
                    $("#tel1").css("border-color", "#ff9025");
                    $("#erreurType").css("display", "block");
                }
            }
            // Teste si le champ  Telephone2 est remplis
            if ($("#tel2").val() == "")  {

            }
            else {   // et au bon format
                if ($("#tel2").val().match(/^[+]{1}[0-9]{2}[ ]{1}[0-9]{2}[ ]{1}[0-9]{3}[ ]{1}[0-9]{2}[ ]{1}[0-9]{2}/)) {
                    $("#tel2").css("border-color", "#00ff00")
                }
                else {
                    valid = false;
                    $("#tel2").css("border-color", "#ff9025");
                    $("#erreurType").css("display", "block");
                }
            }
            // Teste si le champ  Telephone3 est remplis
            if ($("#tel3").val() == "")  {
                $("#tel3").css("border-color", "")
            }
            else {   // et au bon format
                if ($("#tel3").val().match(/^[+]{1}[0-9]{2}[ ]{1}[0-9]{2}[ ]{1}[0-9]{3}[ ]{1}[0-9]{2}[ ]{1}[0-9]{2}/)) {
                    $("#tel3").css("border-color", "#00ff00")
                }
                else {
                    valid = false;
                    $("#tel3").css("border-color", "#ff9025");
                    $("#erreurType").css("display", "block");
                }
            }
            // Teste si le champ  Telephone3 est remplis
            if ($("#tel4").val() == "")  {
                $("#tel4").css("border-color", "")
            }
            else {   // et au bon format
                if ($("#tel4").val().match(/^[+]{1}[0-9]{2}[ ]{1}[0-9]{2}[ ]{1}[0-9]{3}[ ]{1}[0-9]{2}[ ]{1}[0-9]{2}/)) {
                    $("#tel4").css("border-color", "#00ff00")
                }
                else {
                    valid = false;
                    $("#tel4").css("border-color", "#ff9025");
                    $("#erreurType").css("display", "block");
                }
            }
            // Teste si le champ  AVS est remplis
            if ($("#Avs").val() == "")  {

            }
            else {   // et au bon format
                if ($("#Avs").val().match(/^[0-9]{3}[.]{1}([0-9]{4}[.]{1}){2}[0-9]{2}/)) {
                    $("#Avs").css("border-color", "#00ff00")
                }
                else {
                    valid = false;
                    $("#Avs").css("border-color", "#ff9025");
                    $("#erreurType").css("display", "block");
                }
            }
            // Teste si le champ  IBAN est remplis
            if ($("#IBAN").val() == "")  {

            }
            else {   // et au bon format
                if ($("#IBAN").val().match(/^[a-zA-Z]{2}[0-9]{2}[ ]{1}([a-zA-Z0-9]{4}[ ]{1}){4}[a-zA-Z0-9]/)) {
                    $("#IBAN").css("border-color", "#00ff00")
                }
                else {
                    valid = false;
                    $("#IBAN").css("border-color", "#ff9025");
                    $("#erreurType").css("display", "block");
                }
            }
            if ($("#Region").val() == "0") {
                valid = false;
                $("#Region").css("border-color", "#FF0000");
                $("#erreurVide").css("display", "block");

            }
            else {
                $("#Region").css("border-color", "#00FF00");
            }
            if ($("#Langue").val() == "0") {
                valid = false;
                $("#Langue").css("border-color", "#FF0000");
                $("#erreurVide").css("display", "block");
            }
            else {
                $("#Langue").css("border-color", "#00FF00");
            }
            // Teste si le champ  DateNaissande est remplis
            if ($("#Naissance").val() == "")  {
            }
            else {   // et au bon format
                if ($("#Naissance").val().match(/^[0-9]{2}[.][0-9]{2}[.][0-9]{4}/)) {
                    $("#Naissance").css("border-color", "#00ff00")
                }
                else {
                    valid = false;
                    $("#Naissance").css("border-color", "#ff9025");
                    $("#erreurType").css("display", "block");
                }
            }
            // Teste si le champ  Validiter est remplis
            if ($("#Validiter").val() == "")  {
            }
            else {   // et au bon format
                if ($("#Validiter").val().match(/^[0-9]{2}[.][0-9]{2}[.][0-9]{4}/)) {
                    $("#Validiter").css("border-color", "#00ff00")
                }
                else {
                    valid = false;
                    $("#Validiter").css("border-color", "#ff9025");
                    $("#erreurType").css("display", "block");
                }
            }

            // Teste si le champ Mail
            if ($("#Mail").val() == "")  {
            }
            else {   // et au bon format
                if ($("#Mail").val().match(/^[a-z-_.0-9]{1,}[@]{1,}[a-z-_.0-9]{1,}[.]{1}[a-z]{2,}/)) {
                    $("#Mail").css("border-color", "#00ff00")
                }
                else {
                    valid = false;
                    $("#Mail").css("border-color", "#ff9025");
                    $("#erreurType").css("display", "block");
                }
            }
            return valid;
        })
    });
</script>
<?php
    ob_end_flush();
?>