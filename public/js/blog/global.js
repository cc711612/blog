
// Vanilla JS replacement for jQuery global setup
window._csrfToken = document.querySelector('meta[name="csrf-token"]')
    ? document.querySelector('meta[name="csrf-token"]').getAttribute('content')
    : '';

function ajaxLoadingOpen() {
    const el = document.getElementById('loading');
    if (el) el.style.display = '';
}

function ajaxLoadingClose() {
    const el = document.getElementById('loading');
    if (el) el.style.display = 'none';
}

