@extends('components.appbar')

@section('content')
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Kontrak</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1>Daftar Kontrak</h1>
            <a href="{{ route('contract.create') }}" class="btn btn-primary">Add Contract</a>
        </div>

        <div class="card">
            <div class="card-body">
                <table class="table table-striped table-bordered">
                    <thead class="text-center align-middle">
                        <tr>
                            <th rowspan="2">#</th>
                            <th rowspan="2">Contract Name</th>
                            <th rowspan="2">Company</th>
                            <th rowspan="2">Date</th>
                            <th colspan="2">Stock</th>
                            <th rowspan="2">Created At</th>
                            <th rowspan="2">Action</th>
                        </tr>
                        <tr>
                            <th>9m</th>
                            <th>12m</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($contracts as $contract)
                            <tr>
                                <th scope="row" class="text-center">{{ $contracts->firstItem() + $loop->index }}</th>
                                <td>{{ $contract->name }}</td>
                                <td>{{ $contract->company->name ?? 'N/A' }}</td>
                                <td class="text-center">{{ $contract->contract_date->format('d M Y') }}</td>
                                {{-- Menampilkan data stok baru --}}
                                <td class="text-center">{{ number_format($contract->stock_9m) }}</td>
                                <td class="text-center">{{ number_format($contract->stock_12m) }}</td>
                                <td class="text-center">{{ $contract->created_at->format('d M Y') }}</td>
                                <td class="text-center">
                                    <a href="{{ route('contract.edit', $contract->id) }}" class="btn btn-sm btn-warning">Edit</a>
                                    <form action="{{ route('contract.destroy', $contract->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this contract?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center">No contracts found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="card-footer">
                {{ $contracts->links() }}
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
@endsection