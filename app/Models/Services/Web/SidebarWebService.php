<?php

namespace App\Models\Services\Web;

use App\Models\Entities\ArticleEntity;

class SidebarWebService
{
    /**
     * 取得最新文章
     */
    public function getRecentArticles(int $limit = 5): \Illuminate\Database\Eloquent\Collection
    {
        return ArticleEntity::with(['users'])
            ->where('status', 1)
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get(['id', 'title', 'created_at', 'user_id']);
    }

    /**
     * 取得熱門關鍵字
     */
    public function getPopularKeywords(): array
    {
        return [
            'Docker', 'Linux', 'Node.js', 'Nginx', 'Ubuntu', 
            'Laravel', 'PHP', 'MySQL', 'Redis', 'Git', 
            'JavaScript', 'Vue.js', 'AWS', 'Python', 'React'
        ];
    }
}
