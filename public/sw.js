const CACHE_NAME = 'blog-v1.0.1';
const urlsToCache = [
    '/',
    '/css/styles.css',
    '/css/custom-theme.css',
    '/css/main.css',
    '/css/badge.css',
    '/css/desktop-optimized.css',
    '/js/app.js',
    '/js/blog/global.js',
    '/js/livewire.js'
];

// 安裝 Service Worker
self.addEventListener('install', function(event) {
    event.waitUntil(
        caches.open(CACHE_NAME)
            .then(function(cache) {
                return cache.addAll(urlsToCache);
            })
    );
});

// 攔截請求並使用快取
self.addEventListener('fetch', function(event) {
    event.respondWith(
        caches.match(event.request)
            .then(function(response) {
                // 快取命中，返回快取版本
                if (response) {
                    return response;
                }
                
                // 網路請求
                return fetch(event.request).then(
                    function(response) {
                        // 檢查是否為有效響應
                        if(!response || response.status !== 200 || response.type !== 'basic') {
                            return response;
                        }
                        
                        // 克隆響應
                        var responseToCache = response.clone();
                        
                        caches.open(CACHE_NAME)
                            .then(function(cache) {
                                cache.put(event.request, responseToCache);
                            });
                        
                        return response;
                    }
                );
            })
    );
});

// 清理舊快取
self.addEventListener('activate', function(event) {
    event.waitUntil(
        caches.keys().then(function(cacheNames) {
            return Promise.all(
                cacheNames.map(function(cacheName) {
                    if (cacheName !== CACHE_NAME) {
                        return caches.delete(cacheName);
                    }
                })
            );
        })
    );
});
