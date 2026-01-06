<?php

namespace App\Http\Controllers;

use App\Models\Log;
use App\Models\Recap;
use App\Models\Company;
use App\Models\Contract;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ContractController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $contracts = Contract::with('company')->latest()->paginate(20);
        return view('pages.contract', compact('contracts'));
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $companies = Company::orderBy('name')->get();
        return view('pages.contract-create', compact('companies'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Cek apakah user mencentang checkbox (karena checkbox html tidak kirim value jika unchecked)
        $has9m = $request->has('has_9m');
        $has12m = $request->has('has_12m');

        // Validasi dasar
        $request->validate([
            'name' => 'required|string|max:255|unique:contracts',
            'company_id' => 'required|exists:companies,id',
            'contract_date' => 'required|date',
            // Jika 9m dicentang, stok 9m wajib diisi angka (min 0)
            'stock_9m' => $has9m ? 'required|integer|min:0' : 'nullable',
            // Jika 12m dicentang, stok 12m wajib diisi angka (min 0)
            'stock_12m' => $has12m ? 'required|integer|min:0' : 'nullable',
        ]);

        // Simpan data
        Contract::create([
            'name' => $request->name,
            'company_id' => $request->company_id,
            'contract_date' => $request->contract_date,
            'has_9m' => $has9m,
            'has_12m' => $has12m,
            // Jika tidak dicentang, paksa stok jadi 0
            'stock_9m' => $has9m ? $request->stock_9m : 0,
            'stock_12m' => $has12m ? $request->stock_12m : 0,
        ]);

        return redirect()->route('contract.index')->with('success', 'Contract created successfully!');
    }
    public function edit(Contract $contract)
    {
        $companies = Company::orderBy('name')->get();
        return view('pages.contract-edit', compact('contract', 'companies'));
    }

    // 2. Method UPDATE (Menyimpan Perubahan)
    public function update(Request $request, Contract $contract)
    {
        // Cek checkbox
        $has9m = $request->has('has_9m');
        $has12m = $request->has('has_12m');

        // Validasi
        $validatedData = $request->validate([
            // Unique ignorable: nama boleh sama dengan dirinya sendiri saat update
            'name' => 'required|string|max:255|unique:contracts,name,' . $contract->id,
            'company_id' => 'required|exists:companies,id',
            'contract_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:contract_date',
            // Validasi stok bergantung checkbox
            'stock_9m' => $has9m ? 'required|integer|min:0' : 'nullable',
            'stock_12m' => $has12m ? 'required|integer|min:0' : 'nullable',
        ]);

        // Update Data
        $contract->update([
            'name' => $request->name,
            'company_id' => $request->company_id,
            'contract_date' => $request->contract_date,
            'end_date' => $request->end_date,
            'has_9m' => $has9m,
            'has_12m' => $has12m,
            // Jika tidak dicentang, paksa stok jadi 0
            'stock_9m' => $has9m ? $request->stock_9m : 0,
            'stock_12m' => $has12m ? $request->stock_12m : 0,
        ]);

        // Catat Log
        Log::create([
            'user_id' => Auth::id(),
            'action_type' => 'update',
            'action_detail' => "Updated contract: {$contract->name} (ID: {$contract->id})"
        ]);

        return redirect()->route('contract.index')->with('success', 'Contract updated successfully!');
    }
    
    public function destroy(Contract $contract)
    {
        $contractName = $contract->name;
        $contractId = $contract->id;

        // 2. SOLUSI ERROR: Hapus semua Rekap yang terhubung dengan kontrak ini terlebih dahulu
        Recap::where('contract_id', $contract->id)->delete();

        // 3. Setelah bersih, baru hapus Kontraknya
        $contract->delete();

        // 4. Catat Log
        Log::create([
            'user_id' => Auth::id(),
            'action_type' => 'delete',
            'action_detail' => "Deleted contract: {$contractName} (ID: {$contractId}) and its related recaps."
        ]);

        return redirect()->route('contract.index')->with('success', 'Contract and related recaps deleted successfully!');
    }
}
