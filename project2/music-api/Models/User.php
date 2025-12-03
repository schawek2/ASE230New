<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Model;

class User extends Model
{
	/** From module2/lecture/1_Laravel/5 Slide 8
	*/
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

	protected $hidden = [
		'password',
	];

}
