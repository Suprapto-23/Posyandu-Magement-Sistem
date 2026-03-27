@extends('layouts.user')
@section('title', 'Ruang Konseling')

@section('content')
<div class="max-w-5xl mx-auto animate-[slideDown_0.5s_ease-out]">

    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-black text-slate-800 tracking-tight font-poppins">Konsultasi Medis</h1>
            <p class="text-sm text-slate-500 mt-1">Sampaikan keluhan kesehatan Anda secara rahasia kepada Bidan.</p>
        </div>
    </div>

    <div class="bg-white rounded-[24px] border border-slate-200/80 shadow-[0_8px_30px_rgb(0,0,0,0.04)] overflow-hidden flex flex-col h-[75vh] min-h-[500px]">
        
        <div class="p-4 border-b border-slate-100 bg-white flex items-center gap-4 shadow-sm z-20">
            <div class="w-12 h-12 rounded-full bg-teal-50 border border-teal-100 text-teal-600 flex items-center justify-center text-xl shrink-0">
                <i class="fas fa-user-md"></i>
            </div>
            <div>
                <h4 class="font-bold text-slate-800 text-[15px]">Bidan Posyandu</h4>
                <p class="text-[11px] text-emerald-500 font-bold flex items-center gap-1.5 mt-0.5">
                    <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span> Siap Melayani (Enkripsi Aktif)
                </p>
            </div>
        </div>

        <div class="flex-1 bg-[#e5ddd5]/20 relative relative">
            <div class="absolute inset-0 opacity-[0.03] pointer-events-none z-0" style="background-image: radial-gradient(#000 1px, transparent 1px); background-size: 20px 20px;"></div>
            
            <div id="chatArea" class="absolute inset-0 p-5 overflow-y-auto custom-scrollbar z-10 flex flex-col">
                <div class="flex items-center justify-center h-full w-full" id="chatLoader">
                    <i class="fas fa-circle-notch animate-spin text-teal-500 text-3xl"></i>
                </div>
            </div>
        </div>

        <div class="p-4 bg-slate-50 border-t border-slate-200 z-20">
            <form id="formChat" class="flex items-end gap-3">
                <div class="flex-1 relative">
                    <input type="text" id="pesanInput" placeholder="Ketik pesan atau keluhan Anda di sini..." class="w-full bg-white border border-slate-300 text-sm font-medium rounded-2xl px-5 py-3.5 outline-none focus:border-teal-500 focus:ring-4 focus:ring-teal-500/10 transition-all shadow-sm" required autocomplete="off">
                </div>
                <button type="submit" id="btnKirim" class="w-12 h-12 bg-teal-600 text-white rounded-2xl flex items-center justify-center hover:bg-teal-700 transition-all shadow-[0_4px_12px_rgba(13,148,136,0.3)] hover:-translate-y-0.5 shrink-0">
                    <i class="fas fa-paper-plane text-lg"></i>
                </button>
            </form>
        </div>

    </div>
</div>

@push('scripts')
<script>
    const chatArea = document.getElementById('chatArea');
    const formChat = document.getElementById('formChat');
    const inputPesan = document.getElementById('pesanInput');
    const btnKirim = document.getElementById('btnKirim');
    const loader = document.getElementById('chatLoader');
    let isFirstLoad = true;

    // FUNGSI MEMUAT CHAT
    function fetchChat() {
        fetch("{{ route('user.konseling.fetch-chat') }}", {
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(res => res.json())
        .then(data => {
            if(loader) loader.style.display = 'none';

            // Jika belum ada chat sama sekali
            if(data.html.trim() === '') {
                chatArea.innerHTML = `
                    <div class="flex flex-col items-center justify-center h-full text-center opacity-60 m-auto">
                        <i class="fas fa-comment-medical text-5xl mb-4 text-teal-500"></i>
                        <p class="text-[15px] font-bold text-slate-700">Mulai Konsultasi Anda</p>
                        <p class="text-[12px] font-medium text-slate-500 mt-1 max-w-xs">Kirim pesan pertama Anda. Bidan akan membalas pesan Anda di sini.</p>
                    </div>`;
                return;
            }

            // Cek apakah user sedang scroll ke atas (agar tidak dipaksa ke bawah saat baca chat lama)
            const isScrolledToBottom = chatArea.scrollHeight - chatArea.clientHeight <= chatArea.scrollTop + 50;
            
            chatArea.innerHTML = data.html;
            
            // Scroll otomatis ke bawah jika ini load pertama, ATAU jika user memang sedang di bawah
            if(isScrolledToBottom || isFirstLoad) {
                chatArea.scrollTop = chatArea.scrollHeight;
                isFirstLoad = false;
            }
        })
        .catch(err => console.error("Gagal memuat chat:", err));
    }

    // FUNGSI MENGIRIM CHAT (AJAX) - Mencegah halaman reload (Halaman Putih Success:true)
    formChat.addEventListener('submit', function(e) {
        e.preventDefault(); // 👈 INI KUNCI AGAR TIDAK PINDAH HALAMAN!

        const pesanText = inputPesan.value.trim();
        if(pesanText === '') return;

        // Siapkan data untuk dikirim
        const formData = new FormData();
        formData.append('pesan', pesanText);
        formData.append('topik', 'Konsultasi Lanjutan'); // Topik dikirim otomatis di belakang layar
        formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));

        // Ubah UI saat mengirim
        inputPesan.value = '';
        inputPesan.disabled = true;
        btnKirim.disabled = true;
        btnKirim.innerHTML = '<i class="fas fa-circle-notch animate-spin"></i>';

        // Kirim pakai AJAX
        fetch("{{ route('user.konseling.store') }}", {
            method: 'POST',
            body: formData,
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(res => res.json())
        .then(data => {
            // Jika berhasil terkirim
            inputPesan.disabled = false;
            btnKirim.disabled = false;
            btnKirim.innerHTML = '<i class="fas fa-paper-plane text-lg"></i>';
            inputPesan.focus();
            
            // Panggil chat baru & paksakan scroll ke bawah
            fetchChat();
            setTimeout(() => chatArea.scrollTop = chatArea.scrollHeight, 100);
        })
        .catch(err => {
            console.error("Gagal mengirim:", err);
            inputPesan.disabled = false;
            btnKirim.disabled = false;
            btnKirim.innerHTML = '<i class="fas fa-paper-plane text-lg"></i>';
        });
    });

    // Load awal
    fetchChat();

    // Polling setiap 3 detik untuk chat masuk dari Bidan
    setInterval(fetchChat, 3000);
</script>
@endpush
@endsection