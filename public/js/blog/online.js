
$().ready(function () {
    if (typeof window.Echo !== 'undefined' && window.Echo.join) {
        window.Echo.join('common_room')
            .here((users) => {
                onlineUsers = users ? users.length : 0;
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
    } else {
        // 如果 Echo 未載入，設置預設值
        onlineUsers = 0;
        update_online_counter();
    }
});
function update_online_counter() {
    document.getElementById('online').textContent = '' + onlineUsers;
}


