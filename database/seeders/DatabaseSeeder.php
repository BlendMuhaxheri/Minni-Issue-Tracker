<?php

namespace Database\Seeders;

use App\Models\Comment;
use App\Models\Issue;
use App\Models\Project;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::factory(5)->create();
        $tags = Tag::factory(8)->create();

        /**
         * =========================
         * PROJECTS (each has owner)
         * =========================
         */
        $projects = Project::factory(3)
            ->create([
                'user_id' => $users->random()->id,
            ]);

        /**
         * =========================
         * ISSUES (belong to projects)
         * =========================
         */
        $issues = Issue::factory(15)
            ->make()
            ->each(function ($issue) use ($projects, $tags, $users) {

                $issue->project_id = $projects->random()->id;
                $issue->save();

                /**
                 * TAGS (many-to-many)
                 */
                $issue->tags()->attach(
                    $tags->random(rand(1, 3))->pluck('id')->toArray()
                );

                /**
                 * MEMBERS (many-to-many)
                 */
                $issue->members()->attach(
                    $users->random(rand(1, 3))->pluck('id')->toArray()
                );

                /**
                 * COMMENTS
                 */
                Comment::factory(rand(2, 6))->create([
                    'issue_id' => $issue->id,
                ]);
            });
    }
}
