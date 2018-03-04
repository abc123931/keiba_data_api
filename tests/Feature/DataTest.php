<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Horse;
use App\Http\Controllers\DataController;
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
        $response = $this->json('GET', '/api/horse', ['name' => 'test']);
        $response
            ->assertStatus(200)
            ->assertExactJson([
                'names' => ['test1','test2','test3','test4','test5']
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

    /**
     * @dataProvider validationProvider
     */
    public function test_getDataGraphでバリデーションにかかる($name, $yaxis, $xaxis, $expected)
    {
        $response = $this->json('POST', '/api/data',
            [
                'names' => $name,
                'yaxis' => $yaxis,
                'xaxis' => $xaxis
            ]);
        $response
            ->assertStatus(200)
            ->assertExactJson([
                'result' => [],
                'xaxis' => [],
                'error' => [$expected]
            ]);
    }

    public function validationProvider()
    {
        return [
            ['', '縦軸', '横軸', '馬が選択されていません。'],
            ['馬', '', '横軸', '縦軸が選択されていません。'],
            ['馬', '縦軸', '', '横軸が選択されていません。']
        ];
    }
}
