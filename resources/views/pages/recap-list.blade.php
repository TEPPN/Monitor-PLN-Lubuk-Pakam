@extends('components.appbar')

@section('title', 'Rekap Permintaan')
@section('content')
    <div class="container mt-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1>Rekap Permintaan Tiang</h1>
            <a href="{{ route('recap.create') }}" class="btn btn-primary">Add Recap</a>
        </div>

        <div class="card mb-4">
            <div class="card-body">
                <form action="{{ route('recap.index') }}" method="GET">
                    <div class="row align-items-end">
                        <div class="col-md-4">
                            <label for="contract_id" class="form-label">Filter by Contract</label>
                            <select name="contract_id" id="contract_id" class="form-select" onchange="this.form.submit()">
                                <option value="">All Contracts</option>
                                @foreach ($contracts as $contract)
                                    <option value="{{ $contract->id }}" {{ request('contract_id') == $contract->id ? 'selected' : '' }}>{{ $contract->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        @if (session('success'))
            <div class="alert alert-success mb-3">
                {{ session('success') }}
            </div>
        @endif

        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th scope="col">Pekerjaan</th>
                                <th scope="col">Kontrak</th>
                                <th scope="col">Pelaksana</th>
                                <th scope="col">Alamat</th>
                                <th scope="col">Request</th>
                                <th scope="col">Tertanam</th>
                                <th scope="col">Dibuat Oleh</th>
                                <th scope="col">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($recaps as $recap)
                                <tr>
                                    <td>{{ $recap->job }}</td>
                                    <td>{{ $recap->contract->name ?? $recap->contract ?? 'N/A' }}</td>
                                    <td>{{ $recap->executor }}</td>
                                    <td>{{ $recap->address }}</td>
                                    <td>{{ $recap->request }}</td>
                                    <td>{{ $recap->planted }}</td>
                                    <td>{{ $recap->createdBy->name ?? 'N/A' }}</td>
                                    <td>
                                        <a href="{{ route('recap.edit', $recap->id) }}" class="btn btn-sm btn-warning">Edit</a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center">No recaps found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card-footer">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        {{ $recaps->links() }}
                    </div>
                    @if (isset($selectedContract))
                        <div class="text-start">
                            <h6 class="mb-0">Stock for: {{ $selectedContract->name }}</h6>
                            <small>Initial: {{ number_format($selectedContract->stock) }} | Remaining: <span class="fw-bold">{{ number_format($remainingStock) }}</span></small>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
