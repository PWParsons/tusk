<?php

use App\Models\Comment;
use App\Models\Project;
use App\Models\Task;
use Illuminate\Database\Seeder;

class DemoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        $projects = factory(Project::class, 10)->create();

        $projects->each(static function ($project) {
            $tasks = factory(Task::class, 5)->create([
                'project_id' => $project->id,
            ]);

            $tasks->each(static function ($task) {
                factory(Comment::class, 3)->create([
                    'task_id' => $task->id,
                ]);
            });
        });
    }
}
