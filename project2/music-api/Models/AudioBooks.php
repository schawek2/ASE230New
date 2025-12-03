<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AudioBooks extends Model
{
    //module2/lecture/1_laravel/5 Slide 8

	protected $table = 'audiobooks';

	protected $fillable = [
		'bookName',
		'writerName',
		'readerName'
	];
}
