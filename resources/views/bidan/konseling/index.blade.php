@extends('layouts.bidan')

@section('title', 'Layanan Telemedisin')
@section('page-name', 'Konseling Interaktif')

@push('styles')
<style>
    .animate-slide-up { opacity: 0; animation: slideUpFade 0.5s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
    @keyframes slideUpFade { from { opacity: 0; transform: translateY(15px); } to { opacity: 1; transform: translateY(0); } }
    
    .chat-container { height: calc(100vh - 140px); min-height: 600px; }
    @media (max-width: 1024px) { .chat-container { height: 80vh; min-height: auto; flex-direction: column; } }
    
    .contact-item { transition: all 0.2s ease; border-left: 4px solid transparent; }
    .contact-item:hover { background-color: #f8fafc; }
    .contact-item.active { background-color: #ecfeff; border-left-color: #0891b2; }
    
    .chat-bubble-user { background-color: #ffffff; border: 1px solid #e2e8f0; color: #334155; border-radius: 20px 20px 20px 4px; box-shadow: 0 2px 4px rgba(0,0,0,0.02); }
    .chat-bubble-bidan { background-color: #0891b2; color: #ffffff; border-radius: 20px 20px 4px 20px; box-shadow: 0 4px 10px rgba(8,145,178,0.2); }
    
    .chat-input-area { background-color: #f8fafc; border-top: 1px solid #e2e8f0; }
    .chat-input { width: 100%; border: none; background: transparent; padding: 16px 20px; outline: none; font-size: 14px; font-weight: 500; color: #1e293b; resize: none; max-height: 120px;}
    
    [x-cloak] { display: none !important; }
</style>
<script src="https://cdn.jsdelivr.net/npm/alpinejs@3.13.3/dist/cdn.min.js" defer></script>
@endpush

@section('content')
<div class="max-w-[1400px] mx-auto animate-slide-up" x-data="chatApp()">

    <div class="chat-container bg-white rounded-[32px] shadow-[0_10px_40px_rgba(0,0,0,0.04)] border border-slate-200/80 flex overflow-hidden">
        
        {{-- ==========================================
             KOLOM KIRI: DAFTAR KONTAK / PESAN MASUK
             ========================================== --}}
        <div class="w-full lg:w-[380px] xl:w-[400px] flex flex-col border-r border-slate-100 shrink-0 h-[400px] lg:h-auto bg-white z-10">
            
            <div class="p-6 border-b border-slate-100 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-cyan-50 text-cyan-600 rounded-[12px] flex items-center justify-center text-lg"><i class="fas fa-comments"></i></div>
                    <h2 class="text-[18px] font-black text-slate-800 font-poppins">Pesan Masuk</h2>
                </div>
                <div class="w-10 h-10 rounded-full bg-slate-50 text-slate-400 flex items-center justify-center"><i class="fas fa-inbox"></i></div>
            </div>

            <div class="px-5 py-4 bg-slate-50/50">
                <div class="relative">
                    <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                    <input type="text" x-model="searchQuery" placeholder="Cari nama pasien..." class="w-full bg-white border border-slate-200 rounded-full pl-10 pr-4 py-2.5 text-[13px] font-bold text-slate-700 outline-none focus:border-cyan-500 focus:ring-2 focus:ring-cyan-50">
                </div>
            </div>

            <div class="flex-1 overflow-y-auto custom-scrollbar bg-white">
                
                {{-- Loader List Kontak --}}
                <div x-show="loadingContacts" class="p-8 text-center text-cyan-500">
                    <i class="fas fa-circle-notch fa-spin text-2xl"></i>
                    <p class="text-[12px] font-bold text-slate-400 mt-2">Memuat kontak...</p>
                </div>

                {{-- Looping Kontak dari Fetch AJAX --}}
                <template x-for="contact in filteredContacts" :key="contact.user_id">
                    <div @click="openChat(contact)" 
                         class="contact-item cursor-pointer p-5 border-b border-slate-50 flex items-start gap-4"
                         :class="activeUser && activeUser.user_id === contact.user_id ? 'active' : ''">
                        
                        <div class="relative">
                            <div class="w-12 h-12 rounded-full bg-slate-100 border border-slate-200 text-slate-500 flex items-center justify-center font-black text-lg shrink-0" x-text="contact.inisial"></div>
                            <div x-show="contact.unread > 0" class="absolute -top-1 -right-1 w-5 h-5 bg-rose-500 rounded-full border-2 border-white flex items-center justify-center text-white text-[9px] font-black" x-text="contact.unread"></div>
                        </div>
                        
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center justify-between mb-1">
                                <h4 class="text-[14px] font-black text-slate-800 truncate font-poppins" x-text="contact.nama"></h4>
                                <span class="text-[10px] font-bold" :class="contact.unread > 0 ? 'text-cyan-600' : 'text-slate-400'" x-text="contact.last_time"></span>
                            </div>
                            <p class="text-[12px] font-medium truncate" :class="contact.unread > 0 ? 'text-slate-800 font-bold' : 'text-slate-500'" x-text="contact.last_message"></p>
                            <span class="inline-block mt-1.5 px-2 py-0.5 bg-slate-50 text-slate-500 text-[9px] font-black uppercase tracking-widest rounded border border-slate-100" x-text="contact.kategori"></span>
                        </div>
                    </div>
                </template>

                <div x-show="!loadingContacts && filteredContacts.length === 0" class="p-8 text-center text-slate-400 font-bold text-[12px]">
                    Tidak ada obrolan ditemukan.
                </div>

            </div>
        </div>

        {{-- ==========================================
             KOLOM KANAN: AREA CHATTING (BUBBLES)
             ========================================== --}}
        <div class="flex-1 bg-[#f0fdfa]/30 flex flex-col relative h-[500px] lg:h-auto overflow-hidden">
            
            {{-- STATE 1: KOSONG (Belum memilih chat) --}}
            <div x-show="!activeUser" x-transition.opacity class="absolute inset-0 flex flex-col items-center justify-center text-center p-8 z-10 bg-slate-50/50">
                <div class="w-24 h-24 bg-white rounded-[24px] shadow-sm border border-slate-100 flex items-center justify-center text-cyan-500 text-5xl mb-6">
                    <i class="fas fa-hand-holding-medical"></i>
                </div>
                <h3 class="text-2xl font-black text-slate-800 font-poppins mb-2">Layanan Telemedisin</h3>
                <p class="text-[13px] font-medium text-slate-500 max-w-sm">Pilih nama pasien di menu sebelah kiri untuk membaca dan merespons keluhan medis mereka secara real-time.</p>
            </div>

            {{-- STATE 2: CHAT AKTIF --}}
            <template x-if="activeUser">
                <div class="flex flex-col h-full w-full absolute inset-0 z-20 bg-white">
                    
                    {{-- Header Chat --}}
                    <div class="p-5 border-b border-slate-100 flex items-center justify-between bg-white shadow-sm z-10">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 rounded-full bg-cyan-50 text-cyan-600 border border-cyan-100 flex items-center justify-center text-lg font-black" x-text="activeUser.inisial"></div>
                            <div>
                                <h3 class="text-[16px] font-black text-slate-800 font-poppins leading-tight" x-text="activeUser.nama"></h3>
                                <p class="text-[11px] font-bold text-cyan-600 mt-0.5"><i class="fas fa-circle text-[8px] text-emerald-400 mr-1 animate-pulse"></i> Terhubung • <span x-text="activeUser.kategori"></span></p>
                            </div>
                        </div>
                        <button @click="closeChat()" class="w-10 h-10 rounded-full bg-slate-50 text-slate-400 hover:text-rose-500 hover:bg-rose-50 flex items-center justify-center transition-colors"><i class="fas fa-times"></i></button>
                    </div>

                    {{-- Area Obrolan (Bubbles) --}}
                    <div id="chatBox" class="flex-1 p-6 overflow-y-auto custom-scrollbar bg-slate-50/50 space-y-4">
                        
                        <div x-show="loadingChats" class="text-center py-4"><i class="fas fa-circle-notch fa-spin text-cyan-500"></i></div>

                        <template x-for="chat in chats" :key="chat.id">
                            <div class="w-full flex flex-col">
                                
                                {{-- Jika Pengirim adalah Pasien (Kiri) --}}
                                <template x-if="chat.sender === 'user'">
                                    <div class="flex flex-col items-start max-w-[80%] mb-4">
                                        <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest ml-2 mb-1">Keluhan Warga</span>
                                        <div class="chat-bubble-user p-4 text-[13px] leading-relaxed whitespace-pre-wrap" x-text="chat.message"></div>
                                        <span class="text-[10px] font-bold text-slate-400 ml-2 mt-1" x-text="chat.time"></span>
                                    </div>
                                </template>

                                {{-- Jika Pengirim adalah Bidan (Kanan) --}}
                                <template x-if="chat.sender === 'bidan'">
                                    <div class="flex flex-col items-end max-w-[80%] self-end mb-4">
                                        <span class="text-[9px] font-black text-cyan-500 uppercase tracking-widest mr-2 mb-1">Respons Anda</span>
                                        <div class="chat-bubble-bidan p-4 text-[13px] leading-relaxed whitespace-pre-wrap" x-text="chat.message"></div>
                                        <span class="text-[10px] font-bold text-slate-400 mr-2 mt-1" x-text="chat.time"></span>
                                    </div>
                                </template>

                            </div>
                        </template>
                    </div>

                    {{-- Area Input Ketik Pesan --}}
                    <div class="chat-input-area p-4">
                        <form @submit.prevent="sendReply()" class="flex items-end gap-3 bg-white rounded-[20px] border border-slate-200 p-1 shadow-sm focus-within:border-cyan-400 focus-within:ring-4 focus-within:ring-cyan-50 transition-all">
                            
                            <button type="button" class="w-10 h-10 shrink-0 text-slate-400 hover:text-cyan-500 flex items-center justify-center text-xl mb-1"><i class="fas fa-plus-circle"></i></button>
                            
                            <textarea x-model="newMessage" @keydown.enter.prevent="sendReply()" rows="1" class="chat-input custom-scrollbar" placeholder="Ketik respons edukasi atau resep medis di sini... (Tekan Enter untuk kirim)"></textarea>
                            
                            <button type="submit" :disabled="isSending || newMessage.trim() === ''" class="w-10 h-10 shrink-0 bg-cyan-500 text-white rounded-[16px] flex items-center justify-center text-lg shadow-md hover:bg-cyan-600 transition-all disabled:opacity-50 disabled:cursor-not-allowed mb-1 mr-1">
                                <i class="fas" :class="isSending ? 'fa-spinner fa-spin' : 'fa-paper-plane'"></i>
                            </button>

                        </form>
                    </div>

                </div>
            </template>

        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('chatApp', () => ({
            searchQuery: '',
            contacts: [],
            loadingContacts: true,
            
            activeUser: null,
            chats: [],
            loadingChats: false,
            
            newMessage: '',
            isSending: false,

            init() {
                this.fetchContacts();
            },

            // 1. Ambil List Pasien di Kiri
            fetchContacts() {
                fetch("{{ url('bidan/konseling/fetch-list') }}")
                    .then(res => res.json())
                    .then(data => {
                        this.contacts = data;
                        this.loadingContacts = false;
                    })
                    .catch(err => {
                        console.error('Gagal mengambil data:', err);
                        this.loadingContacts = false;
                    });
            },

            get filteredContacts() {
                if (this.searchQuery === '') return this.contacts;
                return this.contacts.filter(c => c.nama.toLowerCase().includes(this.searchQuery.toLowerCase()));
            },

            // 2. Klik Pasien -> Buka Chat
            openChat(user) {
                this.activeUser = user;
                this.chats = [];
                this.loadingChats = true;
                
                // Jika ada pesan belum dibaca, bisa dikosongkan (opsional)
                user.unread = 0; 

                fetch("{{ url('bidan/konseling/fetch-chat') }}/" + user.user_id)
                    .then(res => res.json())
                    .then(data => {
                        this.chats = data;
                        this.loadingChats = false;
                        this.scrollToBottom();
                    });
            },

            closeChat() {
                this.activeUser = null;
                this.chats = [];
            },

            // 3. Kirim Balasan Bidan
            sendReply() {
                if(this.newMessage.trim() === '') return;
                
                this.isSending = true;
                const msgPayload = this.newMessage;
                this.newMessage = ''; // Kosongkan input duluan

                fetch("{{ url('bidan/konseling/reply') }}/" + this.activeUser.user_id, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ message: msgPayload })
                })
                .then(res => res.json())
                .then(data => {
                    // Tambahkan bubble chat baru ke layar secara instan
                    this.chats.push(data.chat);
                    this.isSending = false;
                    this.scrollToBottom();
                    this.fetchContacts(); // Update preview pesan terakhir di kolom kiri
                })
                .catch(err => {
                    alert('Gagal mengirim pesan. Silakan coba lagi.');
                    this.isSending = false;
                });
            },

            scrollToBottom() {
                setTimeout(() => {
                    const box = document.getElementById('chatBox');
                    if(box) box.scrollTop = box.scrollHeight;
                }, 100);
            }
        }))
    });
</script>
@endpush
@endsection