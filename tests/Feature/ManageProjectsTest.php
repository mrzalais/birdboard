<?php

namespace Tests\Feature;

use App\Models\Project;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;
use Facades\Tests\Setup\ProjectFactory;
use Tests\TestCase;

class ManageProjectsTest extends TestCase
{
    use WithFaker, RefreshDatabase;

    /** @test */
    public function guest_cannot_create_projects(): void
    {
        $attributes = Project::factory()->raw();

        $this->post('/projects', $attributes)->assertRedirect('login');
    }

    /** @test */
    public function guest_cannot_view_projects(): void
    {
        $this->get('/projects')->assertRedirect('login');
    }

    /** @test */
    public function guest_cannot_view_a_single_project(): void
    {
        $project = Project::factory()->create();

        $this->get($project->path())->assertRedirect('login');
    }

    /** @test */
    public function guest_cannot_view_create_project_page(): void
    {
        $this->get('/projects/create')->assertRedirect('login');
    }

    /** @test */
    public function guest_cannot_view_edit_project_page(): void
    {
        $project = Project::factory()->create();

        $this->get($project->path() . '/edit')->assertRedirect('login');
    }


    /** @test */
    public function a_user_can_create_a_project(): void
    {
        $this->signIn();

        $this->get('/projects/create')->assertStatus(200);

        $this->followingRedirects()
            ->post('/projects', $attributes = Project::factory()->raw())
            ->assertSee($attributes['title'])
            ->assertSee(Str::limit($attributes['description'], 50))
            ->assertSee($attributes['notes']);
    }

    /** @test */
    public function a_user_can_see_all_the_projects_they_have_been_invited_to_on_their_dashboard(): void
    {
        $project = tap(ProjectFactory::create())->invite($this->signIn());

        $this->get('/projects')->assertSee($project->title);
    }

    /** @test */
    public function unauthorized_users_cannot_delete_a_project(): void
    {
        $project = ProjectFactory::create();

        $this->delete($project->path())
            ->assertRedirect('/login');

        $user = $this->signIn();

        $this->delete($project->path())
            ->assertStatus(Response::HTTP_FORBIDDEN);

        $project->invite($user);

        $this->delete($project->path())
            ->assertStatus(Response::HTTP_FORBIDDEN);
    }

    /** @test */
    public function a_user_can_delete_a_project(): void
    {
        $project = ProjectFactory::create();

        $this->actingAs($project->owner)
            ->delete($project->path())
            ->assertRedirect('/projects');

        $this->assertDatabaseMissing('projects', $project->only('id'));
    }

    /** @test */
    public function a_user_can_update_project(): void
    {
        $this->withoutExceptionHandling();
        $this->signIn();

        $project = ProjectFactory::create();

        $this->actingAs($project->owner)
            ->patch($project->path(), $attributes = [
                'title' => 'Changed',
                'description' => 'Changed',
                'notes' => 'Changed'
            ]
            )->assertRedirect($project->path());

        $this->get($project->path() . '/edit')->assertOk();

        $this->assertDatabaseHas('projects', $attributes);
    }

    /** @test */
    public function a_user_can_view_their_project(): void
    {
        $this->signIn();

        $project = ProjectFactory::create();

        $this->actingAs($project->owner)
            ->get($project->path())
            ->assertSee($project->title)
            ->assertSee(Str::limit($project->description, 50));
    }

    /** @test */
    public function an_authenticated_user_cannot_view_the_projects_of_others(): void
    {
        $this->signIn();

        $project = Project::factory()->create();

        $this->get($project->path())->assertStatus(Response::HTTP_FORBIDDEN);
    }

    /** @test */
    public function an_authenticated_user_cannot_update_the_projects_of_others(): void
    {
        $this->signIn();

        $project = Project::factory()->create();

        $this->patch($project->path(), [])->assertStatus(Response::HTTP_FORBIDDEN);
    }

    /** @test */
    public function a_project_requires_a_title(): void
    {
        $this->signIn();

        $project = Project::factory()->raw(['title' => '']);

        $this->post('/projects', $project)->assertSessionHasErrors('title');
    }

    /** @test */
    public function a_project_requires_a_description(): void
    {
        $this->signIn();

        $project = Project::factory()->raw(['description' => '']);

        $this->post('/projects', $project)->assertSessionHasErrors('description');
    }
}
