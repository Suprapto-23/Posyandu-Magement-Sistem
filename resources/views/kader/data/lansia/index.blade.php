@extends('layouts.kader')

@section('title', 'Data Lansia')
@section('page-name', 'Database Lansia')

@push('styles')
<style>
    .animate-slide-up { opacity: 0; animation: slideUpFade 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
    @keyframes slideUpFade { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }

    .search-input {
        width: 100%; background-color: #f8fafc; border: 1px solid #e2e8f0; color: #0f172a;
        font-size: 0.875rem; border-radius: 1rem; padding: 0.75rem 1rem 0.75rem 2.75rem;
        outline: none; transition: all 0.3s ease; font-weight: 600;
        box-shadow: inset 0 2px 4px 0 rgb(0 0 0 / 0.02);
    }
    .search-input:focus { background-color: #ffffff; border-color: #10b981; box-shadow: 0 4px 12px -3px rgba(16,185,129,0.15); }
    .search-input::placeholder { color: #94a3b8; font-weight: 500; }

    .custom-scrollbar::-webkit-scrollbar { height: 8px; }
    .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
    .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #94a3b8; }

    /* Kemandirian Badge */
    .badge-kem-a { background:#d1fae5; color:#065f46; border-color:#a7f3d0; }
    .badge-kem-b { background:#fef3c7; color:#92400e; border-color:#fde68a; }
    .badge-kem-c { background:#fee2e2; color:#991b1b; border-color:#fca5a5; }
</style>
@endpush

@section('content')
<div class="max-w-7xl mx-auto animate-slide-up">

    {{-- HEADER --}}
    <div class="bg-emerald-50/50 rounded-3xl p-8 mb-6 flex flex-col md:flex-row items-start md:items-center justify-between gap-6 border border-emerald-100">
        <div class="flex items-center gap-5">
            <div class="w-16 h-16 rounded-[20px] bg-emerald-100 text-emerald-600 flex items-center justify-center text-3xl shadow-sm border border-emerald-200/50 transform -rotate-3">
                <i class="fas fa-user-clock"></i>
            </div>
            <div>
                <h1 class="text-3xl font-black text-slate-900 tracking-tight font-poppins">Database Lansia</h1>
                <p class="text-slate-500 mt-1 font-medium text-sm">Kelola data warga lanjut usia beserta kemandirian & IMT.</p>
            </div>
        </div>
        <a href="{{ route('kader.data.lansia.create') }}" class="inline-flex items-center justify-center gap-2 px-6 py-3.5 bg-emerald-500 text-white font-extrabold text-sm rounded-xl hover:bg-emerald-600 shadow-[0_4px_12px_rgba(16,185,129,0.3)] hover:-translate-y-0.5 transition-all w-full md:w-auto">
            <i class="fas fa-plus"></i> Tambah Lansia Baru
        </a>
    </div>

    {{-- ALERT MESSAGES --}}
    @if(session('success'))
        <div class="mb-4 p-4 bg-emerald-50 border border-emerald-200 rounded-2xl flex items-center gap-3 text-emerald-700 font-semibold text-sm">
            <i class="fas fa-check-circle text-emerald-500 text-lg"></i> {{ session('success') }}
        </div>
    @endif
    @if(session('warning'))
        <div class="mb-4 p-4 bg-amber-50 border border-amber-200 rounded-2xl flex items-center gap-3 text-amber-700 font-semibold text-sm">
            <i class="fas fa-exclamation-triangle text-amber-500 text-lg"></i> {{ session('warning') }}
        </div>
    @endif
    @if(session('error'))
        <div class="mb-4 p-4 bg-rose-50 border border-rose-200 rounded-2xl flex items-center gap-3 text-rose-700 font-semibold text-sm">
            <i class="fas fa-times-circle text-rose-500 text-lg"></i> {{ session('error') }}
        </div>
    @endif

    {{-- SEARCH BAR --}}
    <div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm p-4 mb-6">
        <form action="{{ route('kader.data.lansia.index') }}" method="GET" class="flex flex-col sm:flex-row gap-3">
            <div class="relative flex-1">
                <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 text-sm"></i>
                <input type="text" name="search" value="{{ $search }}" placeholder="Cari nama lansia, NIK, atau kode..." class="search-input">
            </div>
            <div class="flex gap-2">
                <button type="submit" class="px-8 py-3 bg-slate-800 text-white font-extrabold text-sm rounded-xl hover:bg-slate-900 shadow-sm transition-colors w-full sm:w-auto">Cari Data</button>
                @if($search)
                    <a href="{{ route('kader.data.lansia.index') }}" class="px-5 py-3 bg-slate-100 text-slate-600 font-bold text-sm rounded-xl hover:bg-slate-200 transition-colors">Reset</a>
                @endif
            </div>
        </form>
    </div>

    {{-- LEGENDA KEMANDIRIAN --}}
    <div class="flex flex-wrap items-center gap-3 mb-4 px-2">
        <span class="text-[11px] font-extrabold text-slate-400 uppercase tracking-widest">Ket. Kemandirian:</span>
        <span class="px-3 py-1 rounded-lg text-[11px] font-black badge-kem-a border"><i class="fas fa-walking mr-1"></i> A — Mandiri</span>
        <span class="px-3 py-1 rounded-lg text-[11px] font-black badge-kem-b border"><i class="fas fa-hands-helping mr-1"></i> B — Sebagian Bantuan</span>
        <span class="px-3 py-1 rounded-lg text-[11px] font-black badge-kem-c border"><i class="fas fa-wheelchair mr-1"></i> C — Total (misal: Stroke)</span>
    </div>

    {{-- TABLE DATA --}}
    <div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm overflow-hidden">
        <div class="overflow-x-auto custom-scrollbar">
            <table class="w-full text-left border-collapse min-w-[1050px]">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-200">
                        <th class="px-6 py-5 text-[10px] font-extrabold text-slate-400 uppercase tracking-widest">Profil Lansia</th>
                        <th class="px-6 py-5 text-[10px] font-extrabold text-slate-400 uppercase tracking-widest">Usia & TTL</th>
                        <th class="px-6 py-5 text-[10px] font-extrabold text-slate-400 uppercase tracking-widest text-center">Kemandirian</th>
                        <th class="px-6 py-5 text-[10px] font-extrabold text-slate-400 uppercase tracking-widest text-center">IMT</th>
                        <th class="px-6 py-5 text-[10px] font-extrabold text-slate-400 uppercase tracking-widest">Riwayat Penyakit</th>
                        <th class="px-6 py-5 text-[10px] font-extrabold text-slate-400 uppercase tracking-widest text-center">Status Akun</th>
                        <th class="px-6 py-5 text-[10px] font-extrabold text-slate-400 uppercase tracking-widest text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($lansias as $lansia)
                    @php
                        /* ====== KEMANDIRIAN ====== */
                        $kem = $lansia->kemandirian; // null | 'A' | 'B' | 'C'

                        $kemConfig = [
                            'A' => ['label' => 'Mandiri',          'icon' => 'fa-walking',       'badge' => 'badge-kem-a', 'border' => 'border-emerald-200'],
                            'B' => ['label' => 'Sebagian Bantuan', 'icon' => 'fa-hands-helping', 'badge' => 'badge-kem-b', 'border' => 'border-amber-200'],
                            'C' => ['label' => 'Total',            'icon' => 'fa-wheelchair',    'badge' => 'badge-kem-c', 'border' => 'border-rose-200'],
                        ];

                        /* ====== IMT ====== */
                        // Ambil dari kolom lansias.imt (sudah ditambahkan via migration)
                        $imtVal = $lansia->imt !== null ? (float) $lansia->imt : null;

                        $imtLabel   = null;
                        $imtClass   = null;
                        $imtBgClass = null;

                        if ($imtVal !== null && $imtVal > 0) {
                            if      ($imtVal < 18.5) { $imtLabel = 'Kurus';    $imtClass = 'text-blue-600';    $imtBgClass = 'bg-blue-50 border-blue-100'; }
                            elseif  ($imtVal < 25.0) { $imtLabel = 'Normal';   $imtClass = 'text-emerald-600'; $imtBgClass = 'bg-emerald-50 border-emerald-100'; }
                            elseif  ($imtVal < 27.0) { $imtLabel = 'Gemuk';    $imtClass = 'text-amber-600';   $imtBgClass = 'bg-amber-50 border-amber-100'; }
                            else                     { $imtLabel = 'Obesitas'; $imtClass = 'text-rose-600';    $imtBgClass = 'bg-rose-50 border-rose-100'; }
                        }
                    @endphp
                    <tr class="hover:bg-slate-50/70 transition-colors group">

                        {{-- Profil --}}
                        <td class="px-6 py-5 align-middle">
                            <div class="flex items-center gap-4">
                                <div class="w-12 h-12 rounded-2xl flex items-center justify-center font-black text-lg shadow-sm border border-white {{ $lansia->jenis_kelamin == 'L' ? 'bg-sky-50 text-sky-600' : 'bg-rose-50 text-rose-600' }}">
                                    {{ strtoupper(substr($lansia->nama_lengkap, 0, 1)) }}
                                </div>
                                <div>
                                    <p class="font-extrabold text-slate-800 text-[15px] mb-0.5">{{ $lansia->nama_lengkap }}</p>
                                    <p class="text-[11px] font-bold text-slate-400 flex items-center gap-1.5">
                                        <i class="fas fa-barcode"></i> {{ $lansia->nik ?? '-' }}
                                    </p>
                                </div>
                            </div>
                        </td>

                        {{-- Usia & TTL --}}
                        <td class="px-6 py-5 align-middle">
                            <div class="inline-flex items-center gap-2 px-3 py-1.5 bg-emerald-50 text-emerald-700 text-[11px] font-black rounded-lg mb-1 border border-emerald-100">
                                <i class="fas fa-hourglass-half"></i>
                                {{ \Carbon\Carbon::parse($lansia->tanggal_lahir)->age }} Tahun
                            </div>
                            <p class="text-[10px] font-bold text-slate-500 pl-1">
                                {{ $lansia->tempat_lahir }}, {{ \Carbon\Carbon::parse($lansia->tanggal_lahir)->translatedFormat('d M Y') }}
                            </p>
                        </td>

                        {{-- Kemandirian --}}
                        <td class="px-6 py-5 text-center align-middle">
                            @if($kem && isset($kemConfig[$kem]))
                                @php $kc = $kemConfig[$kem]; @endphp
                                <span class="inline-flex flex-col items-center gap-1 px-3 py-2 rounded-xl text-[11px] font-black border {{ $kc['badge'] }} {{ $kc['border'] }}">
                                    <i class="fas {{ $kc['icon'] }} text-base"></i>
                                    <span class="uppercase tracking-wide">{{ $kem }} — {{ $kc['label'] }}</span>
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-slate-100 text-slate-400 text-[10px] font-bold rounded-lg border border-slate-200">
                                    <i class="fas fa-question-circle"></i> Belum Diatur
                                </span>
                            @endif
                        </td>

                        {{-- IMT --}}
                        <td class="px-6 py-5 text-center align-middle">
                            @if($imtVal !== null && $imtVal > 0)
                                <div class="inline-flex flex-col items-center gap-0.5 px-3 py-2 rounded-xl border {{ $imtBgClass }}">
                                    <span class="text-[17px] font-black {{ $imtClass }}">{{ number_format($imtVal, 1) }}</span>
                                    <span class="text-[10px] font-extrabold uppercase tracking-wider {{ $imtClass }}">{{ $imtLabel }}</span>
                                    @if($lansia->berat_badan && $lansia->tinggi_badan)
                                        <span class="text-[9px] text-slate-400 font-medium">{{ $lansia->berat_badan }}kg / {{ $lansia->tinggi_badan }}cm</span>
                                    @endif
                                </div>
                            @else
                                <span class="text-slate-300 text-lg font-bold" title="Belum diukur">—</span>
                            @endif
                        </td>

                        {{-- Riwayat Penyakit --}}
                        <td class="px-6 py-5 align-middle">
                            <div class="flex flex-wrap gap-1.5 max-w-[200px]">
                                @if($lansia->penyakit_bawaan)
                                    @foreach(explode(',', $lansia->penyakit_bawaan) as $sakit)
                                        <span class="px-2.5 py-1 bg-rose-50 text-rose-600 border border-rose-100 rounded-lg text-[10px] font-bold shadow-sm">
                                            <i class="fas fa-virus text-[9px] mr-1"></i> {{ trim($sakit) }}
                                        </span>
                                    @endforeach
                                @else
                                    <span class="inline-flex items-center gap-1 text-emerald-600 font-bold text-[11px]">
                                        <i class="fas fa-check-circle"></i> Sehat / Normal
                                    </span>
                                @endif
                            </div>
                        </td>

                        {{-- Status Akun — FIXED (dulu if/else keduanya tampil "Belum") --}}
                        <td class="px-6 py-5 text-center align-middle">
                            @if($lansia->user_id)
                                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-emerald-50 text-emerald-600 text-[10px] font-black border border-emerald-200">
                                    <i class="fas fa-link text-emerald-500"></i> Terhubung
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-amber-50 text-amber-600 text-[10px] font-black border border-amber-200">
                                    <i class="fas fa-exclamation-circle text-amber-500"></i> Belum
                                </span>
                            @endif
                        </td>

                        {{-- Aksi --}}
                        <td class="px-6 py-5 text-center align-middle">
                            <div class="flex items-center justify-center gap-2">
                                <a href="{{ route('kader.data.lansia.show', $lansia->id) }}"
                                   class="w-10 h-10 rounded-xl bg-slate-50 border border-slate-200 flex items-center justify-center text-slate-500 hover:text-emerald-600 hover:bg-emerald-50 hover:border-emerald-200 transition-colors"
                                   title="Detail">
                                    <i class="fas fa-folder-open"></i>
                                </a>
                                <a href="{{ route('kader.data.lansia.edit', $lansia->id) }}"
                                   class="w-10 h-10 rounded-xl bg-slate-50 border border-slate-200 flex items-center justify-center text-slate-500 hover:text-amber-600 hover:bg-amber-50 hover:border-amber-200 transition-colors"
                                   title="Edit">
                                    <i class="fas fa-pen"></i>
                                </a>
                                <form action="{{ route('kader.data.lansia.destroy', $lansia->id) }}" method="POST"
                                      onsubmit="return confirm('Yakin hapus data {{ addslashes($lansia->nama_lengkap) }}? Tindakan ini tidak dapat dibatalkan.');"
                                      class="inline-block m-0">
                                    @csrf @method('DELETE')
                                    <button type="submit"
                                            class="w-10 h-10 rounded-xl bg-slate-50 border border-slate-200 flex items-center justify-center text-slate-500 hover:text-rose-600 hover:bg-rose-50 hover:border-rose-200 transition-colors"
                                            title="Hapus">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </form>
                            </div>
                        </td>

                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-20 text-center">
                            <div class="w-20 h-20 bg-slate-50 rounded-[20px] flex items-center justify-center text-slate-300 mx-auto mb-4 text-3xl shadow-inner border border-slate-100">
                                <i class="fas fa-user-slash"></i>
                            </div>
                            <h3 class="font-black text-slate-800 text-lg">
                                @if($search) Tidak Ditemukan @else Belum Ada Data Lansia @endif
                            </h3>
                            <p class="text-sm text-slate-500 mt-1 max-w-md mx-auto">
                                @if($search)
                                    Pencarian "<strong>{{ $search }}</strong>" tidak menemukan hasil. Coba kata kunci lain.
                                @else
                                    Anda belum menambahkan data lansia.
                                @endif
                            </p>
                            @if(!$search)
                                <a href="{{ route('kader.data.lansia.create') }}" class="inline-flex items-center gap-2 mt-5 px-6 py-2.5 bg-emerald-50 text-emerald-600 font-bold rounded-xl border border-emerald-100 hover:bg-emerald-100 transition-colors">
                                    <i class="fas fa-plus"></i> Tambah Data Sekarang
                                </a>
                            @endif
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- PAGINATION --}}
        @if($lansias->hasPages())
        <div class="px-6 py-4 border-t border-slate-100 bg-slate-50">
            {{ $lansias->appends(['search' => $search])->links() }}
        </div>
        @endif
    </div>

</div>
@endsection