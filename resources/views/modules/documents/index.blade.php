@extends('layouts.app')
@section('title','Document Control')
@section('breadcrumb') <span class="current">Document Control</span> @endsection
@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title"><i class="fa-solid fa-folder-tree" style="color:var(--accent);margin-right:10px"></i>Document Control</h1>
        <p class="page-subtitle">Manage all company documents, drawings, revisions and approvals</p>
    </div>
    <div class="d-flex gap-2">
        <button class="btn btn-secondary" data-bs-toggle="modal" data-bs-target="#quickUploadModal">
            <i class="fa-solid fa-upload"></i> Quick Upload
        </button>
        <a href="{{ route('documents.create') }}" class="btn btn-primary">
            <i class="fa-solid fa-plus"></i> New Document
        </a>
    </div>
</div>

<div class="row g-3 mb-4">
    @foreach([
        ['Total Documents','0','accent','fa-folder-tree'],
        ['Pending Review','0','warning','fa-clock'],
        ['Approved','0','success','fa-check-circle'],
        ['Rev Control','0','info','fa-code-branch'],
    ] as [$l,$v,$c,$i])
    <div class="col-md-3">
        <div class="stat-card {{ $c }}">
            <div class="stat-icon {{ $c }}"><i class="fa-solid {{ $i }}"></i></div>
            <div class="stat-content">
                <div class="stat-value">{{ $documents->count() ?? 0 }}</div>
                <div class="stat-label">{{ $l }}</div>
            </div>
        </div>
    </div>
    @endforeach
</div>

