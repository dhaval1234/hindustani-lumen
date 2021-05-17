<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    protected $fillable = ['name', 'adhar_card', 'bod', 'gender','father_id','mother_id'];
    //protected $appends = ['child','mother','father'];

    public function getFatherIdAttribute($value)
    {
        $getFather = User::where('id', $value)->first();
        return $getFather;
    }

    public function getMotherIdAttribute($value)
    {
        $getMother = User::where('id', $value)->first();
        return $getMother;
    }

    
}
