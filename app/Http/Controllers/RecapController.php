<?php

namespace App\Http\Controllers;

use App\Models\Log;
use App\Models\Recap;
use App\Models\Contract;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\StreamedResponse;
use App\Models\Company; // <--- PENTING: Tambahkan ini agar tidak error Class not found

class RecapController extends Controller
{
    public function index(Request $request)
    {
        $contracts = Contract::orderBy('name')->get();
        
        $recapsQuery = Recap::with(['contract.company', 'createdBy'])->latest();

        $selectedContract = null;
        $remainingStock = ['9m' => 0, '12m' => 0];

        if ($request->filled('contract_id')) {
            $contractId = $request->contract_id;
            $recapsQuery->where('contract_id', $contractId);

            $selectedContract = Contract::find($contractId);

            if ($selectedContract) {
                $totalRequested9m = Recap::where('contract_id', $contractId)->sum('request_9m');
                $remainingStock['9m'] = $selectedContract->stock_9m - $totalRequested9m;

                $totalRequested12m = Recap::where('contract_id', $contractId)->sum('request_12m');
                $remainingStock['12m'] = $selectedContract->stock_12m - $totalRequested12m;
            }
        }

        $recaps = $recapsQuery->paginate(20)->appends($request->query());
        return view('pages.recap-list', compact('recaps', 'contracts', 'selectedContract', 'remainingStock'));
    }

    public function create()
    {
        $contracts = Contract::orderBy('name')->get();
        return view('pages.recap-create', compact('contracts'));
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'contract_id' => 'nullable|exists:contracts,id',
            'job' => 'required|string',
            'address' => 'required|string|max:255',
            'request_9m' => 'required|integer|min:0',
            'request_12m' => 'required|integer|min:0',
            'planted_9m' => 'required|integer|min:0',
            'planted_12m' => 'required|integer|min:0',
            'x_cord' => 'required|numeric',
            'y_cord' => 'required|numeric',
            'contract' => 'nullable|string|max:255',
            'executor' => 'required|string|max:255',
        ]);

        $validatedData['created_by'] = Auth::id();

        $recap = Recap::create($validatedData);

        Log::create([
            'user_id' => Auth::id(),
            'action_type' => 'create',
            'action_detail' => "Created recap: {$recap->job} (ID: {$recap->id})"
        ]);

        return redirect()->route('recap.index')->with('success', 'Recap created successfully!');
    }

    public function edit(Recap $recap)
    {
        $contracts = Contract::orderBy('name')->get();
        return view('pages.recap-edit', compact('recap', 'contracts'));
    }

    public function update(Request $request, Recap $recap)
    {
        $validatedData = $request->validate([
            'contract_id' => 'nullable|exists:contracts,id',
            'job' => 'required|string',
            'address' => 'required|string|max:255',
            'request_9m' => 'required|integer|min:0',
            'request_12m' => 'required|integer|min:0',
            'planted_9m' => 'required|integer|min:0',
            'planted_12m' => 'required|integer|min:0',
            'x_cord' => 'required|numeric',
            'y_cord' => 'required|numeric',
            'contract' => 'nullable|string|max:255',
            'executor' => 'required|string|max:255',
        ]);

        $recap->update($validatedData);

        Log::create([
            'user_id' => Auth::id(),
            'action_type' => 'update',
            'action_detail' => "Updated recap: {$recap->job} (ID: {$recap->id})"
        ]);

        return redirect()->route('recap.index')->with('success', 'Recap updated successfully!');
    }

    public function destroy(Recap $recap)
    {
        $recapJob = $recap->job;
        $recapId = $recap->id;
        $recap->delete();

        Log::create([
            'user_id' => Auth::id(),
            'action_type' => 'delete',
            'action_detail' => "Deleted recap: {$recapJob} (ID: {$recapId})"
        ]);

        return redirect()->route('recap.index')->with('success', 'Recap deleted successfully!');
    }

    // --- FUNGSI MAP YANG SUDAH DIPERBAIKI ---
    public function map(Request $request)
    {
        // 1. Query Dasar: Ambil recap yang punya koordinat
        $query = Recap::with(['contract.company'])
            ->whereNotNull('x_cord')
            ->whereNotNull('y_cord');

        // 2. Filter: Company (Perusahaan)
        if ($request->filled('company_id')) {
            $query->whereHas('contract', function ($q) use ($request) {
                $q->where('company_id', $request->company_id);
            });
        }

        // 3. Filter: Tahun
        if ($request->filled('year')) {
            $query->whereYear('created_at', $request->year);
        }

        // 4. Filter: Ukuran Tiang (Logic baru berdasarkan kolom planted)
        if ($request->filled('pole_size')) {
            if ($request->pole_size == '9 meter') {
                $query->where('planted_9m', '>', 0);
            } elseif ($request->pole_size == '12 meter') {
                $query->where('planted_12m', '>', 0);
            }
        }
        if ($request->filled('contract_id')) {
            $query->where('contract_id', $request->contract_id);
        }
        

        // Eksekusi Query
        $recaps = $query->get();

        // 5. Data Pendukung untuk Dropdown Filter
        $companies = Company::orderBy('name')->get();

        $contracts = Contract::orderBy('name')->get();
        
        // Ambil list tahun unik dari data Recap yang ada
        $years = Recap::selectRaw('EXTRACT(YEAR FROM created_at) as year')
            ->distinct()
            ->orderBy('year', 'desc')
            ->pluck('year');

        // Kirim semua variabel ke View (INI YANG SEBELUMNYA KURANG)
        return view('pages.map', compact('recaps', 'companies', 'years', 'contracts'));
    }
    public function export(Request $request)
    {
        $filename = 'recap-data-' . date('Y-m-d-His') . '.csv';

        $response = new StreamedResponse(function () use ($request) {
            $handle = fopen('php://output', 'w');

            // 1. Header CSV
            fputcsv($handle, [
                'No', 'Kontrak', 'Pelaksana', 'Pekerjaan', 'Alamat',
                'Req 9m', 'Tanam 9m', 'Req 12m', 'Tanam 12m', 
                'Koordinat X', 'Koordinat Y', 'Tanggal Input'
            ]);

            // 2. Query Data (Copy logic filter dari index)
            $query = Recap::with('contract');

            if ($request->filled('contract_id')) {
                $query->where('contract_id', $request->contract_id);
            }

            // Gunakan chunk untuk hemat memori jika data ribuan
            $query->latest()->chunk(500, function ($recaps) use ($handle) {
                foreach ($recaps as $index => $recap) {
                    fputcsv($handle, [
                        $recap->id, // Atau nomor urut jika mau hitung manual
                        $recap->contract->name ?? $recap->contract, // Nama kontrak relasi atau text manual
                        $recap->executor,
                        $recap->job,
                        $recap->address,
                        $recap->request_9m,
                        $recap->planted_9m,
                        $recap->request_12m,
                        $recap->planted_12m,
                        $recap->x_cord,
                        $recap->y_cord,
                        $recap->created_at->format('Y-m-d H:i')
                    ]);
                }
            });

            fclose($handle);
        });

        $response->headers->set('Content-Type', 'text/csv');
        $response->headers->set('Content-Disposition', 'attachment; filename="' . $filename . '"');

        return $response;
    }
}