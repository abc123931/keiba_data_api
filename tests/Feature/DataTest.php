<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class DataTest extends TestCase
{
    /**
     * DataControllerのテスト
     */
    private $target;

    public function test_getHorseNamesで正常終了()
    {
        var_dump(phpversion());

        $names = factory(\App\Horse::class)->make();
        $response = $this->json('GET', '/api/horse', ['name' => 'test']);
        $response
            ->assertStatus(200)
            ->assertExactJson([
                'names' => ['test1']
            ]);

    }

    public function test_getHorseNamesでリクエストがない場合空の配列が返る()
    {
        $response = $this->json('GET', '/api/horse', ['name' => '']);
        $response
            ->assertStatus(200)
            ->assertExactJson([
                'names' => []
            ]);
    }
}
