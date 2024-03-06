<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Redirect extends Model
{
    use SoftDeletes;

    protected $fillable = ['status', 'target_url', 'last_accessed_at'];

    public function logs()
    {
        return $this->hasMany(RedirectLog::class);
    }
}
