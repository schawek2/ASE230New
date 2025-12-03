<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Album extends Model
{
    /** module2/lecture/1_Laravel/5 Slide 8
	*/

	protected $fillable = [
		'albumName',
		'artistName',
		'numOfSongs'
	];

	protected $casts = [
		'numOfSongs' => 'integer',
	];
}
