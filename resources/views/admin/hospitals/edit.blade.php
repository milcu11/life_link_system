@extends('layout.app')

@section('title', 'Edit Hospital - LifeLink')

@section('content')
<div class="bg-gray-50 min-h-screen py-8">
    <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Page Header -->
        <div class="mb-8">
            <div class="flex items-center gap-4 mb-4">
                <a href="{{ route('admin.hospitals.show', $hospital) }}" class="text-gray-600 hover:text-gray-900">
                    <i class="fas fa-arrow-left mr-2"></i>Back to Hospital
                </a>
            </div>
            <h1 class="text-3xl font-bold text-gray-900">Edit Hospital</h1>
            <p class="text-gray-600 mt-1">Update hospital information</p>
        </div>

        <!-- Edit Form -->
        <div class="bg-white rounded-lg shadow p-6">
            <form method="POST" action="{{ route('admin.hospitals.update', $hospital) }}">
                @csrf
                @method('PUT')

                @if ($errors->any())
                    <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded mb-6">
                        <ul class="list-disc list-inside text-sm">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="space-y-6">
                    <!-- Basic Information -->
                    <div>
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Basic Information</h3>
                        <div class="grid grid-cols-1 gap-6">
                            <div>
                                <label for="name" class="block text-sm font-medium text-gray-700">Hospital Name *</label>
                                <input type="text" name="name" id="name" value="{{ old('name', $hospital->name) }}" required
                                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-red-500 focus:border-red-500 sm:text-sm">
                            </div>

                            <div>
                                <label for="email" class="block text-sm font-medium text-gray-700">Email Address *</label>
                                <input type="email" name="email" id="email" value="{{ old('email', $hospital->email) }}" required
                                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-red-500 focus:border-red-500 sm:text-sm">
                            </div>

                            <div>
                                <label for="phone" class="block text-sm font-medium text-gray-700">Phone Number</label>
                                <input type="tel" name="phone" id="phone" value="{{ old('phone', $hospital->phone) }}"
                                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-red-500 focus:border-red-500 sm:text-sm">
                            </div>
                        </div>
                    </div>

                    <!-- Location Information -->
                    <div>
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Location Information</h3>
                        <div class="grid grid-cols-1 gap-6">
                            <div>
                                <label for="location" class="block text-sm font-medium text-gray-700">Address</label>
                                <input type="text" name="location" id="location" value="{{ old('location', $hospital->location) }}"
                                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-red-500 focus:border-red-500 sm:text-sm"
                                       placeholder="Hospital address">
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label for="latitude" class="block text-sm font-medium text-gray-700">Latitude</label>
                                    <input type="number" step="any" name="latitude" id="latitude" value="{{ old('latitude', $hospital->latitude) }}"
                                           class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-red-500 focus:border-red-500 sm:text-sm">
                                </div>
                                <div>
                                    <label for="longitude" class="block text-sm font-medium text-gray-700">Longitude</label>
                                    <input type="number" step="any" name="longitude" id="longitude" value="{{ old('longitude', $hospital->longitude) }}"
                                           class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-red-500 focus:border-red-500 sm:text-sm">
                                </div>
                            </div>
                            <div class="mt-4">
                                <button type="button" onclick="getLocation()" class="inline-flex items-center px-4 py-2 bg-gray-100 text-gray-700 rounded-md hover:bg-gray-200 transition">
                                    <i class="fas fa-map-marker-alt mr-2"></i>Use My Current Location
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Account Status -->
                    <div>
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Account Status</h3>
                        <div class="space-y-4">
                            <div class="flex items-center">
                                <input type="checkbox" name="is_active" id="is_active" value="1"
                                       {{ old('is_active', $hospital->is_active) ? 'checked' : '' }}
                                       class="h-4 w-4 text-red-600 focus:ring-red-500 border-gray-300 rounded">
                                <label for="is_active" class="ml-2 block text-sm text-gray-900">
                                    Active Account
                                </label>
                            </div>
                            <p class="text-sm text-gray-500">Inactive hospitals cannot log in or create new requests</p>
                        </div>
                    </div>
                </div>

                <div class="mt-8 flex justify-end gap-4">
                    <a href="{{ route('admin.hospitals.show', $hospital) }}" class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50">
                        Cancel
                    </a>
                    <button type="submit" class="px-4 py-2 bg-red-600 border border-transparent rounded-md text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                        <i class="fas fa-save mr-2"></i>Update Hospital
                    </button>
                </div>
            </form>
        </div>

        <!-- Delete Hospital -->
        <div class="mt-8 bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-medium text-red-900 mb-4">Danger Zone</h3>
            <p class="text-sm text-gray-600 mb-4">
                Deleting this hospital will permanently remove their account and all associated data.
                This action cannot be undone.
            </p>
            <form method="POST" action="{{ route('admin.hospitals.delete', $hospital) }}"
                  onsubmit="return confirm('Are you sure you want to delete this hospital? This action cannot be undone.')">
                @csrf
                @method('DELETE')
                <button type="submit" class="px-4 py-2 bg-red-600 border border-transparent rounded-md text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                    <i class="fas fa-trash mr-2"></i>Delete Hospital
                </button>
            </form>
        </div>
    </div>
</div>

<script>
    function getLocation() {
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(function(position) {
                document.getElementById('latitude').value = position.coords.latitude;
                document.getElementById('longitude').value = position.coords.longitude;
            }, function(error) {
                alert('Unable to get your location. Please enter latitude and longitude manually.');
            });
        } else {
            alert('Geolocation is not supported by your browser.');
        }
    }
</script>
@endsection