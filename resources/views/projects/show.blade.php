@extends('layouts.app')

@section('content')
    <header class="flex items-center mb-3 py-4">
        <div class="flex justify-between items-end w-full">
            <p class="text-grey text-sm font-normal">
                <a href="/projects" class="text-gray text-sm font-normal hover:underline">
                    My projects
                </a>
                / {{ $project->title }}
            </p>

            <a href="/projects/create" class="btn">Create New Project</a>
        </div>
    </header>

    <main>
        <div class="lg:flex -mx-3">
            <div class="lg:w-3/4 px-3 mb-6">
                <div class="mb-8">
                    <h2 class=" text-lg text-grey font-normal mb-3">Tasks</h2>
                    @foreach ($project->tasks as $task)
                        <div class="card mb-3 {{--{{ $loop->last ? '' : 'mb-3' }}--}}">
                            <form method="POST" action="{{ $task->path() }}">
                                @method('PATCH')
                                @csrf

                                <div class="flex">
                                    <input
                                        name="body"
                                        value="{{ $task->body }}"
                                        class="w-full {{ $task->completed ? 'text-gray-500 line-through' : '' }}"
                                    >
                                    <input
                                        name="completed"
                                        type="checkbox"
                                        onChange="this.form.submit()"
                                        {{ $task->completed ? 'checked' : '' }}
                                    >
                                </div>
                            </form>
                        </div>
                    @endforeach

                    <div class="card">
                        <form action="{{ $project->path() . '/tasks' }}" method="post">
                            @csrf
                            <input placeholder="Add a new task..." class="w-full" name="body">
                        </form>
                    </div>
                </div>

                <div>
                    <h2 class=" text-lg text-grey font-normal mb-3">General Notes</h2>
                    <textarea class="card w-full" style="min-height: 200px">Lorem ipsum.</textarea>
                </div>
            </div>

            <div class="lg:w-1/4 px-3">
                @include('projects.card')
            </div>
        </div>
    </main>
@endsection
