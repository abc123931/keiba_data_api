<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Horse extends Model
{
    protected $table = 'horse';

    ////////////////////////////////////////////////////////////////////////////
	// scope
	////////////////////////////////////////////////////////////////////////////
    public function scopePlaceOrderByGoal($query, $horse_id) {
        return $query->leftJoin('result', 'horse.horse_id', '=', 'result.horse_id')
                     ->leftJoin('race', 'result.race_id', '=', 'race.race_id')
                     ->leftJoin('place', 'race.race_place', '=', 'place.place_id')
                     ->selectRaw('avg(result.goal::integer) as yaxis, place.place_name as xaxis, place.place_id as sort_order')
                     ->groupBy('place.place_id')
                     ->groupBy('place.place_name')
                     ->orderBy('place.place_id', 'asc')
                     ->where('horse.horse_id', $horse_id)
                     ->where('place.place_id', "!=", null);
    }

    public function scopeBabaOrderByGoal($query, $horse_id) {
        return $query->leftJoin('result', 'horse.horse_id', '=', 'result.horse_id')
                     ->leftJoin('race', 'result.race_id', '=', 'race.race_id')
                     ->leftJoin('baba', 'race.race_baba', '=', 'baba.baba_name')
                     ->selectRaw('avg(result.goal::integer) as yaxis, baba.baba_name as xaxis, baba.baba_id as sort_order')
                     ->groupBy('baba.baba_id')
                     ->groupBy('baba.baba_name')
                     ->orderBy('baba.baba_id', 'asc')
                     ->where('horse.horse_id', $horse_id)
                     ->where('baba.baba_id', "!=", null);
    }

    public function scopeDistanceOrderByGoal($query, $horse_id) {
        return $query->leftJoin('result', 'horse.horse_id', '=', 'result.horse_id')
                     ->leftJoin('race', 'result.race_id', '=', 'race.race_id')
                     ->selectRaw('avg(result.goal::integer) as yaxis, race.race_distance as xaxis, race.race_distance as sort_order')
                     ->groupBy('race.race_distance')
                     ->orderBy('race.race_distance', 'asc')
                     ->where('horse.horse_id', $horse_id)
                     ->where('race.race_distance', "!=", null);
    }
}
