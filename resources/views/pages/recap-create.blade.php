@extends('components.appbar')

@section('title', 'Add Recap')

@section('content')
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Recap - PLN Monitoring</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* CSS agar input readonly terlihat seperti disabled */
        .input-locked {
            background-color: #e9ecef !important;
            pointer-events: none;
            color: #6c757d;
            border-color: #ced4da;
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1>Add New Recap</h1>
            <a href="{{ route('recap.index') }}" class="btn btn-secondary">Back to Recap List</a>
        </div>

        <div class="card">
            <div class="card-body">
                <form action="{{ route('recap.store') }}" method="POST">
                    @csrf
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="contract_id" class="form-label">Contract</label>
                            {{-- Event onchange untuk update status input --}}
                            <select class="form-select @error('contract_id') is-invalid @enderror" id="contract_id" name="contract_id" onchange="updateFormInputs()">
                                <option value="" selected>Select a contract (optional)</option>
                                @foreach ($contracts as $contract)
                                    <option value="{{ $contract->id }}" 
                                            {{-- Cast ke int agar aman --}}
                                            data-has-9m="{{ (int) $contract->has_9m }}" 
                                            data-has-12m="{{ (int) $contract->has_12m }}"
                                            {{ old('contract_id') == $contract->id ? 'selected' : '' }}>
                                        {{ $contract->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('contract_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="executor" class="form-label">Pelaksana</label>
                            <input type="text" class="form-control @error('executor') is-invalid @enderror" id="executor" name="executor" value="{{ old('executor') }}" required>
                            @error('executor')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="contract" class="form-label">Contract (Text)</label>
                        <input type="text" class="form-control @error('contract') is-invalid @enderror" id="contract" name="contract" value="{{ old('contract') }}" placeholder="Enter contract details if not in the list">
                        @error('contract')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-3">
                        <label for="job" class="form-label">Pekerjaan</label>
                        <textarea class="form-control @error('job') is-invalid @enderror" id="job" name="job" rows="3" required>{{ old('job') }}</textarea>
                        @error('job')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-3">
                        <label for="address" class="form-label">Alamat</label>
                        <input type="text" class="form-control @error('address') is-invalid @enderror" id="address" name="address" value="{{ old('address') }}" required>
                        @error('address')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    {{-- Request Inputs --}}
                    <h5 class="mt-3">Permintaan (Request)</h5>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="request_9m" class="form-label">Request 9 Meter</label>
                            <input type="number" class="form-control @error('request_9m') is-invalid @enderror" id="request_9m" name="request_9m" value="{{ old('request_9m', 0) }}" required min="0">
                             @error('request_9m')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="request_12m" class="form-label">Request 12 Meter</label>
                            <input type="number" class="form-control @error('request_12m') is-invalid @enderror" id="request_12m" name="request_12m" value="{{ old('request_12m', 0) }}" required min="0">
                             @error('request_12m')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    {{-- Planted Inputs --}}
                    <h5 class="mt-2">Tertanam (Planted)</h5>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="planted_9m" class="form-label">Tertanam 9 Meter</label>
                            <input type="number" class="form-control @error('planted_9m') is-invalid @enderror" id="planted_9m" name="planted_9m" value="{{ old('planted_9m', 0) }}" required min="0">
                             @error('planted_9m')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="planted_12m" class="form-label">Tertanam 12 Meter</label>
                            <input type="number" class="form-control @error('planted_12m') is-invalid @enderror" id="planted_12m" name="planted_12m" value="{{ old('planted_12m', 0) }}" required min="0">
                             @error('planted_12m')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="col-md-6 mb-3">
                            <label for="x_cord" class="form-label">X Coordinate</label>
                            <input type="text" class="form-control @error('x_cord') is-invalid @enderror" id="x_cord" name="x_cord" value="{{ old('x_cord') }}" required>
                            @error('x_cord')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="y_cord" class="form-label">Y Coordinate</label>
                            <input type="text" class="form-control @error('y_cord') is-invalid @enderror" id="y_cord" name="y_cord" value="{{ old('y_cord') }}" required>
                            @error('y_cord')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary mt-3">Save Recap</button>
                </form>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        function updateFormInputs() {
            const select = document.getElementById('contract_id');
            // Jika tidak ada kontrak dipilih (pilihan Select a contract), reset ke terbuka semua
            if (!select || select.selectedIndex <= 0) {
                toggleInput('request_9m', true);
                toggleInput('planted_9m', true);
                toggleInput('request_12m', true);
                toggleInput('planted_12m', true);
                return;
            }

            const selectedOption = select.options[select.selectedIndex];

            // Ambil data attribut
            let has9m = selectedOption.getAttribute('data-has-9m') === '1';
            let has12m = selectedOption.getAttribute('data-has-12m') === '1';

            // Fallback untuk kontrak lama (jika keduanya 0/false, anggap true semua agar aman)
            if (!has9m && !has12m) {
                has9m = true;
                has12m = true;
            }

            // Atur Input 9m
            toggleInput('request_9m', has9m);
            toggleInput('planted_9m', has9m);

            // Atur Input 12m
            toggleInput('request_12m', has12m);
            toggleInput('planted_12m', has12m);
        }

        function toggleInput(id, isEnabled) {
            const input = document.getElementById(id);
            if (!input) return;

            if (isEnabled) {
                // Hapus readonly agar bisa diisi
                input.removeAttribute('readonly');
                // Hapus styling terkunci
                input.classList.remove('input-locked');
                // Pastikan disabled juga hilang (jika ada sisa sebelumnya)
                input.removeAttribute('disabled');
            } else {
                // KUNCI FIELD: Gunakan readonly, BUKAN disabled
                input.setAttribute('readonly', true);
                // Set nilai 0 agar data terkirim
                input.value = 0; 
                // Tambahkan styling agar terlihat mati
                input.classList.add('input-locked');
            }
        }
        
        // Jalankan saat halaman load
        document.addEventListener("DOMContentLoaded", function() {
            updateFormInputs();
        });
    </script>
</body>
</html>
@endsection