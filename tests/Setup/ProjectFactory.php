<?php

namespace Tests\Setup;

use App\Models\Project;
use App\Models\Task;
use App\Models\User;

class ProjectFactory
{
    protected int $tasksCount = 0;
    protected $user;

    public function withTasks(int $count): ProjectFactory
    {
        $this->tasksCount = $count;

        return $this;
    }

    public function ownedBy($user): ProjectFactory
    {
        $this->user = $user;

        return $this;
    }

    public function create()
    {
        $project = Project::factory()->create([
            'owner_id' => $this->user ?? User::factory(),
        ]);

        Task::factory()->count($this->tasksCount)->create([
            'project_id' => $project->id,
        ]);

        return $project;
    }
}
