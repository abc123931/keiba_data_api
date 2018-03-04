<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Horse;
use App\Race;
use App\Result;
use Illuminate\Support\Facades\Log;
use App\Http\Validator\GetDataGraphValidator;

class DataController extends Controller
{
    // 馬名検索機能
    public function getHorseName(Request $request)
    {
        if (!empty($request->name)) {
            $name = mb_convert_kana($request->name, "C", "UTF-8");
            $names = Horse::where("name", "like", "%$name%")->take(5)->pluck('name');
        } else {
            $names = array();
        }
        return response()->json([
            'names' => $names
        ]);
    }

    // グラフに描画するデータ取得
    public function getDataGraph(Request $request)
    {
        $result = array();
        $xaxis = array();
        $errors = GetDataGraphValidator::validate($request);

        // validatorにひっかからなかったら
        if (empty($errors)) {
            $names = $request->names;
            for ($i = 0; $i < count($names); $i++) {
                // ひらがなをカタカナに変換
                $name = mb_convert_kana($names[$i], "C", "UTF-8");
                // 対象のhorse_idを取得
                $horse_id = Horse::where('name', $name)->value('horse_id');

                if (!empty($horse_id)) {
                    if ($request->xaxis === 'racecourse') {
                        // x,y軸の値とsortの基準となる数値を取得
                        $result[$name] = Horse::placeOrderByGoal($horse_id)->get();
                    }

                    if ($request->xaxis === 'racebaba') {
                        $result[$name] = Horse::babaOrderByGoal($horse_id)->get();
                    }
                    /*
                     * sortの番号とx軸の値の配列をつくる
                     * 例:
                     * array (
                     *    '05' => '東京',
                     *    '06' => '中山',
                     *    '08' => '京都',
                     *  )
                     */
                    for ($j = 0; $j < count($result[$name]); $j++) {
                        $xaxis[$result[$name][$j]["sort_order"]] = $result[$name][$j]["xaxis"];
                    }

                    // sort_orderでソートする
                    ksort($xaxis);
                } else {
                    $errors[] = "馬名「" . $names[$i] . "」は見つかりませんでした。";
                    $result[$name] = [];
                }
            }
        }

        return response()->json([
            "result" => $result,
            "xaxis"  => array_values($xaxis),
            "error"  => $errors
        ]);
    }

}
