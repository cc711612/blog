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
 
        // Content Security Policy (CSP)
        $csp = [
            "default-src 'self'",
            "script-src 'self' 'unsafe-inline' 'unsafe-eval' https://www.googletagmanager.com https://www.google-analytics.com https://cdnjs.cloudflare.com https://cdn.jsdelivr.net https://static.cloudflareinsights.com https://pagead2.googlesyndication.com https://use.fontawesome.com https://ep2.adtrafficquality.google https://tpc.googlesyndication.com https://adservice.google.com https://fundingchoicesmessages.google.com",
            "style-src 'self' 'unsafe-inline' https://fonts.googleapis.com https://cdnjs.cloudflare.com https://pro.fontawesome.com",
            "font-src 'self' https://fonts.gstatic.com https://cdnjs.cloudflare.com https://pro.fontawesome.com",
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
        
        return $response;
    }
}
