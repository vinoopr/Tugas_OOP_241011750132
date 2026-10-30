@extends('layouts.admin')

@section('title', 'Edit Project')

@section('content')
<div class="container-fluid px-0">
    <div class="card bg-white shadow-sm border-0">
        <div class="card-header bg-white border-bottom px-4 py-3"><h1 class="h4 my-2">Edit Project</h1></div>
        <form action="{{ route('admin.projects.update', $project) }}" method="POST" enctype="multipart/form-data" class="card-body p-4">
            @csrf
            @method('PUT')
            @include('admin.projects._form')
            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary">Ubah</button>
                <a href="{{ route('admin.projects.index') }}" class="btn btn-danger">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection
