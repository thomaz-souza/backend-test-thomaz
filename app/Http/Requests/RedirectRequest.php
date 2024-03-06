<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RedirectRequest extends FormRequest
{

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Pega as regras de validação e aplica ao request
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'status' => 'boolean',
            'target_url' => [
                'required',
                'url',
                'active_url',
                'not_in:' . url('/'),
            ],
        ];
    }

    public function messages()
    {
        return [
            'target_url.required' => 'O campo URL de destino é obrigatório.',
            'target_url.url' => 'O formato da URL de destino é inválido.',
            'target_url.active_url' => 'A URL de destino não está ativa.',
            'target_url.not_in' => 'A URL de destino não pode ser a mesma da aplicação.',
        ];
    }

    public function response(array $errors)
    {
        return response()->json($errors, 422);
    }
}
