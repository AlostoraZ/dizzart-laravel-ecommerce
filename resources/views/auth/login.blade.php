@extends('layouts.app')

@section('title', 'Login | Dizzart')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card p-4">
                <h3 class="mb-4 text-center" style="font-family: 'Playfair Display', serif;">Welcome Back</h3>
                <form method="POST" action="{{ route('login') }}">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control" value="{{ old('email') }}" required autofocus>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Password</label>
                        <input type="password" name="password" class="form-control" required>
                    </div>
                    <div class="mb-3 form-check">
                        <input type="checkbox" name="remember" class="form-check-input" id="remember">
                        <label class="form-check-label" for="remember">Remember me</label>
                    </div>
                    <button type="submit" class="btn btn-dizzart w-100">Login</button>
                </form>
                <p class="text-center mt-3 mb-0 small">Don't have an account? <a href="{{ route('register') }}">Register</a></p>
            </div>
        </div>
    </div>
@endsection
