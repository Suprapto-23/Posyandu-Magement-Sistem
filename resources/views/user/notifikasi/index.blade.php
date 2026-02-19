@extends('layouts.user')

@section('title', 'Notifikasi')
@section('page-title', 'Pusat Notifikasi')

@section('content')
<div class="container-fluid animate-fade-in">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                    <h6 class="fw-bold mb-0">Semua Pesan</h6>
                    <span id="unread-badge" class="badge bg-danger d-none">0 Baru</span>
                </div>
                
                {{-- ID ditambahkan untuk target manipulasi DOM --}}
                <div id="notification-list" class="list-group list-group-flush">
                    @forelse($notifikasi as $notif)
                    <div class="list-group-item p-4 {{ $notif->dibaca ? 'bg-white' : 'bg-light border-start border-4 border-primary' }}">
                        <div class="d-flex justify-content-between mb-2">
                            <h6 class="mb-0 fw-bold {{ $notif->dibaca ? 'text-dark' : 'text-primary' }}">
                                {{ $notif->judul }}
                            </h6>
                            <small class="text-muted">{{ $notif->created_at->diffForHumans() }}</small>
                        </div>
                        <p class="mb-0 text-secondary">{{ $notif->pesan }}</p>
                    </div>
                    @empty
                    <div id="no-notification" class="text-center py-5 text-muted">
                        <i class="far fa-bell-slash fa-2x mb-3"></i>
                        <p>Tidak ada notifikasi saat ini.</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function fetchNotifications() {
        fetch("{{ route('user.notifikasi.latest') }}")
            .then(response => response.json())
            .then(data => {
                const list = document.getElementById('notification-list');
                const badge = document.getElementById('unread-badge');
                
                // Update Badge Angka Belum Dibaca
                if(data.unread_count > 0) {
                    badge.innerText = `${data.unread_count} Baru`;
                    badge.classList.remove('d-none');
                } else {
                    badge.classList.add('d-none');
                }

                if (data.notifikasi.length > 0) {
                    // Kosongkan list lama (opsional: bisa dikembangkan dengan membandingkan ID terbaru)
                    let html = '';
                    data.notifikasi.forEach(notif => {
                        const bgClass = notif.dibaca ? 'bg-white' : 'bg-light border-start border-4 border-primary';
                        const textClass = notif.dibaca ? 'text-dark' : 'text-primary';
                        
                        html += `
                            <div class="list-group-item p-4 ${bgClass}">
                                <div class="d-flex justify-content-between mb-2">
                                    <h6 class="mb-0 fw-bold ${textClass}">${notif.judul}</h6>
                                    <small class="text-muted">${notif.waktu}</small>
                                </div>
                                <p class="mb-0 text-secondary">${notif.pesan}</p>
                            </div>
                        `;
                    });
                    list.innerHTML = html;
                }
            })
            .catch(error => console.error('Error fetching notifications:', error));
    }

    // Jalankan setiap 15 detik (Real-time polling)
    setInterval(fetchNotifications, 15000);
</script>
@endpush
@endsection