/*!
 * jquery-adminTable v1
 * Author: MrpSynergies
 */
if (typeof jQuery === "undefined") {
    throw new Error("jquery-confirm requires jQuery");
}

$('.btnDetail').click(function () {


    $(this).closest("tr").addClass('edited');
    $(this).closest("tr").find("td").each(function() {
        var field=this.id;
        var fText = '';
        field = field.substr(0,field.lastIndexOf("_"));
        if($(this).children("span").length > 0){
            fText = $(this).children("span").text();
        }else{
            fText = this.innerText;
        }
        if ($('input[name="'+field+'"]').length > 0){
            if ($( 'input[name="'+field+'"]' ).is("[type=checkbox]")){
                if(fText==1){
                    $('input[name="'+field+'"]').prop("checked", true);
                }else{
                    $('input[name="'+field+'"]').prop("checked", false);
                }
            }else {
                $('input[name="' + field + '"]').val(fText);
            }
        }else if ($('select[name="'+field+'"]').length > 0){
            $('select[name="'+field+'"]').val(fText);
        }
    })
    $('#btn-add').addClass("hide");
    $('#btn-edit').removeClass("hide");
    $('#btn-delete').removeClass("hide");
    $([document.documentElement, document.body]).animate({
        scrollTop: $(".tbl-display").offset().top
    }, 200);
});
$(document).ready(function(){
    var clonedtable = $("table.tbl-display").html();
    $('table.tbl-sort')
        .on('click', 'th', function () {
            var index = $(this).index(),
                rows = [],
                thClass = $(this).hasClass('asc') ? 'desc' : 'asc';

            $('.tbl-sort th').removeClass('asc desc');
            $(this).addClass(thClass);

            $('.tbl-sort tbody tr').each(function (index, row) {
                rows.push($(row).detach());
            });

            rows.sort(function (a, b) {
                var aValue = $(a).find('td').eq(index).text(),
                    bValue = $(b).find('td').eq(index).text();
                return aValue > bValue
                    ? 1
                    : aValue < bValue
                        ? -1
                        : 0;
            });
            if ($(this).hasClass('desc')) {
                rows.reverse();
            }
            $.each(rows, function (index, row) {
                $('.tbl-sort tbody').append(row);
            });
        });


    $("#tbl-search-val").on("keyup", function() {
        var searchVal = $(this).val().toLowerCase();
        if($( "#tbl-search-col" ).length){
            var colId = $("#tbl-search-col").attr("name");
            colId = colId.slice(colId.indexOf("-")+1);
            var searchCol = $("#tbl-search-col").val().toLowerCase();

            $(".tbl-display tbody tr").filter(function() {
                var stateVal = $(this).text().toLowerCase().indexOf(searchVal) > -1;
                var stateCol = $(this).find('td').eq(colId).text().toLowerCase().indexOf(searchCol) > -1;
                $(this).toggle(stateVal && stateCol)
            });
        }else{
            $(".tbl-display tbody tr").filter(function() {
                $(this).toggle($(this).text().toLowerCase().indexOf(searchVal) > -1)
            });
        }
        $("#tblcount-rows").html($('.tbl-display >tbody >tr:visible').length);
    });
    $("#tbl-search-col").on("change", function() {
        var colId = $(this).attr("name");
        colId = colId.slice(colId.indexOf("-")+1);
        var searchCol = $(this).val().toLowerCase();
        $(".tbl-display tbody tr").filter(function() {
            $(this).toggle($(this).find('td').eq(colId).text().toLowerCase().indexOf(searchCol) > -1)
        });
        $("#tblcount-rows").html($('.tbl-display >tbody >tr:visible').length);
        $("#tbl-search-val").val('');
    });
    $("#lst-search").on("keyup", function() {
        var value = $(this).val().toLowerCase();
        $(".lst-sort li").filter(function() {
            $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
        });
    });
    $('.btn-reset').click(function () {
        $(':input','#form-search')
            .not(':button, :submit, :reset, :hidden')
            .val('')
            .removeAttr('checked')
            .removeAttr('selected');
        $('table.tbl-display').html(clonedtable);
        $("#tblcount-rows").html($('.tbl-display >tbody >tr:visible').length);
    });
});