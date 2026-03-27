@extends('layouts.bidan')
@section('title', 'Ruang Konseling')

@section('content')
<div class="max-w-7xl mx-auto animate-[slideDown_0.5s_ease-out]">
    
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-black text-slate-800 tracking-tight font-poppins">Ruang Konseling</h1>
            <p class="text-sm text-slate-500 mt-1">Kotak masuk konsultasi dan keluhan dari warga posyandu.</p>
        </div>
    </div>

    <div class="bg-white rounded-[24px] border border-slate-200/80 shadow-[0_8px_30px_rgb(0,0,0,0.04)] overflow-hidden flex h-[75vh] min-h-[500px]">
        
        <div class="w-1/3 md:w-[350px] border-r border-slate-200 flex flex-col bg-white shrink-0 relative z-20">
            <div class="p-4 border-b border-slate-100 bg-slate-50/50">
                <div class="relative w-full">
                    <i class="fas fa-search absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-sm"></i>
                    <input type="text" placeholder="Cari nama warga..." class="w-full bg-white border border-slate-200 text-slate-800 text-[13px] rounded-xl pl-10 pr-3 py-2.5 focus:ring-2 focus:ring-sky-500 outline-none font-medium shadow-sm transition-all">
                </div>
            </div>
            
            <div id="userList" class="flex-1 overflow-y-auto custom-scrollbar bg-white">
                <div class="flex items-center justify-center h-full">
                    <i class="fas fa-circle-notch animate-spin text-sky-500 text-2xl"></i>
                </div>
            </div>
        </div>

        <div class="flex-1 flex flex-col bg-[#e5ddd5]/10 relative z-10">
            
            <div id="emptyState" class="absolute inset-0 flex flex-col items-center justify-center text-slate-400 bg-slate-50 z-30 transition-all duration-300">
                <div class="w-24 h-24 bg-white rounded-full flex items-center justify-center shadow-sm border border-slate-200 mb-5 relative">
                    <i class="fas fa-comments text-5xl text-sky-200"></i>
                    <span class="absolute bottom-1 right-1 w-6 h-6 bg-emerald-500 rounded-full border-2 border-white text-white flex items-center justify-center text-[10px]"><i class="fas fa-lock"></i></span>
                </div>
                <h3 class="text-xl font-black text-slate-700 font-poppins">PosyanduCare Web</h3>
                <p class="text-[13px] font-medium text-slate-500 mt-2 max-w-sm text-center leading-relaxed">Pilih percakapan di sebelah kiri untuk mulai merespons keluhan kesehatan warga. Semua pesan terenkripsi secara end-to-end.</p>
            </div>

            <div id="chatHeader" class="p-4 border-b border-slate-200 bg-white flex items-center gap-3 hidden shadow-sm z-20">
                <div id="chatAvatar" class="w-11 h-11 rounded-full bg-sky-100 text-sky-600 flex items-center justify-center font-black border border-sky-200 shadow-sm">U</div>
                <div>
                    <h4 id="chatName" class="font-bold text-slate-800">Nama Warga</h4>
                    <p class="text-[11px] text-emerald-500 font-bold"><i class="fas fa-circle text-[8px] mr-1 animate-pulse"></i> Online / Tersambung</p>
                </div>
            </div>

            <div class="absolute inset-0 opacity-[0.03] pointer-events-none z-0" style="background-image: radial-gradient(#000 1px, transparent 1px); background-size: 20px 20px;"></div>
            <div id="chatArea" class="flex-1 p-5 overflow-y-auto custom-scrollbar relative z-10 hidden">
                </div>

            <div id="chatInputArea" class="p-4 bg-slate-50 border-t border-slate-200 hidden z-20">
                <form id="formReply" class="flex gap-2">
                    <input type="text" id="replyPesan" placeholder="Ketik balasan medis Anda untuk warga..." class="flex-1 bg-white border border-slate-300 text-sm font-medium rounded-xl px-4 py-3 outline-none focus:border-sky-500 focus:ring-4 focus:ring-sky-500/10 transition-all" required autocomplete="off">
                    <button type="submit" class="w-[52px] h-[52px] bg-sky-600 text-white rounded-xl flex items-center justify-center hover:bg-sky-700 transition-all shadow-[0_4px_12px_rgba(14,165,233,0.3)] hover:-translate-y-0.5 shrink-0">
                        <i class="fas fa-paper-plane text-lg"></i>
                    </button>
                </form>
            </div>

        </div>

    </div>
