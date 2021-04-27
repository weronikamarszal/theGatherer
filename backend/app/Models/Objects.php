<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Objects extends Model
{
    use HasFactory;
    public function valueInts(){
        return $this->hasMany(ValueInt::class,'object_id');
    }
    public function valueFloats(){
        return $this->hasMany(ValueFloat::class,'object_id');
    }
    public function valueStrings(){
        return $this->hasMany(ValueString::class,'object_id');
    }
    public function valueDates(){
        return $this->hasMany(ValueDate::class,'object_id');
    }
}
