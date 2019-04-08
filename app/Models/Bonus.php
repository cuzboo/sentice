<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;

class Bonus extends Model
{
	protected $dates = ['created_at', 'updated_at'];

    public function customer(){

    	return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
