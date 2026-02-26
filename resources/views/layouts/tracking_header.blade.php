@if(is_null(config('services.google.tag')) === false)
<!-- Google Tag Manager -->
<script>
    (function(w, d, l, i) {
        w[l] = w[l] || [];
        w[l].push({'gtm.start': new Date().getTime(), event: 'gtm.js'});

        function loadGtm() {
            var firstScript = d.getElementsByTagName('script')[0];
            var gtmScript = d.createElement('script');
            var dataLayer = l !== 'dataLayer' ? '&l=' + l : '';
            gtmScript.async = true;
            gtmScript.src = 'https://www.googletagmanager.com/gtm.js?id=' + i + dataLayer;
            firstScript.parentNode.insertBefore(gtmScript, firstScript);
        }

        if ('requestIdleCallback' in w) {
            w.requestIdleCallback(loadGtm, { timeout: 2000 });
        } else {
            w.addEventListener('load', loadGtm, { once: true });
        }
    })(window, document, 'dataLayer', @json(config('services.google.tag')));
</script>
<!-- End Google Tag Manager -->
@endif
