{{-- filepath: resources/views/profile/account_settings.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="text-center mb-4">{{ __('Account Settings') }}</h1>

    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    {{-- Tab Navigation --}}
    <ul class="nav nav-tabs" id="accountSettingsTabs" role="tablist">
        <li class="accordion-item" role="presentation">
            <button class="nav-link active" id="display-mode-tab" data-bs-toggle="tab" data-bs-target="#display-mode" type="button" role="tab" aria-controls="display-mode" aria-selected="true">
                {{ __('Display Mode') }}
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="changenametab" data-bs-toggle="tab" data-bs-target="#changename" type="button" role="tab" aria-controls="changename" aria-selected="false">
                {{ __('Change Name') }}
            </button>
        </li>
         <li class="nav-item" role="presentation">
            <button class="nav-link" id="changeemailtab" data-bs-toggle="tab" data-bs-target="#changeemail" type="button" role="tab" aria-controls="changeemail" aria-selected="false">
                {{ __('Change Email') }}
            </button>
        </li>
         <li class="nav-item" role="presentation">
            <button class="nav-link" id="changepasstab" data-bs-toggle="tab" data-bs-target="#changepassinfo" type="button" role="tab" aria-controls="changepass" aria-selected="false">
                {{ __('Change Password') }}
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="country-tab" data-bs-toggle="tab" data-bs-target="#country" type="button" role="tab" aria-controls="country" aria-selected="false">
                {{ __('Country') }}
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="timezone-tab" data-bs-toggle="tab" data-bs-target="#timezone" type="button" role="tab" aria-controls="timezone" aria-selected="false">
                {{ __('Timezone') }}
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
        <div class="tab-pane fade show active" id="display-mode" role="tabpanel" aria-labelledby="display-mode-tab">
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
        <div class="tab-pane fade" id="changename" role="tabpanel" aria-labelledby="changename-tab">
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
        <div class="tab-pane fade" id="changeemail" role="tabpanel" aria-labelledby="changeemail-tab">
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
        <div class="tab-pane fade" id="changepassinfo" role="tabpanel" aria-labelledby="changepass-tab">
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
        {{-- Country Tab --}}
        <div class="tab-pane fade" id="country" role="tabpanel" aria-labelledby="country-tab">
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
                <div class="tab-pane fade" id="timezone" role="tabpanel" aria-labelledby="timezone-tab">
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
        <div class="tab-pane fade" id="delete-account" role="tabpanel" aria-labelledby="delete-account-tab">
            <form id="delete-account-form" method="POST" action="{{ route('profile.delete') }}">
                @csrf
                @method('DELETE')
                <p class="text-danger">{{ __('Warning: Deleting your account is permanent and cannot be undone.') }}</p>
                <button type="submit" class="btn btn-danger">{{ __('Delete Account') }}</button>
            </form>
        </div>
    </div>
</div>
@endsection