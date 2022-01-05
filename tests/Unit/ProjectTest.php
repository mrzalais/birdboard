<?php

namespace Tests\Unit;

use App\Models\Project;
use App\Models\User;
use Facades\Tests\Setup\ProjectFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProjectTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_has_a_path(): void
    {
        $project = ProjectFactory::create();

        $this->assertEquals('/projects/' . $project->id, $project->path());
    }

    /** @test */
    public function it_belongs_to_an_owner(): void
    {
        $project = ProjectFactory::create();

        $this->assertInstanceOf(User::class, $project->owner);
    }

    /** @test */
    public function it_can_have_a_task(): void
    {
        $project = ProjectFactory::create();

        $task = $project->addTask('Test task');

        $this->assertCount(1, $project->tasks);
        $this->assertTrue($project->tasks->contains($task));
    }

    /** @test */
    public function it_can_invite_a_user(): void
    {
        $project = ProjectFactory::create();

        $project->invite($user = User::factory()->create());

        $this->assertTrue($project->members->contains($user));
    }
}
