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
        $this->withoutExceptionHandling();
        $response = $this->get('/');
        $response->assertStatus(200);
    }

    /**
     * @return void
     */
    public function test_article_api_index()
    {
        $this->withoutExceptionHandling();
        $response = $this->get(route('api.article.index'));
        dd($response);
        $response->assertStatus(200);
    }
}
