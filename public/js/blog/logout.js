
$().ready(function () {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    logout();
});

function logout()
{
    $("#logout").click(function (){
        ajaxLoadingOpen();
        $.post(logout_uri, {}, function(Obj){
            ajaxLoadingClose();
            location.reload();
        });
        return false ;
    });
}


