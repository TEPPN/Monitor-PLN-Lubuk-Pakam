<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\Contract;
use Illuminate\Http\Request;

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
        $validatedData = $request->validate([
            'name' => 'required|string|max:255|unique:contracts',
            'company_id' => 'required|exists:companies,id',
            'contract_date' => 'required|date',
            'pole_size' => 'required|in:9 meter,12 meter',
            'stock' => 'required|integer|min:0',
        ]);

        Contract::create($validatedData);

        // You should create a contract index page to redirect to.
        return redirect()->route('contract.index')->with('success', 'Contract created successfully!');
    }
}
