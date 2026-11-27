(() => {
    const root = document.getElementById('user-management');
    if (!root) return;

    const feedback = document.getElementById('users-feedback');
    if (!window.jQuery || !jQuery.fn.DataTable || !window.bootstrap || !window.toastr) {
        feedback.textContent = 'Komponen tabel gagal dimuat. Periksa koneksi internet lalu muat ulang halaman.';
        feedback.classList.remove('d-none');
        return;
    }

    const $ = window.jQuery;
    const form = document.getElementById('user-form');
    const modal = bootstrap.Modal.getOrCreateInstance(document.getElementById('user-modal'));
    const save = document.getElementById('save-user');
    const fields = ['name', 'email', 'password', 'phone', 'address', 'role'];
    let editingId = null;
    let busy = false;
    let loadingUser = false;
    toastr.options = { closeButton: true, escapeHtml: true, timeOut: 5000 };

    function request(method, url, data) {
        return $.ajax({
            url,
            method,
            data: data === undefined ? undefined : JSON.stringify(data),
            contentType: 'application/json',
            headers: {
                Accept: 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        });
    }

    function clearErrors() {
        form.querySelectorAll('.is-invalid').forEach(input => input.classList.remove('is-invalid'));
        form.querySelectorAll('.invalid-feedback').forEach(element => { element.textContent = ''; });
    }

    function showError(xhr) {
        let message = xhr.responseJSON?.message || 'Permintaan gagal. Periksa koneksi lalu coba lagi.';
        if (xhr.status === 401) message = 'Login telah berakhir. Silakan login kembali.';
        if (xhr.status === 403) message = 'Akses ditolak. Pengelolaan user hanya untuk admin.';
        if (xhr.status === 419) message = 'Sesi kedaluwarsa. Muat ulang halaman lalu coba lagi.';
        if (xhr.status === 422) {
            Object.entries(xhr.responseJSON?.errors || {}).forEach(([field, messages]) => {
                const input = form.elements.namedItem(field);
                const error = document.getElementById(`error-${field}`);
                if (input && error) {
                    input.classList.add('is-invalid');
                    error.textContent = messages.join(' ');
                }
            });
            form.querySelector('.is-invalid')?.focus();
        }
        toastr.error(message);
        feedback.textContent = message;
        feedback.classList.remove('d-none');
        if (xhr.status === 401) {
            const link = document.createElement('a');
            link.href = root.dataset.loginUrl;
            link.textContent = ' Buka halaman login';
            feedback.append(link);
        }
    }

    const text = $.fn.dataTable.render.text();
    const table = $('#users-table').DataTable({
        processing: true,
        order: [[0, 'desc']],
        ajax: (data, callback) => {
            request('GET', root.dataset.apiUrl).done(response => {
                feedback.classList.add('d-none');
                callback({ data: response.data });
            }).fail(xhr => {
                callback({ data: [] });
                showError(xhr);
            });
        },
        columns: [
            { data: 'id' },
            ...['name', 'email', 'phone', 'address', 'role'].map(field => ({ data: field, defaultContent: '', render: text })),
            { data: 'is_admin', render: value => value ? 'Admin' : 'User' },
            {
                data: null,
                orderable: false,
                searchable: false,
                render: (data, type, row) => type === 'display'
                    ? `<div class="d-flex gap-2"><button type="button" class="btn btn-success btn-sm edit-user">Edit</button><button type="button" class="btn btn-danger btn-sm delete-user" ${String(row.id) === root.dataset.currentUser ? 'disabled title="Akun yang sedang digunakan"' : ''}>Hapus</button></div>`
                    : ''
            }
        ],
        language: {
            search: 'Cari:', lengthMenu: 'Tampilkan _MENU_ user',
            info: 'Menampilkan _START_–_END_ dari _TOTAL_ user',
            infoEmpty: 'Belum ada user', infoFiltered: '(dari _MAX_ user)',
            emptyTable: 'Belum ada data user.', zeroRecords: 'User tidak ditemukan.',
            processing: 'Memuat data…'
        }
    });

    function openForm(user = null) {
        form.reset();
        clearErrors();
        editingId = user?.id ?? null;
        document.getElementById('user-modal-title').textContent = user ? 'Ubah User' : 'Tambah User';
        document.getElementById('user-password').required = !user;
        document.getElementById('password-help').textContent = user
            ? 'Kosongkan password jika tidak ingin menggantinya. Role tidak memberikan akses admin.'
            : 'Password minimal 6 karakter. Role adalah label dan tidak memberikan akses admin.';
        if (user) fields.filter(field => field !== 'password').forEach(field => { form.elements.namedItem(field).value = user[field] ?? ''; });
        modal.show();
    }

    document.getElementById('user-modal').addEventListener('shown.bs.modal', () => form.elements.namedItem('name').focus());
    document.getElementById('user-modal').addEventListener('hide.bs.modal', event => { if (busy) event.preventDefault(); });
    document.getElementById('add-user').addEventListener('click', () => { if (!busy && !loadingUser) openForm(); });

    $('#users-table').on('click', '.edit-user', function () {
        if (busy || loadingUser) return;
        const user = table.row($(this).closest('tr')).data();
        loadingUser = true;
        request('GET', `${root.dataset.apiUrl}/${user.id}`)
            .done(response => openForm(response.data))
            .fail(showError)
            .always(() => { loadingUser = false; });
    });

    form.addEventListener('submit', event => {
        event.preventDefault();
        if (busy || !form.reportValidity()) return;
        clearErrors();
        const data = Object.fromEntries(fields.map(field => [field, form.elements.namedItem(field).value]));
        if (editingId && !data.password) delete data.password;
        busy = true;
        save.disabled = true;
        save.textContent = 'Menyimpan…';
        request(editingId ? 'PUT' : 'POST', editingId ? `${root.dataset.apiUrl}/${editingId}` : root.dataset.apiUrl, data)
            .done(response => {
                busy = false;
                modal.hide();
                toastr.success(response.message);
                table.ajax.reload(null, false);
            })
            .fail(showError)
            .always(() => {
                busy = false;
                save.disabled = false;
                save.textContent = 'Simpan';
            });
    });

    $('#users-table').on('click', '.delete-user', function () {
        if (busy || loadingUser) return;
        const user = table.row($(this).closest('tr')).data();
        if (String(user.id) === root.dataset.currentUser || !window.confirm(`Hapus user "${user.name}"?`)) return;
        busy = true;
        this.disabled = true;
        request('DELETE', `${root.dataset.apiUrl}/${user.id}`)
            .done(response => {
                toastr.success(response.message);
                table.ajax.reload(null, false);
            })
            .fail(showError)
            .always(() => { busy = false; this.disabled = false; });
    });
})();
