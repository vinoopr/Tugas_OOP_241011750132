<?php

namespace Tests\Feature;

use App\Models\Project;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AdminProjectTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $admin = User::factory()->create();
        $admin->forceFill(['is_admin' => true])->save();
        $this->actingAs($admin);
    }

    public function test_project_crud_and_image_lifecycle(): void
    {
        Storage::fake('public');
        $data = ['nama_project' => 'Website Portofolio', 'teknologi' => 'Laravel', 'description' => 'Website untuk portofolio', 'status' => 'In Progress'];

        $this->get(route('admin.projects.index'))->assertOk()->assertSee('Belum ada data');
        $this->get(route('admin.projects.create'))->assertOk();
        $this->post(route('admin.projects.store'), $data + ['image' => UploadedFile::fake()->image('guitar.jpg')])
            ->assertRedirect(route('admin.projects.index'));

        $project = Project::firstOrFail();
        $firstImage = $project->image;
        Storage::disk('public')->assertExists($firstImage);
        $this->get(route('admin.home'))->assertOk()->assertSee('Selamat Datang');
        $this->get(route('project.index'))->assertOk()->assertSee('Website Portofolio')->assertSee($project->image_url);
        $this->get(route('project.show', $project))->assertOk()->assertSee('Website Portofolio');
        $this->get(route('admin.projects.index'))->assertOk()->assertSee('Website Portofolio');
        $this->get(route('admin.projects.edit', $project))->assertOk()->assertSee('Website untuk portofolio');

        $data['nama_project'] = 'Website Baru';
        $this->put(route('admin.projects.update', $project), $data)->assertRedirect(route('admin.projects.index'));
        $this->assertSame($firstImage, $project->fresh()->image);

        $this->put(route('admin.projects.update', $project), $data + ['image' => UploadedFile::fake()->image('replacement.png')])
            ->assertRedirect(route('admin.projects.index'));
        $replacement = $project->fresh()->image;
        Storage::disk('public')->assertMissing($firstImage);
        Storage::disk('public')->assertExists($replacement);
        $this->assertDatabaseHas('projects', ['nama_project' => 'Website Baru', 'status' => 'In Progress']);

        $this->delete(route('admin.projects.destroy', $project))->assertRedirect(route('admin.projects.index'));
        $this->assertDatabaseCount('projects', 0);
        Storage::disk('public')->assertMissing($replacement);
    }

    public function test_invalid_data_is_rejected_and_missing_project_returns_404(): void
    {
        Storage::fake('public');
        $this->post(route('admin.projects.store'), [
            'nama_project' => '', 'teknologi' => '', 'description' => '', 'status' => 'Invalid',
            'image' => UploadedFile::fake()->create('document.pdf', 10, 'application/pdf'),
        ])->assertSessionHasErrors(['nama_project', 'teknologi', 'description', 'status', 'image']);
        $this->assertDatabaseCount('projects', 0);
        $this->get(route('admin.projects.edit', 999))->assertNotFound();
        $this->put(route('admin.projects.update', 999), [])->assertNotFound();
        $this->delete(route('admin.projects.destroy', 999))->assertNotFound();
    }
}
