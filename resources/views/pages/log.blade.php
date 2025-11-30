@extends('components.appbar')

@section('title', 'Activity Log')

@section('content')
<!DOCTYPE html>
    <div class="container mt-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1>Activity Log</h1>
            <a href="{{ route('log.download') }}" class="btn btn-primary">Download Log</a>
        </div>

        <div class="card">
            <div class="card-body">
                <textarea class="form-control" rows="25" readonly>{{ $logContent }}</textarea>
            </div>
        </div>
    </div>
    
@endsection
