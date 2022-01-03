@extends ('layouts.app')

@section('content')
    <header class="flex items-center mb-3 py-4">
        <div class="flex justify-between items-end w-full">
            <p class="text-gray-400 text-sm font-normal">
                <a href="/projects" class="text-gray-400 text-sm font-normal no-underline">
                    My Projects
                </a>
                / {{ $project->title }}
            </p>
            <a href="/projects/create" class="bg-blue text-gray-400 button">New Project</a>
        </div>
    </header>

    <main>
        <div class="lg:flex -mx-3">
            <div class="lg:w-3/4 px-3 mb-6">
                <div class="mb-8">
                    <h2 class="text-lg text-gray-400 font-normal mb-3">Tasks</h2>
                    {{--Tasks--}}
                    @foreach($project->tasks as $task)
                        <div class="card mb-3">{{ $task->body }}</div>
                    @endforeach
                </div>

                <div>
                    {{--General notes--}}
                    <div><h2 class="text-lg text-gray-400 font-normal">General Notes</h2></div>

                    <textarea class="card w-full" style="min-height: 200px">Lorem ipsum.</textarea>
                </div>
            </div>
            <div class="lg:w-1/4">
                @include ('projects.card')
            </div>
        </div>
    </main>
@endsection
