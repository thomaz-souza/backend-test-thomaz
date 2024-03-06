<?php

namespace App\Http\Controllers;

use App\Models\Redirect;
use App\Http\Requests\RedirectRequest;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;

class RedirectController extends Controller
{
    //Cria um redirecionamento validando informações
    public function store(RedirectRequest $request)
    {
        $target_url = $request->validated()['target_url'];

        // Verifica se a URL de destino está disponível e é HTTPS
        $response = Http::get($target_url);

        if (!$response->getProtocolVersion() === '2') {
            return response()->json(['error' => 'A URL de destino não é HTTPS'], 400);
        }

        // Cria o redirecionamento
        $redirect = Redirect::create([
            'status' => $response->successful() ? 1 : 0,
            'target_url' => $target_url,
            'last_accessed_at' => now()
        ]);

        return response()->json($redirect, 201);
    }


    //Mostra as informações do redirecionamento
    public function show($code)
    {
        // Encontra o redirecionamento com base no código
        $redirect = Redirect::findByCode($code);

        // Se o redirecionamento não for encontrado
        if (!$redirect) {
            return response()->json(['error' => 'Redirecionamento não encontrado'], 404);
        }

        // Formatar o status para "ativo" ou "inativo"
        $status = $redirect->status ? 'Ativo' : 'Inativo';

        // Retornar os detalhes do redirect
        return response()->json([
            'Código' => $code,
            'Status' => $status,
            'URL de destino' => $redirect->target_url,
            'Último acesso' => $redirect->last_accessed_at,
            'Data de criação' => $redirect->created_at,
            'Data de atualização' => $redirect->updated_at,
        ]);
    }


    //Atualiza apenas a URL de destino e muda o status do redirecionamento
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

    //Cria uma data no campo date_delete e muda o status do redirecionamento
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
