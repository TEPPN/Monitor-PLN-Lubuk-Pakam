@extends('components.appbar')

@section('content')
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Contract - PLN Monitoring</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1>Edit Contract</h1>
            <a href="{{ route('contract.index') }}" class="btn btn-secondary">Back to List</a>
        </div>

        <div class="card">
            <div class="card-body">
                <form action="{{ route('contract.update', $contract->id) }}" method="POST">
                    @csrf
                    @method('PUT') {{-- PENTING UNTUK UPDATE --}}

                    <div class="mb-3">
                        <label for="name" class="form-label">Contract Name</label>
                        <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $contract->name) }}" required>
                    </div>

                    <div class="mb-3">
                        <label for="company_id" class="form-label">Company</label>
                        <select class="form-select" id="company_id" name="company_id" required>
                            <option value="" disabled>Select a company</option>
                            @foreach ($companies as $company)
                                <option value="{{ $company->id }}" {{ old('company_id', $contract->company_id) == $company->id ? 'selected' : '' }}>
                                    {{ $company->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="contract_date" class="form-label">Tanggal Mulai</label>
                            <input type="date" class="form-control" id="contract_date" name="contract_date" 
                                value="{{ old('contract_date', $contract->contract_date ? \Carbon\Carbon::parse($contract->contract_date)->format('Y-m-d') : '') }}" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="end_date" class="form-label">Tanggal Berakhir</label>
                            <input type="date" class="form-control" id="end_date" name="end_date" 
                                value="{{ old('end_date', $contract->end_date ? \Carbon\Carbon::parse($contract->end_date)->format('Y-m-d') : '') }}" required>
                        </div>
                    </div>

                    {{-- Checklist Tipe Tiang --}}
                    <label class="form-label fw-bold">Pilih Tipe Tiang</label>
                    <div class="mb-3">
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="checkbox" id="check_9m" name="has_9m" value="1" 
                                {{ old('has_9m', $contract->has_9m) ? 'checked' : '' }} onchange="toggleStock('9m')">
                            <label class="form-check-label" for="check_9m">Tiang 9 Meter</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="checkbox" id="check_12m" name="has_12m" value="1" 
                                {{ old('has_12m', $contract->has_12m) ? 'checked' : '' }} onchange="toggleStock('12m')">
                            <label class="form-check-label" for="check_12m">Tiang 12 Meter</label>
                        </div>
                    </div>

                    {{-- Input Stock --}}
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="stock_9m" class="form-label">Stock Tiang 9 Meter</label>
                            <input type="number" class="form-control" id="stock_9m" name="stock_9m" value="{{ old('stock_9m', $contract->stock_9m) }}" min="0">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="stock_12m" class="form-label">Stock Tiang 12 Meter</label>
                            <input type="number" class="form-control" id="stock_12m" name="stock_12m" value="{{ old('stock_12m', $contract->stock_12m) }}" min="0">
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary">Update Contract</button>
                </form>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function toggleStock(type) {
            const checkbox = document.getElementById('check_' + type);
            const input = document.getElementById('stock_' + type);

            if (checkbox.checked) {
                input.disabled = false;
            } else {
                input.disabled = true;
                // Jangan reset ke 0 visualnya agar user tidak kaget, 
                // tapi controller akan memaksa 0 jika unchecked.
            }
        }

        // Jalankan saat load halaman untuk menyesuaikan status awal (disabled/enabled)
        // berdasarkan data dari database
        document.addEventListener("DOMContentLoaded", function() {
            toggleStock('9m');
            toggleStock('12m');
        });
    </script>
</body>
</html>
@endsection