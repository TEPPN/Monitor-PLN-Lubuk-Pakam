<?php

namespace App\Http\Controllers;

use App\Models\Recap;
use App\Models\Contract;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Carbon\Carbon; // <--- PENTING: Jangan lupa import Carbon

class DashboardController extends Controller
{
    public function index()
    {
        // Ambil semua kontrak (urutan query tidak masalah karena nanti disortir array-nya)
        $contracts = Contract::all();

        // Ambil data agregat request/planted
        $recapAggregates = Recap::select(
            'contract_id',
            DB::raw('SUM(request_9m) as total_request_9m'),
            DB::raw('SUM(request_12m) as total_request_12m'),
            DB::raw('SUM(planted_9m) as total_planted_9m'),
            DB::raw('SUM(planted_12m) as total_planted_12m')
        )
            ->groupBy('contract_id')
            ->get()
            ->keyBy('contract_id');

        $dashboardData = [];
        
        foreach ($contracts as $contract) {
            $aggregates = $recapAggregates->get($contract->id);

            // Hitung Sisa Hari
            // diffInDays(..., false) agar menghasilkan nilai negatif jika tanggal sudah lewat
            $remainingDays = $contract->end_date ? Carbon::now()->diffInDays(Carbon::parse($contract->end_date), false) : 9999; 
            
            // Format pesan sisa hari
            if (!$contract->end_date) {
                $daysText = '-';
            } elseif ($remainingDays < 0) {
                $daysText = 'Expired (' . abs(intval($remainingDays)) . ' hari lalu)';
            } else {
                $daysText = intval($remainingDays) . ' Hari';
            }

            $data = [
                'contract_id'   => $contract->id, // Perlu ID untuk link shortcut
                'contract_name' => $contract->name,
                'end_date'      => $contract->end_date,
                'remaining_days_val' => intval($remainingDays), // Untuk sorting
                'remaining_days_text'=> $daysText, // Untuk tampilan
                
                // Data Tiang 9 Meter
                'stock_9m'   => $contract->stock_9m,
                'request_9m' => $aggregates->total_request_9m ?? 0,
                'planted_9m' => $aggregates->total_planted_9m ?? 0,
                // Data Tiang 12 Meter
                'stock_12m'   => $contract->stock_12m,
                'request_12m' => $aggregates->total_request_12m ?? 0,
                'planted_12m' => $aggregates->total_planted_12m ?? 0,
            ];

            $dashboardData[] = $data;
        }

        // LOGIKA SORTING (ASCENDING)
        // Expired (negatif) akan muncul paling atas, diikuti sisa hari sedikit, lalu sisa hari banyak
        usort($dashboardData, function ($a, $b) {
            return $a['remaining_days_val'] <=> $b['remaining_days_val'];
        });

        return view('pages.dashboard', compact('dashboardData'));
    }
    public function export()
    {
        $filename = 'dashboard-monitoring-' . date('Y-m-d') . '.csv';

        $response = new StreamedResponse(function () {
            $handle = fopen('php://output', 'w');

            // 1. Tulis Header CSV
            fputcsv($handle, [
                'Nama Kontrak',
                'Sisa Masa Berlaku (Hari)',
                'Stok 9m',
                'Request 9m',
                'Tertanam 9m',
                'Stok 12m',
                'Request 12m',
                'Tertanam 12m'
            ]);

            // 2. Ambil & Hitung Data (Logic sama dengan index)
            $contracts = Contract::all();
            
            $recapAggregates = Recap::select(
                'contract_id',
                DB::raw('SUM(request_9m) as total_request_9m'),
                DB::raw('SUM(request_12m) as total_request_12m'),
                DB::raw('SUM(planted_9m) as total_planted_9m'),
                DB::raw('SUM(planted_12m) as total_planted_12m')
            )->groupBy('contract_id')->get()->keyBy('contract_id');

            $dashboardData = [];

            foreach ($contracts as $contract) {
                $aggregates = $recapAggregates->get($contract->id);
                $remainingDays = $contract->end_date ? Carbon::now()->diffInDays(Carbon::parse($contract->end_date), false) : 99999;

                // Teks Sisa Hari
                if (!$contract->end_date) $daysText = '-';
                elseif ($remainingDays < 0) $daysText = 'Expired (' . abs(intval($remainingDays)) . ' hari lalu)';
                else $daysText = intval($remainingDays);

                $dashboardData[] = [
                    'name' => $contract->name,
                    'remaining' => $remainingDays, // value asli untuk sort
                    'remaining_text' => $daysText,
                    's9' => $contract->stock_9m,
                    'r9' => $aggregates->total_request_9m ?? 0,
                    'p9' => $aggregates->total_planted_9m ?? 0,
                    's12' => $contract->stock_12m,
                    'r12' => $aggregates->total_request_12m ?? 0,
                    'p12' => $aggregates->total_planted_12m ?? 0,
                ];
            }

            // Sortir data (Ascending sisa hari)
            usort($dashboardData, fn($a, $b) => $a['remaining'] <=> $b['remaining']);

            // 3. Tulis Baris Data ke CSV
            foreach ($dashboardData as $data) {
                fputcsv($handle, [
                    $data['name'],
                    $data['remaining_text'],
                    $data['s9'],
                    $data['r9'],
                    $data['p9'],
                    $data['s12'],
                    $data['r12'],
                    $data['p12'],
                ]);
            }

            fclose($handle);
        });

        $response->headers->set('Content-Type', 'text/csv');
        $response->headers->set('Content-Disposition', 'attachment; filename="' . $filename . '"');

        return $response;
    }
}