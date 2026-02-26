
document.addEventListener('DOMContentLoaded', function () {
    logout();
});

function logout() {
    var btn = document.getElementById('logout');
    if (!btn) return;
    btn.addEventListener('click', function (e) {
        e.preventDefault();
        ajaxLoadingOpen();
        fetch(logout_uri, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': window._csrfToken,
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            }
        }).then(function () {
            location.reload();
        });
    });
}
