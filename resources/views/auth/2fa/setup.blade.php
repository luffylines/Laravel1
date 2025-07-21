@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="text-center">Setup Two-Factor Authentication</h1>
    <p class="text-center">Scan the QR code below with your authenticator app or enter the secret key manually.</p>
    
    <div class="text-center">
        @if(isset($qrCodeUrl))
            <img src="{{ $qrCodeUrl }}" alt="QR Code">
        @else
            <p class="text-danger">QR Code could not be generated.</p>
        @endif
    </div>

    <p class="text-center">Secret Key: <strong>{{ $secretKey }}</strong></p>

    <form method="POST" action="{{ route('2fa.verify') }}">
        @csrf
        <div class="form-group mb-3">
            <label for="country" class="form-label">Select Country</label>
            <select id="country" name="country" class="form-control" required>
                @foreach($countries as $code => $name)
                    <option value="{{ $code }}" {{ Auth::user()->country === $code ? 'selected' : '' }}>
                        {{ $name }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="form-group mb-3">
            <label for="one_time_password" class="form-label">Enter One-Time Password</label>
            <input id="one_time_password" type="text" class="form-control" name="one_time_password" required>
        </div>
        <button type="submit" class="btn btn-primary">Verify</button>
    </form>
</div>
@endsection