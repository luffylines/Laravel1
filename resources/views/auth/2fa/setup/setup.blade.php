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

        {{-- Success Message --}}
    @if (session('success'))
        <div class="alert alert-success" id="success-message">
            {{ session('success') }}
        </div>
    @endif

    {{-- Error Message --}}
    @if (session('error'))
        <div class="alert alert-danger" id="error-message">
            {{ session('error') }}
        </div>
    @endif
    
    {{-- Validation Errors --}}
    @if ($errors->any())
        <div class="alert alert-danger" id="validation-error">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    {{-- Specific Field Error --}}
@error('one_time_password')
    <div class="alert alert-danger">{{ $message }}</div>
@enderror

    <form method="POST" action="{{ route('2fa.verify.setup') }}">
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
            <input id="one_time_password" type="text" class="form-control" name="one_time_password" placeholder="XXXXXX" required>
            @error('one_time_password')
                <div class="alert alert-danger">{{ $message }}</div>
            @enderror
        </div>
        <button type="submit" class="btn btn-primary">Verify</button>
    </form>
</div>

<script>
    // Automatically hide success and error messages after 3 seconds
    setTimeout(() => {
        const successMessage = document.getElementById('success-message');
        const errorMessage = document.getElementById('error-message');
        const validationError = document.getElementById('validation-error');

        if (successMessage) successMessage.style.display = 'none';
        if (errorMessage) errorMessage.style.display = 'none';
        if (validationError) validationError.style.display = 'none';
    }, 3000);
</script>
@endsection