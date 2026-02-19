@extends('layouts.kader')

@section('title', 'Ganti Password')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card shadow border-0 rounded-4">
            <div class="card-header bg-white py-3">
                <h6 class="m-0 font-weight-bold text-danger">Ganti Password</h6>
            </div>
            <div class="card-body">
                <form action="{{ route('kader.profile.update-password') }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="mb-3">
                        <label class="form-label">Password Lama</label>
                        <input type="password" class="form-control" name="current_password" required>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Password Baru</label>
                        <input type="password" class="form-control" name="password" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Konfirmasi Password Baru</label>
                        <input type="password" class="form-control" name="password_confirmation" required>
                    </div>

                    <div class="d-grid">
                        <button type="submit" class="btn btn-danger">Ubah Password</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection