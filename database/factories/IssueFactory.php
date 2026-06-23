<?php

namespace Database\Factories;

use App\Enums\Issue\IssuePriority;
use App\Enums\Issue\IssueStatus;
use App\Models\Issue;
use App\Models\Project;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Issue>
 */
class IssueFactory extends Factory
{
    protected $model = Issue::class;

    public function definition(): array
    {
        return [
            'project_id' => Project::factory(),
            'title' => fake()->sentence(),
            'description' => fake()->paragraph(),
            'status' => fake()->randomElement(IssueStatus::cases())->value,
            'priority' => fake()->randomElement(IssuePriority::cases())->value,
            'due_date' => fake()->optional()->date(),
        ];
    }
}
