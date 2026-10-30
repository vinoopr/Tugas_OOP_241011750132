@if ($errors->any())
    <div class="alert alert-danger" role="alert"><ul class="mb-0">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>
@endif
<div class="row g-3 mb-4">
<div class="col-12 col-md-6">
    <label for="nama_project" class="form-label">Nama Project</label>
    <input id="nama_project" name="nama_project" type="text" class="form-control @error('nama_project') is-invalid @enderror" value="{{ old('nama_project', $project->nama_project ?? '') }}" required maxlength="255">
    @error('nama_project')<div class="invalid-feedback">{{ $message }}</div>@enderror
</div>
<div class="col-12 col-md-6">
    <label for="teknologi" class="form-label">Teknologi</label>
    <input id="teknologi" name="teknologi" type="text" class="form-control @error('teknologi') is-invalid @enderror" value="{{ old('teknologi', $project->teknologi ?? '') }}" required maxlength="255">
    @error('teknologi')<div class="invalid-feedback">{{ $message }}</div>@enderror
</div>
<div class="col-12 col-md-6">
    <label for="status" class="form-label">Status</label>
    <select id="status" name="status" class="form-select @error('status') is-invalid @enderror" required>
        @foreach (\App\Models\Project::STATUSES as $status)
            <option value="{{ $status }}" @selected(old('status', $project->status ?? 'In Progress') === $status)>{{ $status }}</option>
        @endforeach
    </select>
    @error('status')<div class="invalid-feedback">{{ $message }}</div>@enderror
</div>
<div class="col-12 col-md-6">
    <label for="image" class="form-label">Image</label>
    <div class="mb-2">
        <img id="image-preview" src="{{ isset($project) ? $project->image_url : '' }}" alt="Preview gambar project" class="img-thumbnail {{ isset($project) && $project->image ? '' : 'd-none' }}" style="max-height: 160px;">
    </div>
    <input id="image" name="image" type="file" class="form-control @error('image') is-invalid @enderror" accept="image/jpeg,image/png,image/webp" aria-describedby="image-help">
    <div id="image-help" class="form-text">JPG, PNG, WebP maksimal 2 MB. Kosongkan untuk mempertahankan gambar saat mengubah data.</div>
    @error('image')<div class="invalid-feedback">{{ $message }}</div>@enderror
</div>
<div class="col-12">
    <label for="description" class="form-label">Description</label>
    <textarea id="description" name="description" class="form-control @error('description') is-invalid @enderror" rows="5" required maxlength="10000">{{ old('description', $project->description ?? '') }}</textarea>
    @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
</div>
</div>
<script>
    (() => {
        const input = document.getElementById('image');
        const preview = document.getElementById('image-preview');
        const originalUrl = preview.getAttribute('src');
        let previewUrl;

        input.addEventListener('change', () => {
            if (previewUrl) URL.revokeObjectURL(previewUrl);
            const file = input.files[0];
            previewUrl = file ? URL.createObjectURL(file) : null;
            preview.src = previewUrl || originalUrl;
            preview.classList.toggle('d-none', !previewUrl && !originalUrl);
        });
    })();
</script>
