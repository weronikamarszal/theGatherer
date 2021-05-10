<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ValueDate extends Model
{
    use HasFactory;
    public function attributeName(){
        return $this->hasOne(ObjectAttributes::class,'id','attribute_id');
    }
    public function valueObject(){
        return $this->hasOne(Objects::class,'id','object_id');
    }
}
