<div class="card" style="height: 200px">
    <h3 class="font-normal text-xl py-4 -ml-5 mb-3 border-l-4 border-light-blue pl-4 mb-4">
        <a href="{{ $project->path() }}" class="text-black">{{ $project->title }}</a>
    </h3>

    <div class=""
         style="opacity: 0.5">{{ Illuminate\Support\Str::limit($project->description, 50) }}</div>
</div>
