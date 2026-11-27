@extends('layouts.admin')

@section('title', 'Data User')

@push('styles')
<link rel="stylesheet" href="https://cdn.datatables.net/2.3.5/css/dataTables.bootstrap5.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/2.1.4/toastr.min.css">
@endpush

@section('content')
<div class="container pb-4" id="user-management" data-api-url="{{ url('/api/users') }}" data-login-url="{{ route('login') }}" data-current-user="{{ auth()->id() }}">
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
        <h1 class="h3 mb-0">Data User</h1>
        <button type="button" class="btn btn-primary" id="add-user">Tambah User</button>
    </div>
    <div id="users-feedback" class="alert alert-danger d-none" role="alert"></div>
    <div class="card shadow-sm p-3">
        <div class="table-responsive">
            <table id="users-table" class="table table-striped table-hover align-middle w-100">
                <thead><tr><th>ID</th><th>Nama</th><th>Email</th><th>Telepon</th><th>Alamat</th><th>Role</th><th>Akses</th><th>Aksi</th></tr></thead>
                <tbody></tbody>
            </table>
        </div>
    </div>
    <div class="modal fade" id="user-modal" tabindex="-1" aria-labelledby="user-modal-title" aria-hidden="true" data-bs-backdrop="static">
        <div class="modal-dialog modal-dialog-scrollable">
            <form id="user-form" class="modal-content">
                <div class="modal-header">
                    <h2 class="modal-title fs-5" id="user-modal-title">Tambah User</h2>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>
                <div class="modal-body">
                    <p id="password-help" class="text-muted small">Password minimal 6 karakter. Role adalah label dan tidak memberikan akses admin.</p>
                    <div class="mb-3">
                        <label for="user-name" class="form-label">Nama</label>
                        <input type="text" id="user-name" name="name" class="form-control" required maxlength="255" aria-describedby="error-name">
                        <div id="error-name" class="invalid-feedback"></div>
                    </div>
                    <div class="mb-3">
                        <label for="user-email" class="form-label">Email</label>
                        <input type="email" id="user-email" name="email" class="form-control" required maxlength="255" aria-describedby="error-email">
                        <div id="error-email" class="invalid-feedback"></div>
                    </div>
                    <div class="mb-3">
                        <label for="user-password" class="form-label">Password</label>
                        <input type="password" id="user-password" name="password" class="form-control" required autocomplete="new-password" minlength="6" aria-describedby="error-password">
                        <div id="error-password" class="invalid-feedback"></div>
                    </div>
                    <div class="mb-3">
                        <label for="user-phone" class="form-label">Telepon</label>
                        <input type="text" id="user-phone" name="phone" class="form-control"  maxlength="255" aria-describedby="error-phone">
                        <div id="error-phone" class="invalid-feedback"></div>
                    </div>
                    <div class="mb-3">
                        <label for="user-role" class="form-label">Role</label>
                        <input type="text" id="user-role" name="role" class="form-control"  maxlength="255" aria-describedby="error-role">
                        <div id="error-role" class="invalid-feedback"></div>
                    </div>
                    <div class="mb-3">
                        <label for="user-address" class="form-label">Alamat</label>
                        <textarea id="user-address" name="address" class="form-control" rows="3" aria-describedby="error-address"></textarea>
                        <div id="error-address" class="invalid-feedback"></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary" id="save-user">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js" defer></script>
<script src="https://cdn.datatables.net/2.3.5/js/dataTables.min.js" defer></script>
<script src="https://cdn.datatables.net/2.3.5/js/dataTables.bootstrap5.min.js" defer></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/2.1.4/toastr.min.js" defer></script>
<script src="{{ asset('js/admin-users.js') }}" defer></script>
@endpush
