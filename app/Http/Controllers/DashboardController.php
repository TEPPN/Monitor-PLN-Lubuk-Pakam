<?php

namespace App\Http\Controllers;

use App\Models\Contract;
use App\Models\Recap;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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
}