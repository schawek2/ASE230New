<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LiveEvents extends Model
{
    //module2/lecture/1_laravel/5 Slide 8

	protected $table = 'liveEvents';

	protected $fillable = [
		'venue',
		'artist',
		'day'
	];
}
