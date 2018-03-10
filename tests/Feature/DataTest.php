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

    public function test_getDataGraph_登録されていない馬名の場合()
    {
        $response = $this->json('POST', '/api/data',
            [
                'names' => ["てすと"],
                'yaxis' => 'y',
                'xaxis' => 'x'
            ]);
        $response
            ->assertStatus(200)
            ->assertExactJson([
                'result' => ["テスト" => []],
                'xaxis' => [],
                'error' => ["馬名「てすと」は見つかりませんでした。"]
            ]);

    }

    public function test_getDataGraph_xaxisが不正な場合()
    {
        $response = $this->json('POST', '/api/data',
            [
                'names' => ["test1"],
                'yaxis' => 'y',
                'xaxis' => 'x'
            ]);
        $response
            ->assertStatus(200)
            ->assertExactJson([
                'result' => [],
                'xaxis' => [],
                'error' => ["x軸が正しくありません。"]
            ]);

    }

    public function test_getDataGraph_xaxisがracecourseでyaxisがracerankの場合()
    {
        $response = $this->json('POST', '/api/data',
            [
                'names' => ["トラストセレビー"],
                'yaxis' => 'racerank',
                'xaxis' => 'racecourse'
            ]);
        $response
            ->assertStatus(200)
            ->assertExactJson([
                'result' => [
                    "トラストセレビー" => [
                        [
                            "yaxis" => "14.5000000000000000",
                            "xaxis" => "東京",
                            "sort_order" => "05"
                        ],
                        [
                            "yaxis" => "15.0000000000000000",
                            "xaxis" => "中山",
                            "sort_order" => "06"
                        ],
                        [
                            "yaxis" => "13.0000000000000000",
                            "xaxis" => "京都",
                            "sort_order" => "08"
                        ]
                    ]
                ],
                'xaxis' => ["東京", "中山", "京都"],
                'error' => []
            ]);
    }

    public function test_getDataGraph_xaxisがracebabaでyaxisがracerankの場合()
    {
        $response = $this->json('POST', '/api/data',
            [
                'names' => ["トラストセレビー"],
                'yaxis' => 'racerank',
                'xaxis' => 'racebaba'
            ]);
        $response
            ->assertStatus(200)
            ->assertExactJson([
                'result' => [
                    "トラストセレビー" => [
                        [
                            "yaxis" => "14.6666666666666667",
                            "xaxis" => "良",
                            "sort_order" => "1"
                        ],
                        [
                            "yaxis" => "13.0000000000000000",
                            "xaxis" => "重",
                            "sort_order" => "3"
                        ]
                    ]
                ],
                'xaxis' => ["良", "重"],
                'error' => []
            ]);
    }
}
