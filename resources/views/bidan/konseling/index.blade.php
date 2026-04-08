@extends('layouts.bidan')
@section('title', 'Layanan Telemedisin')
@section('page-name', 'Konseling Tertutup')

@section('content')
<style>
    .animate-slide-up { opacity: 0; animation: slideUpFade 0.4s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
    @keyframes slideUpFade { from { opacity: 0; transform: translateY(15px); } to { opacity: 1; transform: translateY(0); } }
    
    /* Layout Sizing yang Akurat (Tidak akan menabrak sidebar) */
    .chat-wrapper { height: calc(100vh - 130px); min-height: 500px; }
    @media (max-width: 1024px) { .chat-wrapper { height: calc(100vh - 180px); } }

    .chat-scroll::-webkit-scrollbar { width: 5px; }
    .chat-scroll::-webkit-scrollbar-track { background: transparent; }
    .chat-scroll::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
    
    /* Animasi Bubble Chat */
    @keyframes popBubble { 0% { opacity: 0; transform: scale(0.9) translateY(10px); } 100% { opacity: 1; transform: scale(1) translateY(0); } }
    .bubble-enter { animation: popBubble 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275) forwards; transform-origin: bottom; }
    
    .contact-active { background-color: #f0fdfa; border-left: 4px solid #06b6d4; }
</style>

<div class="w-full animate-slide-up">
    
    {{-- Kontainer Utama Chat (Fluid & Responsive) --}}
    <div class="chat-wrapper w-full bg-white rounded-[24px] border border-slate-200/80 shadow-[0_8px_30px_rgb(0,0,0,0.03)] overflow-hidden flex flex-col lg:flex-row relative z-10">
        
        {{-- =============================================== --}}
        {{-- PANEL KIRI: LIST KONTAK (Fixed Width di Desktop) --}}
        {{-- =============================================== --}}
        <div class="w-full lg:w-[320px] xl:w-[380px] border-b lg:border-b-0 lg:border-r border-slate-200/80 flex flex-col bg-white shrink-0 h-[250px] lg:h-full">
            <div class="p-5 border-b border-slate-100 bg-slate-50/50 flex items-center justify-between sticky top-0 z-10 shrink-0">
                <h2 class="text-[15px] font-black text-slate-800 font-poppins flex items-center gap-2">
                    <i class="fas fa-comments text-cyan-500"></i> Pesan Masuk
                </h2>
                <div class="w-7 h-7 rounded-full bg-cyan-100 text-cyan-600 flex items-center justify-center text-[10px] shadow-inner" id="totalUnreadIcon">
                    <i class="fas fa-inbox"></i>
                </div>
            </div>
            <div class="flex-1 overflow-y-auto chat-scroll p-2 space-y-1" id="contactList">
                <div class="flex flex-col items-center justify-center py-10 text-slate-400">
                    <i class="fas fa-circle-notch fa-spin text-2xl mb-2 text-cyan-500"></i>
                    <p class="text-[10px] font-bold uppercase tracking-widest">Memuat...</p>
                </div>
            </div>
        </div>

        {{-- =============================================== --}}
        {{-- PANEL KANAN: CHAT AREA (Flex-1 / Sisa Layar)    --}}
        {{-- =============================================== --}}
        <div class="flex-1 flex flex-col bg-[#f8fafc] h-[400px] lg:h-full relative min-w-0">
            
            {{-- Layar Kosong (Belum Pilih Kontak) --}}
            <div id="emptyState" class="absolute inset-0 flex flex-col items-center justify-center bg-slate-50/95 backdrop-blur-sm z-20">
                <div class="w-20 h-20 bg-white rounded-[20px] flex items-center justify-center text-3xl text-cyan-500 shadow-sm border border-cyan-100 mb-4 transform -rotate-6">
                    <i class="fas fa-hand-holding-medical"></i>
                </div>
                <h3 class="text-lg font-black text-slate-800 font-poppins tracking-tight">Layanan Telemedisin</h3>
                <p class="text-[12px] font-medium text-slate-500 mt-1 max-w-xs text-center leading-relaxed">Pilih nama pasien di menu sebelah kiri untuk membaca dan merespons keluhan medis.</p>
            </div>

            {{-- Header Chat --}}
            <div id="chatHeader" class="p-4 sm:p-5 border-b border-slate-200 bg-white/95 backdrop-blur shadow-sm flex items-center justify-between sticky top-0 z-10 opacity-0 pointer-events-none transition-opacity shrink-0">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-cyan-100 text-cyan-600 flex items-center justify-center font-black border border-cyan-200 shadow-inner text-lg" id="activeAvatar">W</div>
                    <div>
                        <h4 class="font-black text-slate-800 text-[14px] leading-tight" id="activeName">Nama Pasien</h4>
                        <span class="text-[9px] font-bold text-emerald-500 uppercase tracking-widest flex items-center gap-1 mt-1"><div class="w-1.5 h-1.5 bg-emerald-500 rounded-full animate-pulse"></div> Sesi Aktif</span>
                    </div>
                </div>
            </div>

            {{-- Area Pesan (Bubbles) --}}
            <div class="flex-1 overflow-y-auto overflow-x-hidden chat-scroll p-4 sm:p-6 space-y-4" id="chatArea"></div>

            {{-- Area Input Ketik --}}
            <div id="chatInputArea" class="p-4 sm:p-5 bg-white border-t border-slate-200 opacity-0 pointer-events-none transition-opacity shrink-0">
                <form id="replyForm" class="flex items-end gap-3">
                    <textarea id="replyMessage" rows="1" placeholder="Ketik saran atau resep medis untuk pasien..." class="flex-1 bg-slate-50 border border-slate-200 rounded-2xl px-5 py-3.5 text-[13px] font-medium text-slate-700 focus:bg-white focus:border-cyan-400 focus:ring-4 focus:ring-cyan-50 outline-none transition-all resize-none max-h-32 custom-scrollbar" oninput="this.style.height = ''; this.style.height = this.scrollHeight + 'px'"></textarea>
                    
                    <button type="submit" id="btnSend" class="w-12 h-12 shrink-0 rounded-[14px] bg-cyan-600 hover:bg-cyan-700 text-white flex items-center justify-center shadow-[0_4px_12px_rgba(6,182,212,0.3)] transition-transform active:scale-95 text-lg">
                        <i class="fas fa-paper-plane transform -translate-x-0.5 translate-y-0.5"></i>
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    
    let activeUserId = null;
    let listInterval = null;
    let chatInterval = null;

    const contactList = document.getElementById('contactList');
    const chatArea = document.getElementById('chatArea');
    const emptyState = document.getElementById('emptyState');
    const chatHeader = document.getElementById('chatHeader');
    const chatInputArea = document.getElementById('chatInputArea');
    const replyForm = document.getElementById('replyForm');
    const replyMessage = document.getElementById('replyMessage');
    const btnSend = document.getElementById('btnSend');

    // 1. RENDER DAFTAR KONTAK
    const loadContacts = async () => {
        try {
            const res = await fetch("{{ route('bidan.konseling.fetch-list') }}");
            const contacts = await res.json();
            
            let html = '';
            if(contacts.length === 0) {
                html = `<div class="text-center py-10"><p class="text-[11px] font-bold text-slate-400">Belum ada pesan masuk.</p></div>`;
            } else {
                contacts.forEach(c => {
                    const isActive = c.user_id === activeUserId ? 'contact-active' : 'border-transparent hover:bg-slate-50';
                    const unreadBadge = c.unread > 0 ? `<div class="w-5 h-5 rounded-full bg-rose-500 text-white flex items-center justify-center text-[9px] font-black shadow-sm ml-auto animate-pulse">${c.unread}</div>` : '';
                    const fw = c.unread > 0 ? 'font-black text-slate-900' : 'font-bold text-slate-700';
                    const msgColor = c.unread > 0 ? 'text-slate-800 font-bold' : 'text-slate-500';

                    html += `
                        <div onclick="openChat(${c.user_id})" class="p-3.5 rounded-xl cursor-pointer flex items-center gap-3 transition-all border-l-4 ${isActive}">
                            <div class="w-10 h-10 rounded-xl bg-slate-100 text-slate-500 border border-slate-200 flex items-center justify-center font-black text-[13px] shrink-0">${c.avatar_text}</div>
                            <div class="flex-1 min-w-0">
                                <div class="flex justify-between items-center mb-0.5">
                                    <h5 class="text-[13px] ${fw} truncate pr-2">${c.name}</h5>
                                    <span class="text-[9px] font-bold text-slate-400 shrink-0">${c.time}</span>
                                </div>
                                <div class="flex items-center">
                                    <p class="text-[11px] ${msgColor} truncate w-full pr-2">${c.last_message}</p>
                                    ${unreadBadge}
                                </div>
                            </div>
                        </div>
                    `;
                });
            }
            contactList.innerHTML = html;
        } catch (e) { console.log("Gagal memuat kontak"); }
    };

    // 2. RENDER BUBBLE CHAT KETIKA DIKLIK
    window.openChat = async (userId) => {
        activeUserId = userId;
        
        emptyState.classList.add('opacity-0', 'pointer-events-none');
        chatHeader.classList.remove('opacity-0', 'pointer-events-none');
        chatInputArea.classList.remove('opacity-0', 'pointer-events-none');
        
        loadContacts(); // Refresh UI aktif
        chatArea.innerHTML = `<div class="flex justify-center py-10"><i class="fas fa-circle-notch fa-spin text-cyan-500 text-xl"></i></div>`;
        
        fetchChatData(userId, true);
        if(chatInterval) clearInterval(chatInterval);
        chatInterval = setInterval(() => fetchChatData(activeUserId, false), 3000);
    };

    const fetchChatData = async (userId, forceScroll) => {
        if(!userId) return;
        try {
            const res = await fetch(`{{ url('bidan/konseling/fetch-chat') }}/${userId}`);
            const data = await res.json();
            
            document.getElementById('activeName').innerText = data.user.name;
            document.getElementById('activeAvatar').innerText = data.user.name.charAt(0).toUpperCase();

            let html = '';
            let prevSender = null;

            data.chats.forEach(chat => {
                const isWarga = chat.pengirim === 'warga';
                const justify = isWarga ? 'justify-start' : 'justify-end';
                const bubbleCol = isWarga ? 'bg-white border border-slate-200 text-slate-700 rounded-tr-2xl rounded-br-2xl rounded-bl-sm rounded-tl-2xl' : 'bg-cyan-600 text-white shadow-[0_4px_12px_rgba(6,182,212,0.2)] rounded-tl-2xl rounded-bl-2xl rounded-br-sm rounded-tr-2xl';
                const timeCol = isWarga ? 'text-slate-400 text-left' : 'text-cyan-100 text-right';
                
                let topikHtml = '';
                if(isWarga && chat.topik && prevSender !== 'warga') {
                    topikHtml = `<div class="text-[10px] font-black text-rose-500 uppercase tracking-widest mb-1.5 border-b border-slate-100 pb-1.5"><i class="fas fa-tag"></i> Topik: ${chat.topik}</div>`;
                }

                html += `
                    <div class="flex ${justify} w-full bubble-enter">
                        <div class="max-w-[85%] sm:max-w-[75%] px-4 py-3 ${bubbleCol}">
                            ${topikHtml}
                            <p class="text-[13px] leading-relaxed whitespace-pre-wrap word-break break-words">${chat.pesan}</p>
                            <span class="block text-[9px] font-bold ${timeCol} mt-1.5">${chat.time}</span>
                        </div>
                    </div>
                `;
                prevSender = chat.pengirim;
            });

            const isScrolledToBottom = chatArea.scrollHeight - chatArea.clientHeight <= chatArea.scrollTop + 50;
            chatArea.innerHTML = html;
            
            if (forceScroll || isScrolledToBottom) {
                chatArea.scrollTop = chatArea.scrollHeight;
            }
        } catch (e) { console.log(e); }
    };

    // 3. KIRIM PESAN (REPLY) & OPTIMISTIC UPDATE
    replyForm.addEventListener('submit', async (e) => {
        e.preventDefault();
        const msg = replyMessage.value.trim();
        if(!msg || !activeUserId) return;

        // Tampilkan pesan seketika
        const tempHtml = `
            <div class="flex justify-end w-full bubble-enter">
                <div class="max-w-[85%] sm:max-w-[75%] px-4 py-3 bg-cyan-600 text-white shadow-sm rounded-tl-2xl rounded-bl-2xl rounded-br-sm rounded-tr-2xl opacity-70">
                    <p class="text-[13px] leading-relaxed whitespace-pre-wrap word-break break-words">${msg}</p>
                    <span class="block text-[9px] font-bold text-cyan-100 text-right mt-1.5"><i class="fas fa-clock"></i> Mengirim...</span>
                </div>
            </div>`;
        chatArea.insertAdjacentHTML('beforeend', tempHtml);
        chatArea.scrollTop = chatArea.scrollHeight;
        
        replyMessage.value = '';
        replyMessage.style.height = 'auto'; 
        btnSend.innerHTML = '<i class="fas fa-circle-notch fa-spin"></i>';

        try {
            const formData = new FormData();
            formData.append('_token', '{{ csrf_token() }}');
            formData.append('pesan', msg);

            await fetch(`{{ url('bidan/konseling/reply') }}/${activeUserId}`, {
                method: 'POST',
                body: formData
            });

            fetchChatData(activeUserId, true);
        } catch (e) {
            alert('Gagal mengirim pesan. Cek koneksi.');
        } finally {
            btnSend.innerHTML = '<i class="fas fa-paper-plane transform -translate-x-0.5 translate-y-0.5"></i>';
        }
    });

    // Enter untuk kirim pesan
    replyMessage.addEventListener('keypress', function (e) {
        if (e.key === 'Enter' && !e.shiftKey) {
            e.preventDefault();
            replyForm.dispatchEvent(new Event('submit'));
        }
    });

    loadContacts();
    listInterval = setInterval(loadContacts, 5000); 
});
</script>
@endpush
@endsection