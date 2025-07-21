{{-- filepath: resources/views/admin/users/edit.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4">Edit User</h1>
    <form method="POST" action="{{ route('admin.users.update', $user->id) }}">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label for="firstname" class="form-label">First Name</label>
            <input type="text" id="firstname" name="firstname" class="form-control" value="{{ $user->firstname }}" required>
        </div>
        <div class="mb-3">
            <label for="lastname" class="form-label">Last Name</label>
            <input type="text" id="lastname" name="lastname" class="form-control" value="{{ $user->lastname }}" required>
        </div>
        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" id="email" name="email" class="form-control" value="{{ $user->email }}" required>
        </div>
        <div class="mb-3">
            <label for="password" class="form-label">New Password</label>
            <input type="password" id="password" name="password" class="form-control">
        </div>
        <div class="mb-3">
            <label for="password_confirmation" class="form-label">Confirm Password</label>
            <input type="password" id="password_confirmation" name="password_confirmation" class="form-control">
        </div>
        <button type="submit" class="btn btn-primary">Update User</button>
    </form>
</div>
@endsection