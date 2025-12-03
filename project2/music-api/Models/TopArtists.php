<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TopArtists extends Model
{
    //module2/lecture/1_laravel/5 Slide 8

	protected $table = 'topArtists';

	protected $fillable = [
		'name',
		'genre',
		'artistRank'
	];

	protected $casts = [
		'artistRank' => 'integer',
	];
}
