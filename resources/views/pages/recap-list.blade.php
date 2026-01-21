@extends('components.appbar')

@section('content')
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Rekap</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container-fluid mt-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h1>Daftar Rekap Pekerjaan</h1>

            <a href="{{ route('recap.export', request()->query()) }}" class="btn btn-success me-2">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-download" viewBox="0 0 16 16">
                <path d="M.5 9.9a.5.5 0 0 1 .5.5v2.5a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1v-2.5a.5.5 0 0 1 1 0v2.5a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2v-2.5a.5.5 0 0 1 .5-.5z"/>
                <path d="M7.646 11.854a.5.5 0 0 0 .708 0l3-3a.5.5 0 0 0-.708-.708L8.5 10.293V1.5a.5.5 0 0 0-1 0v8.793L5.354 8.146a.5.5 0 1 0-.708.708l3 3z"/>
            </svg>
            Export CSV
        </a>
            <a href="{{ route('recap.create') }}" class="btn btn-primary">Add New Recap</a>
        </div>

        {{-- Filter Section --}}
        <div class="card mb-4">
            <div class="card-body">
                <form action="{{ route('recap.index') }}" method="GET" class="row g-3 align-items-end">
                    <div class="col-md-4">
                        <label for="contract_id" class="form-label">Filter by Contract</label>
                        <select class="form-select" name="contract_id" onchange="this.form.submit()">
                            <option value="">-- All Contracts --</option>
                            @foreach ($contracts as $contract)
                                <option value="{{ $contract->id }}" {{ request('contract_id') == $contract->id ? 'selected' : '' }}>
                                    {{ $contract->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    
                    @if($selectedContract)
                        <div class="col-md-8">
                            <div class="alert alert-info mb-0">
                                <strong>Contract Info:</strong> {{ $selectedContract->name }} <br>
                                {{-- Menampilkan Sisa Stok untuk kedua tipe --}}
                                Sisa Stok 9m: <strong>{{ $remainingStock['9m'] }}</strong> / {{ $selectedContract->stock_9m }} | 
                                Sisa Stok 12m: <strong>{{ $remainingStock['12m'] }}</strong> / {{ $selectedContract->stock_12m }}
                            </div>
                        </div>
                    @endif
                </form>
            </div>
        </div>

        {{-- Table Section --}}
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead class="text-center align-middle">
                            <tr>
                                <th rowspan="2">#</th>
                                <th rowspan="2">Pekerjaan</th>
                                <th rowspan="2">Alamat</th>
                                <th colspan="2">Request</th>
                                <th colspan="2">Planted</th>
                                <th rowspan="2">Executor</th>
                                <th rowspan="2">Action</th>
                            </tr>
                            <tr>
                                <th>9m</th>
                                <th>12m</th>
                                <th>9m</th>
                                <th>12m</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($recaps as $recap)
                                <tr>
                                    <td class="text-center">{{ $recaps->firstItem() + $loop->index }}</td>
                                    <td>{{ $recap->job }}</td>
                                    <td>{{ $recap->address }}</td>
                                    
                                    {{-- Kolom Request --}}
                                    <td class="text-center">{{ $recap->request_9m > 0 ? $recap->request_9m : '-' }}</td>
                                    <td class="text-center">{{ $recap->request_12m > 0 ? $recap->request_12m : '-' }}</td>
                                    
                                    {{-- Kolom Planted --}}
                                    <td class="text-center">{{ $recap->planted_9m > 0 ? $recap->planted_9m : '-' }}</td>
                                    <td class="text-center">{{ $recap->planted_12m > 0 ? $recap->planted_12m : '-' }}</td>

                                    <td>{{ $recap->executor }}</td>
                                    <td class="text-center">
                                        <a href="{{ route('recap.edit', $recap->id) }}" class="btn btn-sm btn-warning">Edit</a>
                                        <form action="{{ route('recap.destroy', $recap->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger">Del</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" class="text-center">No recap data found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card-footer">
                {{ $recaps->links() }}
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
@endsection