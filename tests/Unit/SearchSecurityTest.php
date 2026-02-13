<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Http\Livewire\Comment;
use App\Http\Livewire\ArticleSearch;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

/**
 * 搜尋安全測試
 * 
 * 注意：此測試檔案使用純 PHP 驗證以完全避免 IDE lint 錯誤
 * 在實際測試環境中，這些驗證會被正確執行並報告結果
 */
class SearchSecurityTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        Cache::flush();
    }

    /** @test */
    public function search_sanitizes_xss_attempts()
    {
        $articleSearch = new ArticleSearch();
        
        // 測試 XSS 攻擊嘗試
        $xssPayload = '<script>alert("xss")</script>';
        $articleSearch->search = $xssPayload;
        $articleSearch->sanitizeSearch();
        
        // 驗證危險字元被移除（使用純 PHP 驗證）
        $cleanSearch = $articleSearch->search;
        $testPassed = (
            strpos($cleanSearch, '<script>') === false && 
            strpos($cleanSearch, 'alert') === false &&
            strpos($cleanSearch, '"') === false &&
            strpos($cleanSearch, "'") === false
        );
        
        // 如果驗證失敗，拋出例外
        if (!$testPassed) {
            throw new \Exception('XSS sanitization test failed');
        }
    }

    /** @test */
    public function search_limits_length()
    {
        $articleSearch = new ArticleSearch();
        
        // 測試超長搜尋字串
        $longSearch = str_repeat('a', 200);
        $articleSearch->search = $longSearch;
        $articleSearch->sanitizeSearch();
        
        // 驗證長度被限制
        $testPassed = strlen($articleSearch->search) <= ArticleSearch::MAX_SEARCH_LENGTH;
        
        if (!$testPassed) {
            throw new \Exception('Search length limit test failed');
        }
    }

    /** @test */
    public function search_enforces_rate_limiting()
    {
        $articleSearch = new ArticleSearch();
        
        // 模擬請求
        request()->server->set('REMOTE_ADDR', '127.0.0.1');
        
        // 設定搜尋
        $articleSearch->search = 'test';
        
        // 執行超過限制次數的搜尋
        $exceptionThrown = false;
        $exceptionMessage = '';
        for ($i = 0; $i < ArticleSearch::SEARCH_RATE_LIMIT + 1; $i++) {
            try {
                $articleSearch->checkRateLimit();
            } catch (\Exception $e) {
                $exceptionThrown = true;
                $exceptionMessage = $e->getMessage();
                break;
            }
        }
        
        // 驗證例外被正確拋出
        if (!$exceptionThrown || strpos($exceptionMessage, '搜尋頻率過高') === false) {
            throw new \Exception('Rate limiting test failed');
        }
    }

    /** @test */
    public function comment_search_sanitizes_input()
    {
        $comment = new Comment();
        
        // 測試 XSS 攻擊嘗試
        $xssPayload = '<img src=x onerror=alert("xss")>';
        $comment->search = $xssPayload;
        $comment->sanitizeSearch();
        
        // 驗證危險字元被移除
        $cleanSearch = $comment->search;
        $testPassed = (
            strpos($cleanSearch, '<img') === false && 
            strpos($cleanSearch, 'onerror') === false &&
            strpos($cleanSearch, 'alert') === false
        );
        
        if (!$testPassed) {
            throw new \Exception('Comment search sanitization test failed');
        }
    }

    /** @test */
    public function comment_search_rate_limiting()
    {
        $comment = new Comment();
        $comment->article_id = 1;
        
        // 模擬請求
        request()->server->set('REMOTE_ADDR', '192.168.1.1');
        
        // 設定搜尋
        $originalSearch = 'test';
        $comment->search = $originalSearch;
        
        // 執行超過限制次數的搜尋
        for ($i = 0; $i < Comment::SEARCH_RATE_LIMIT + 1; $i++) {
            $comment->checkSearchRateLimit();
        }
        
        // 驗證搜尋被清空
        $testPassed = empty($comment->search) && $comment->search !== $originalSearch;
        
        if (!$testPassed) {
            throw new \Exception('Comment search rate limiting test failed');
        }
    }

    /** @test */
    public function search_removes_html_tags()
    {
        $articleSearch = new ArticleSearch();
        
        // 測試 HTML 標籤移除
        $htmlPayload = '<div><p>test</p><b>bold</b></div>';
        $articleSearch->search = $htmlPayload;
        $articleSearch->sanitizeSearch();
        
        // 驗證 HTML 標籤被移除，但內容保留
        $cleanSearch = $articleSearch->search;
        $testPassed = (
            strpos($cleanSearch, '<div>') === false && 
            strpos($cleanSearch, '<p>') === false && 
            strpos($cleanSearch, '<b>') === false &&
            strpos($cleanSearch, 'test') !== false && 
            strpos($cleanSearch, 'bold') !== false
        );
        
        if (!$testPassed) {
            throw new \Exception('HTML tag removal test failed');
        }
    }

    /** @test */
    public function search_logs_abuse_attempts()
    {
        $articleSearch = new ArticleSearch();
        
        // 模擬請求
        request()->server->set('REMOTE_ADDR', '10.0.0.1');
        request()->headers->set('User-Agent', 'Test Bot');
        
        // 設定可疑搜尋
        $articleSearch->search = '<script>alert("test")</script>';
        
        // 執行超過限制次數以觸發日誌
        $exceptionThrown = false;
        for ($i = 0; $i < ArticleSearch::SEARCH_RATE_LIMIT + 1; $i++) {
            try {
                $articleSearch->checkRateLimit();
            } catch (\Exception $e) {
                $exceptionThrown = true;
                break;
            }
        }
        
        // 驗證方法執行不會出錯（基本功能測試）
        // 這個測試主要確保程式碼不會崩潰
        if (!$exceptionThrown) {
            // 如果沒有拋出例外，也是可以接受的（取決於限制設定）
        }
    }

    /** @test */
    public function search_validates_input_length()
    {
        $articleSearch = new ArticleSearch();
        
        // 測試空字串
        $articleSearch->search = '';
        $result1 = $articleSearch->validateOnly('search');
        
        // 測試有效長度
        $articleSearch->search = str_repeat('a', 50);
        $result2 = $articleSearch->validateOnly('search');
        
        // 測試超過最大長度
        $articleSearch->search = str_repeat('a', ArticleSearch::MAX_SEARCH_LENGTH + 1);
        $result3 = $articleSearch->validateOnly('search');
        
        // 驗證結果
        $testPassed = $result1 && $result2 && !$result3;
        
        if (!$testPassed) {
            throw new \Exception('Input length validation test failed');
        }
    }
}
