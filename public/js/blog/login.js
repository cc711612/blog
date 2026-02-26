document.addEventListener('DOMContentLoaded', function () {
    login();
});

function login() {
    var btn = document.getElementById('login');
    if (!btn) return;
    btn.addEventListener('click', function (e) {
        e.preventDefault();
        ajaxLoadingOpen();
        var form = document.getElementById('form');
        var action = form.getAttribute('action');
        var data = new FormData(form);

        fetch(action, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': window._csrfToken,
                'Accept': 'application/json'
            },
            body: data
        })
        .then(function (response) { return response.json(); })
        .then(function (obj) {
            ajaxLoadingClose();
            if (obj.status) {
                if (obj.redirect) {
                    location.href = obj.redirect;
                }
            } else {
                Object.keys(obj.message).forEach(function (key) {
                    alert(obj.message[key].join('\r'));
                });
            }
        });
    });
}
