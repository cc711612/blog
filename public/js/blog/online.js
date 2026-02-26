
document.addEventListener('DOMContentLoaded', function () {
    if (typeof window.Echo !== 'undefined' && window.Echo.join) {
        window.Echo.join('common_room')
            .here(function (users) {
                onlineUsers = users ? users.length : 0;
                update_online_counter();
            })
            .joining(function () {
                onlineUsers++;
                update_online_counter();
            })
            .leaving(function () {
                onlineUsers--;
                update_online_counter();
            });
    } else {
        onlineUsers = 0;
        update_online_counter();
    }
});

function update_online_counter() {
    var el = document.getElementById('online');
    if (el) el.textContent = '' + onlineUsers;
}
