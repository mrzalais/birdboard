<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Task extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $touches = ['project'];

    protected $casts = [
        'completed' => 'boolean'
    ];

    public function complete(): void
    {
        $this->update(['completed' => true]);

        $this->project->recordActivity('completed_task');
    }

    public function uncomplete(): void
    {
        $this->update(['completed' => false]);

        $this->project->recordActivity('uncompleted_task');
    }

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function path(): string
    {
        return "/projects/{$this->project->id}/tasks/$this->id";
    }
}
