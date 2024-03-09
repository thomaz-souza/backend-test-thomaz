<?php

namespace Database\Factories;

use App\Models\Redirect;
use App\Models\RedirectLog;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Http;

class RedirectLogFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = RedirectLog::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {

        return [
            'redirect_id' =>  Redirect::latest()->first()->id,
            'user_agent' => $this->faker->userAgent,
            'referer' => 'https://example.com',
            'query_params' => json_encode(['param1' => 'value1', 'param2' => 'value2']),
            'accessed_at' => now(),
        ];
    }
}
