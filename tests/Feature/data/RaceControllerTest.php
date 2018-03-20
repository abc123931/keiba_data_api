<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class RaceControllerTest extends TestCase
{
    /**
     * data\RaceControllerのテスト
     */
    public function test_getRaceNameで正常終了()
    {
        $response = $this->json('GET', '/api/data/race', ['name' => '有馬']);
        $response
            ->assertStatus(200)
            ->assertExactJson([
                'names' => ["有馬記念"]
            ]);
    }

    public function test_getRaceNameでリクエストがない場合空の配列が返る()
    {
        $response = $this->json('GET', '/api/data/race', ['name' => '']);
        $response
            ->assertStatus(200)
            ->assertExactJson([
                'names' => []
            ]);
    }

    public function test_getRaceNameで漢字じゃない場合読みから探す()
    {
        $response = $this->json('GET', '/api/data/race', ['name' => 'ありま']);
        $response
            ->assertStatus(200)
            ->assertExactJson([
                'names' => ["有馬記念"]
            ]);
    }

    public function test_getRaceNameで漢字とひらがなの場合空を返す()
    {
        $response = $this->json('GET', '/api/data/race', ['name' => '有ま']);
        $response
            ->assertStatus(200)
            ->assertExactJson([
                'names' => []
            ]);
    }
}
