<?php

namespace App\Http\Controllers;

use App\Models\Project;
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
}
