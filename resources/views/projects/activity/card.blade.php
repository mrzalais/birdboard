<div class="card mt-6">
    <ul class="text-xs list-reset">
        @foreach ($project->activity as $activity)
            <li class="{{ $loop->last ? '' : 'mb-1' }}">
                @if(View::exists("projects.activity.{$activity->description}"))
                    @include ("projects.activity.{$activity->description}")
                    <span class="text-gray-400"> {{ $activity->created_at->diffForHumans(null, null, true) }}</span>
                @else
                    {{ $activity->description }}
                @endif
            </li>
        @endforeach
    </ul>
</div>
