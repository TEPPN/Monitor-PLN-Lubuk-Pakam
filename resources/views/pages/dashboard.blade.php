@extends('components.appbar')

@section('title', 'Dashboard')
@section('content')
    <div class="container-fluid mt-5">
        <h1 class="mb-4">Dashboard Kontrak</h1>

        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead class="text-center align-middle">
                            <tr>
                                <th rowspan="2">#</th>
                                <th rowspan="2">Nama Kontrak</th>
                                <th colspan="2">Stock</th>
                                <th colspan="2">Request</th>
                                <th colspan="2">Planted</th>
                                <th colspan="2">Leftover</th>
                                <th rowspan="2">Detail</th>
                                <th rowspan="2">Masa Kontrak</th>
                                <th rowspan="2">Aksi</th>
                            </tr>
                            <tr>
                                <th>9m</th>
                                <th>12m</th>
                                <th>9m</th>
                                <th>12m</th>
                                <th>9m</th>
                                <th>12m</th>
                                <th>9m</th>
                                <th>12m</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($dashboardData as $data)
                                @php
                                    $leftover_9m = $data['stock_9m'] - $data['planted_9m'];
                                    $leftover_12m = $data['stock_12m'] - $data['planted_12m'];
                                    
                                    // Status Done jika Stock sama dengan Planted untuk KEDUA tipe (atau stock 0)
                                    $done9m = $data['stock_9m'] == $data['planted_9m'];
                                    $done12m = $data['stock_12m'] == $data['planted_12m'];
                                    $isDone = $done9m && $done12m;
                                @endphp
                                <tr class="text-center">
                                    <td>{{ $loop->iteration }}</td>
                                    <td class="text-start">{{ $data['contract_name'] }}</td>
                                    <td>{{ $data['stock_9m'] > 0 ? number_format($data['stock_9m']) : '-' }}</td>
                                    <td>{{ $data['stock_12m'] > 0 ? number_format($data['stock_12m']) : '-' }}</td>
                                    <td>{{ $data['stock_9m'] > 0 ? number_format($data['request_9m']) : '-' }}</td>
                                    <td>{{ $data['stock_12m'] > 0 ? number_format($data['request_12m']) : '-' }}</td>
                                    <td>{{ $data['stock_9m'] > 0 ? number_format($data['planted_9m']) : '-' }}</td>
                                    <td>{{ $data['stock_12m'] > 0 ? number_format($data['planted_12m']) : '-' }}</td>
                                    <td class="fw-bold">{{ $data['stock_9m'] > 0 ? number_format($leftover_9m) : '-' }}</td>
                                    <td class="fw-bold">{{ $data['stock_12m'] > 0 ? number_format($leftover_12m) : '-' }}</td>
                                    <td>
                                        @if ($isDone)
                                            <span class="badge bg-success">Done</span>
                                        @else
                                            <span class="badge bg-warning text-dark">In Progress</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <span class="badge {{ $data['remaining_days_val'] < 0 ? 'bg-danger' : 'bg-success' }}">
                                            {{ $data['remaining_days_text'] }}
                                        </span>
                                    </td>
                                    <td>
                                        <a href="{{ route('recap.index', ['contract_id' => $data['contract_id']]) }}" 
                                        class="btn btn-sm btn-primary">
                                            Lihat Rekap
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="11" class="text-center">No contract data found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
