@extends('layouts.bidan')

@section('title', 'Data Remaja')
@section('page-title', 'Data Remaja')
@section('page-subtitle', 'Monitoring kesehatan remaja & reproduksi')

@section('content')
<div class="card border-0 shadow-sm rounded-4">
    <div class="card-header bg-white py-3 d-flex flex-column flex-md-row justify-content-between align-items-center gap-3">
        <div class="input-group" style="max-width: 400px;">
            <span class="input-group-text bg-light border-end-0"><i class="fas fa-search text-muted"></i></span>
            <input type="text" id="searchInput" class="form-control border-start-0 bg-light" 
                   placeholder="Cari nama remaja, sekolah, atau NIK..." autocomplete="off">
        </div>

        <div class="d-flex gap-2 align-items-center">
            <a href="{{ route('bidan.laporan.remaja') }}" class="btn btn-sm btn-outline-danger d-flex align-items-center gap-2">
                <i class="fas fa-file-pdf"></i>
                <span class="d-none d-md-inline">Laporan PDF</span>
            </a>
            <div class="d-none d-md-block">
                <span class="badge bg-success bg-opacity-10 text-success px-3 py-2">
                    <i class="fas fa-sync-alt me-1"></i> Data Realtime
                </span>
            </div>
        </div>
    </div>

    <div class="card-body p-0">
        <div id="dataContainer">
            @include('bidan.pasien.partials.table_remaja')
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
        if(!url) url = "{{ route('bidan.pasien.remaja') }}";
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