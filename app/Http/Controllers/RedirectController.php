<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Redirect;
use App\Http\Requests\RedirectRequest;

class RedirectController extends Controller
{
    //Mostra as informações do redirecionamento
    public function show($id)
    {
        // Buscar o redirect pelo ID
        $redirect = Redirect::findOrFail($id);

        // Formatar o status para "ativo" ou "inativo"
        $status = $redirect->status ? 'Ativo' : 'Inativo';

        // Retornar os detalhes do redirect
        return response()->json([
            'Código' => $redirect->id,
            'Status' => $status,
            'URL de destino' => $redirect->target_url,
            'Último acesso' => $redirect->last_accessed_at,
            'Data de criação' => $redirect->created_at,
            'Data de atualização' => $redirect->updated_at,
        ]);
    }

    //Atualiza apenas a URL de destino e muda o status do redirecionamento
    public function update(RedirectRequest $request, $id)
    {
        // Busca o redirect pelo ID
        $redirect = Redirect::findOrFail($id);

        // Atualiza os campos do redirect
        $redirect->fill($request->all());

        // Salva as alterações
        $redirect->save();

        // Retorna uma resposta de sucesso
        return response()->json(['message' => 'Redirecionamento atualizado com sucesso.']);
    }

    //Cria uma data no campo date_delete e muda o status do redirecionamento
    public function destroy($id)
    {
        // Busca o redirect pelo ID
        $redirect = Redirect::findOrFail($id);

        // Desativa o redirect (define o status como false)
        $redirect->status = false;

        // Soft delete do redirect
        $redirect->delete();

        // Retorna uma resposta de sucesso
        return response()->json(['message' => 'Redirecionamento deletado com sucesso.']);
    }
}
