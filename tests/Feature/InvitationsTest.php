<?php

namespace Tests\Feature;

use App\Http\Controllers\ProjectTasksController;
use App\Models\User;
use Facades\Tests\Setup\ProjectFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class InvitationsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_user_can_be_invited_to_a_project(): void
    {
        $this->withoutExceptionHandling();

        $project = ProjectFactory::create();

        $project->invite($newUser = User::factory()->create());

        /** @var User $newUser */
        $this->signIn($newUser);
        $this->post(action([ProjectTasksController::class, 'store'], $project), $task = ['body' => 'Test task']);

        $this->assertDatabaseHas('tasks', $task);
    }
}
