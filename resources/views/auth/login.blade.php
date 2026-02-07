@extends('layouts.app')

@section('title', 'Login')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-primary-50 to-primary-100 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8">
        <div class="bg-white rounded-2xl shadow-xl p-8">
            <!-- Logo & Title -->
            <div class="text-center mb-8">
                <div class="mx-auto w-16 h-16 bg-primary-600 rounded-2xl flex items-center justify-center mb-4">
                    <i class="bi bi-car-front text-white text-3xl"></i>
                </div>
                <h2 class="text-3xl font-bold text-gray-900">Car Service Management</h2>
                <p class="mt-2 text-sm text-gray-600">Sign in to your account</p>
            </div>

            <!-- Login Form -->
            <form method="POST" action="{{ route('login') }}" class="space-y-6">
                @csrf

                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1">
                        Email Address
                    </label>
                    <input type="email" 
                           id="email" 
                           name="email" 
                           value="{{ old('email') }}" 
                           required 
                           autofocus
                           class="input-modern @error('email') border-red-300 @enderror"
                           placeholder="you@example.com">
                    @error('email')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-1">
                        Password
                    </label>
                    <input type="password" 
                           id="password" 
                           name="password" 
                           required
                           class="input-modern @error('password') border-red-300 @enderror"
                           placeholder="••••••••">
                    @error('password')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <input type="checkbox" 
                               id="remember" 
                               name="remember"
                               class="h-4 w-4 text-primary-600 focus:ring-primary-500 border-gray-300 rounded">
                        <label for="remember" class="ml-2 block text-sm text-gray-700">
                            Remember me
                        </label>
                    </div>
                </div>

                <button type="submit" class="w-full btn-primary-modern justify-center py-3">
                    <i class="bi bi-box-arrow-in-right"></i>
                    Sign In
                </button>
            </form>

            <!-- Default Credentials Info -->
            <div class="mt-6 p-4 bg-gray-50 rounded-lg border border-gray-200">
                <p class="text-xs font-medium text-gray-700 mb-2">Default Login:</p>
                <p class="text-xs text-gray-600">Email: <span class="font-mono">admin@carservice.com</span></p>
                <p class="text-xs text-gray-600">Password: <span class="font-mono">password</span></p>
            </div>
        </div>
    </div>
</div>
@endsection
