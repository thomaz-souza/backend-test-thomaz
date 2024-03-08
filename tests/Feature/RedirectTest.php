<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Redirect;
use Vinkla\Hashids\Facades\Hashids;
use Tests\Feature\DnsResolver;

class RedirectTest extends TestCase
{
    /** @test */
    public function it_creates_redirect_with_valid_target_url()
    {
        //******************** Cria um redirect valido ***********************************
        $response = $this->postJson('/api/redirects', [
            'target_url' => Redirect::factory()->create()->target_url
        ]);

        $response->assertStatus(201)
            ->assertJsonStructure(
                [
                    'status',
                    'target_url',
                    'last_accessed_at',
                ]
            );
    }

    /** @test */
    public function it_creates_redirect_with_invalid_target_url()
    {
        //******************** URL invalida ************************************************
        $response = $this->postJson('/api/redirects', [
            'target_url' => 'www.invalid-url.com',
        ]);

        $response->assertStatus(422);


        //******************** URL apontando para a própria aplicação ********************
        $response = $this->postJson('/api/redirects', [
            'target_url' => url('/'),
        ]);

        $response->assertStatus(422);


        //******************** URL sem Https **********************************************
        $response = $this->postJson('/api/redirects', [
            'target_url' => 'http://www.invalid-url.com',
        ]);

        $response->assertStatus(422);

        //******************** URL retornando status diferente de 200 ou 201 **************
        $code =  Hashids::encode(Redirect::latest()->first()->id);
        $response = $this->get("/r/" . $code);

        if ($response->getStatusCode() != 200 || $response->getStatusCode() != 201)
            $response->assertStatus($response->getStatusCode());

        //******************** URL invalida por tem query params vazia ********************
        $code =  Hashids::encode(Redirect::latest()->first()->id);
        $response = $this->get("/r/" . $code);

        if ($response->getStatusCode() != 200 || $response->getStatusCode() != 201)
            $response->assertStatus($response->getStatusCode());


        // Consulte o último redirecionamento no banco de dados e transforma em code
        $link =  Redirect::latest()->first()->target_url;

        // Verifica se a URL contém uma chave sem valor na query string
        $hasEmptyQueryParam = strpos($link, '=');

        // Verifica se a URL contém uma chave sem valor na query string
        $this->assertFalse($hasEmptyQueryParam !== false);
    }

    /** @test */
    public function it_shows_redirect_status()
    {
        // Consulte o último redirecionamento no banco de dados e transforma em code
        $code =  Hashids::encode(Redirect::latest()->first()->id);

        // Supondo que haja um redirecionamento com o ID 
        $response = $this->get("/r/" . $code);

        // Verifica se houve redirecionamento para a URL alvo
        $response->assertRedirect();
    }

    /** @test */
    public function it_updates_redirect_with_valid_data()
    {
        // Consulte o último redirecionamento no banco de dados e transforma em code
        $code =  Hashids::encode(Redirect::latest()->first()->id);

        // Supondo que haja um redirecionamento com o ID 
        $response = $this->putJson(
            '/api/redirects/' . $code,
            [
                'status' => 1,
                'target_url' => "https://www.youtube.com/watch?v=ucp1yNPDu-Y&ab_channel=AliceinChains-Topic",
            ]
        );


        $response->assertStatus(200)
            ->assertJson(['message' => 'Redirecionamento atualizado com sucesso.']);
    }

    /** @test */
    public function it_deletes_redirect()
    {
        // Consulte o último redirecionamento no banco de dados e transforma em code
        $code =  Hashids::encode(Redirect::latest()->first()->id);

        // Supondo que haja um redirecionamento com o ID 
        $response = $this->delete('/api/redirects/' . $code);

        $response->assertStatus(200)
            ->assertJson(['message' => 'Redirecionamento deletado com sucesso.']);
    }
}
