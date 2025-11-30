@extends('components.appbar')

@section('title', 'My Profile')

@section('content')
    <div class="container mt-5">
        <h1>My Profile</h1>

        @if (session('success'))
            <div class="alert alert-success mb-3">
                {{ session('success') }}
            </div>
        @endif

        <div class="row">
            <!-- Edit Profile Section -->
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h4>Profile Details</h4>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('profile.update') }}" method="POST">
                            @csrf
                            @method('PUT')

                            <div class="mb-3">
                                <label for="name" class="form-label">Username</label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $user->name) }}" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Account Type</label>
                                <input type="text" class="form-control" value="{{ ucfirst($user->account_type) }}" readonly disabled>
                            </div>

                            <button type="submit" class="btn btn-primary">Update Username</button>
                        </form>
                    </div>
                    <div class="card-footer text-end">
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-danger">Logout</button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Activity Log Section -->
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h4>My Activity Log</h4>
                    </div>
                    <div class="card-body">
                        <textarea class="form-control" rows="15" readonly>{{ $logContent }}</textarea>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
