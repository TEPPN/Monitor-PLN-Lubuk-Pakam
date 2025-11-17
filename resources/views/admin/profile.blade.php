@extends('components.appbar')

@section('title', 'Admin Profile')

@section('content')
<div class="container mt-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Admin Profile</h1>
        <form action="{{ route('admin.logout') }}" method="POST">
            @csrf
            <button type="submit" class="btn btn-danger">Logout</button>
        </form>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h4>Profile Details</h4>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label">Username</label>
                        <input type="text" class="form-control" value="{{ $admin->name }}" readonly disabled>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Account Type</label>
                        <input type="text" class="form-control" value="{{ ucfirst($admin->account_type) }}" readonly disabled>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h4>My Activity Log</h4>
                </div>
                <div class="card-body">
                    <textarea class="form-control" rows="10" readonly>{{ $logContent ?: 'No activity recorded.' }}</textarea>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
