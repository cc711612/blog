$().ready(function () {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    login();
});

function login() {
    $("#login").click(function () {
        ajaxLoadingOpen();
        let form = $("#form");
        $.post(form.attr('action'), form.serialize(), function (Obj) {
            ajaxLoadingClose();
            if(Obj.status){
                if (Obj.redirect != '' && Obj.redirect != undefined) {
                    location.href = Obj.redirect;
                }
            }else {
                $.each(Obj.message, function (key, value) {
                    alert(value.join('\r'));
                });
            }
        });
        return false;
    });
}


