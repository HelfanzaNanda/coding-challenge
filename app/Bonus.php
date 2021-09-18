<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Bonus extends Model
{
    protected $guarded = [];

	public function items()
	{
		return $this->hasMany(BonusItems::class);
	}
}
