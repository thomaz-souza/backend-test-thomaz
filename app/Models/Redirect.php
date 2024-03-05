<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Vinkla\Hashids\Facades\Hashids;

class Redirect extends Model
{
    protected $fillable = ['url', 'code'];

    public function setHashIdAttribute($id)
    {
        $this->attributes['code'] = Hashids::connection('main')->encode($id);
    }

    public function getCodeAttribute()
    {
        return Hashids::connection('main')->decode($this->attributes['code'])[0];
    }
}
