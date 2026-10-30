@extends('layouts.admin')

@section('title', 'Tambah Project')

@section('content')
<div class="container-fluid px-0">
    <div class="card bg-white shadow-sm border-0">
        <div class="card-header bg-white border-bottom px-4 py-3"><h1 class="h4 my-2">Tambah Project</h1></div>
        <form action="{{ route('admin.projects.store') }}" method="POST" enctype="multipart/form-data" class="card-body p-4">
            @csrf
            @include('admin.projects._form')
            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary">Simpan</button>
                <a href="{{ route('admin.projects.index') }}" class="btn btn-danger">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection
