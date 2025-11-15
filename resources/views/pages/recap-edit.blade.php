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
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="contract_id" class="form-label">Contract</label>
                            <select class="form-select @error('contract_id') is-invalid @enderror" id="contract_id" name="contract_id">
                                <option value="">Select a contract (optional)</option>
                                @foreach ($contracts as $contract)
                                    <option value="{{ $contract->id }}" {{ old('contract_id', $recap->contract_id) == $contract->id ? 'selected' : '' }}>
                                        {{ $contract->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('contract_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="executor" class="form-label">Pelaksana</label>
                            <input type="text" class="form-control @error('executor') is-invalid @enderror" id="executor" name="executor" value="{{ old('executor', $recap->executor) }}" required>
                            @error('executor')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="contract" class="form-label">Contract (Text)</label>
                        <input type="text" class="form-control @error('contract') is-invalid @enderror" id="contract" name="contract" value="{{ old('contract', $recap->contract) }}" placeholder="Enter contract details if not in the list">
                        @error('contract')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-3">
                        <label for="job" class="form-label">Pekerjaan</label>
                        <textarea class="form-control @error('job') is-invalid @enderror" id="job" name="job" rows="3" required>{{ old('job', $recap->job) }}</textarea>
                        @error('job')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-3">
                        <label for="address" class="form-label">Alamat</label>
                        <input type="text" class="form-control @error('address') is-invalid @enderror" id="address" name="address" value="{{ old('address', $recap->address) }}" required>
                        @error('address')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="request" class="form-label">Request</label>
                            <input type="number" class="form-control @error('request') is-invalid @enderror" id="request" name="request" value="{{ old('request', $recap->request) }}" required>
                            @error('request')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="planted" class="form-label">Tertanam</label>
                            <input type="number" class="form-control @error('planted') is-invalid @enderror" id="planted" name="planted" value="{{ old('planted', $recap->planted) }}" required>
                            @error('planted')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="x_cord" class="form-label">X Coordinate</label>
                            <input type="text" class="form-control @error('x_cord') is-invalid @enderror" id="x_cord" name="x_cord" value="{{ old('x_cord', $recap->x_cord) }}" required>
                            @error('x_cord')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="y_cord" class="form-label">Y Coordinate</label>
                            <input type="text" class="form-control @error('y_cord') is-invalid @enderror" id="y_cord" name="y_cord" value="{{ old('y_cord', $recap->y_cord) }}" required>
                            @error('y_cord')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary">Update Recap</button>
                </form>
            </div>
            <div class="card-footer text-end">
                 <form action="{{ route('recap.destroy', $recap->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this recap?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Delete Recap</button>
                </form>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
@endsection
