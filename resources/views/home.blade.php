@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Dashboard') }}</div>

                <div class="card-body">
                    @if (session('success'))
                        <div class="alert alert-success" role="alert">
                            <strong>🎉 {{ session('success') }}</strong>
                        </div>
                    @endif
                    
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <h4>Welcome back, {{ Auth::user()->firstname }}!</h4>
                    
                    <div class="mt-4">
                        <a href="{{ route('rooms.index') }}" class="btn btn-primary">Browse Rooms</a>
                        <a href="{{ route('bookings.my-bookings') }}" class="btn btn-outline-secondary">My Bookings</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
