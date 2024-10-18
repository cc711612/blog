<?php

namespace Tests\Unit;

use Tests\TestCase;

class ArticleTest extends TestCase
{
    /**
     * @return void
     */
    public function test_article_index()
    {
        $response = $this->get('/');
        $response->assertStatus(200);
    }

    /**
     * @return void
     */
    public function test_article_api_index()
    {
        $response = $this->get(route('api.article.index'));
        $response->assertStatus(200);
    }
}
