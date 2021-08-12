
$().ready(function () {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
});

function ajaxLoadingOpen()
{
    $("#loading").show();
}

function ajaxLoadingClose()
{
    $("#loading").hide();
}


