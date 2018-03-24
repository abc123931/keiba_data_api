<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Horse;
use App\Http\Controllers\DataController;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class HorseControllerTest extends TestCase
{
    /**
     * data\HorseControllerのテスト
     */
    public function test_getHorseNameで正常終了()
    {
        $response = $this->json('GET', '/api/data/horse', ['name' => 'とに']);
        $response
            ->assertStatus(200)
            ->assertExactJson([
                'names' => ['エアブリトニー','トニーゴールド','アルトニベル','レガシーオブトニー','トニーズメモリイ']
            ]);

    }

    public function test_getHorseNameでリクエストがない場合空の配列が返る()
    {
        $response = $this->json('GET', '/api/data/horse', ['name' => '']);
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
        $response = $this->json('POST', '/api/data/graph/horse',
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
        $response = $this->json('POST', '/api/data/graph/horse',
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
        $response = $this->json('POST', '/api/data/graph/horse',
            [
                'names' => ["エアブリトニー"],
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
        $response = $this->json('POST', '/api/data/graph/horse',
            [
                'names' => ["ハングインゼア"],
                'yaxis' => 'racerank',
                'xaxis' => 'racecourse'
            ]);
        $response
            ->assertStatus(200)
            ->assertExactJson([
                'result' => [
                    "ハングインゼア" => [
                        [
                            "yaxis" => "10.0000000000000000",
                            "xaxis" => "札幌",
                            "sort_order" => "01"
                        ],
                        [
                            "yaxis" => "7.0000000000000000",
                            "xaxis" => "福島",
                            "sort_order" => "03"
                        ],
                        [
                            "yaxis" => "6.3333333333333333",
                            "xaxis" => "中京",
                            "sort_order" => "07"
                        ],
                        [
                            "yaxis" => "8.0000000000000000",
                            "xaxis" => "京都",
                            "sort_order" => "08"
                        ]
                    ]
                ],
                'xaxis' => ["札幌","福島","中京","京都"],
                'error' => []
            ]);
    }

    public function test_getDataGraph_xaxisがracebabaでyaxisがracerankの場合()
    {
        $response = $this->json('POST', '/api/data/graph/horse',
            [
                'names' => ["ハングインゼア"],
                'yaxis' => 'racerank',
                'xaxis' => 'racebaba'
            ]);
        $response
            ->assertStatus(200)
            ->assertExactJson([
                'result' => [
                    "ハングインゼア" => [
                        [
                            "yaxis" => "8.5000000000000000",
                            "xaxis" => "良",
                            "sort_order" => "1"
                        ],
                        [
                            "yaxis" => "5.3333333333333333",
                            "xaxis" => "稍重",
                            "sort_order" => "2"
                        ],
                        [
                            "yaxis" => "9.5000000000000000",
                            "xaxis" => "重",
                            "sort_order" => "3"
                        ]
                    ]
                ],
                'xaxis' => ["良", "稍重", "重"],
                'error' => []
            ]);
    }

    public function test_getDataGraph_xaxisがracedistanceでyaxisがracerankの場合()
    {
        $response = $this->json('POST', '/api/data/graph/horse',
            [
                'names' => ["ハングインゼア"],
                'yaxis' => 'racerank',
                'xaxis' => 'racedistance'
            ]);
        $response
            ->assertStatus(200)
            ->assertExactJson([
                'result' => [
                    "ハングインゼア" => [
                        [
                            "yaxis" => "9.0000000000000000",
                            "xaxis" => 1000,
                            "sort_order" => 1000
                        ],
                        [
                            "yaxis" => "6.8000000000000000",
                            "xaxis" => 1200,
                            "sort_order" => 1200
                        ],
                        [
                            "yaxis" => "8.5000000000000000",
                            "xaxis" => 1400,
                            "sort_order" => 1400
                        ],
                        [
                            "yaxis" => "9.5000000000000000",
                            "xaxis" => 1700,
                            "sort_order" => 1700
                        ],
                        [
                            "yaxis" => "7.0000000000000000",
                            "xaxis" => 1800,
                            "sort_order" => 1800
                        ]
                    ]
                ],
                'xaxis' => [1000, 1200, 1400, 1700, 1800],
                'error' => []
            ]);
    }
}
