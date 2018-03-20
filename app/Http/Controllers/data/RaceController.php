<?php

namespace App\Http\Controllers\data;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\RaceName;
use Illuminate\Support\Facades\Log;

class RaceController extends Controller
{
    // レース名検索
    public function getRaceName(Request $request)
    {
        $names = [];
        if (!empty($request->name)) {
            $names = RaceName::where("race_name", "like", "%$request->name%")->take(5)->pluck('race_name');
            // 漢字表記で見つけられなかった場合よみから探す
            if (!isset($names[0])) {
                // 漢字が含まれなければ検索
                if (!preg_match("/([\x{3005}\x{3007}\x{303b}\x{3400}-\x{9FFF}\x{F900}-\x{FAFF}\x{20000}-\x{2FFFF}])(.*|)/u", $request->name)) {
                    $name = mb_convert_kana($request->name, "C", "UTF-8");
                    $names = RaceName::where("race_name_yomi", "like", "%$name%")->take(5)->pluck('race_name');
                }
            }
        }

        return response()->json([
            'names' => $names
        ]);
    }
}
