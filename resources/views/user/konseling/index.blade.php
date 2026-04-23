@extends('layouts.user')

@section('content')
<div class="p-4 md:p-8 font-poppins bg-[#f8fafc] min-h-screen">
    
    <div class="mb-6">
        <div class="inline-flex items-center gap-3 px-4 py-2 bg-violet-50 rounded-full shadow-sm border border-violet-100 mb-4">
            <span class="relative flex h-2.5 w-2.5">
              <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-violet-400 opacity-75"></span>
              <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-violet-500"></span>
            </span>
            <span class="text-[11px] font-black tracking-widest uppercase text-violet-700">Layanan Aktif</span>
        </div>
        <h1 class="text-2xl md:text-3xl font-black text-slate-800 tracking-tight">Tanya Bidan (Konseling) 💬</h1>
        <p class="text-sm font-medium text-slate-500 mt-2 max-w-2xl leading-relaxed">Ruang privasi Anda untuk berkonsultasi seputar kesehatan reproduksi, gizi anak, dan keluhan fisik secara langsung dengan Bidan.</p>
    </div>

    <div class="max-w-4xl bg-white rounded-[2rem] border border-slate-200 shadow-sm overflow-hidden flex flex-col h-[650px] max-h-[75vh]">
        
        <div class="px-6 py-4 bg-white border-b border-slate-100 flex items-center justify-between shrink-0 z-10 shadow-[0_4px_10px_-10px_rgba(0,0,0,0.1)]">
            <div class="flex items-center gap-4">
                <div class="relative">
                    <div class="w-12 h-12 rounded-full bg-teal-50 text-teal-600 flex items-center justify-center text-xl border border-teal-100">
                        <i class="fas fa-user-md"></i>
                    </div>
                    <span class="absolute bottom-0 right-0 w-3.5 h-3.5 bg-emerald-500 border-2 border-white rounded-full"></span>
                </div>
                <div>
                    <h2 class="text-sm font-black text-slate-800">Bidan Posyandu</h2>
                    <p class="text-[11px] font-bold text-emerald-500">Online — Siap Membantu</p>
                </div>
            </div>
            
            <div class="hidden sm:flex items-center gap-2">
                <span class="px-3 py-1 bg-slate-50 text-slate-500 text-[10px] font-black uppercase tracking-wider rounded-lg border border-slate-100"><i class="fas fa-lock mr-1"></i> Enkripsi End-to-End</span>
            </div>
        </div>

        <div class="flex-1 bg-slate-50/50 p-6 overflow-y-auto custom-scrollbar relative" id="chat-container">
            
            <div class="flex justify-center mb-6">
                <span class="px-4 py-1.5 bg-amber-50 border border-amber-100 text-amber-600 text-[10px] font-bold uppercase tracking-widest rounded-full shadow-sm">
                    Sesi Konseling Dimulai
                </span>
            </div>
            
            <div class="flex justify-start mb-6">
                <div class="max-w-[85%] sm:max-w-[70%] bg-white border border-slate-200 text-slate-700 rounded-2xl rounded-tl-sm px-5 py-4 shadow-sm">
                    <p class="text-[11px] text-teal-600 font-black mb-1.5 uppercase tracking-wider flex items-center gap-1.5">
                        <i class="fas fa-robot"></i> Asisten Posyandu
                    </p>
                    <p class="text-[13px] leading-relaxed font-medium">
                        Halo! Selamat datang di ruang konseling tertutup. Silakan pilih topik dan sampaikan pertanyaan atau keluhan Anda. Bidan kami akan membalas pesan Anda sesegera mungkin. Identitas Anda aman dan dirahasiakan.
                    </p>
                </div>
            </div>

            <div id="chat-messages" class="space-y-2">
                </div>

            <div id="typing-indicator" class="hidden justify-start mb-4">
                <div class="bg-white border border-slate-200 rounded-2xl rounded-bl-sm px-5 py-3 shadow-sm flex items-center gap-1">
                    <span class="w-1.5 h-1.5 bg-slate-400 rounded-full animate-bounce"></span>
                    <span class="w-1.5 h-1.5 bg-slate-400 rounded-full animate-bounce" style="animation-delay: 0.1s"></span>
                    <span class="w-1.5 h-1.5 bg-slate-400 rounded-full animate-bounce" style="animation-delay: 0.2s"></span>
                </div>
            </div>
        </div>

        <div class="p-4 sm:p-5 bg-white border-t border-slate-100 shrink-0">
            <form id="form-konseling" class="flex flex-col sm:flex-row items-end sm:items-center gap-3">
                
                <div class="w-full sm:w-1/3 shrink-0">
                    <select id="topik" name="topik" required class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-[13px] font-bold text-slate-700 focus:outline-none focus:ring-2 focus:ring-violet-500 focus:bg-white transition-all cursor-pointer">
                        <option value="" disabled selected>-- Pilih Topik --</option>
                        <option value="Kesehatan Reproduksi">Kesehatan Reproduksi</option>
                        <option value="Gizi & Tumbuh Kembang">Gizi & Tumbuh Kembang</option>
                        <option value="Keluhan Fisik/Penyakit">Keluhan Fisik / Penyakit</option>
                        <option value="Psikologi Remaja">Psikologi Remaja</option>
                        <option value="Lainnya">Topik Lainnya</option>
                    </select>
                </div>

                <div class="flex-1 w-full relative">
                    <textarea id="pesan" name="pesan" rows="1" required placeholder="Ketik pesan keluhan Anda di sini..." class="w-full px-5 py-3 pr-14 bg-slate-50 border border-slate-200 rounded-xl text-[13px] font-medium text-slate-700 focus:outline-none focus:ring-2 focus:ring-violet-500 focus:bg-white transition-all resize-none overflow-hidden" style="min-height: 46px;"></textarea>
                    
                    <button type="submit" id="btn-send" class="absolute right-2 bottom-1.5 w-9 h-9 bg-violet-600 text-white rounded-lg flex items-center justify-center hover:bg-violet-700 transition-colors shadow-sm disabled:opacity-50 disabled:cursor-not-allowed">
                        <i class="fas fa-paper-plane text-sm"></i>
                    </button>
                </div>
            </form>
        </div>

    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const chatMessages = document.getElementById('chat-messages');
        const chatContainer = document.getElementById('chat-container');
        const formKonseling = document.getElementById('form-konseling');
        const inputPesan = document.getElementById('pesan');
        const typingIndicator = document.getElementById('typing-indicator');
        const btnSend = document.getElementById('btn-send');

        let isFetching = false;

        // Auto-resize textarea
        inputPesan.addEventListener('input', function() {
            this.style.height = 'auto';
            this.style.height = (this.scrollHeight) + 'px';
            if(this.value === '') this.style.height = '46px';
        });

        // Enter untuk kirim (Shift+Enter untuk baris baru)
        inputPesan.addEventListener('keydown', function(e) {
            if (e.key === 'Enter' && !e.shiftKey) {
                e.preventDefault();
                formKonseling.dispatchEvent(new Event('submit'));
            }
        });

        // Fungsi Load Chat via AJAX
        function loadChat(autoScroll = false) {
            if (isFetching) return;
            isFetching = true;

            fetch("{{ route('user.konseling.fetch') }}", {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.html) {
                    const currentHtml = chatMessages.innerHTML;
                    if (currentHtml !== data.html) {
                        chatMessages.innerHTML = data.html;
                        if (autoScroll) scrollToBottom();
                    }
                }
                isFetching = false;
            })
            .catch(error => {
                console.error('Error fetching chats:', error);
                isFetching = false;
            });
        }

        // Fungsi Scroll ke bawah
        function scrollToBottom() {
            chatContainer.scrollTop = chatContainer.scrollHeight;
        }

        // Submit Pesan (AJAX)
        formKonseling.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const topik = document.getElementById('topik').value;
            const pesan = inputPesan.value.trim();

            if (!topik) {
                alert("Silakan pilih topik terlebih dahulu.");
                return;
            }
            if (!pesan) return;

            // UI Feedback
            btnSend.disabled = true;
            btnSend.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
            inputPesan.disabled = true;

            const formData = new FormData(this);

            fetch("{{ route('user.konseling.store') }}", {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json'
                },
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                // Reset Form
                inputPesan.value = '';
                inputPesan.style.height = '46px';
                inputPesan.disabled = false;
                btnSend.disabled = false;
                btnSend.innerHTML = '<i class="fas fa-paper-plane text-sm"></i>';
                
                // Reload chat & scroll
                loadChat(true);
                inputPesan.focus();
            })
            .catch(error => {
                console.error('Error sending message:', error);
                alert('Gagal mengirim pesan. Silakan coba lagi.');
                inputPesan.disabled = false;
                btnSend.disabled = false;
                btnSend.innerHTML = '<i class="fas fa-paper-plane text-sm"></i>';
            });
        });

        // Load chat pertama kali saat halaman dibuka
        loadChat(true);

        // Polling: Cek pesan baru setiap 5 detik
        setInterval(() => loadChat(false), 5000);
    });
</script>
@endpush