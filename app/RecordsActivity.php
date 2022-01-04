<?php

namespace App;

use App\Models\Activity;
use App\Models\Project;
use Illuminate\Database\Eloquent\Relations\MorphMany;

trait RecordsActivity
{
    public array $oldAttributes = [];

    public static function bootRecordsActivity(): void
    {
        foreach (self::recordableEvents() as $event) {
            static::$event(function ($model) use ($event) {
                $model->recordActivity($model->activityDescription($event));
            });

            if ($event === 'updated') {
                static::updating(function ($model) {
                    $model->oldAttributes = $model->getOriginal();
                });
            }
        }
    }

    protected function activityDescription($description): string
    {
        return "{$description}_" . strtolower(class_basename($this));
    }

    protected static function recordableEvents(): array
    {
        return static::$recordableEvents ?? ['created', 'updated', 'deleted'];
    }

    public function recordActivity(string $description): void
    {
        $this->activity()->create([
            'description' => $description,
            'changes' => $this->activityChanges(),
            'project_id' => class_basename($this) === 'Project' ? $this->id : $this->project_id,
        ]);
    }

    public function activity()
    {
        if (get_class($this) === Project::class) {
            return $this->hasMany(Activity::class)->latest();
        }

        return $this->morphMany(Activity::class, 'subject')->latest();
    }

    public function activityChanges(): ?array
    {
        if ($this->wasChanged()) {
            return [
                'before' => array_diff($this->oldAttributes, $this->getAttributes()),
                'after' => $this->getChanges(),
            ];
        }
        return null;
    }
}
