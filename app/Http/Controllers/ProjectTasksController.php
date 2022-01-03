<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Task;
use Symfony\Component\HttpFoundation\Response;

class ProjectTasksController extends Controller
{
    public function store(Project $project)
    {
        if (auth()->user()->isNot($project->owner)) {
            abort(Response::HTTP_FORBIDDEN);
        }
        request()->validate(['body' => 'required']);
        $project->addTask(request('body'));

        return redirect($project->path());
    }

    public function update(Project $project, Task $task)
    {
        if (auth()->user()->isNot($project->owner)) {
            abort(Response::HTTP_FORBIDDEN);
        }

        request()->validate(['body' => 'required']);

        $task->update([
            'body' => request('body'),
            'completed' =>  request()->has('completed'),
        ]);

        return redirect($project->path());
    }
}