</div>

@push('scripts')
<script>
    const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
    let activeUserId = null;
    
    const userList = document.getElementById('userList');
    const chatArea = document.getElementById('chatArea');
    const emptyState = document.getElementById('emptyState');
    const chatHeader = document.getElementById('chatHeader');
    const chatInputArea = document.getElementById('chatInputArea');

    // 1. Fungsi memuat list warga di sebelah kiri
    function fetchList() {
        fetch("{{ route('bidan.konseling.fetch-list') }}", {
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(res => res.json())
        .then(data => {
            userList.innerHTML = data.html;
            
            // Jaga agar yang sedang diklik tetap ada background abu-abu nya
            if(activeUserId) {
                const activeEl = userList.querySelector(`.chat-item[data-id="${activeUserId}"]`);
                if(activeEl) activeEl.classList.add('bg-slate-100');
            }
        });
    }

    // 2. Fungsi memuat gelembung chat (Kanan)
    function fetchChat() {
        if(!activeUserId) return;
        
        fetch(`/bidan/konseling/fetch-chat/${activeUserId}`, {
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(res => res.json())
        .then(data => {
            // Cek posisi scroll, jika user sedang di bawah, otomatis scroll ke bawah saat ada chat baru
            const isScrolledToBottom = chatArea.scrollHeight - chatArea.clientHeight <= chatArea.scrollTop + 50;
            
            chatArea.innerHTML = data.html;
            
            if(isScrolledToBottom) {
                chatArea.scrollTop = chatArea.scrollHeight;
            }
        });
    }

    // 3. Event Delegation: Menangkap klik pada List Warga
    userList.addEventListener('click', function(e) {
        const item = e.target.closest('.chat-item');
        if(!item) return;

        // Ubah ID yang aktif
        activeUserId = item.dataset.id;
        
        // Hapus styling aktif di semua list, tambahkan ke yang baru diklik
        document.querySelectorAll('.chat-item').forEach(el => el.classList.remove('bg-slate-100'));
        item.classList.add('bg-slate-100');
        
        // Sembunyikan layar kosong, tampilkan area chat
        emptyState.classList.add('hidden');
        chatHeader.classList.remove('hidden');
        chatArea.classList.remove('hidden');
        chatInputArea.classList.remove('hidden');
        
        // Ganti Nama dan Avatar di Header Chat
        document.getElementById('chatName').innerText = item.dataset.name;
        document.getElementById('chatAvatar').innerText = item.dataset.name.charAt(0).toUpperCase();

        // Segera load isi chat, lalu scroll paksakan ke bawah
        fetchChat();
        setTimeout(() => chatArea.scrollTop = chatArea.scrollHeight, 200);
    });

    // 4. Submit Form Balasan ke Warga
    document.getElementById('formReply').addEventListener('submit', function(e) {
        e.preventDefault();
        if(!activeUserId) return;

        const input = document.getElementById('replyPesan');
        const formData = new FormData();
        formData.append('pesan', input.value);
        
        // Kosongkan input langsung agar terasa cepat
        input.value = '';

        fetch(`/bidan/konseling/reply/${activeUserId}`, {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': csrfToken },
            body: formData
        })
        .then(() => {
            fetchChat();
            fetchList(); // Refresh list biar badge merah update
            setTimeout(() => chatArea.scrollTop = chatArea.scrollHeight, 100);
        });
    });

    // 5. Jalankan Looping AJAX Polling
    fetchList(); // Load awal
    
    // Refresh List Warga setiap 4 Detik
    setInterval(fetchList, 4000);
    
    // Refresh Gelembung Chat setiap 3 Detik
    setInterval(fetchChat, 3000);

</script>
@endpush
@endsection