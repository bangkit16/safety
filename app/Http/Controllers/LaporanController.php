<?php

namespace App\Http\Controllers;

use App\Exports\LaporanPatroliExport;
use App\Exports\PatrolExport;
use App\Models\Divisi;
use App\Models\Patrol;
use App\Models\Perbaikan;
use App\Models\Role;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class LaporanController extends Controller
{
    public function index(Request $request)
    {
        $limit = $request->input('limit', 10);  // Default pagination limit
        $search = $request->search;

        // Pemetaan nama bulan ke angka
        $bulanMap = [
            'januari' => '01', 'februari' => '02', 'maret' => '03',
            'april' => '04', 'mei' => '05', 'juni' => '06',
            'juli' => '07', 'agustus' => '08', 'september' => '09',
            'oktober' => '10', 'november' => '11', 'desember' => '12',
        ];

        // Cek apakah pencarian mengandung nama bulan
        $bulan = null;
        if ($search) {
            $searchLower = strtolower($search);
            if (isset($bulanMap[$searchLower])) {
                $bulan = $bulanMap[$searchLower];
            }
        }

        // Query dasar
        $data = Patrol::query();

        // Filter pencarian
        if ($search) {
            $divisi = Divisi::where('nama', 'like', "%{$search}%")->pluck('divisi_id');
            $user = User::where('name', 'like', "%{$search}%")->pluck('user_id'); // Ambil ID User

            $data->where(function ($q) use ($search, $bulan, $divisi, $user) {
                if ($divisi->isNotEmpty()) {
                    $q->orWhereIn('divisi_id', $divisi);
                }
                if ($user->isNotEmpty()) {
                    $q->orWhere('user_id', $user); // Asumsikan patrol memiliki `user_id`
                }
                if ($bulan) {
                    $q->orWhereMonth('tanggal', $bulan); // Cari berdasarkan bulan
                }
                $q->orWhere('tanggal', 'like', "%{$search}%");
            });
        }        

        // Terapkan sorting
        $sortBy = $request->get('sort_by', 'patrol_id');
        $order = $request->get('order', 'asc');
        $data->orderBy($sortBy, $order);

        // Pagination atau semua data
        if ($limit == 'all') {
            $data = $data->get();  // Ambil semua data
        } else {
            $data = $data->paginate($limit)->appends($request->only('search', 'limit', 'sort_by', 'order')); // Tambahkan query params
        }

        $divisi = Divisi::all();
        $user = User::all();


        $divisi = Divisi::all();
        $user = User::all();
        $perbaikan = Perbaikan::all();

        // Kirim data ke view
        return view('admin.laporan.index', compact('data', 'perbaikan' ,'user', 'divisi', 'sortBy', 'order'));
    }

    public function prepareData($id)
    {
        $patrol = Patrol::find($id);
        $perbaikan = Perbaikan::where('patrol_id', $id)->get();
        $user = User::all();
        $divisi = Divisi::all();
        $nama = '';
        $tim = '';
        $pic = '';
        foreach ($user as $d) {
            if ($d->user_id == $patrol->user_id) {
                $nama = $d->name;
            }
        }

        foreach ($divisi as $d) {
            if ($d->divisi_id == $patrol->divisi_id) {
                $tim = $d->nama;
            }
        }

        $no = [];
        $num = 0;
        foreach ($perbaikan as $key => $p) {
            $num += 1;
            $no[$key] = $num;
        }

        // dd($patrol);

        return [
            'perbaikan' => $perbaikan,
            'patrol' => $patrol,
            'user' => $user,
            'divisi' => $divisi,
            'nama' => $nama,
            'tim' => $tim,
            'no' => $no,
        ];
    }

    public function downloadPDF(Request $request, $id)
    {
        $data = $this->prepareData($id);

        if (!$data) {
            return response()->json(['error' => 'Data tidak ditemukan']);
        }

        $pdf = Pdf::loadView('admin.laporan.cetak', $data, compact('id'));

        return $pdf->download('patroli-keselamatan.pdf');
    }

    // 📊 Download Excel Method
    public function downloadExcel(Request $request, $id)
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $patrol = Patrol::find($id);
        $user = User::find($patrol->user_id);
        $divisi = Divisi::find($patrol->divisi_id);

        $nama = [];
        $data = Perbaikan::all();
        $us = User::all();
        foreach ($data as $key => $value) {
            foreach ($us as $d) {
                if ($d->user_id == $value->user_id) {
                    $nama[$key] = $d->name;
                }
            }
        }
        // dd($nama);
     
        // Judul Laporan
        $sheet->setCellValue('A1', 'Unit Kerja: ' . $user->name);
        $sheet->mergeCells('A1:D1');
        $sheet->setCellValue('A2', 'Tanggal Safety Patrol: ' . $patrol->tanggal);
        $sheet->mergeCells('A2:D2');
        $sheet->setCellValue('E1', 'Tim Inspeksi: ' . $divisi->nama);
        $sheet->mergeCells('E1:H1');
    
        // Judul Kolom
        $sheet->setCellValue('A6', 'No');
        $sheet->setCellValue('B6', 'Dokumentasi Temuan');
        $sheet->setCellValue('C6', 'Keterangan Temuan');
        $sheet->setCellValue('D6', 'Rekomendasi Perbaikan');
        $sheet->setCellValue('E6', 'PIC ');
        $sheet->setCellValue('F6', 'Target');
        $sheet->setCellValue('G6', 'Status');
        $sheet->setCellValue('H6', 'Dokumentasi');
    
        $perbaikan = Perbaikan::where('patrol_id', $id)->get();
        $row = 7;
    
        foreach ($perbaikan as $index => $item) {
            $sheet->setCellValue('A' . $row, $index + 1);
            $sheet->setCellValue('C' . $row, $item->keterangan ?? '-'); // Default jika kosong
            $sheet->setCellValue('D' . $row, $item->perbaikan ?? '-'); // Default jika kosong
            $sheet->setCellValue('E' . $row, $nama[$index] ?? '-');
            $sheet->setCellValue('F' . $row, $item->target ?? '-'); // Default jika kosong
            $sheet->setCellValue('G' . $row, $item->status ?? '-'); // Default jika kosong
        
            // Dokumentasi Temuan
            if (!empty($item->temuan)) {
                $temuanPath = storage_path('app/public/' . $item->temuan);
                if (file_exists($temuanPath)) {
                    $drawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
                    $drawing->setName('Temuan');
                    $drawing->setPath($temuanPath);
                    $drawing->setHeight(100);
                    $drawing->setCoordinates('B' . $row);
                    $drawing->setWorksheet($sheet);
                } else {
                    $sheet->setCellValue('B' . $row, 'Gambar tidak ditemukan');
                }
            } else {
                $sheet->setCellValue('B' . $row, 'Dokumentasi kosong');
            }
        
            // Dokumentasi Perbaikan
            if (!empty($item->dokumentasi)) {
                $dokumentasiPath = storage_path('app/public/' . $item->dokumentasi);
                if (file_exists($dokumentasiPath)) {
                    $drawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
                    $drawing->setName('Dokumentasi');
                    $drawing->setPath($dokumentasiPath);
                    $drawing->setHeight(100);
                    $drawing->setCoordinates('H' . $row);
                    $drawing->setWorksheet($sheet);
                } else {
                    $sheet->setCellValue('H' . $row, 'Gambar tidak ditemukan');
                }
            } else {
                $sheet->setCellValue('H' . $row, 'Dokumentasi kosong');
            }
        
            $row++;
        }
        
    
        foreach (range('A', 'H') as $column) {
            $sheet->getColumnDimension($column)->setAutoSize(true);
        }
    
        $writer = new Xlsx($spreadsheet);
    
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="laporan_keselamatan.xlsx"');
        header('Cache-Control: max-age=0');
    
        $writer->save('php://output');
        exit;
    }
    
    public function print(Request $request, $id)
    {
        $data = $this->prepareData($id);

        // dd(count($data['data'][4]['sub_uraian']));

        if (!$data) {
            return response()->json(['error' => 'Data tidak ditemukan']);
        }

        return view('admin.laporan.cetak2', $data, compact('id'));
    }
}
