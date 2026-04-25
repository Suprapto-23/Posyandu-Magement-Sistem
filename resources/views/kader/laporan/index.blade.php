@extends('layouts.kader')
@section('title', 'Generator Laporan PDF')
@section('page-name', 'Pusat Cetak Dokumen')

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
<style>
    .animate-slide-up { opacity: 0; animation: slideUpFade 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
    @keyframes slideUpFade { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
    
    .report-card { transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); border: 1px solid rgba(226, 232, 240, 0.8); }
    .report-card:hover { transform: translateY(-5px); box-shadow: 0 20px 40px -15px rgba(0,0,0,0.1); border-color: rgba(99, 102, 241, 0.3); }
    
    .custom-select { 
        appearance: none; 
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%2364748b'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M19 9l-7 7-7-7'%3E%3C/path%3E%3C/svg%3E"); 
        background-repeat: no-repeat; background-position: right 1rem center; background-size: 1rem; 
    }
    
    .btn-pdf-hover { transition: all 0.3s; }
    .report-card:hover .btn-pdf-hover { background-color: #4f46e5; color: white; border-color: #4f46e5; box-shadow: 0 4px 12px rgba(79, 70, 229, 0.2); }
</style>
@endpush

@section('content')
<div class="max-w-[1400px] mx-auto animate-slide-up pb-10">

    {{-- HEADER BANNER EKSKLUSIF --}}
    <div class="bg-slate-900 rounded-[32px] p-8 md:p-12 mb-8 relative overflow-hidden shadow-2xl flex flex-col md:flex-row items-center justify-between gap-6 text-center sm:text-left">
        <div class="absolute right-0 top-0 w-[500px] h-[500px] bg-indigo-500/20 rounded-full blur-[80px] pointer-events-none transform translate-x-1/3 -translate-y-1/4"></div>
        <div class="absolute left-0 bottom-0 w-[300px] h-[300px] bg-rose-500/20 rounded-full blur-[60px] pointer-events-none transform -translate-x-1/2 translate-y-1/3"></div>
        
        <div class="relative z-10 flex flex-col sm:flex-row items-center gap-6">
            <div class="w-20 h-20 rounded-[24px] bg-white/10 border border-white/20 text-white flex items-center justify-center text-4xl shrink-0 backdrop-blur-md shadow-lg transform -rotate-3">
                <i class="fas fa-print"></i>
            </div>
            <div>
                <div class="inline-flex items-center gap-2 px-3 py-1 bg-indigo-500/30 border border-indigo-400/50 text-indigo-100 text-[10px] font-black uppercase tracking-widest rounded-full mb-3">
                    <i class="fas fa-bolt text-amber-300"></i> Direct Print Engine
                </div>
                <h1 class="text-3xl sm:text-4xl font-black text-white tracking-tight font-poppins mb-2">Pusat Cetak Dokumen Resmi</h1>
                <p class="text-slate-400 font-medium text-[14px] max-w-xl leading-relaxed">
                    Pilih parameter bulan dan tahun, lalu sistem akan otomatis menyusun tabel data beserta kop surat resmi desa ke dalam format <strong class="text-white">PDF siap cetak</strong>.
                </p>
            </div>
        </div>
    </div>

    @php
        $reports = [
            ['type' => 'balita', 'title' => 'Register Balita', 'desc' => 'Tumbuh Kembang & Status Gizi', 'icon' => 'fa-baby', 'color' => 'rose'],
            ['type' => 'ibu_hamil', 'title' => 'Register Ibu Hamil', 'desc' => 'Pemeriksaan & Risiko KEK', 'icon' => 'fa-female', 'color' => 'pink'],
            ['type' => 'remaja', 'title' => 'Register Remaja', 'desc' => 'Skrining PTM Remaja', 'icon' => 'fa-user-graduate', 'color' => 'sky'],
            ['type' => 'lansia', 'title' => 'Register Lansia', 'desc' => 'Pemeriksaan Lab & Tensi', 'icon' => 'fa-wheelchair', 'color' => 'emerald'],
            ['type' => 'imunisasi', 'title' => 'Register Imunisasi', 'desc' => 'Log Pemberian Vaksinasi', 'icon' => 'fa-syringe', 'color' => 'indigo'],
            ['type' => 'kunjungan', 'title' => 'Buku Kehadiran', 'desc' => 'Absensi Warga di Meja 1', 'icon' => 'fa-clipboard-user', 'color' => 'amber'],
        ];
        $currentMonth = date('m');
        $currentYear = date('Y');
    @endphp

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($reports as $r)
        <div class="report-card bg-white rounded-[28px] p-7 flex flex-col group">
            
            {{-- HEADER KARTU --}}
            <div class="flex items-center gap-4 mb-6">
                <div class="w-14 h-14 rounded-2xl bg-{{ $r['color'] }}-50 text-{{ $r['color'] }}-500 flex items-center justify-center text-2xl border border-{{ $r['color'] }}-100 shrink-0 group-hover:scale-110 transition-transform duration-300">
                    <i class="fas {{ $r['icon'] }}"></i>
                </div>
                <div>
                    <h3 class="font-black text-slate-800 text-[17px] font-poppins leading-tight tracking-tight">{{ $r['title'] }}</h3>
                    <p class="text-[11px] font-bold text-slate-400 mt-1 uppercase tracking-wider">{{ $r['desc'] }}</p>
                </div>
            </div>

           
           {{-- FORM FILTER & DOWNLOAD --}}
            <form action="{{ route('kader.laporan.generate') }}" method="GET" class="mt-auto flex flex-col gap-4">
                {{-- JANGAN masukkan @csrf di sini --}}
                <input type="hidden" name="type" value="{{ $r['type'] }}">
                
                <div class="bg-slate-50 p-1.5 rounded-[16px] border border-slate-200 flex items-center gap-1">
                    <div class="relative w-full">
                        <select name="bulan" class="custom-select w-full bg-white border-none shadow-sm text-slate-700 text-[13px] font-bold rounded-xl pl-4 pr-10 py-3 focus:ring-2 focus:ring-{{ $r['color'] }}-400 transition-all cursor-pointer">
                            @foreach(range(1, 12) as $m)
                                <option value="{{ str_pad($m, 2, '0', STR_PAD_LEFT) }}" {{ date('m') == $m ? 'selected' : '' }}>
                                    {{ \Carbon\Carbon::create()->month((int)$m)->translatedFormat('F') }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="w-px h-8 bg-slate-200 mx-1"></div>
                    <div class="relative w-28 shrink-0">
                        <select name="tahun" class="custom-select w-full bg-white border-none shadow-sm text-slate-700 text-[13px] font-bold rounded-xl pl-4 pr-8 py-3 focus:ring-2 focus:ring-{{ $r['color'] }}-400 transition-all cursor-pointer">
                            @foreach(range(date('Y')-2, date('Y')) as $y)
                                <option value="{{ $y }}" {{ date('Y') == $y ? 'selected' : '' }}>{{ $y }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <button type="submit" onclick="tampilkanLoading()" class="btn-pdf-hover w-full py-4 bg-white border-2 border-slate-200 text-slate-600 font-black text-[13px] uppercase tracking-widest rounded-[16px] flex items-center justify-center gap-2 relative overflow-hidden">
                    <i class="fas fa-file-pdf text-rose-500"></i> Unduh PDF Dokumen
                </button>
            </form>
        </div>
        @endforeach
    </div>

</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    // Logic Loading yang otomatis tertutup jika download berhasil
    const forms = document.querySelectorAll('.form-download');
    forms.forEach(form => {
        form.addEventListener('submit', function() {
            Swal.fire({
                title: 'Menyusun Laporan...',
                html: '<p class="text-sm text-slate-500">Mengekstrak data dari database dan menyusun dokumen PDF.</p>',
                allowOutsideClick: false,
                showConfirmButton: false,
                timer: 3000, 
                willOpen: () => { Swal.showLoading(); },
                customClass: { popup: 'rounded-[24px]' }
            });
        });
    });

    // Menangkap Error Session dari Controller
    @if(session('error'))
        Swal.fire({
            icon: 'error',
            title: 'Laporan Kosong',
            text: "{{ session('error') }}",
            confirmButtonColor: '#4f46e5',
            customClass: { popup: 'rounded-[24px]' }
        });
    @endif
</script>
@endpush
@endsection