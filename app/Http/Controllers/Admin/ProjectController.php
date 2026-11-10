<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Project;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class ProjectController extends Controller
{
    public function cetakLaporan()
    {
        $projects = Project::latest('id')->get();

        return Pdf::loadView('admin.projects.laporan-pdf', compact('projects'))
            ->setPaper('a4', 'portrait')
            ->stream('laporan.pdf');
    }

    public function index()
    {
        $projects = Project::latest('id')->paginate(10);

        return view('admin.projects.index', compact('projects'));
    }

    public function create()
    {
        return view('admin.projects.create');
    }

    public function store(Request $request)
    {
        $data = $this->validatedData($request);
        unset($data['image']);

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('projects', 'public');
        }

        try {
            Project::create($data);
        } catch (\Throwable $exception) {
            if (isset($data['image'])) {
                Storage::disk('public')->delete($data['image']);
            }
            throw $exception;
        }

        return redirect()->route('admin.projects.index')->with('success', 'Project berhasil ditambahkan.');
    }

    public function edit(Project $project)
    {
        return view('admin.projects.edit', compact('project'));
    }

    public function update(Request $request, Project $project)
    {
        $data = $this->validatedData($request);
        unset($data['image']);
        $oldImage = $project->image;

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('projects', 'public');
        }

        try {
            $project->update($data);
        } catch (\Throwable $exception) {
            if (isset($data['image'])) {
                Storage::disk('public')->delete($data['image']);
            }
            throw $exception;
        }

        if (isset($data['image']) && $oldImage && str_starts_with($oldImage, 'projects/')) {
            Storage::disk('public')->delete($oldImage);
        }

        return redirect()->route('admin.projects.index')->with('success', 'Project berhasil diperbarui.');
    }

    public function destroy(Project $project)
    {
        $image = $project->image;
        $project->delete();

        if ($image && str_starts_with($image, 'projects/')) {
            Storage::disk('public')->delete($image);
        }

        return redirect()->route('admin.projects.index')->with('success', 'Project berhasil dihapus.');
    }

    private function validatedData(Request $request): array
    {
        return $request->validate([
            'nama_project' => ['required', 'string', 'max:255'],
            'teknologi' => ['required', 'string', 'max:255'],
            'status' => ['required', Rule::in(Project::STATUSES)],
            'description' => ['required', 'string', 'max:10000'],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ]);
    }
}
