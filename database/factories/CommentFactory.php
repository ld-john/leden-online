<?php

namespace Database\Factories;

use App\Models\Comment;
use Illuminate\Database\Eloquent\Factories\Factory;

class CommentFactory extends Factory
{
    protected $model = Comment::class;

    public function definition(): array
    {
        return [
            'commentable_type' => $this->faker->word(),
            'private' => $this->faker->boolean(),
            'content' => $this->faker->word(),
            'user_id' => $this->faker->randomNumber(),
            'commentable_id' => $this->faker->word(),
            'dealer_comment' => $this->faker->boolean(),
        ];
    }
}
