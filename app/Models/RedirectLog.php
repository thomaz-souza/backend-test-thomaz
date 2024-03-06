<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RedirectLog extends Model
{
    protected $fillable = ['redirect_id', 'request_ip', 'user_agent', 'referer', 'query_params', 'accessed_at'];

    public function redirect()
    {
        return $this->belongsTo(Redirect::class);
    }
}