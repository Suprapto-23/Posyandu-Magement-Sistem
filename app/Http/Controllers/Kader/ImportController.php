<?php

namespace App\Http\Controllers\Kader;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use Illuminate\Support\Facades\Response;

// Memanggil Model Utama
use App\Models\DataImport; 

// Memanggil Class Import
use App\Imports\BalitaImport;
use App\Imports\RemajaImport;
use App\Imports\LansiaImport;

class ImportController extends Controller
{
    public function index()
    {
        return view('kader.import.index');
    }

    public function create(Request $request)
    {
        $type = $request->get('type', '');
        return view('kader.import.create', compact('type'));
    }

    public function store(Request $request)
    {
        // 1. Validasi
        $request->validate([
            'jenis_data' => 'required|in:balita,remaja,lansia',
            'file'       => 'required|file|mimes:xlsx,xls,csv|max:10240', 
        ]);

        $file = $request->file('file');
        $originalName = $file->getClientOriginalName();
        $jenisData = $request->jenis_data;
        $isSmartImport = $request->has('smart_import');

        // 2. Simpan fisik file
        $path = $file->store('imports');

        // 3. Catat ke DB (Status Processing)
        $riwayat = DataImport::create([
            'nama_file'  => $originalName,
            'jenis_data' => $jenisData,
            'file_path'  => $path,
            'status'     => 'processing',
            'created_by' => auth()->id(),
        ]);

        try {
            $importClass = match($jenisData) {
                'balita' => new BalitaImport(),
                'remaja' => new RemajaImport(),
                'lansia' => new LansiaImport(),
            };
            
            // HITUNG BARIS DULU SEBELUM IMPORT AGAR MUNCUL DI TERMINAL SHOW
            $arrayData = Excel::toArray($importClass, $file);
            $jumlahBaris = 0;
            if (isset($arrayData[0])) {
                $jumlahBaris = count($arrayData[0]); // Ambil jumlah baris di Sheet 1
            }

            // EKSEKUSI IMPORT
            Excel::import($importClass, $file);

            $modeText = $isSmartImport ? '[Mode Smart Mapping AI Aktif]' : '[Mode Standar]';
            
            // Update Riwayat dengan Jumlah Baris
            $riwayat->update([
                'status'         => 'completed',
                'data_tersimpan' => $jumlahBaris, // Angka ini akan muncul di show.blade.php
                'catatan'        => "{$modeText} Berhasil membaca {$jumlahBaris} baris data dari Excel. Data telah dianalisis dan disimpan ke sistem.",
            ]);

            // RESPONSE AJAX (Real-Time dengan JSON)
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'status' => 'success',
                    'message' => "Sukses! {$jumlahBaris} baris data berhasil di-import.",
                    'redirect' => route('kader.import.history')
                ]);
            }

            return redirect()->route('kader.import.history')->with('success', 'Sukses! Data berhasil di-import.');

        } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
            $failures = $e->failures();
            $errorMsg = "Gagal di baris ke-" . $failures[0]->row() . ": " . $failures[0]->errors()[0];
            
            $riwayat->update(['status' => 'failed', 'catatan' => $errorMsg]);
            
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['status' => 'error', 'message' => $errorMsg], 422);
            }
            return redirect()->route('kader.import.history')->with('error', $errorMsg);

        } catch (\Throwable $e) { 
            Log::error('Kesalahan Import Data : ' . $e->getMessage());
            $riwayat->update(['status' => 'failed', 'catatan' => 'Error Server: ' . $e->getMessage()]);

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['status' => 'error', 'message' => 'Format Excel tidak valid atau kolom tidak sesuai.'], 500);
            }
            return redirect()->route('kader.import.history')->with('error', 'Terjadi kesalahan sistem.');
        }
    }

    public function history(Request $request)
    {
        $tanggal = $request->get('tanggal');
        $query = DataImport::query();

        if ($tanggal) {
            $query->whereDate('created_at', $tanggal);
        }

        $imports = $query->latest()->paginate(10)->withQueryString();
        return view('kader.import.history', compact('imports', 'tanggal'));
    }

    public function show($id)
    {
        $import = DataImport::findOrFail($id);
        return view('kader.import.show', compact('import'));
    }

    public function destroy($id)
    {
        try {
            $import = DataImport::findOrFail($id);
            if ($import->file_path && Storage::exists($import->file_path)) {
                Storage::delete($import->file_path);
            }
            $import->delete();
            return redirect()->route('kader.import.history')->with('success', 'Log riwayat import dihapus.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menghapus data riwayat.');
        }
    }

    public function downloadTemplate($type)
    {
        if (!in_array($type, ['balita', 'remaja', 'lansia'])) abort(404);

        $headers = match($type) {
            'balita' => ['nama_lengkap', 'nik_balita', 'jenis_kelamin', 'tempat_lahir', 'tanggal_lahir_yyyy_mm_dd', 'nama_ibu', 'nik_ibu', 'berat_lahir_kg', 'panjang_lahir_cm', 'alamat_lengkap'],
            'remaja' => ['nama_lengkap', 'nik', 'jenis_kelamin', 'tempat_lahir', 'tanggal_lahir_yyyy_mm_dd', 'nama_sekolah', 'kelas', 'nama_ortu', 'no_hp_ortu', 'alamat_lengkap'],
            'lansia' => ['nama_lengkap', 'nik', 'jenis_kelamin', 'tempat_lahir', 'tanggal_lahir_yyyy_mm_dd', 'riwayat_penyakit', 'status_keluarga', 'no_hp', 'alamat_lengkap'],
        };

        $csvFileName = "Template_KaderCare_" . strtoupper($type) . ".csv";
        $handle = fopen('php://temp', 'w');
        fputcsv($handle, $headers); 
        
        $dummyData = match($type) {
            'balita' => ['Budi Santoso', '3200000000000001', 'L', 'Jakarta', '2024-01-15', 'Siti Aminah', '3200000000000002', '3.5', '50', 'Jl. Merdeka No 1'],
            'remaja' => ['Ahmad Yani', '3200000000000003', 'L', 'Bandung', '2010-05-20', 'SMPN 1 Bandung', '8A', 'Bambang', '08123456789', 'Jl. Pahlawan No 2'],
            'lansia' => ['Suprapto', '3200000000000004', 'L', 'Surabaya', '1950-10-10', 'Hipertensi', 'Kepala Keluarga', '08111222333', 'Jl. Sudirman No 3'],
        };
        fputcsv($handle, $dummyData); 

        rewind($handle);
        $csvContent = stream_get_contents($handle);
        fclose($handle);

        return Response::make($csvContent, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $csvFileName . '"',
        ]);
    }
}