<?php

namespace App\Http\Controllers\Kader;

use App\Http\Controllers\Controller;
use App\Models\DataImport;
use App\Models\Balita;
use App\Models\Remaja;
use App\Models\Lansia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\TemplateImportBalitaExport;
use App\Exports\TemplateImportRemajaExport;
use App\Exports\TemplateImportLansiaExport;

class ImportController extends Controller
{
    public function index()
    {
        return view('kader.import.index');
    }

    public function create()
    {
        return view('kader.import.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'jenis_data' => 'required|in:balita,remaja,lansia',
            'file' => 'required|file|mimes:xlsx,xls,csv|max:5120',
        ]);
        
        $file = $request->file('file');
        $filename = time() . '_' . $file->getClientOriginalName();
        $path = $file->storeAs('imports', $filename);
        
        $dataImport = DataImport::create([
            'nama_file' => $file->getClientOriginalName(),
            'jenis_data' => $request->jenis_data,
            'file_path' => $path,
            'status' => 'pending',
            'created_by' => Auth::id(),
        ]);
        
        try {
            $dataImport->update(['status' => 'processing']);
            
            // Mengubah file menjadi array data
            $data = Excel::toArray([], storage_path('app/' . $path));
            
            if (empty($data[0])) {
                throw new \Exception('File Excel kosong atau tidak terbaca');
            }

            $rows = $data[0];
            unset($rows[0]); // Menghapus baris pertama (Header Excel)

            $successCount = 0;
            $errorCount = 0;

            DB::beginTransaction();

            foreach ($rows as $row) {
                // Pastikan kolom NIK tidak kosong (Baris Dasar)
                if (empty($row[0])) continue;

                if ($request->jenis_data == 'balita') {
                    Balita::updateOrCreate(
                        ['nik' => $row[0]], // Cek NIK agar tidak duplikat
                        [
                            'kode_balita' => 'BLT-' . date('Ym') . str_pad(Balita::count() + 1, 3, '0', STR_PAD_LEFT),
                            'nama_lengkap' => $row[1],
                            'tempat_lahir' => $row[2],
                            'tanggal_lahir' => $row[3],
                            'jenis_kelamin' => $row[4],
                            'nama_ibu'     => $row[5],
                            'alamat'       => $row[6],
                            'created_by'   => Auth::id(),
                        ]
                    );
                } 
                elseif ($request->jenis_data == 'remaja') {
                    Remaja::updateOrCreate(
                        ['nik' => $row[0]],
                        [
                            'kode_remaja'  => 'RMJ-' . date('Ym') . str_pad(Remaja::count() + 1, 3, '0', STR_PAD_LEFT),
                            'nama_lengkap' => $row[1],
                            'tempat_lahir' => $row[2],
                            'tanggal_lahir' => $row[3],
                            'jenis_kelamin' => $row[4],
                            'alamat'       => $row[5],
                            'created_by'   => Auth::id(),
                        ]
                    );
                } 
                elseif ($request->jenis_data == 'lansia') {
                    Lansia::updateOrCreate(
                        ['nik' => $row[0]],
                        [
                            'kode_lansia'  => 'LNS-' . date('Ym') . str_pad(Lansia::count() + 1, 3, '0', STR_PAD_LEFT),
                            'nama_lengkap' => $row[1],
                            'tempat_lahir' => $row[2],
                            'tanggal_lahir' => $row[3],
                            'jenis_kelamin' => $row[4],
                            'alamat'       => $row[5],
                            'created_by'   => Auth::id(),
                        ]
                    );
                }
                $successCount++;
            }

            DB::commit();

            $dataImport->update([
                'status' => 'completed',
                'total_data' => count($rows),
                'data_berhasil' => $successCount,
                'data_gagal' => $errorCount,
                'catatan' => 'Import data berhasil diproses ke database',
            ]);
            
            return redirect()->route('kader.import.history')
                ->with('success', "Proses selesai: $successCount data berhasil dimasukkan.");
                
        } catch (\Exception $e) {
            DB::rollBack();
            $dataImport->update([
                'status' => 'failed',
                'catatan' => 'Error: ' . $e->getMessage(),
            ]);
            
            return back()->with('error', 'Gagal memproses data: ' . $e->getMessage());
        }
    }

    public function history()
    {
        $imports = DataImport::where('created_by', Auth::id())
            ->orderBy('created_at', 'desc')
            ->paginate(15);
            
        return view('kader.import.history', compact('imports'));
    }

    public function show($id)
    {
        $import = DataImport::findOrFail($id);
        return view('kader.import.show', compact('import'));
    }

    public function downloadTemplate($type)
    {
        if (!in_array($type, ['balita', 'remaja', 'lansia'])) {
            return back()->with('error', 'Jenis template tidak valid');
        }
        
        $filename = 'template_import_' . $type . '_' . date('YmdHis') . '.xlsx';
        
        switch ($type) {
            case 'balita':
                return Excel::download(new TemplateImportBalitaExport(), $filename);
            case 'remaja':
                return Excel::download(new TemplateImportRemajaExport(), $filename);
            case 'lansia':
                return Excel::download(new TemplateImportLansiaExport(), $filename);
            default:
                return back()->with('error', 'Jenis template tidak valid');
        }
    }
}