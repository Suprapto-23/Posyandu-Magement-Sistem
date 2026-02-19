@extends('layouts.bidan')

@section('title', 'Data Lansia')
@section('page-title', 'Monitoring Lansia')
@section('page-subtitle', 'Pemantauan kesehatan lansia & penyakit kronis')

@section('content')
<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="card border-0 shadow-sm rounded-4 h-100 bg-success bg-opacity-10 border-start border-success border-4">
            <div class="card-body d-flex align-items-center">
                <div class="flex-grow-1">
                    <h6 class="text-muted text-uppercase small fw-bold mb-1">Tensi Normal</h6>
                    <h2 class="mb-0 fw-bold text-success">{{ $statistik->normal ?? 0 }}</h2>
                </div>
                <i class="fas fa-heart text-success opacity-25 fa-3x"></i>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card border-0 shadow-sm rounded-4 h-100 bg-warning bg-opacity-10 border-start border-warning border-4">
            <div class="card-body d-flex align-items-center">
                <div class="flex-grow-1">
                    <h6 class="text-muted text-uppercase small fw-bold mb-1">Hipertensi</h6>
                    <h2 class="mb-0 fw-bold text-warning">{{ $statistik->hipertensi ?? 0 }}</h2>
                </div>
                <i class="fas fa-exclamation-circle text-warning opacity-25 fa-3x"></i>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card border-0 shadow-sm rounded-4 h-100 bg-danger bg-opacity-10 border-start border-danger border-4">
            <div class="card-body d-flex align-items-center">
                <div class="flex-grow-1">
                    <h6 class="text-muted text-uppercase small fw-bold mb-1">Bahaya / Kritis</h6>
                    <h2 class="mb-0 fw-bold text-danger">{{ $statistik->kritis ?? 0 }}</h2>
                </div>
                <i class="fas fa-ambulance text-danger opacity-25 fa-3x"></i>
            </div>
        </div>
    </div>
</div>

<div class="card border-0 shadow-sm rounded-4">
    <div class="card-header bg-white py-3 d-flex flex-column flex-md-row justify-content-between align-items-center gap-3">
        <div class="input-group" style="max-width: 400px;">
            <span class="input-group-text bg-light border-end-0"><i class="fas fa-search text-muted"></i></span>
            <input type="text" id="searchInput" class="form-control border-start-0 bg-light" 
                   placeholder="Cari nama lansia, alamat, atau penyakit..." autocomplete="off">
        </div>
        
        <div class="d-flex gap-2">
            <a href="{{ route('bidan.laporan.lansia') }}" class="btn btn-sm btn-outline-danger">
                <i class="fas fa-file-pdf me-2"></i>Cetak Laporan
            </a>
            <div class="d-none d-md-block">
                <span class="badge bg-primary bg-opacity-10 text-primary px-3 py-2 h-100 d-flex align-items-center">
                    <i class="fas fa-sync-alt me-2"></i> Data Realtime
                </span>
            </div>
        </div>
    </div>

    <div class="card-body p-0">
        <div id="dataContainer">
            @include('bidan.pasien.partials.table_lansia')
        </div>
    </div>
</div>

@push('scripts')
<script>
$(document).ready(function() {
    let searchTimer;
    
    // 1. Live Search
    $('#searchInput').on('keyup', function() {
        clearTimeout(searchTimer);
        let query = $(this).val();
        searchTimer = setTimeout(function() { fetchData(query); }, 300);
    });

    // 2. Pagination Handling
    $(document).on('click', '.pagination a', function(e) {
        e.preventDefault();
        let url = $(this).attr('href');
        fetchData($('#searchInput').val(), url);
    });

    // 3. Auto Refresh (15 Detik)
    setInterval(function() {
        if ($('#searchInput').val() === '') {
            fetchData('', window.location.href, false);
        }
    }, 15000);

    function fetchData(query = '', url = null, showLoading = true) {
        if(!url) url = "{{ route('bidan.pasien.lansia') }}";
        
        if(showLoading) $('#dataContainer').css('opacity', '0.5');

        $.ajax({
            url: url,
            type: "GET",
            data: { search: query },
            success: function(response) {
                $('#dataContainer').html(response).css('opacity', '1');
            },
            error: function() { $('#dataContainer').css('opacity', '1'); }
        });
    }
});
</script>
@endpush
@endsection