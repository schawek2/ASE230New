<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Podcasts extends Model
{
    //module2/lecture/1_laravel/5 Slide 8

	protected $fillable = [
		'name',
		'hostName',
		'totalTime'
	];

	protected $casts = [
		'totalTime' => 'integer',
	];
}
