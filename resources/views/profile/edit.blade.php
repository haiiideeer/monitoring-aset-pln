@extends('layouts.app')

@section('title', 'Profile')

@section('content')
<div class="container mt-4">
    <h2 class="mb-4">Profile</h2>

    <div class="row">
        <div class="col-md-8">

            {{-- Update Profile Information --}}
            <div class="card mb-4 shadow-sm">
                <div class="card-body">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            {{-- Update Password --}}
            <div class="card mb-4 shadow-sm">
                <div class="card-body">
                    @include('profile.partials.update-password-form')
                </div>
            </div>

            {{-- Delete User --}}
            <div class="card mb-4 shadow-sm">
                <div class="card-body">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>

        </div>
    </div>
</div>
@endsection
