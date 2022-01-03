<div class="card" style="height: 200px">
    <h3 class="font-normal text-lg py-4 -ml-5 mb-3 border-l-4 border-light-blue pl-4">
        <a href="{{ $project->path() }}" class="text-black">{{ $project->title }}
    </h3>

    <div class="text-gray-400">{{ Str::limit($project->description, 50) }}</div>
</div>
