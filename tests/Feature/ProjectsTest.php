<?php

namespace Tests\Feature;

use App\Models\Project;
use App\Models\User;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class ProjectsTest extends TestCase
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
    public function a_user_can_create_a_project(): void
    {
        $this->withoutExceptionHandling();

        $user = User::factory()->create();

        /** @var Authenticatable $user */
        $this->actingAs($user);

        $attributes = [
            'title' => $this->faker->sentence,
            'description' => $this->faker->paragraph,
        ];

        $this->post('/projects', $attributes)->assertRedirect('/projects');

        $this->assertDatabaseHas('projects', $attributes);

        $this->get('/projects')->assertSee($attributes['title']);
    }

    /** @test */
    public function a_user_can_view_their_project(): void
    {
        $user = User::factory()->create();

        /** @var Authenticatable $user */
        $this->actingAs($user);

        $this->withoutExceptionHandling();

        $project = Project::factory()->create(['owner_id' => auth()->id()]);

        $this->get($project->path())
            ->assertSee($project->title)
            ->assertSee($project->description);
    }

    /** @test */
    public function an_authenticated_user_cannot_view_the_projects_of_others(): void
    {
        $user = User::factory()->create();

        /** @var Authenticatable $user */
        $this->actingAs($user);

        $project = Project::factory()->create();

        $this->get($project->path())->assertStatus(Response::HTTP_FORBIDDEN);
    }

    /** @test */
    public function a_project_requires_a_title(): void
    {
        $user = User::factory()->create();

        /** @var Authenticatable $user */
        $this->actingAs($user);

        $project = Project::factory()->raw(['title' => '']);

        $this->post('/projects', $project)->assertSessionHasErrors('title');
    }

    /** @test */
    public function a_project_requires_a_description(): void
    {
        $user = User::factory()->create();

        /** @var Authenticatable $user */
        $this->actingAs($user);

        $project = Project::factory()->raw(['description' => '']);

        $this->post('/projects', $project)->assertSessionHasErrors('description');
    }
}
