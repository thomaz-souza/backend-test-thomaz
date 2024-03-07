<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Vinkla\Hashids\Facades\Hashids;

class Redirect extends Model
{
    use HasFactory;
    use SoftDeletes;

    // Define os campos a serem mostrados
    protected $fillable = [
        'status',
        'target_url',
        'last_accessed_at'
    ];

    protected $dates = [
        'last_accessed_at',
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    // Define os campos a serem ocultados
    protected $hidden = ['id'];

    // Define os campos a serem transformados
    protected $appends = ['code'];

    // Define o campo 'code' a ser gerado a partir do ID
    public function getCodeAttribute()
    {
        return Hashids::encode($this->id);
    }

    // Encontra o modelo pelo 'code'
    public static function findByCode($code)
    {
        $id = Hashids::decode($code);

        if (!empty($id)) {
            return static::find($id[0]);
        }

        return null;
    }

    public function logs()
    {
        return $this->hasMany(RedirectLog::class);
    }
}
