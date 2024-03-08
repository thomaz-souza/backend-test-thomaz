<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;


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
                'not_in:' . url('/'),
                'regex:/^https:/'
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
            'target_url.regex' => "A url não é HTTPS"
        ];
    }

    public function response(array $errors)
    {
        return response()->json($errors, 422);
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            response()->json(['errors' => $validator->errors()], JsonResponse::HTTP_UNPROCESSABLE_ENTITY)
        );
    }
}
