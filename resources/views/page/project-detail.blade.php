@extends('layouts.app')

@section('title', 'Project Detail')

@section('content')
<div class="container flex-grow-1">
    <div class="mb-4">
        <a href="{{ route('project.index') }}" class="btn btn-danger">Kembali</a>
    </div>
    <div class="row g-4">
        <div class="col-md-7">
            @if ($project->image)
                <img src="{{ $project->image_url }}" alt="" class="img-fluid h-100 w-100"
                style="min-height: 300px; object-fit: cover;">
                @endif
        </div>
        <div class="col-md-5">
            <div class="card-body ">
                <h5 class="card-title mb-4">{{ $project->nama_project }}</h5>
                <div class="mb-2">
                    <small>Tech : {{ $project->teknologi }}</small>
                </div>
                <span class="badge bg-success">{{ $project->status }}</span>
                <p class="card-text">{{ $project->description }}</p>
            </div>
            <div class="card-footer bg-white border-0 pb-3">
                <div class="mb-2">
                    <small>Dibuat : {{ $project->created_at->format('d M Y') }}</small>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection
