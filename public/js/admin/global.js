
$().ready(function () {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    // 隱藏左下角logo
    $(".main-footer").children('strong').hide();
});

function ajaxLoadingOpen()
{
    $("#loading").show();
}

function ajaxLoadingClose()
{
    $("#loading").hide();
}
function default_user(obj)
{
    $(obj).attr('src',"https://roy.usongrat.tw/storage/images/default.png")
}


