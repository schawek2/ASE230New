<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Playlists extends Model
{
    //module2/lecture/1_laravel/5 Slide 8

	protected $fillable = [
		'usersName',
		'playlistName',
		'genre'
	];
}
