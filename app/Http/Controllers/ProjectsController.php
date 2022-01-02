<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Symfony\Component\HttpFoundation\Response;

class ProjectsController extends Controller
{
    public function index()
    {
        $projects = auth()->user()->projects;

        return view('projects.index', compact('projects'));
    }

    public function show(Project $project)
    {
        if (auth()->user()->isNot($project->owner)) {
            abort(Response::HTTP_FORBIDDEN);
        }

        return view('projects.show', compact('project'));
    }

    public function store()
    {
        $attributes = request()->validate([
            'title' => 'required',
            'description' => 'required',
        ]);

        auth()->user()->projects()->create($attributes);

        return redirect('/projects');
    }
}
