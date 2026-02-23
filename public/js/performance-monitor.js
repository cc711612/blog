// Performance Monitoring Script
// 監控網站性能指標

class PerformanceMonitor {
    constructor() {
        this.metrics = {};
        this.init();
    }

    init() {
        // 等待頁面完全載入
        if (document.readyState === 'complete') {
            this.collectMetrics();
        } else {
            window.addEventListener('load', () => this.collectMetrics());
        }
    }

    collectMetrics() {
        // Core Web Vitals
        this.getFCP();
        this.getLCP();
        this.getCLS();
        this.getFID();
        
        // 其他性能指標
        this.getLoadTime();
        this.getResourceTiming();
        
        // 發送到分析服務
        this.sendMetrics();
    }

    getFCP() {
        const observer = new PerformanceObserver((list) => {
            const entries = list.getEntries();
            const fcpEntry = entries.find(entry => entry.name === 'first-contentful-paint');
            if (fcpEntry) {
                this.metrics.fcp = fcpEntry.startTime;
            }
        });
        observer.observe({ entryTypes: ['paint'] });
    }

    getLCP() {
        const observer = new PerformanceObserver((list) => {
            const entries = list.getEntries();
            const lastEntry = entries[entries.length - 1];
            this.metrics.lcp = lastEntry.startTime;
        });
        observer.observe({ entryTypes: ['largest-contentful-paint'] });
    }

    getCLS() {
        let clsValue = 0;
        const observer = new PerformanceObserver((list) => {
            list.getEntries().forEach(entry => {
                if (!entry.hadRecentInput) {
                    clsValue += entry.value;
                }
            });
            this.metrics.cls = clsValue;
        });
        observer.observe({ entryTypes: ['layout-shift'] });
    }

    getFID() {
        const observer = new PerformanceObserver((list) => {
            const entries = list.getEntries();
            if (entries.length > 0) {
                this.metrics.fid = entries[0].processingStart - entries[0].startTime;
            }
        });
        observer.observe({ entryTypes: ['first-input'] });
    }

    getLoadTime() {
        const navigation = performance.getEntriesByType('navigation')[0];
        this.metrics.loadTime = navigation.loadEventEnd - navigation.navigationStart;
        this.metrics.domContentLoaded = navigation.domContentLoadedEventEnd - navigation.navigationStart;
    }

    getResourceTiming() {
        const resources = performance.getEntriesByType('resource');
        this.metrics.resourceCount = resources.length;
        
        // 計算資源載入時間
        const totalResourceTime = resources.reduce((total, resource) => {
            return total + (resource.responseEnd - resource.requestStart);
        }, 0);
        this.metrics.avgResourceTime = totalResourceTime / resources.length;
    }

    sendMetrics() {
        // 發送到 Google Analytics 或其他分析服務
        if (typeof gtag !== 'undefined') {
            Object.entries(this.metrics).forEach(([metric, value]) => {
                gtag('event', metric, {
                    value: Math.round(value),
                    custom_parameter: metric
                });
            });
        }

        // 或發送到自定義端點
        if (navigator.sendBeacon) {
            const data = JSON.stringify(this.metrics);
            navigator.sendBeacon('/api/performance', data);
        }
    }
}

// 初始化性能監控
new PerformanceMonitor();
