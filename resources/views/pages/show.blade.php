@extends('components.appbar')

@section('title', 'User Activity Log')

@section('content')
<div class="container mt-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Activity Log for: {{ $user->name }}</h1>
        <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">Back to User List</a>
    </div>

    <div class="card">
        <div class="card-header">
            User Details
        </div>
        <div class="card-body">
            <p><strong>Username:</strong> {{ $user->name }}</p>
            <p><strong>Account Type:</strong> {{ ucfirst($user->account_type) }}</p>
            <p><strong>Member Since:</strong> {{ $user->created_at->format('d F Y H:i') }}</p>
        </div>
    </div>

    <div class="card mt-4">
        <div class="card-header">Log Data</div>
        <div class="card-body">
            <textarea class="form-control" rows="20" readonly>{{ $logContent ?: 'No activity recorded for this user.' }}</textarea>
        </div>
    </div>
</div>
@endsection
