@extends('layouts.user')

@section('title', 'Data Remaja')

@section('content')
<div class="container-fluid animate-fade-in">
    <div class="alert alert-light border text-center py-5 shadow-sm">
        <div class="mb-3">
            <i class="fas fa-user-graduate fa-4x text-muted opacity-25"></i>
        </div>
        <h5 class="fw-bold text-dark">Data Tidak Ditemukan</h5>
        <p class="text-muted mb-0">
            NIK akun Anda tidak terdaftar sebagai peserta Remaja di sistem Posyandu.<br>
            Jika Anda merasa ini kesalahan, silakan hubungi Kader.
        </p>
    </div>
</div>
@endsection