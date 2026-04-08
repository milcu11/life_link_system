@extends('layout.app')

@section('title', 'Hospital Profile - LifeLink')

@section('content')
<div class="bg-gray-50 min-h-screen py-10">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white shadow rounded-lg p-6">
            <div class="mb-6">
                <h1 class="text-2xl font-bold text-gray-900">Complete Hospital Profile</h1>
                <p class="text-sm text-gray-600 mt-1">Enter your hospital address and location so the system can match donor requests correctly.</p>
            </div>

            @if(session('info'))
                <div class="mb-4 p-4 rounded-lg bg-blue-50 border border-blue-200 text-blue-700">
                    {{ session('info') }}
                </div>
            @endif
            @if(session('success'))
                <div class="mb-4 p-4 rounded-lg bg-green-50 border border-green-200 text-green-700">
                    {{ session('success') }}
                </div>
            @endif

            <form method="POST" action="{{ route('hospital.profile.update') }}">
                @csrf

                <div class="space-y-6">
                    <div>
                        <label for="location" class="block text-sm font-medium text-gray-700">Hospital Address</label>
                        <textarea name="location" id="location" rows="3" required
                                  class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-red-500 focus:border-red-500 sm:text-sm">{{ old('location', auth()->user()->location) }}</textarea>
                        @error('location')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="latitude" class="block text-sm font-medium text-gray-700">Latitude</label>
                            <input type="number" step="any" name="latitude" id="latitude" required
                                   value="{{ old('latitude', auth()->user()->latitude) }}"
                                   class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-red-500 focus:border-red-500 sm:text-sm">
                            @error('latitude')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="longitude" class="block text-sm font-medium text-gray-700">Longitude</label>
                            <input type="number" step="any" name="longitude" id="longitude" required
                                   value="{{ old('longitude', auth()->user()->longitude) }}"
                                   class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-red-500 focus:border-red-500 sm:text-sm">
                            @error('longitude')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div>
                        <button type="button" onclick="getLocation()" class="inline-flex items-center px-4 py-2 bg-gray-100 text-gray-700 rounded-md hover:bg-gray-200 transition">
                            <i class="fas fa-map-marker-alt mr-2"></i>Use My Current Location
                        </button>
                    </div>
                </div>

                <div class="mt-8 flex flex-col sm:flex-row gap-3 sm:gap-4 justify-end">
                    <a href="{{ route('dashboard') }}" class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50">Cancel</a>
                    <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700">Save Profile</button>
                </div>
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
            }, function() {
                alert('Unable to get your location. Please enter latitude and longitude manually.');
            });
        } else {
            alert('Geolocation is not supported by your browser.');
        }
    }
</script>
@endsection
