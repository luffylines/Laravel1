@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="text-center">Two-Factor Authentication</h1>
    <p class="text-center">Please enter the One-Time Password (OTP) from your authenticator app to continue.</p>

    <form method="POST" action="{{ route('2fa.verify.login') }}">
        @csrf
        <div class="form-group mb-3">
            <label for="one_time_password" class="form-label">One-Time Password</label>
            <input id="one_time_password" type="text" class="form-control" name="one_time_password" required>
        </div>
        <button type="submit" class="btn btn-primary">Verify</button>
    </form>

    <p class="text-center mt-3">
        <a href="#" onclick="document.getElementById('forgot-otp-form').submit();">Forgot OTP?</a>
    </p>

    <form id="forgot-otp-form" method="POST" action="{{ route('2fa.forgot') }}" style="display: none;">
        @csrf
        <input type="hidden" name="password" value="">
    </form>
</div>
@endsection