<div class="card mb-3">
    <div class="card-body" style="padding:14px 20px">
        <form method="GET">
            <div class="row g-2 align-items-end">
                <div class="col-md-3">
                    <div style="position:relative">
                        <i class="fa-solid fa-search" style="position:absolute;left:10px;top:50%;transform:translateY(-50%);color:var(--text-light);font-size:12px"></i>
                        <input type="text" name="search" class="form-control" placeholder="Title, filename, tags..."
                               value="{{ request('search') }}" style="padding-left:32px">
                    </div>
                </div>
                <div class="col-md-2">
                    <select name="category" class="form-select">
                        <option value="">All Categories</option>
                        @foreach(['Engineering','Quality','Process','Customer','Vendor','Manufacturing','Archive','Other'] as $cat)
                        <option value="{{ $cat }}" {{ request('category')===$cat?'selected':'' }}>{{ $cat }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <select name="status" class="form-select">
                        <option value="">All Status</option>
                        @foreach(['draft','review','approved','rev_control'] as $s)
                        <option value="{{ $s }}" {{ request('status')===$s?'selected':'' }}>{{ ucfirst(str_replace('_',' ',$s)) }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100"><i class="fa-solid fa-search"></i> Filter</button>
                </div>
                <div class="col-md-2">
                    <a href="{{ route('documents.index') }}" class="btn btn-secondary w-100">Clear</a>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="card table-card">
    <div class="card-header">
        <div class="card-title"><i class="fa-solid fa-list" style="color:var(--accent)"></i> All Documents</div>
    </div>
    <div style="overflow-x:auto">
        <table class="erp-table" style="width:100%">
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Category</th>
                    <th>Rev</th>
                    <th>Status</th>
                    <th>File</th>
                    <th>Uploaded By</th>
                    <th>Date</th>
                    <th style="width:130px">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($documents ?? collect() as $doc)
                <tr>
                    <td>
                        <div style="font-weight:500">{{ $doc->title }}</div>
                        @if($doc->description)
                        <div style="font-size:11.5px;color:var(--text-muted)">{{ Str::limit($doc->description,60) }}</div>
                        @endif
                    </td>
                    <td><span class="badge badge-secondary">{{ $doc->category ?? '—' }}</span></td>
                    <td style="font-family:monospace;font-size:12.5px">{{ $doc->revision ?? '—' }}</td>
                    <td><x-status-badge :status="$doc->status" /></td>
                    <td>
                        <div style="display:flex;align-items:center;gap:6px;font-size:12.5px;color:var(--text-muted)">
                            @php
                                $ft = $doc->file_type ?? '';
                                $iconClass = str_contains($ft,'pdf') ? 'fa-file-pdf' : (str_contains($ft,'image') ? 'fa-file-image' : (str_contains($ft,'word') ? 'fa-file-word' : 'fa-file'));
                                $iconColor = str_contains($ft,'pdf') ? 'var(--danger)' : (str_contains($ft,'image') ? 'var(--success)' : 'var(--accent)');
                            @endphp
                            <i class="fa-solid {{ $iconClass }}" style="color:{{ $iconColor }}"></i>
                            {{ Str::limit($doc->file_name ?? '—', 30) }}
                        </div>
                    </td>
                    <td style="font-size:12.5px;color:var(--text-muted)">{{ $doc->uploadedBy?->name ?? '—' }}</td>
                    <td style="font-size:12px;color:var(--text-muted)">{{ $doc->created_at->format('M d, Y') }}</td>
                    <td>
                        <div class="d-flex gap-1">
                            <a href="{{ route('documents.download',$doc) }}" class="btn btn-secondary btn-sm btn-icon" title="Download">
                                <i class="fa-solid fa-download" style="font-size:11px"></i>
                            </a>
                            <a href="{{ route('documents.edit',$doc) }}" class="btn btn-secondary btn-sm btn-icon" title="Edit">
                                <i class="fa-solid fa-pen" style="font-size:11px"></i>
                            </a>
                            @if($doc->status !== 'approved')
                            <form method="POST" action="{{ route('documents.approve',$doc) }}">
                                @csrf
                                <button type="submit" class="btn btn-success btn-sm btn-icon" title="Approve">
                                    <i class="fa-solid fa-check" style="font-size:11px"></i>
                                </button>
                            </form>
                            @endif
                            <form method="POST" action="{{ route('documents.destroy',$doc) }}" onsubmit="return confirm('Delete document?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-secondary btn-sm btn-icon">
                                    <i class="fa-solid fa-trash" style="font-size:11px;color:var(--danger)"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                {{-- Demo rows --}}
                @php
                $demoDocs = [
                    ['PCB Assembly X72 — Work Instructions','Engineering','B','approved','WI-PCB-X72-RevB.pdf','Engineering'],
                    ['Quality Control Plan 2025','Quality','1.2','approved','QCP-2025-v1.2.pdf','Quality'],
                    ['BOM — Motor Controller v3','Engineering','C','rev_control','BOM-MC-v3-RevC.xlsx','Engineering'],
                    ['Supplier Qualification Form','Vendor','1.0','review','SQF-v1.0.docx','Purchasing'],
                    ['ISO 9001 Audit Report Jul 2025','Quality','1.0','draft','ISO-Audit-Jul25.pdf','Quality'],
                    ['Assembly Drawing — Enclosure Fab','Engineering','A','approved','DWG-ENC-001-RevA.dxf','Engineering'],
                ];
                @endphp
                @foreach($demoDocs as $i => $d)
                <tr>
                    <td>
                        <div style="font-weight:500">{{ $d[0] }}</div>
                    </td>
                    <td><span class="badge badge-secondary">{{ $d[1] }}</span></td>
                    <td style="font-family:monospace;font-size:12.5px">Rev {{ $d[2] }}</td>
                    <td><x-status-badge :status="$d[3]" /></td>
                    <td>
                        <div style="display:flex;align-items:center;gap:6px;font-size:12.5px;color:var(--text-muted)">
                            @php
                                $ext = pathinfo($d[4], PATHINFO_EXTENSION);
                                $ic = match($ext) { 'pdf'=>'fa-file-pdf', 'xlsx','xls'=>'fa-file-excel', 'docx','doc'=>'fa-file-word', 'dxf','dwg'=>'fa-file-code', default=>'fa-file' };
                                $icolor = match($ext) { 'pdf'=>'var(--danger)', 'xlsx','xls'=>'var(--success)', 'docx','doc'=>'#2563eb', default=>'var(--accent)' };
                            @endphp
                            <i class="fa-solid {{ $ic }}" style="color:{{ $icolor }}"></i>
                            {{ $d[4] }}
                        </div>
                    </td>
                    <td style="font-size:12.5px;color:var(--text-muted)">{{ $d[5] }}</td>
                    <td style="font-size:12px;color:var(--text-muted)">{{ now()->subDays($i * 3)->format('M d, Y') }}</td>
                    <td>
                        <div class="d-flex gap-1">
                            <button class="btn btn-secondary btn-sm btn-icon" title="Download" onclick="showToast('Download started','info')">
                                <i class="fa-solid fa-download" style="font-size:11px"></i>
                            </button>
                            <button class="btn btn-secondary btn-sm btn-icon" title="Edit">
                                <i class="fa-solid fa-pen" style="font-size:11px"></i>
                            </button>
                            @if($d[3] !== 'approved')
                            <button class="btn btn-success btn-sm btn-icon" title="Approve" onclick="showToast('Document approved','success')">
                                <i class="fa-solid fa-check" style="font-size:11px"></i>
                            </button>
                            @endif
                            <button class="btn btn-secondary btn-sm btn-icon" onclick="return confirm('Delete?')">
                                <i class="fa-solid fa-trash" style="font-size:11px;color:var(--danger)"></i>
                            </button>
                        </div>
                    </td>
                </tr>
                @endforeach
                @endforelse
            </tbody>
        </table>
    </div>
    @if(isset($documents) && method_exists($documents,'hasPages') && $documents->hasPages())
    <div class="card-body" style="padding:12px 20px;border-top:1px solid var(--border)">{{ $documents->links() }}</div>
    @endif
</div>

{{-- Quick Upload Modal --}}
<div class="modal fade" id="quickUploadModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content" style="background:var(--card-bg);border:1px solid var(--border);border-radius:12px">
            <div class="modal-header" style="border-bottom:1px solid var(--border)">
                <h5 class="modal-title" style="font-size:15px;font-weight:600">
                    <i class="fa-solid fa-upload" style="color:var(--accent);margin-right:8px"></i>Quick Upload
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="{{ route('documents.upload') }}" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label">Title <span style="color:var(--danger)">*</span></label>
                            <input type="text" name="title" class="form-control" required placeholder="Document title...">
                        </div>
                        <div class="col-12">
                            <label class="form-label">File <span style="color:var(--danger)">*</span></label>
                            <input type="file" name="file" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Category</label>
                            <select name="category" class="form-select">
                                @foreach(['Engineering','Quality','Process','Customer','Vendor','Other'] as $cat)
                                <option>{{ $cat }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Revision</label>
                            <input type="text" name="revision" class="form-control" placeholder="A, 1.0..." value="A">
                        </div>
                        <div class="col-12">
                            <label class="form-label">Description</label>
                            <textarea name="description" class="form-control" rows="2" placeholder="Brief description..."></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer" style="border-top:1px solid var(--border)">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary"><i class="fa-solid fa-upload"></i> Upload</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
