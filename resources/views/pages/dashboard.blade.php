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
                                    $isDone = ($data['stock_9m'] > 0 && $data['stock_9m'] == $data['planted_9m']) || ($data['stock_12m'] > 0 && $data['stock_12m'] == $data['planted_12m']);
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
