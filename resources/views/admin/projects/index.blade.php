@extends('layouts.admin')

@section('title', 'Data Project')

@section('content')
<div class="container pb-4">
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
        <h1 class="h3 mb-0">Data Project</h1>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.projects.create') }}" class="btn btn-primary">Tambah Project</a>
            <a href="{{ route('admin.projects.pdf') }}" class="btn btn-danger" target="_blank" rel="noopener">Cetak PDF</a>
        </div>
    </div>
    @if (session('success'))
        <div class="alert alert-success" role="status">{{ session('success') }}</div>
    @endif
    <div class="card shadow-sm">
        <div class="table-responsive">
            <table class="table table-striped table-hover align-middle mb-0">
                <thead class="table-primary">
                    <tr><th scope="col">No.</th><th scope="col">Gambar</th><th scope="col">Nama</th><th scope="col">Teknologi</th><th scope="col">Deskripsi</th><th scope="col">Status</th><th scope="col">Aksi</th></tr>
                </thead>
                <tbody>
                    @forelse ($projects as $project)
                        <tr>
                            <td>{{ $projects->firstItem() + $loop->index }}</td>
                            <td>
                                @if ($project->image)
                                    <img src="{{ $project->image_url }}" alt="{{ $project->nama_project }}" class="rounded" width="80" height="80" style="object-fit: cover;">
                                @else
                                    <span class="text-muted">Tanpa gambar</span>
                                @endif
                            </td>
                            <td>{{ $project->nama_project }}</td>
                            <td>{{ $project->teknologi }}</td>
                            <td>{{ \Illuminate\Support\Str::limit($project->description, 100) }}</td>
                            <td class="text-nowrap">{{ $project->status }}</td>
                            
                            <td>
                                <div class="d-flex gap-2">
                                    <a href="{{ route('admin.projects.edit', $project) }}" class="btn btn-primary btn-sm">Ubah</a>
                                    <form action="{{ route('admin.projects.destroy', $project) }}" method="POST" onsubmit="return confirm('Hapus project ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="text-center text-muted py-4">Belum ada data project.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div class="mt-3">{{ $projects->links('pagination::bootstrap-5') }}</div>
</div>
@endsection
