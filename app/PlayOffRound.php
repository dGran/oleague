<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PlayOffRound extends Model
{
	public $timestamps = false;
	protected $table = 'playoffs_rounds';

    protected $fillable = ['playoff_id', 'name', 'round_trip', 'double_value', 'date_limit', 'play_amount', 'win_amount', 'lose_amount'];

    public function playoff()
    {
        return $this->hasOne('App\PlayOff', 'id', 'playoff_id');
    }

    public function clashes()
    {
        return $this->hasMany('App\PlayOffRoundClash', 'round_id', 'id');
    }
}