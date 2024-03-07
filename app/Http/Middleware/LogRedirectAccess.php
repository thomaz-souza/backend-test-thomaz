<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Redirect;
use App\Models\RedirectLog;

class LogRedirectAccess
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $code = explode('/', $request->getPathInfo())[3];
        $id_redirect = Redirect::findByCode($code)['id'];

        RedirectLog::create([
            'redirect_id' => $id_redirect,
            'request_ip' => $request->ip(),
            'user_agent' => $request->header('User-Agent'),
            'referer' => $request->header('Referer'),
            'query_params' => json_encode($request->query()),
            'accessed_at' => now()
        ]);

        return $next($request);
    }
}
