@extends('components.appbar')
{{-- Pastikan layout yang digunakan sesuai, di file asli Anda tidak ada extends, tapi sebaiknya ada --}}

@section('content')
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Contract - PLN Monitoring</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1>Add New Contract</h1>
            <a href="{{ route('contract.index') }}" class="btn btn-secondary">Back to Contract List</a>
        </div>

        <div class="card">
            <div class="card-body">
                <form action="{{ route('contract.store') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="name" class="form-label">Contract Name</label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" required>
                        @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-3">
                        <label for="company_id" class="form-label">Company</label>
                        <select class="form-select @error('company_id') is-invalid @enderror" id="company_id" name="company_id" required>
                            <option value="" disabled selected>Select a company</option>
                            @foreach ($companies as $company)
                                <option value="{{ $company->id }}" {{ old('company_id') == $company->id ? 'selected' : '' }}>
                                    {{ $company->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('company_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-3">
                        <label for="contract_date" class="form-label">Contract Date</label>
                        <input type="date" class="form-control @error('contract_date') is-invalid @enderror" id="contract_date" name="contract_date" value="{{ old('contract_date') }}" required>
                        @error('contract_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="end_date" class="form-label">Tanggal Berakhir (Masa Berlaku)</label>
                        <input type="date" class="form-control" id="end_date" name="end_date" required>
                    </div>

                    {{-- PERUBAHAN: Input Stock dipisah menjadi 9m dan 12m --}}
                    {{-- Input Stock (Awalnya disabled) --}}
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="stock_9m" class="form-label">Stock Tiang 9 Meter</label>
                            {{-- Tambahkan attribut disabled secara default --}}
                            <input type="number" class="form-control" id="stock_9m" name="stock_9m" value="0" min="0" disabled>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="stock_12m" class="form-label">Stock Tiang 12 Meter</label>
                            {{-- Tambahkan attribut disabled secara default --}}
                            <input type="number" class="form-control" id="stock_12m" name="stock_12m" value="0" min="0" disabled>
                        </div>
                    </div>
                    <label class="form-label fw-bold">Pilih Tipe Tiang</label>
                    <div class="mb-3">
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="checkbox" id="check_9m" name="has_9m" value="1" onchange="toggleStock('9m')">
                            <label class="form-check-label" for="check_9m">Tiang 9 Meter</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="checkbox" id="check_12m" name="has_12m" value="1" onchange="toggleStock('12m')">
                            <label class="form-check-label" for="check_12m">Tiang 12 Meter</label>
                        </div>
                    </div>

                    
                    <button type="submit" class="btn btn-primary">Save Contract</button>
                </form>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function toggleStock(type) {
            // Ambil elemen checkbox dan input berdasarkan tipe (9m/12m)
            const checkbox = document.getElementById('check_' + type);
            const input = document.getElementById('stock_' + type);

            if (checkbox.checked) {
                input.disabled = false; // Aktifkan input
                input.focus();
            } else {
                input.disabled = true;  // Matikan input
                input.value = 0;        // Reset nilai ke 0
            }
        }
    </script>
</body>
</html>
@endsection