@extends('layouts.app')

@section('title', 'Register | Dizzart')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card p-4">
                <h3 class="mb-4 text-center" style="font-family: 'Playfair Display', serif;">Create an Account</h3>
                <form method="POST" action="{{ route('register') }}">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Full Name</label>
                        <input type="text" name="name" class="form-control" value="{{ old('name') }}" required autofocus>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control" value="{{ old('email') }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Password</label>
                        <input type="password" name="password" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Confirm Password</label>
                        <input type="password" name="password_confirmation" class="form-control" required>
                    </div>
                    <button type="submit" class="btn btn-dizzart w-100">Register</button>
                </form>
                <p class="text-center mt-3 mb-0 small">Already have an account? <a href="{{ route('login') }}">Login</a></p>
            </div>
        </div>
    </div>
@endsection
