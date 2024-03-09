<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class RedirectLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'redirect_id',
        'request_ip',
        'user_agent',
        'referer',
        'query_params',
        'accessed_at'
    ];

    public function redirect()
    {
        return $this->belongsTo(Redirect::class);
    }

    public static function stats()
    {
        // Subconsulta para os últimos 10 dias
        $subquery = RedirectLog::select(
            DB::raw('DATE(accessed_at) as date'),
            DB::raw('count(*) as total'),
            DB::raw('count(distinct request_ip) as `unique`')
        )
            ->where('accessed_at', '>=', now()->subDays(10))
            ->groupBy('date');


        // Total de acessos
        $totalAcessos = RedirectLog::count();

        // Total de acessos Únicos (IPs únicos)
        $totalAcessosUnicos = RedirectLog::distinct('request_ip')->count('request_ip');

        // Top Referrers (Headers referer mais comum entre os Redirecionamentos)
        $topReferrers = RedirectLog::select('referer', DB::raw('count(*) as total'))
            ->groupBy('referer')
            ->orderByDesc('total')
            ->limit(10)
            ->get();

        // Array com total de acessos dos últimos 10 dias
        $totalAcessosUltimosDias = $subquery->orderBy('date')->get();

        // Resultados
        return [
            'total_acessos' => $totalAcessos,
            'total_acessos_unicos' => $totalAcessosUnicos,
            'top_referrers' => $topReferrers,
            'total_acessos_ultimos_10_dias' => $totalAcessosUltimosDias,
        ];
    }
}
