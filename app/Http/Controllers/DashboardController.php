<?php

namespace App\Http\Controllers;

use App\Models\Contract;
use App\Models\Recap;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Display the dashboard with a summary of all contracts.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $contracts = Contract::orderBy('name')->get();

        // Get total requested and planted amounts for each contract
        $recapAggregates = Recap::select(
            'contract_id',
            DB::raw('SUM(request) as total_request'),
            DB::raw('SUM(planted) as total_planted')
        )
            ->groupBy('contract_id')
            ->get()
            ->keyBy('contract_id');

        $dashboardData = [];
        foreach ($contracts as $contract) {
            $aggregates = $recapAggregates->get($contract->id);
            $totalRequest = $aggregates->total_request ?? 0;
            $totalPlanted = $aggregates->total_planted ?? 0;

            $data = [
                'contract_name' => $contract->name,
                'stock_9m' => 0,
                'stock_12m' => 0,
                'request_9m' => 0,
                'request_12m' => 0,
                'planted_9m' => 0,
                'planted_12m' => 0,
            ];

            if ($contract->pole_size === '9 meter') {
                $data['stock_9m'] = $contract->stock;
                $data['request_9m'] = $totalRequest;
                $data['planted_9m'] = $totalPlanted;
            } elseif ($contract->pole_size === '12 meter') {
                $data['stock_12m'] = $contract->stock;
                $data['request_12m'] = $totalRequest;
                $data['planted_12m'] = $totalPlanted;
            }

            $dashboardData[] = $data;
        }

        return view('pages.dashboard', compact('dashboardData'));
    }
}
