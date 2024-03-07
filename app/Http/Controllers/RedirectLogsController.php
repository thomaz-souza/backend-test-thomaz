<?php

namespace App\Http\Controllers;

use App\Models\Redirect;
use App\Models\RedirectLog;

class RedirectLogsController extends Controller
{
    // ***********************************************Mostra as informações do redirecionamento**************************************************
    public function showLogs($code)
    {
        // Encontra o redirecionamento com base no código
        $redirect = Redirect::findByCode($code)['id'];
        $redirectLogs = RedirectLog::where('redirect_id', $redirect)
            ->get();

        // Se o redirecionamento não for encontrado
        if (!$redirect || !$redirectLogs) {
            return response()->json(['error' => 'Redirecionamento não encontrado ou não há logs ainda registrados'], 404);
        }

        return response()->json($redirectLogs);
    }


    // ***********************************************Mostra as estatísticas de acesso*********************************************************
    public function showStats($code)
    {
        //Chama no model a query que realiza as estatísticas de acesso
        $stats = RedirectLog::stats();

        return response()->json($stats);
    }
}
