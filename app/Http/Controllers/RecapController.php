<?php

namespace App\Http\Controllers;

use App\Models\Recap;
use App\Models\Log;
use App\Models\Contract;
use App\Models\Company; 
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class RecapController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $contracts = Contract::orderBy('name')->get();
        
        $recapsQuery = Recap::with(['contract.company', 'createdBy'])->latest();

        $selectedContract = null;
        $remainingStock = null;

        if ($request->filled('contract_id')) {
            $contractId = $request->contract_id;
            $recapsQuery->where('contract_id', $contractId);

            // Fetch the selected contract to get its initial stock
            $selectedContract = Contract::find($contractId);

            if ($selectedContract) {
                // Sum all 'request' values for this contract from the recaps table
                $totalRequested = Recap::where('contract_id', $contractId)->sum('request');
                $remainingStock = $selectedContract->stock - $totalRequested;
            }
        }

        $recaps = $recapsQuery->paginate(20)->appends($request->query());
        return view('pages.recap-list', compact('recaps', 'contracts', 'selectedContract', 'remainingStock'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $contracts = Contract::orderBy('name')->get();
        return view('pages.recap-create', compact('contracts'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'contract_id' => 'nullable|exists:contracts,id',
            'job' => 'required|string',
            'address' => 'required|string|max:255',
            'request' => 'required|integer|min:0',
            'planted' => 'required|integer|min:0',
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

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Recap  $recap
     * @return \Illuminate\Http\Response
     */
    public function show(Recap $recap)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Recap  $recap
     * @return \Illuminate\Http\Response
     */
    public function edit(Recap $recap)
    {
        $contracts = Contract::orderBy('name')->get();
        // The $recap is automatically fetched by Laravel's route model binding
        return view('pages.recap-edit', compact('recap', 'contracts'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Recap  $recap
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Recap $recap)
    {
        $validatedData = $request->validate([
            'contract_id' => 'nullable|exists:contracts,id',
            'job' => 'required|string',
            'address' => 'required|string|max:255',
            'request' => 'required|integer|min:0',
            'planted' => 'required|integer|min:0',
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

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Recap  $recap
     * @return \Illuminate\Http\Response
     */
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

    public function map(Request $request)
    {
        // 1. Base Query
        $query = Recap::with(['contract.company'])
            ->whereNotNull('x_cord')
            ->whereNotNull('y_cord');

        // 2. Apply Filters
        
        // Filter by Company
        if ($request->filled('company_id')) {
            $query->whereHas('contract', function ($q) use ($request) {
                $q->where('company_id', $request->company_id);
            });
        }

        // Filter by Pole Size
        if ($request->filled('pole_size')) {
            $query->whereHas('contract', function ($q) use ($request) {
                $q->where('pole_size', $request->pole_size);
            });
        }

        // Filter by Year
        if ($request->filled('year')) {
            $query->whereHas('contract', function ($q) use ($request) {
                $q->whereYear('contract_date', $request->year);
            });
        }

        $recaps = $query->get();

        // 3. Get Data for Filter Dropdowns
        $companies = Company::orderBy('name')->get();
        
        // FIXED: Use EXTRACT(YEAR FROM ...) for PostgreSQL compatibility
        $years = Contract::selectRaw('EXTRACT(YEAR FROM contract_date) as year')
            ->distinct()
            ->orderBy('year', 'desc')
            ->pluck('year');

        return view('pages.map', compact('recaps', 'companies', 'years'));
    }
}
