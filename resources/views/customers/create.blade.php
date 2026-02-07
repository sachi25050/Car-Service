@extends('layouts.app')

@section('title', 'Add Customer')
@section('page-title', 'Add Customer')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Add Customer</h1>
            <p class="mt-1 text-sm text-gray-500">Create a new customer profile</p>
        </div>
        <a href="{{ route('customers.index') }}" class="btn-secondary-modern">
            <i class="bi bi-arrow-left"></i>
            Back
        </a>
    </div>

    <!-- Form Card -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <form action="{{ route('customers.store') }}" method="POST" class="space-y-6">
            @csrf

            <!-- Personal Information -->
            <div>
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Personal Information</h3>
                <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                    <div>
                        <label for="first_name" class="block text-sm font-medium text-gray-700 mb-1">
                            First Name <span class="text-red-500">*</span>
                        </label>
                        <input type="text" 
                               id="first_name" 
                               name="first_name" 
                               value="{{ old('first_name') }}"
                               required
                               class="input-modern @error('first_name') border-red-300 @enderror">
                        @error('first_name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="last_name" class="block text-sm font-medium text-gray-700 mb-1">
                            Last Name <span class="text-red-500">*</span>
                        </label>
                        <input type="text" 
                               id="last_name" 
                               name="last_name" 
                               value="{{ old('last_name') }}"
                               required
                               class="input-modern @error('last_name') border-red-300 @enderror">
                        @error('last_name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                        <input type="email" 
                               id="email" 
                               name="email" 
                               value="{{ old('email') }}"
                               class="input-modern @error('email') border-red-300 @enderror">
                        @error('email')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="phone" class="block text-sm font-medium text-gray-700 mb-1">
                            Phone <span class="text-red-500">*</span>
                        </label>
                        <input type="text" 
                               id="phone" 
                               name="phone" 
                               value="{{ old('phone') }}"
                               required
                               class="input-modern @error('phone') border-red-300 @enderror">
                        @error('phone')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="alternate_phone" class="block text-sm font-medium text-gray-700 mb-1">Alternate Phone</label>
                        <input type="text" 
                               id="alternate_phone" 
                               name="alternate_phone" 
                               value="{{ old('alternate_phone') }}"
                               class="input-modern">
                    </div>

                    <div>
                        <label for="gender" class="block text-sm font-medium text-gray-700 mb-1">Gender</label>
                        <select id="gender" name="gender" class="input-modern">
                            <option value="">Select</option>
                            <option value="male" {{ old('gender') === 'male' ? 'selected' : '' }}>Male</option>
                            <option value="female" {{ old('gender') === 'female' ? 'selected' : '' }}>Female</option>
                            <option value="other" {{ old('gender') === 'other' ? 'selected' : '' }}>Other</option>
                        </select>
                    </div>

                    <div>
                        <label for="date_of_birth" class="block text-sm font-medium text-gray-700 mb-1">Date of Birth</label>
                        <input type="date" 
                               id="date_of_birth" 
                               name="date_of_birth" 
                               value="{{ old('date_of_birth') }}"
                               class="input-modern">
                    </div>
                </div>
            </div>

            <!-- Address Information -->
            <div class="border-t border-gray-200 pt-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Address Information</h3>
                <div class="space-y-4">
                    <div>
                        <label for="address" class="block text-sm font-medium text-gray-700 mb-1">Address</label>
                        <textarea id="address" 
                                  name="address" 
                                  rows="2"
                                  class="input-modern">{{ old('address') }}</textarea>
                    </div>

                    <div class="grid grid-cols-1 gap-6 sm:grid-cols-3">
                        <div>
                            <label for="city" class="block text-sm font-medium text-gray-700 mb-1">City</label>
                            <input type="text" 
                                   id="city" 
                                   name="city" 
                                   value="{{ old('city') }}"
                                   class="input-modern">
                        </div>

                        <div>
                            <label for="state" class="block text-sm font-medium text-gray-700 mb-1">State</label>
                            <input type="text" 
                                   id="state" 
                                   name="state" 
                                   value="{{ old('state') }}"
                                   class="input-modern">
                        </div>

                        <div>
                            <label for="zip_code" class="block text-sm font-medium text-gray-700 mb-1">Zip Code</label>
                            <input type="text" 
                                   id="zip_code" 
                                   name="zip_code" 
                                   value="{{ old('zip_code') }}"
                                   class="input-modern">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Additional Information -->
            <div class="border-t border-gray-200 pt-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Additional Information</h3>
                <div class="space-y-4">
                    <div class="flex items-center">
                        <input type="checkbox" 
                               id="is_active" 
                               name="is_active" 
                               value="1" 
                               {{ old('is_active', true) ? 'checked' : '' }}
                               class="h-4 w-4 text-primary-600 focus:ring-primary-500 border-gray-300 rounded">
                        <label for="is_active" class="ml-2 block text-sm text-gray-700">
                            Active Customer
                        </label>
                    </div>

                    <div>
                        <label for="notes" class="block text-sm font-medium text-gray-700 mb-1">Notes</label>
                        <textarea id="notes" 
                                  name="notes" 
                                  rows="3"
                                  class="input-modern">{{ old('notes') }}</textarea>
                    </div>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="flex items-center justify-end gap-3 pt-6 border-t border-gray-200">
                <a href="{{ route('customers.index') }}" class="btn-secondary-modern">
                    Cancel
                </a>
                <button type="submit" class="btn-primary-modern">
                    <i class="bi bi-check-circle"></i>
                    Create Customer
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
