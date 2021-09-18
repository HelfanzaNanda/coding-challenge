<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BonusItems extends Model
{
    protected $guarded = [];

	public function bonus()
	{
		return $this->belongsTo(Bonus::class);
	}
}
