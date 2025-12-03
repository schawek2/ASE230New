<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TopSongs extends Model
{
    //module2/lecture/1_laravel/5 Slide 8

	protected $table = 'topSongs';

	protected $fillable = [
		'name',
		'artist',
		'songRank'
	];

	protected $casts = [
		'songRank' => 'integer',
	];
}
