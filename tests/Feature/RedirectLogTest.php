<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Redirect;
use App\Models\RedirectLog;
use Illuminate\Support\Carbon;
use Illuminate\Foundation\Testing\DatabaseTransactions;


class RedirectLogTest extends TestCase
{

    use DatabaseTransactions;

    /** @test */
    public function stats_validation()
    {
        Redirect::factory()->create();

        RedirectLog::factory()->create(['request_ip' => '192.168.1.1']);
        RedirectLog::factory()->create(['request_ip' => '192.168.1.2']);

        // Chama a função stats()
        $stats = RedirectLog::stats();

        // Validação dos resultados esperados
        $this->assertEquals(2, $stats['total_acessos']); // Verifica se o total de acessos é correto
        $this->assertEquals(2, $stats['total_acessos_unicos']); // Verifica se o total de acessos únicos é correto
        $this->assertCount(1, $stats['top_referrers']); // Verifica se a lista de referers tem o tamanho esperado
        $this->assertEquals('https://example.com', $stats['top_referrers'][0]->referer); // Verifica se o referer mais comum está correto

        // Verifica se os dados dos últimos 10 dias estão corretos
        $this->assertCount(1, $stats['total_acessos_ultimos_10_dias']);
        $this->assertEquals(2, $stats['total_acessos_ultimos_10_dias'][0]->total); // Verifica se o total de acessos dos últimos 10 dias é correto
        $this->assertEquals(2, $stats['total_acessos_ultimos_10_dias'][0]->unique); // Verifica se o total de acessos únicos dos últimos 10 dias é correto
    }
}
