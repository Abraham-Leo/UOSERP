@extends('layouts.app')
@section('title', isset($document->id) ? 'Edit Document' : 'New Document')
@section('breadcrumb')
    <a href="{{ route('documents.index') }}" style="color:var(--text-muted);text-decoration:none">Document Control</a> /
    <span class="current">{{ isset($document->id) ? $document->title : 'New Document' }}</span>
@endsection
@section('content')
@php $document = $document ?? null; @endphp
<form method="POST"
      action="{{ isset($document->id) ? route('documents.update',$document) : route('documents.store') }}"
      enctype="multipart/form-data">
@csrf
@if(isset($document->id)) @method('PUT') @endif
<div class="page-header">
    <div><h1 class="page-title"><i class="fa-solid fa-file-circle-plus" style="color:var(--accent);margin-right:10px"></i>
        {{ isset($document->id) ? 'Edit: '.$document->title : 'New Document' }}
    </h1></div>
    <div class="d-flex gap-2">
        <a href="{{ route('documents.index') }}" class="btn btn-secondary"><i class="fa-solid fa-arrow-left"></i> Back</a>
        <button type="submit" class="btn btn-primary"><i class="fa-solid fa-save"></i> Save Document</button>
    </div>
</div>

<div class="row g-4">
    <div class="col-lg-8">
        <div class="card mb-4">
            <div class="card-header"><div class="card-title"><i class="fa-solid fa-info-circle" style="color:var(--accent)"></i> Document Information</div></div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-8">
                        <label class="form-label">Title <span style="color:var(--danger)">*</span></label>
                        <input type="text" name="title" class="form-control @error('title') is-invalid @enderror"
                               value="{{ old('title', $document->title ?? '') }}" required
                               placeholder="Document title...">
                        @error('title')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Revision</label>
                        <input type="text" name="revision" class="form-control"
                               value="{{ old('revision', $document->revision ?? 'A') }}" placeholder="A, 1.0, 2.1...">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Category</label>
                        <select name="category" class="form-select">
                            <option value="">— Select Category —</option>
                            @foreach(['Engineering','Quality','Process','Customer','Vendor','Manufacturing','Archive','Other'] as $cat)
                            <option value="{{ $cat }}" {{ old('category', $document->category ?? '') === $cat ? 'selected' : '' }}>{{ $cat }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-select">
                            @foreach(['draft','review','approved','rev_control'] as $s)
                            <option value="{{ $s }}" {{ old('status', $document->status ?? 'draft') === $s ? 'selected' : '' }}>
                                {{ ucfirst(str_replace('_',' ',$s)) }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Document Tags</label>
                        <input type="text" name="tags" class="form-control"
                               value="{{ old('tags', is_array($document->tags ?? null) ? implode(', ', $document->tags) : ($document->tags ?? '')) }}"
                               placeholder="tag1, tag2, tag3...">
                    </div>
                    <div class="col-12">
                        <label class="form-label">Description</label>
                        <textarea name="description" class="form-control" rows="3"
                                  placeholder="Brief description of this document...">{{ old('description', $document->description ?? '') }}</textarea>
                    </div>

                    @if(!isset($document->id))
                    <div class="col-12">
                        <label class="form-label">Upload File <span style="color:var(--danger)">*</span></label>
                        <div style="border:2px dashed var(--border);border-radius:10px;padding:24px;text-align:center;cursor:pointer;transition:border-color 0.2s"
                             id="dropZone" onclick="document.getElementById('fileInput').click()"
                             ondragover="event.preventDefault();this.style.borderColor='var(--accent)'"
                             ondragleave="this.style.borderColor='var(--border)'"
                             ondrop="handleDrop(event)">
                            <i class="fa-solid fa-cloud-arrow-up" style="font-size:32px;color:var(--text-muted);display:block;margin-bottom:8px"></i>
                            <div style="font-size:14px;color:var(--text-muted)">Click to browse or drag & drop file here</div>
                            <div style="font-size:12px;color:var(--text-light);margin-top:4px">PDF, DOCX, XLSX, DXF, DWG, PNG, JPG (max 50MB)</div>
                            <div id="fileName" style="margin-top:10px;font-size:13px;font-weight:500;color:var(--accent)"></div>
                        </div>
                        <input type="file" id="fileInput" name="file" style="display:none"
                               onchange="document.getElementById('fileName').textContent = this.files[0]?.name ?? ''">
                    </div>
                    @else
                    <div class="col-12">
                        <label class="form-label">Replace File (optional)</label>
                        <input type="file" name="file" class="form-control">
                        <div style="margin-top:6px;font-size:12.5px;color:var(--text-muted)">
                            Current: <span style="color:var(--accent)">{{ $document->file_name }}</span>
                            ({{ number_format($document->file_size / 1024, 1) }} KB)
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card mb-4">
            <div class="card-header"><div class="card-title"><i class="fa-solid fa-link" style="color:var(--info)"></i> Link to Transaction</div></div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-12">
                        <label class="form-label">Link Type</label>
                        <select name="documentable_type" class="form-select" id="linkType" onchange="toggleLinkId()">
                            <option value="">— None —</option>
                            <option value="App\Models\Part" {{ old('documentable_type', $document->documentable_type ?? '') === 'App\Models\Part' ? 'selected' : '' }}>Part / BOM</option>
                            <option value="App\Models\Order" {{ old('documentable_type', $document->documentable_type ?? '') === 'App\Models\Order' ? 'selected' : '' }}>Sales Order</option>
                            <option value="App\Models\WorkOrder" {{ old('documentable_type', $document->documentable_type ?? '') === 'App\Models\WorkOrder' ? 'selected' : '' }}>Work Order</option>
                            <option value="App\Models\PurchaseOrder" {{ old('documentable_type', $document->documentable_type ?? '') === 'App\Models\PurchaseOrder' ? 'selected' : '' }}>Purchase Order</option>
                            <option value="App\Models\Ncr" {{ old('documentable_type', $document->documentable_type ?? '') === 'App\Models\Ncr' ? 'selected' : '' }}>NCR</option>
                            <option value="App\Models\Eco" {{ old('documentable_type', $document->documentable_type ?? '') === 'App\Models\Eco' ? 'selected' : '' }}>ECO</option>
                        </select>
                    </div>
                    <div class="col-12" id="linkIdField">
                        <label class="form-label">Reference ID</label>
                        <input type="number" name="documentable_id" class="form-control"
                               value="{{ old('documentable_id', $document->documentable_id ?? '') }}"
                               placeholder="Enter record ID...">
                    </div>
                </div>
            </div>
        </div>

        <div class="card mb-4">
            <div class="card-header"><div class="card-title"><i class="fa-solid fa-circle-info" style="color:var(--info)"></i> Document Control Workflow</div></div>
            <div class="card-body">
                @foreach([
                    ['Draft','Author creates document','secondary'],
                    ['Review','Peer review process','warning'],
                    ['Approved','Management sign-off','success'],
                    ['Rev Control','Under change control','accent'],
                ] as [$s,$d,$c])
                <div style="display:flex;align-items:flex-start;gap:10px;margin-bottom:12px">
                    <span class="badge badge-{{ $c }}" style="font-size:10px;flex-shrink:0;margin-top:2px">{{ $s }}</span>
                    <span style="font-size:12.5px;color:var(--text-muted)">{{ $d }}</span>
                </div>
                @endforeach
            </div>
        </div>

        <button type="submit" class="btn btn-primary w-100 btn-lg">
            <i class="fa-solid fa-{{ isset($document->id) ? 'save' : 'upload' }}"></i>
            {{ isset($document->id) ? 'Update Document' : 'Upload Document' }}
        </button>
    </div>
</div>
</form>
@endsection
@push('scripts')
<script>
function handleDrop(event) {
    event.preventDefault();
    document.getElementById('dropZone').style.borderColor = 'var(--border)';
    const file = event.dataTransfer.files[0];
    if (file) {
        const input = document.getElementById('fileInput');
        const dt = new DataTransfer();
        dt.items.add(file);
        input.files = dt.files;
        document.getElementById('fileName').textContent = file.name;
    }
}
function toggleLinkId() {
    const val = document.getElementById('linkType').value;
    document.getElementById('linkIdField').style.display = val ? '' : 'none';
}
toggleLinkId();
</script>
@endpush
