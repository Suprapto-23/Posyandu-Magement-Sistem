@extends('layouts.kader')

@section('title', 'Profil Saya')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card shadow border-0 rounded-4">
            <div class="card-header bg-white py-3">
                <h6 class="m-0 font-weight-bold text-primary">Edit Profil</h6>
            </div>
            <div class="card-body">
                <form action="{{ route('kader.profile.update') }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="mb-3">
                        <label class="form-label">Nama Lengkap</label>
                        <input type="text" class="form-control" name="name" value="{{ $user->profile->full_name ?? '' }}" required>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" class="form-control" name="email" value="{{ $user->email }}" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">No. HP / WhatsApp</label>
                        <input type="text" class="form-control" name="no_hp" value="{{ $user->profile->phone_number ?? '' }}">
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="{{ route('kader.profile.password') }}" class="btn btn-outline-danger">Ganti Password</a>
                        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection