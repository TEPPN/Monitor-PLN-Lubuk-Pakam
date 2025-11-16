@extends('components.appbar')

@section('title', 'Contracts')

@section('content')
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contract List - PLN Monitoring</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1>Contract List</h1>
            <a href="{{ route('contract.create') }}" class="btn btn-primary">Add Contract</a>
        </div>

        @if (session('success'))
            <div class="alert alert-success mb-3">
                {{ session('success') }}
            </div>
        @endif

        <div class="card">
            <div class="card-body">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th scope="col">Contract Name</th>
                            <th scope="col">Company</th>
                            <th scope="col">Year</th>
                            <th scope="col">Pole Size</th>
                            <th scope="col">Stock</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($contracts as $contract)
                            <tr>
                                <td>{{ $contract->name }}</td>
                                <td>{{ $contract->company->name ?? 'N/A' }}</td>
                                <td>{{ $contract->contract_date->format('F Y') }}</td>
                                <td>{{ $contract->pole_size }}</td>
                                <td>{{ $contract->stock }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center">No contracts found.</td>
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
