<?php

namespace App\Policies;

use App\Models\Project;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ProjectPolicy
{
    use HandlesAuthorization;

    public function update(User $user, Project $project): bool
    {
        return $user->is($project->owner) || $project->members->contains($user);
    }

    public function manage(User $user, Project $project): bool
    {
        return $user->is($project->owner);
    }
}
