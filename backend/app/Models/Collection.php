<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Collection extends Model
{
    use HasFactory;

    public function objects(){
        return $this->hasMany(Objects::class);
    }
    public function attributes(){
        return $this->hasMany(ObjectAttributes::class);
    }
}
