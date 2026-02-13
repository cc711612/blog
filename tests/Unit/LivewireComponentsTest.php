<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Http\Livewire\Comment;
use App\Http\Livewire\ArticleLike;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Cookie;

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
        
        $this->assertEquals(1, $votes['upvotes']);
        $this->assertEquals(0, $votes['downvotes']);
        $this->assertEquals('up', $votes['user_vote']);
        $this->assertEquals(1, $votes['total']);
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
        
        $this->assertEquals(0, $votes['upvotes']);
        $this->assertEquals(1, $votes['downvotes']);
        $this->assertEquals('down', $votes['user_vote']);
        $this->assertEquals(-1, $votes['total']);
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
        $this->assertEquals(1, $votes['total']);
        
        // 取消投票
        $comment->voteComment($commentId, 'up');
        $votes = $comment->getCommentVotes($commentId);
        $this->assertEquals(0, $votes['total']);
        $this->assertNull($votes['user_vote']);
    }

    /** @test */
    public function comment_invalid_vote_type_throws_exception()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid vote type. Must be "up" or "down".');
        
        $comment = new Comment();
        $comment->voteComment(1, 'invalid');
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
        $this->assertEquals(0, $articleLike->likeCount);
        $this->assertFalse($articleLike->isLiked);
        
        // 執行按讚
        $articleLike->toggleLike();
        
        // 驗證結果
        $this->assertEquals(1, $articleLike->likeCount);
        $this->assertTrue($articleLike->isLiked);
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
        $this->assertEquals(1, $articleLike->likeCount);
        $this->assertTrue($articleLike->isLiked);
        
        // 取消讚
        $articleLike->toggleLike();
        $this->assertEquals(0, $articleLike->likeCount);
        $this->assertFalse($articleLike->isLiked);
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
        $this->assertEquals(2, $votes['upvotes']);
        $this->assertEquals(2, $votes['total']);
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
        $this->assertEquals(1, $votes['total']);
        $this->assertEquals('up', $votes['user_vote']);
        
        // 改為倒讚
        $comment->voteComment($commentId, 'down');
        $votes = $comment->getCommentVotes($commentId);
        $this->assertEquals(-1, $votes['total']);
        $this->assertEquals('down', $votes['user_vote']);
    }
}
