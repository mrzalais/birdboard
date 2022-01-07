<div class="card flex flex-col" style="height: 200px">
    <h3 class="font-normal text-lg py-4 -ml-5 mb-3 border-l-4 border-light-blue pl-4">
        <a href="{{ $project->path() }}" class="text-default no-underline">{{ $project->title }}</a>
    </h3>

    <div class="text-gray-400">{{ Str::limit($project->description, 50) }}</div>

    @can ('manage', $project)
        <footer>
            <form method="POST" action="{{ $project->path() }}" class="text-right">
                @method('DELETE')
                @csrf
                <button type="submit" class="text-xs">Delete</button>
            </form>
        </footer>
    @endcan
</div>
