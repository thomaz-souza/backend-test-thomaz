<?php

namespace App\Http\Controllers;

use App\Models\Redirect;
use App\Http\Requests\RedirectRequest;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class RedirectController extends Controller
{
    // ***********************************************Mostra todas as redirects cadastradas****************************************************
    public function index()
    {
        // Recupera todos os redirecionamentos do banco de dados
        $redirects = Redirect::all();

        return response()->json($redirects);
    }



    // ***********************************************Cria um Redirecionamento validando informações********************************************


    public function store(RedirectRequest $request)
    {
        $target_url = $request->validated()['target_url'];

        // Cria o redirecionamento
        $redirect = Redirect::create([
            'status' => 1,
            'target_url' => $target_url,
            'last_accessed_at' => now()
        ]);

        return response()->json($redirect, 201);
    }



    // ***********************************************Mostra as informações do redirecionamento*************************************************
    public function show(Request $request, $code)
    {
        //Query params da request
        $query_params = http_build_query($request->query());

        // Encontra o redirecionamento com base no código
        $redirect = Redirect::findByCode($code);

        // Se o redirecionamento não for encontrado
        if (!$redirect) {
            return response()->json(['error' => 'Redirecionamento não encontrado ou não está acessível'], 404);
        }

        // Se não tiver parametros na query
        if (!$query_params) {
            return redirect($redirect->target_url);
        }

        return redirect($redirect->target_url . "?" . $query_params);
    }



    // ***********************************************Atualiza apenas a URL de destino e muda o status do redirecionamento**********************
    public function update(RedirectRequest $request, $code)
    {
        $validatedData = $request->validated();

        // Encontra o redirecionamento com base no código
        $redirect = Redirect::findByCode($code);

        if (!$redirect) {
            // Se o redirecionamento não for encontrado
            return response()->json(['error' => 'Redirect não encontrado'], 404);
        }

        // Atualiza os dados do redirecionamento
        $redirect->status = $validatedData['status'];
        $redirect->target_url = $validatedData['target_url'];
        $redirect->save();

        // Retorna uma resposta de sucesso
        return response()->json(['message' => 'Redirecionamento atualizado com sucesso.']);
    }



    // ***********************************************Cria uma data no campo date_delete e muda o status do redirecionamento********************
    public function destroy($code)
    {
        // Encontra o redirecionamento com base no código
        $redirect = Redirect::findByCode($code);

        if (!$redirect) {
            // Se o redirecionamento não for encontrado
            return response()->json(['error' => 'Redirect não encontrado'], 404);
        }

        // Desativa o redirect (define o status como false)
        $redirect->status = false;

        // Soft delete do redirect
        $redirect->delete();

        // Retorna uma resposta de sucesso
        return response()->json(['message' => 'Redirecionamento deletado com sucesso.']);
    }
}
