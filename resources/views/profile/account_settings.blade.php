{{-- filepath: resources/views/profile/account_settings.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="text-center mb-4">{{ __('Account Settings') }}</h1>

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

    {{-- Tab Navigation --}}
    <ul class="nav nav-tabs" id="accountSettingsTabs" role="tablist">
        <li class="accordion-item" role="presentation">
            <button class="nav-link {{ session('activeTab') === 'display-mode' ? 'active' : '' }}" id="display-mode-tab" data-bs-toggle="tab" data-bs-target="#display-mode" type="button" role="tab" aria-controls="display-mode" aria-selected="true">
                {{ __('Display Mode') }}
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link {{ session('activeTab') === 'changename' ? 'active' : '' }}" id="changenametab" data-bs-toggle="tab" data-bs-target="#changename" type="button" role="tab" aria-controls="changename" aria-selected="false">
                {{ __('Change Name') }}
            </button>
        </li>
         <li class="nav-item" role="presentation">
            <button class="nav-link {{ session('activeTab') === 'changeemail' ? 'active' : '' }}" id="changeemailtab" data-bs-toggle="tab" data-bs-target="#changeemail" type="button" role="tab" aria-controls="changeemail" aria-selected="false">
                {{ __('Change Email') }}
            </button>
        </li>
         <li class="nav-item" role="presentation">
            <button class="nav-link {{ session('activeTab') === 'changepass' ? 'active' : '' }}" id="changepasstab" data-bs-toggle="tab" data-bs-target="#changepassinfo" type="button" role="tab" aria-controls="changepass" aria-selected="false">
                {{ __('Change Password') }}
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link {{ session('activeTab') === 'country' ? 'active' : '' }}" id="country-tab" data-bs-toggle="tab" data-bs-target="#country" type="button" role="tab" aria-controls="country" aria-selected="false">
                {{ __('Country') }}
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link {{ session('activeTab') === 'timezone' ? 'active' : '' }}" id="timezone-tab" data-bs-toggle="tab" data-bs-target="#timezone" type="button" role="tab" aria-controls="timezone" aria-selected="false">
                {{ __('Timezone') }}
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link {{ session('activeTab') === 'two-factor' ? 'active' : '' }}" id="two-factor-tab" data-bs-toggle="tab" data-bs-target="#two-factor" type="button" role="tab" aria-controls="two-factor" aria-selected="false">
                {{ __('Two-Factor Authentication') }}
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link text-danger" id="delete-account-tab" data-bs-toggle="tab" data-bs-target="#delete-account" type="button" role="tab" aria-controls="delete-account" aria-selected="false">
                {{ __('Delete Account') }}
            </button>
        </li>
    </ul>

    {{-- Tab Content --}}
    <div class="tab-content mt-4" id="accountSettingsTabsContent">
        {{-- Display Mode Tab --}}
        <div class="tab-pane fade {{ session('activeTab') === 'display-mode' ? 'show active' : '' }}" id="display-mode" role="tabpanel" aria-labelledby="display-mode-tab">
            <form method="POST" action="{{ route('profile.update') }}">
                @csrf
                @method('PUT')
                <div class="mb-3">
                    <label for="display_mode" class="form-label">{{ __('Display Mode') }}</label>
                    <select id="display_mode" name="display_mode" class="form-control">
                        <option value="light" {{ Auth::user()->display_mode === 'light' ? 'selected' : '' }}>Light</option>
                        <option value="dark" {{ Auth::user()->display_mode === 'dark' ? 'selected' : '' }}>Dark</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary">{{ __('Change Display Mode') }}</button>
            </form>
        </div>
        {{-- Change Name Tab --}}
        <div class="tab-pane fade {{ session('activeTab') === 'changename' ? 'show active' : '' }}" id="changename" role="tabpanel" aria-labelledby="changename-tab">
            <form method="POST" action="{{ route('profile.update') }}">
                @csrf
                @method('PUT')
                <div class="mb-3">
                    <label for="firstname" class="form-label">{{ __('First Name') }}</label>
                    <input type="text" id="firstname" name="firstname" class="form-control" value="{{ Auth::user()->firstname }}" required>
                </div>
                <div class="mb-3">
                    <label for="lastname" class="form-label">{{ __('Last Name') }}</label>
                    <input type="text" id="lastname" name="lastname" class="form-control" value="{{ Auth::user()->lastname }}" required>
                </div>
                <button type="submit" class="btn btn-primary">{{ __('Change Name') }}</button>
            </form>
        </div>
        {{-- Change Email Tab --}}
        <div class="tab-pane fade {{ session('activeTab') === 'changeemail' ? 'show active' : '' }}" id="changeemail" role="tabpanel" aria-labelledby="changeemail-tab">
            <form method="POST" action="{{ route('profile.update') }}">
                @csrf
                @method('PUT')
                <div class="mb-3">
                    <label for="email" class="form-label">{{ __('Email Address') }}</label>
                    <input type="email" id="email" name="email" class="form-control" value="{{ Auth::user()->email }}" required>
                </div>
                <button type="submit" class="btn btn-primary">{{ __('Change Email') }}</button>
            </form>
        </div>
        {{-- Change Password Tab --}}
        <div class="tab-pane fade {{ session('activeTab') === 'changepassinfo' ? 'show active' : '' }}" id="changepassinfo" role="tabpanel" aria-labelledby="changepass-tab">
            <form method="POST" action="{{ route('profile.update') }}">
                @csrf
                @method('PUT')
                <div class="mb-3">
                    <label for="password" class="form-label">{{ __('New Password') }}</label>
                    <input type="password" id="password" name="password" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="password_confirmation" class="form-label">{{ __('Confirm Password') }}</label>
                    <input type="password" id="password_confirmation" name="password_confirmation" class="form-control" required>
                </div>
                <button type="submit" class="btn btn-primary">{{ __('Change Password') }}</button>
            </form>
        </div>
                {{-- Two-Factor Authentication Tab --}}
        <div class="tab-pane fade {{ session('activeTab') === 'two-factor' ? 'show active' : '' }}" id="two-factor" role="tabpanel" aria-labelledby="two-factor-tab">
            <form method="POST" action="{{ route('profile.update') }}">
                @csrf
                @method('PUT')
                <div class="mb-3">
                    <label for="two_factor" class="form-label">{{ __('Enable Two-Factor Authentication') }}</label>
                    <select id="two_factor" name="two_factor" class="form-control" onchange="toggleTwoFactorSetup(this.value)">
                        <option value="on" {{ Auth::user()->google2fa_secret ? 'selected' : '' }}>{{ __('On') }}</option>
                        <option value="off" {{ !Auth::user()->google2fa_secret ? 'selected' : '' }}>{{ __('Off') }}</option>
                    </select>
                </div>
                @if (Auth::user()->google2fa_secret)
                    <p class="text-success">{{ __('Two-Factor Authentication is currently enabled.') }}</p>
                @else
                    <p class="text-danger">{{ __('Two-Factor Authentication is currently disabled.') }}</p>
                @endif
                <button type="submit" class="btn btn-primary">{{ __('Update Two-Factor Authentication') }}</button>
            </form>
        </div>
        {{-- Two-Factor Disable Tab --}}
        <div class="tab-pane fade {{ session('activeTab') === 'two-factor-disable' ? 'show active' : '' }}" id="two-factor-disable" role="tabpanel" aria-labelledby="two-factor-disable-tab">
            <form method="POST" action="{{ route('2fa.disable') }}">
                @csrf
                <input type="hidden" name="activeTab" value="two-factor-disable">
                <p class="text-danger">{{ __('Warning: Disabling Two-Factor Authentication will remove an extra layer of security from your account.') }}</p>
                <button type="submit" class="btn btn-danger">{{ __('Disable Two-Factor Authentication') }}</button>
            </form>
        </div>
        {{-- Country Tab --}}
        <div class="tab-pane fade {{ session('activeTab') === 'country' ? 'show active' : '' }}" id="country" role="tabpanel" aria-labelledby="country-tab">
            <form method="POST" action="{{ route('profile.update') }}">
                @csrf
                @method('PUT')
                {{-- Country Selection --}}
                <div class="mb-3">
                    <label for="country" class="form-label">{{ __('Country') }}</label>
                    <select id="country" name="country" class="form-control">
                        @foreach ($countries as $code => $name)
                            <option value="{{ $code }}" {{ Auth::user()->country === $code ? 'selected' : '' }}>
                                {{ $name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <button type="submit" class="btn btn-primary">{{ __('Change Country') }}</button>
            </form>
        </div>
        {{-- Timezone Tab --}}
                <div class="tab-pane fade {{ session('activeTab') === 'timezone' ? 'show active' : '' }}" id="timezone" role="tabpanel" aria-labelledby="timezone-tab">
            <form method="POST" action="{{ route('profile.update') }}">
                @csrf
                @method('PUT')
                {{-- Timezone Selection --}}
                <div class="mb-3">
                    <label for="timezone" class="form-label">{{ __('Timezone') }}</label>
                    <select id="timezone" name="timezone" class="form-control">
                        @foreach (timezone_identifiers_list() as $timezone)
                            <option value="{{ $timezone }}" {{ Auth::user()->timezone === $timezone ? 'selected' : '' }}>
                                {{ $timezone }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <button type="submit" class="btn btn-primary">{{ __('Change Timezone') }}</button>
            </form>
        </div>
        {{-- Delete Account Tab --}}
        <div class="tab-pane fade {{ session('activeTab') === 'delete-account' ? 'show active' : '' }}" id="delete-account" role="tabpanel" aria-labelledby="delete-account-tab">
            <form id="delete-account-form" method="POST" action="{{ route('profile.delete') }}">
                @csrf
                @method('DELETE')
                <p class="text-danger">{{ __('Warning: Deleting your account is permanent and cannot be undone.') }}</p>
                <button type="submit" class="btn btn-danger">{{ __('Delete Account') }}</button>
            </form>
        </div>
    </div>
</div>
<script>
    function toggleTwoFactorSetup(value) {
        const setupTab = document.getElementById('two-factor-setup');
        const verifyTab = document.getElementById('two-factor-verify');
        const disableTab = document.getElementById('two-factor-disable');
        const updateButton = document.querySelector('#two-factor button[type="submit"]'); // Update button

        if (value === 'on') {
            setupTab.classList.add('show', 'active');
            verifyTab.classList.remove('show', 'active');
            disableTab.classList.remove('show', 'active');
            updateButton.style.display = 'block'; // Show the Update button
        } else if (value === 'off') {
            setupTab.classList.remove('show', 'active');
            verifyTab.classList.remove('show', 'active');
            disableTab.classList.add('show', 'active');
            updateButton.style.display = 'none'; // Hide the Update button
        }
    }
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