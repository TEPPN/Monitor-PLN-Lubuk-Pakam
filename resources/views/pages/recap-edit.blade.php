@extends('components.appbar')

@section('title', 'Edit Recap')

@section('content')
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Recap - PLN Monitoring</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* Style untuk membuat input readonly terlihat seperti disabled */
        .input-locked {
            background-color: #e9ecef !important; /* Warna abu-abu */
            pointer-events: none; /* Mencegah klik */
            color: #6c757d;
            border-color: #ced4da;
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1>Edit Recap</h1>
            <a href="{{ route('recap.index') }}" class="btn btn-secondary">Back to Recap List</a>
        </div>

        <div class="card">
            <div class="card-body">
                <form action="{{ route('recap.update', $recap->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    {{-- Bagian Atas: Kontrak & Pelaksana --}}
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="contract_id" class="form-label">Contract</label>
                            {{-- Event onchange untuk cek tipe tiang saat kontrak diganti --}}
                            <select class="form-select" id="contract_id" name="contract_id" onchange="updateFormInputs()">
                                <option value="">Select a contract (optional)</option>
                                @foreach ($contracts as $contract)
                                    <option value="{{ $contract->id }}" 
                                            data-has-9m="{{ $contract->has_9m }}" 
                                            data-has-12m="{{ $contract->has_12m }}"
                                            {{ old('contract_id', $recap->contract_id) == $contract->id ? 'selected' : '' }}>
                                        {{ $contract->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="executor" class="form-label">Pelaksana</label>
                            <input type="text" class="form-control" id="executor" name="executor" value="{{ old('executor', $recap->executor) }}" required>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="contract" class="form-label">Contract (Text)</label>
                        <input type="text" class="form-control" id="contract" name="contract" value="{{ old('contract', $recap->contract) }}">
                    </div>

                    <div class="mb-3">
                        <label for="job" class="form-label">Pekerjaan</label>
                        <textarea class="form-control" id="job" name="job" rows="3" required>{{ old('job', $recap->job) }}</textarea>
                    </div>

                    <div class="mb-3">
                        <label for="address" class="form-label">Alamat</label>
                        <input type="text" class="form-control" id="address" name="address" value="{{ old('address', $recap->address) }}" required>
                    </div>

                    {{-- Bagian Edit Request (Permintaan) --}}
                    <h5 class="mt-3">Permintaan (Request)</h5>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="request_9m" class="form-label">Request 9 Meter</label>
                            <input type="number" class="form-control" id="request_9m" name="request_9m" value="{{ old('request_9m', $recap->request_9m) }}" required min="0">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="request_12m" class="form-label">Request 12 Meter</label>
                            <input type="number" class="form-control" id="request_12m" name="request_12m" value="{{ old('request_12m', $recap->request_12m) }}" required min="0">
                        </div>
                    </div>

                    {{-- Bagian Edit Planted (Tertanam) --}}
                    <h5 class="mt-2">Tertanam (Planted)</h5>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="planted_9m" class="form-label">Tertanam 9 Meter</label>
                            <input type="number" class="form-control" id="planted_9m" name="planted_9m" value="{{ old('planted_9m', $recap->planted_9m) }}" required min="0">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="planted_12m" class="form-label">Tertanam 12 Meter</label>
                            <input type="number" class="form-control" id="planted_12m" name="planted_12m" value="{{ old('planted_12m', $recap->planted_12m) }}" required min="0">
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="col-md-6 mb-3">
                            <label for="x_cord" class="form-label">X Coordinate</label>
                            <input type="text" class="form-control" id="x_cord" name="x_cord" value="{{ old('x_cord', $recap->x_cord) }}" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="y_cord" class="form-label">Y Coordinate</label>
                            <input type="text" class="form-control" id="y_cord" name="y_cord" value="{{ old('y_cord', $recap->y_cord) }}" required>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary mt-3">Update Recap</button>
                </form>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    {{-- Javascript Logic --}}
    <script>
        function updateFormInputs() {
            const select = document.getElementById('contract_id');
            // Jika tidak ada kontrak dipilih, biarkan terbuka (atau bisa di-lock semua, tergantung kebutuhan)
            if (!select || select.selectedIndex === -1) return;

            const selectedOption = select.options[select.selectedIndex];
            
            // Cek apakah option memiliki value (bukan placeholder)
            const hasValue = selectedOption.value !== "";

            // Ambil data kemampuan kontrak (default true jika kontrak belum dipilih agar bisa manual)
            // data-has-9m="1" berarti true
            const has9m = hasValue ? (selectedOption.getAttribute('data-has-9m') == '1') : true; 
            const has12m = hasValue ? (selectedOption.getAttribute('data-has-12m') == '1') : true;

            // Terapkan logika ke input fields
            toggleInput('request_9m', has9m);
            toggleInput('planted_9m', has9m);
            
            toggleInput('request_12m', has12m);
            toggleInput('planted_12m', has12m);
        }

        function toggleInput(id, isEnabled) {
            const input = document.getElementById(id);
            if (!input) return;

            if (isEnabled) {
                // KONDISI: Kontrak mendukung tipe tiang ini
                // Hapus readonly agar bisa diedit
                input.removeAttribute('readonly');
                // Hapus styling "terkunci"
                input.classList.remove('input-locked');
            } else {
                // KONDISI: Kontrak TIDAK mendukung tipe tiang ini
                // Tambahkan readonly (JANGAN disabled, agar nilai 0 tetap terkirim ke server)
                input.setAttribute('readonly', true);
                // Set nilai ke 0 karena kontrak tidak punya stok ini
                input.value = 0; 
                // Tambahkan styling visual agar terlihat mati
                input.classList.add('input-locked');
            }
        }
        
        // Jalankan fungsi saat halaman selesai dimuat
        // Ini akan otomatis mengunci field yang tidak sesuai dengan kontrak saat ini
        document.addEventListener("DOMContentLoaded", function() {
            updateFormInputs();
        });
    </script>
</body>
</html>
@endsection