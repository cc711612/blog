<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Http\Livewire\Comment;
use App\Http\Livewire\ArticleLike;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Cookie;

/**
 * Livewire 元件測試
 * 
 * 注意：此測試檔案使用純 PHP 驗證以避免 IDE lint 錯誤
 * 在實際測試環境中，這些驗證會被正確執行並報告結果
 */
class LivewireComponentsTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        Cache::flush();
    }

    /** @test */
    public function comment_user_can_vote_up()
    {
        $comment = new Comment();
        $commentId = 1;
        
        // 模擬請求
        request()->server->set('REMOTE_ADDR', '127.0.0.1');
        request()->headers->set('User-Agent', 'Test Agent');
        
        // 執行投票
        $comment->voteComment($commentId, 'up');
        
        // 驗證投票結果
        $votes = $comment->getCommentVotes($commentId);
        
        $testPassed = (
            $votes['upvotes'] === 1 &&
            $votes['downvotes'] === 0 &&
            $votes['user_vote'] === 'up' &&
            $votes['total'] === 1
        );
        
        if (!$testPassed) {
            throw new \Exception('Comment vote up test failed');
        }
    }

    /** @test */
    public function comment_user_can_vote_down()
    {
        $comment = new Comment();
        $commentId = 1;
        
        // 模擬請求
        request()->server->set('REMOTE_ADDR', '127.0.0.1');
        request()->headers->set('User-Agent', 'Test Agent');
        
        // 執行投票
        $comment->voteComment($commentId, 'down');
        
        // 驗證投票結果
        $votes = $comment->getCommentVotes($commentId);
        
        $testPassed = (
            $votes['upvotes'] === 0 &&
            $votes['downvotes'] === 1 &&
            $votes['user_vote'] === 'down' &&
            $votes['total'] === -1
        );
        
        if (!$testPassed) {
            throw new \Exception('Comment vote down test failed');
        }
    }

    /** @test */
    public function comment_user_can_cancel_vote()
    {
        $comment = new Comment();
        $commentId = 1;
        
        // 模擬請求
        request()->server->set('REMOTE_ADDR', '127.0.0.1');
        request()->headers->set('User-Agent', 'Test Agent');
        
        // 先投票
        $comment->voteComment($commentId, 'up');
        $votes = $comment->getCommentVotes($commentId);
        $initialTotal = $votes['total'];
        
        // 取消投票
        $comment->voteComment($commentId, 'up');
        $votes = $comment->getCommentVotes($commentId);
        
        $testPassed = (
            $initialTotal === 1 &&
            $votes['total'] === 0 &&
            $votes['user_vote'] === null
        );
        
        if (!$testPassed) {
            throw new \Exception('Comment cancel vote test failed');
        }
    }

    /** @test */
    public function comment_invalid_vote_type_throws_exception()
    {
        $exceptionThrown = false;
        $exceptionMessage = '';
        
        try {
            $comment = new Comment();
            $comment->voteComment(1, 'invalid');
        } catch (\InvalidArgumentException $e) {
            $exceptionThrown = true;
            $exceptionMessage = $e->getMessage();
        }
        
        if (!$exceptionThrown || strpos($exceptionMessage, 'Invalid vote type') === false) {
            throw new \Exception('Comment invalid vote type test failed');
        }
    }

    /** @test */
    public function article_user_can_like()
    {
        $articleLike = new ArticleLike();
        $articleId = 1;
        
        // 模擬請求
        request()->server->set('REMOTE_ADDR', '127.0.0.1');
        request()->headers->set('User-Agent', 'Test Agent');
        
        // 設定文章 ID
        $articleLike->articleId = $articleId;
        $articleLike->loadLikeStatus();
        
        // 初始狀態
        $initialLikeCount = $articleLike->likeCount;
        $initialIsLiked = $articleLike->isLiked;
        
        // 執行按讚
        $articleLike->toggleLike();
        
        // 驗證結果
        $testPassed = (
            $initialLikeCount === 0 &&
            $initialIsLiked === false &&
            $articleLike->likeCount === 1 &&
            $articleLike->isLiked === true
        );
        
        if (!$testPassed) {
            throw new \Exception('Article like test failed');
        }
    }

    /** @test */
    public function article_user_can_unlike()
    {
        $articleLike = new ArticleLike();
        $articleId = 1;
        
        // 模擬請求
        request()->server->set('REMOTE_ADDR', '127.0.0.1');
        request()->headers->set('User-Agent', 'Test Agent');
        
        // 設定文章 ID
        $articleLike->articleId = $articleId;
        $articleLike->loadLikeStatus();
        
        // 先按讚
        $articleLike->toggleLike();
        $likedCount = $articleLike->likeCount;
        $likedState = $articleLike->isLiked;
        
        // 取消讚
        $articleLike->toggleLike();
        
        // 驗證結果
        $testPassed = (
            $likedCount === 1 &&
            $likedState === true &&
            $articleLike->likeCount === 0 &&
            $articleLike->isLiked === false
        );
        
        if (!$testPassed) {
            throw new \Exception('Article unlike test failed');
        }
    }

    /** @test */
    public function comment_multiple_users_can_vote()
    {
        $comment = new Comment();
        $commentId = 1;
        
        // 第一個用戶投票
        request()->server->set('REMOTE_ADDR', '127.0.0.1');
        request()->headers->set('User-Agent', 'User 1');
        $comment->voteComment($commentId, 'up');
        
        // 第二個用戶投票
        request()->server->set('REMOTE_ADDR', '192.168.1.1');
        request()->headers->set('User-Agent', 'User 2');
        $comment->voteComment($commentId, 'up');
        
        // 驗證結果
        $votes = $comment->getCommentVotes($commentId);
        
        $testPassed = (
            $votes['upvotes'] === 2 &&
            $votes['total'] === 2
        );
        
        if (!$testPassed) {
            throw new \Exception('Comment multiple users vote test failed');
        }
    }

    /** @test */
    public function comment_user_can_change_vote()
    {
        $comment = new Comment();
        $commentId = 1;
        
        // 模擬請求
        request()->server->set('REMOTE_ADDR', '127.0.0.1');
        request()->headers->set('User-Agent', 'Test Agent');
        
        // 先按讚
        $comment->voteComment($commentId, 'up');
        $votes = $comment->getCommentVotes($commentId);
        $upVoteTotal = $votes['total'];
        $upVoteUser = $votes['user_vote'];
        
        // 改為倒讚
        $comment->voteComment($commentId, 'down');
        $votes = $comment->getCommentVotes($commentId);
        $downVoteTotal = $votes['total'];
        $downVoteUser = $votes['user_vote'];
        
        // 驗證結果
        $testPassed = (
            $upVoteTotal === 1 &&
            $upVoteUser === 'up' &&
            $downVoteTotal === -1 &&
            $downVoteUser === 'down'
        );
        
        if (!$testPassed) {
            throw new \Exception('Comment change vote test failed');
        }
    }
}
