<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class SecurityHeaders
{
    /**
     * 處理傳入的請求
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);
 
        // Content Security Policy (CSP) - 移除 cdnjs，改用 jsdelivr
        $csp = [
            "default-src 'self'",
            "script-src 'self' 'unsafe-inline' 'unsafe-eval' https://www.googletagmanager.com https://www.google-analytics.com https://cdn.jsdelivr.net https://static.cloudflareinsights.com https://pagead2.googlesyndication.com https://ep2.adtrafficquality.google https://tpc.googlesyndication.com https://adservice.google.com https://fundingchoicesmessages.google.com",
            "style-src 'self' 'unsafe-inline' https://fonts.googleapis.com https://cdn.jsdelivr.net",
            "font-src 'self' https://fonts.gstatic.com https://cdn.jsdelivr.net",
            "img-src 'self' data: https: blob: https://usongrat.s3.ap-northeast-1.amazonaws.com",
            "connect-src 'self' https://www.google-analytics.com https://cdn.jsdelivr.net https://ep1.adtrafficquality.google https://ep2.adtrafficquality.google https://googleads.g.doubleclick.net https://pagead2.googlesyndication.com https://www.google.com wss: ws:",
            "frame-src 'self' https://googleads.g.doubleclick.net https://tpc.googlesyndication.com https://ep2.adtrafficquality.google https://www.google.com",
            "object-src 'none'",
            "base-uri 'self'",
            "form-action 'self'",
            "frame-ancestors 'none'",
        ];
        
        // 只在生產環境啟用 upgrade-insecure-requests
        if (app()->environment('production')) {
            $csp[] = "upgrade-insecure-requests";
            $response->headers->set('Strict-Transport-Security', 'max-age=31536000; includeSubDomains; preload');
        }
        
        $response->headers->set('Content-Security-Policy', implode('; ', $csp));
        
        // Cross-Origin Embedder Policy (COOP)
        $response->headers->set('Cross-Origin-Opener-Policy', 'same-origin');
 
        // Cross-Origin Embedder Policy (COEP)
        // 使用 unsafe-none 避免封鎖廣告 iframe（doubleclick.net 等）
        $response->headers->set('Cross-Origin-Embedder-Policy', 'unsafe-none');
 
        // X-Frame-Options
        $response->headers->set('X-Frame-Options', 'DENY');
        
        // X-Content-Type-Options
        $response->headers->set('X-Content-Type-Options', 'nosniff');
        
        // Referrer Policy
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');
        
        // Permissions Policy
        $permissions = [
            'camera=()',
            'microphone=()',
            'geolocation=()',
            'payment=()',
            'usb=()',
            'magnetometer=()',
            'gyroscope=()',
            'accelerometer=()'
        ];
        
        $response->headers->set('Permissions-Policy', implode(', ', $permissions));

        // no-cache 允許 bf-cache；Laravel Session 預設 no-store 會阻擋
        if ($request->isMethod('GET') && !$request->expectsJson()
            && !$request->is('api/*') && !$request->is('livewire/*')) {
            $response->headers->set('Cache-Control', 'no-cache, private');
        }
        
        return $response;
    }
}
