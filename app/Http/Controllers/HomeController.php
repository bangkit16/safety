<?php

namespace App\Http\Controllers;

use App\Charts\AparPerBulan;
use App\Charts\SampleChart;
use App\Models\Perbaikan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    // Tampilkan halaman dashboard
    public function index(SampleChart $chart)
    {
        // Ambil data jumlah temuan dari tabel Perbaikan yang berelasi dengan Patrol
       // Query untuk menghitung jumlah temuan berdasarkan Patrol dan Tahun
            $data = Perbaikan::selectRaw('YEAR(patrols.tanggal) as tahun, patrols.tanggal as patrol, COUNT(perbaikans.perbaikan_id) as jumlah_temuan')
            ->join('patrols', 'patrols.patrol_id', '=', 'perbaikans.patrol_id')
            ->groupBy('tahun', 'patrols.tanggal')
            ->orderBy('tahun')
            ->get();

        // Persiapan data untuk chart
        $patrols = $data->pluck('patrol')->unique()->toArray();
        $tahun = $data->pluck('tahun')->unique()->toArray();

        // Menyusun data temuan per Patrol per Tahun
        $temuanData = [];
        foreach ($patrols as $patrol) {
            $temuanPerPatrol = [];
            foreach ($tahun as $th) {
                $temuanPerPatrol[] = $data->where('patrol', $patrol)->where('tahun', $th)->sum('jumlah_temuan');
            }
            $temuanData[$patrol] = $temuanPerPatrol;
        }

        // Kirim ke view
        return view('admin.dashboard', ['chart' => $chart->build(['tahun' => $tahun, 'patrols' => $patrols, 'temuan' => $temuanData])]);
    }

    // Ambil data jumlah apar per bulan
    // public function getMonthlyAparData()
    // {
    //     $data = DB::table('patrols')
    //         ->select(DB::raw('MONTH(tanggal) as month, COUNT(*) as total'))
    //         ->groupBy('month')
    //         ->orderBy('month')
    //         ->pluck('total', 'month');

    //     // dd($data);

    //     $labels = [];
    //     $values = [];

    //     foreach ($data as $month => $total) {
    //         $labels[] = $this->getMonthName((int)$month); // Ubah angka ke nama bulan
    //         $values[] = $total;
    //     }



    //     return response()->json([
    //         'labels' => $labels,
    //         'values' => $values,
    //     ]);
    // }

    // private function getMonthName($month)
    // {
    //     $months = [
    //         1 => 'Januari',
    //         2 => 'Februari',
    //         3 => 'Maret',
    //         4 => 'April',
    //         5 => 'Mei',
    //         6 => 'Juni',
    //         7 => 'Juli',
    //         8 => 'Agustus',
    //         9 => 'September',
    //         10 => 'Oktober',
    //         11 => 'November',
    //         12 => 'Desember',
    //     ];
    //     return $months[$month] ?? '';
    // }
}
