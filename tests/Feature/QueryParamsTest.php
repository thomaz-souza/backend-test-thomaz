<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Redirect;
use Vinkla\Hashids\Facades\Hashids;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class QueryParamsTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function test_query_params()
    {
        // Cria um novo redirecionamento com parâmetros de consulta definidos
        Redirect::factory()->create(['target_url' => 'https://www.facebook.com/search/top?q=isadora%20cardoso']);
        $target_url = Redirect::latest()->first()->target_url;
        $code = Hashids::encode(Redirect::latest()->first()->id);

        // Query Params do Redirect
        $url_components = parse_url($target_url);
        $redirect_query_params = $url_components['query'];

        // Define os parâmetros de consulta na solicitação (Request)
        $request_query_params = 'utm_source=instagram&utm_campaign=ads';

        // Faz a solicitação
        $response = $this->get("/r/" . $code . '?' . $request_query_params);

        // Fusão dos parâmetros de consulta
        $merged_query_params = $redirect_query_params . "&" . $request_query_params;

        // Parse os parâmetros de consulta fundidos
        parse_str($merged_query_params, $merged_query_params_array);

        // Verifica se a fusão dos parâmetros de consulta está correta
        $this->assertEquals($merged_query_params_array, [
            'q' => 'isadora cardoso',
            'utm_source' => 'instagram',
            'utm_campaign' => 'ads',
        ]);

        // Verifica se houve redirecionamento para a URL alvo
        $response->assertRedirect();
    }
}
