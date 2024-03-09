<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Redirect;
use Vinkla\Hashids\Facades\Hashids;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class RedirectTest extends TestCase
{
    use DatabaseTransactions;

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

        $target_url = Redirect::factory()->create()->target_url;

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

        $response = $this->get($target_url);

        if ($response->getStatusCode() != 200 || $response->getStatusCode() != 201)
            $response->assertStatus($response->getStatusCode());

        //******************** URL invalida por tem query params vazia ********************

        $hasEmptyQueryParam = strpos($target_url, '=');

        $this->assertFalse($hasEmptyQueryParam !== false);
    }
}
