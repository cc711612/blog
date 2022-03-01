
$().ready(function () {
    window.Echo.join('common_room')
        .here((users) => {
            onlineUsers = users.length;
            update_online_counter();
        })
        .joining((user) => {
            onlineUsers++;
            update_online_counter();
        })
        .leaving((user) => {
            onlineUsers--;
            update_online_counter();
        });
});
function update_online_counter() {
    document.getElementById('online').textContent = '' + onlineUsers;
}


