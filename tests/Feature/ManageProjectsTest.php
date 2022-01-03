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
    public function a_user_can_create_a_project(): void
    {
        $this->signIn();

        $this->get('/projects/create')->assertStatus(200);

        $attributes = [
            'title' => $this->faker->sentence,
            'description' => $this->faker->word,
            'notes' => 'General notes'
        ];

        $response = $this->post('/projects', $attributes);

        $project = Project::where($attributes)->first();

        $response->assertRedirect($project->path());

        $this->get($project->path())
            ->assertSee($attributes['title'])
            ->assertSee($attributes['description'])
            ->assertSee($attributes['notes']);
    }

    /** @test */
    public function a_user_can_update_project(): void
    {
        $this->signIn();

        $project = ProjectFactory::create();

        $this->actingAs($project->owner)
            ->patch($project->path(), $attributes = ['notes' => 'Changed']
            )->assertRedirect($project->path());

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
