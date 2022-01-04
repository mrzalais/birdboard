<?php

namespace App\Observers;

use App\Models\Project;

class ProjectObserver
{
    /**
     * Handle the Project "created" event.
     *
     * @param \App\Models\Project $project
     * @return void
     */
    public function created(Project $project): void
    {
        $project->recordActivity('created');
    }

    public function updating(Project $project): void
    {
        $project->old = $project->getOriginal();
    }

    /**
     * Handle the Project "updated" event.
     *
     * @param \App\Models\Project $project
     * @return void
     */
    public function updated(Project $project): void
    {
        $project->recordActivity('updated');
    }
}
