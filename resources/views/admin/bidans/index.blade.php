@extends('layouts.app')

@section('title', 'Manajemen Bidan')

@push('styles')
<style>
    .page-header {
        background: linear-gradient(135deg, #27ae60 0%, #229954 100%);
        color: white;
        padding: 2rem;
        border-radius: 15px;
        margin-bottom: 2rem;
        box-shadow: 0 4px 15px rgba(39, 174, 96, 0.3);
    }
    .card-custom {
        border: none;
        border-radius: 15px;
        box-shadow: 0 2px 15px rgba(0, 0, 0, 0.1);
        margin-bottom: 1.5rem;
        overflow: hidden;
        background: white;
    }
    .card-header-custom {
        background: linear-gradient(135deg, #27ae60 0%, #229954 100%);
        border: none;
        padding: 1.25rem 1.5rem;
    }
    .card-header-custom h4 {
        margin: 0;
        color: white;
        font-weight: 600;
    }
    .search-filter-card {
        background: white;
        border-radius: 15px;
        padding: 1.5rem;
        margin-bottom: 1.5rem;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
    }
    .form-control-custom, .form-select-custom {
        border: 2px solid #e9ecef;
        border-radius: 10px;
        padding: 0.75rem 1rem;
    }
    .table-custom thead th {
        background-color: #f8f9fa;
        border: none;
        padding: 1rem 1.25rem;
        font-weight: 600;
        color: #2c3e50;
        border-bottom: 2px solid #27ae60;
    }
    .table-custom tbody td {
        padding: 1rem 1.25rem;
        border-bottom: 1px solid #f1f1f1;
        vertical-align: middle;
    }
    .user-avatar {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: linear-gradient(135deg, #27ae60, #229954);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: 600;
        margin-right: 0.75rem;
    }
    .user-info { display: flex; align-items: center; }
    .user-name { font-weight: 600; color: #2c3e50; margin: 0; font-size: 0.95rem; }
    .user-email { font-size: 0.75rem; color: #7f8c8d; margin: 0; }
    .status-badge {
        padding: 0.25rem 0.75rem;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
    }
    .status-active { background-color: rgba(46, 204, 113, 0.2); color: #27ae60; }
    .status-inactive { background-color: rgba(231, 76, 60, 0.2); color: #e74c3c; }
    .action-buttons { display: flex; gap: 0.5rem; }
    .btn-action {
        width: 36px; height: 36px; border-radius: 10px;
        display: flex; align-items: center; justify-content: center;
        border: none; transition: all 0.3s ease;
    }
    .btn-view { background-color: rgba(52, 152, 219, 0.1); color: #3498db; }
    .btn-edit { background-color: rgba(155, 89, 182, 0.1); color: #9b59b6; }
    .btn-reset { background-color: rgba(243, 156, 18, 0.1); color: #f39c12; }
    .btn-delete { background-color: rgba(231, 76, 60, 0.1); color: #e74c3c; }
</style>
@endpush

@section('content')
<div class="main-content">
    @if(session('password'))
    <div class="alert alert-warning alert-dismissible fade show" role="alert">
        <h5 class="alert-heading"><i class="fas fa-key"></i> Akun Berhasil Dibuat!</h5>
        <hr>
        <p><strong>Email:</strong> {{ session('email') }}</p>
        <p><strong>Password:</strong> <code class="text-danger fs-5">{{ session('password') }}</code></p>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    <div class="page-header">
        <div class="d-flex justify-content-between align-items-center flex-wrap">
            <div>
                <h2><i class="fas fa-user-md me-2"></i>Manajemen Bidan</h2>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">Bidan</li>
                    </ol>
                </nav>
            </div>
            <div class="mt-3 mt-md-0">
                <a href="{{ route('admin.bidans.create') }}" class="btn btn-light">
                    <i class="fas fa-user-plus me-2"></i>Tambah Bidan
                </a>
            </div>
        </div>
    </div>

    <div class="search-filter-card">
        <form method="GET">
            <div class="row align-items-end">
                <div class="col-md-6 mb-3 mb-md-0">
                    <label class="form-label fw-bold">Pencarian</label>
                    <input type="text" name="search" class="form-control form-control-custom" 
                           placeholder="Cari nama atau email..." value="{{ request('search') }}">
                </div>
                <div class="col-md-3 mb-3 mb-md-0">
                    <label class="form-label fw-bold">Status</label>
                    <select name="status" class="form-select form-select-custom">
                        <option value="">Semua Status</option>
                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Aktif</option>
                        <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Nonaktif</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <button type="submit" class="btn w-100 btn-success" style="background: #27ae60;">
                        <i class="fas fa-search me-1"></i>Filter
                    </button>
                </div>
            </div>
        </form>
    </div>

    <div class="card-custom">
        <div class="card-header-custom">
            <div class="d-flex justify-content-between align-items-center flex-wrap">
                <h4><i class="fas fa-list me-2"></i>Daftar Bidan</h4>
                <div class="text-white">Total: <strong>{{ $bidans->total() }}</strong></div>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-custom">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Identitas</th>
                            <th>NIK</th>
                            <th>Status Akun</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($bidans as $bidan)
                        @php
                            $name = $bidan->profile->full_name ?? $bidan->name;
                            $initials = strtoupper(substr($name, 0, 1));
                        @endphp
                        <tr>
                            <td>{{ $loop->iteration + ($bidans->currentPage() - 1) * $bidans->perPage() }}</td>
                            <td>
                                <div class="user-info">
                                    <div class="user-avatar">{{ $initials }}</div>
                                    <div>
                                        <p class="user-name">{{ $name }}</p>
                                        <p class="user-email">{{ $bidan->email }}</p>
                                    </div>
                                </div>
                            </td>
                            <td>{{ $bidan->profile->nik ?? '-' }}</td>
                            <td>
                                <span class="status-badge status-{{ $bidan->status }}">
                                    {{ $bidan->status == 'active' ? 'Aktif' : 'Nonaktif' }}
                                </span>
                            </td>
                            <td>
                                <div class="action-buttons">
                                    <a href="{{ route('admin.bidans.show', $bidan->id) }}" class="btn-action btn-view" title="Detail"><i class="fas fa-eye"></i></a>
                                    <a href="{{ route('admin.bidans.edit', $bidan->id) }}" class="btn-action btn-edit" title="Edit"><i class="fas fa-edit"></i></a>
                                    <form action="{{ route('admin.bidans.reset-password', $bidan->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button class="btn-action btn-reset" title="Reset Password" onclick="return confirm('Reset password?')"><i class="fas fa-key"></i></button>
                                    </form>
                                    <form action="{{ route('admin.bidans.destroy', $bidan->id) }}" method="POST" class="d-inline">
                                        @csrf @method('DELETE')
                                        <button class="btn-action btn-delete" title="Hapus" onclick="return confirm('Hapus bidan ini?')"><i class="fas fa-trash"></i></button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center py-5">
                                <h5 class="text-muted">Tidak ada data bidan</h5>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            @if($bidans->hasPages())
                <div class="mt-4">{{ $bidans->links('vendor.pagination.bootstrap-5') }}</div>
            @endif
        </div>
    </div>
</div>
@endsection