<?php

namespace Tests\Feature;

use App\Http\Controllers\ProjectTasksController;
use App\Models\User;
use Facades\Tests\Setup\ProjectFactory;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class InvitationsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function non_owners_cannot_invite_users(): void
    {
        $user = User::factory()->create();
        $project = ProjectFactory::create();

        $assertInvitationForbidden = function () use ($user, $project) {
            /** @var Authenticatable $user */
            $this->actingAs($user)
                ->post($project->path() . '/invitations')
                ->assertStatus(Response::HTTP_FORBIDDEN);
        };

        $assertInvitationForbidden();

        $project->invite($user);

        $assertInvitationForbidden();
    }

    /** @test */
    public function a_project_owner_can_invite_a_user(): void
    {
        $project = ProjectFactory::create();

        $userToInvite = User::factory()->create();

        $this->actingAs($project->owner)
            ->post($project->path() . '/invitations', [
                'email' => $userToInvite->email,
            ])
            ->assertRedirect($project->path());

        $this->assertTrue($project->members->contains($userToInvite));
    }

    /** @test */
    public function the_email_address_must_be_associated_with_a_valid_birdboard_account(): void
    {
        $project = ProjectFactory::create();

        $this->actingAs($project->owner)
            ->post($project->path() . '/invitations', [
                'email' => 'notauser@example.com',
            ])
            ->assertSessionHasErrors([
                'email' => 'The user you are inviting must have a Birdboard account!'
            ], null, 'invitations');
    }

    /** @test */
    public function invited_users_can_update_project_details(): void
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
