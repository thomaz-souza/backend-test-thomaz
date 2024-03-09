<?php

namespace Database\Factories;

use App\Models\Redirect;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class RedirectFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Redirect::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {

        $url = $this->faker->url;
        $httpsUrl = preg_replace('/^http:/', 'https:', $url);
        $httpsUrl .= '?' . $this->faker->word;

        return [
            'status' => $this->faker->boolean ? 1 : 0, // Define status como 1 ou 0 aleatoriamente
            'target_url' =>  $httpsUrl, // Define uma URL aleatória como target_url
            'last_accessed_at' => $this->faker->dateTimeThisMonth(), // Define uma data e hora aleatória para last_accessed_at
        ];
    }
}
