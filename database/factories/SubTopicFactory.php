<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\SubTopic;
use App\Models\Topic;

class SubTopicFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = SubTopic::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'topic_id' => Topic::factory(),
            'label' => $this->faker->regexify('[A-Za-z0-9]{400}'),
            'description' => $this->faker->text(),
        ];
    }
}
