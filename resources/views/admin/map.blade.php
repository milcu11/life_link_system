@extends('layout.app')

@section('title', 'Geolocation Map - LifeLink')

@section('content')
<div class="bg-gray-50 min-h-screen py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Page Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">Geolocation Map</h1>
            <p class="text-gray-600 mt-1">Real-time donor and request locations</p>
        </div>

        <!-- Map Legend -->
        <div class="bg-white rounded-lg shadow p-4 mb-6">
            <div class="flex flex-wrap gap-8">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-full bg-red-600 border-4 border-white shadow flex items-center justify-center">
                        <i class="fas fa-user text-white text-sm"></i>
                    </div>
                    <span class="text-gray-700 font-medium">Available Donors ({{ $donors->count() }})</span>
                </div>
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-full bg-orange-600 border-4 border-white shadow flex items-center justify-center">
                        <i class="fas fa-hospital text-white text-sm"></i>
                    </div>
                    <span class="text-gray-700 font-medium">Pending Requests ({{ $requests->count() }})</span>
                </div>
            </div>
        </div>

        <!-- Map Container -->
        <div class="bg-white rounded-lg shadow overflow-hidden mb-6" data-testid="map-container">
            <div id="map" style="height: 500px; width: 100%;"></div>
        </div>

        <!-- Data Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Donors List -->
            <div class="bg-white rounded-lg shadow overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                    <h3 class="text-lg font-bold text-gray-900"><i class="fas fa-users text-red-600 mr-2"></i>Available Donors</h3>
                </div>
                <div class="divide-y divide-gray-200 max-h-96 overflow-y-auto">
                    @forelse($donors as $donor)
                        <div class="p-4 hover:bg-gray-50" data-testid="donor-marker-{{ $donor->id }}">
                            <div class="flex items-start justify-between gap-3">
                                <div class="flex-1">
                                    <div class="flex items-center gap-2 mb-2">
                                        <span class="inline-block bg-red-100 text-red-800 px-3 py-1 rounded-full text-sm font-bold">
                                            {{ $donor->blood_type }}
                                        </span>
                                    </div>
                                    <h4 class="font-semibold text-gray-900">{{ $donor->user->name }}</h4>
                                    <p class="text-sm text-gray-600 mt-1"><i class="fas fa-map-marker-alt mr-1 text-red-600"></i> {{ Str::limit($donor->address, 40) }}</p>
                                    <p class="text-sm text-gray-600"><i class="fas fa-phone mr-1 text-red-600"></i> {{ $donor->phone }}</p>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="p-8 text-center text-gray-500">
                            <i class="fas fa-users block text-4xl text-gray-300 mb-2"></i>
                            <p class="font-medium">No available donors</p>
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Requests List -->
            <div class="bg-white rounded-lg shadow overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                    <h3 class="text-lg font-bold text-gray-900"><i class="fas fa-hand-holding-medical text-orange-600 mr-2"></i>Pending Requests</h3>
                </div>
                <div class="divide-y divide-gray-200 max-h-96 overflow-y-auto">
                    @forelse($requests as $request)
                        <div class="p-4 hover:bg-gray-50" data-testid="request-marker-{{ $request->id }}">
                            <div class="flex items-start justify-between gap-3">
                                <div class="flex-1">
                                    <div class="flex items-center gap-2 mb-2">
                                        <span class="inline-block bg-orange-100 text-orange-800 px-3 py-1 rounded-full text-sm font-bold">
                                            {{ $request->blood_type }}
                                        </span>
                                        <span class="inline-block rounded-full text-sm font-medium {{ $request->urgency_level == 'critical' ? 'bg-red-100 text-red-800' : ($request->urgency_level == 'high' ? 'bg-orange-100 text-orange-800' : 'bg-yellow-100 text-yellow-800') }} px-3 py-1">
                                            {{ ucfirst($request->urgency_level) }}
                                        </span>
                                    </div>
                                    <h4 class="font-semibold text-gray-900">{{ $request->hospital->name }}</h4>
                                    <p class="text-sm text-gray-600 mt-1"><i class="fas fa-droplet mr-1 text-orange-600"></i> {{ $request->quantity }} units needed</p>
                                    <p class="text-sm text-gray-600"><i class="fas fa-map-marker-alt mr-1 text-orange-600"></i> {{ Str::limit($request->location, 40) }}</p>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="p-8 text-center text-gray-500">
                            <i class="fas fa-hand-holding-medical block text-4xl text-gray-300 mb-2"></i>
                            <p class="font-medium">No pending requests</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize map (centered on Philippines as default)
    const map = L.map('map').setView([14.5995, 120.9842], 6);

    // Add tile layer
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors'
    }).addTo(map);

    // Add donor markers
    const donorIcon = L.divIcon({
        className: 'custom-marker donor-map-marker',
        html: '<div style="background: #dc2626; width: 30px; height: 30px; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-weight: bold; border: 3px solid white; box-shadow: 0 2px 8px rgba(0,0,0,0.3);"><i class="fas fa-user" style="font-size: 14px;"></i></div>',
        iconSize: [30, 30]
    });

    @foreach($donors as $donor)
        @if($donor->latitude && $donor->longitude)
            L.marker([{{ $donor->latitude }}, {{ $donor->longitude }}], { icon: donorIcon })
                .addTo(map)
                .bindPopup(`
                    <div style="min-width: 200px;">
                        <h4 style="margin: 0 0 8px 0; color: #dc2626;"><i class="fas fa-user-circle"></i> {{ $donor->user->name }}</h4>
                        <p style="margin: 4px 0;"><strong>Blood Type:</strong> <span class="blood-badge">{{ $donor->blood_type }}</span></p>
                        <p style="margin: 4px 0;"><strong>Phone:</strong> {{ $donor->phone }}</p>
                        <p style="margin: 4px 0;"><strong>Address:</strong> {{ $donor->address }}</p>
                    </div>
                `);
        @endif
    @endforeach

    // Add request markers
    const requestIcon = L.divIcon({
        className: 'custom-marker request-map-marker',
        html: '<div style="background: #ea580c; width: 30px; height: 30px; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-weight: bold; border: 3px solid white; box-shadow: 0 2px 8px rgba(0,0,0,0.3);"><i class="fas fa-hospital" style="font-size: 14px;"></i></div>',
        iconSize: [30, 30]
    });

    @foreach($requests as $request)
        @if($request->latitude && $request->longitude)
            L.marker([{{ $request->latitude }}, {{ $request->longitude }}], { icon: requestIcon })
                .addTo(map)
                .bindPopup(`
                    <div style="min-width: 200px;">
                        <h4 style="margin: 0 0 8px 0; color: #ea580c;"><i class="fas fa-hospital"></i> {{ $request->hospital->name }}</h4>
                        <p style="margin: 4px 0;"><strong>Blood Type:</strong> <span class="blood-badge">{{ $request->blood_type }}</span></p>
                        <p style="margin: 4px 0;"><strong>Quantity:</strong> {{ $request->quantity }} units</p>
                        <p style="margin: 4px 0;"><strong>Urgency:</strong> <span class="urgency-badge urgency-{{ $request->urgency_level }}">{{ ucfirst($request->urgency_level) }}</span></p>
                        <p style="margin: 4px 0;"><strong>Location:</strong> {{ $request->location }}</p>
                    </div>
                `);
        @endif
    @endforeach

    // Auto-fit bounds if markers exist
    const allMarkers = [];
    @foreach($donors as $donor)
        @if($donor->latitude && $donor->longitude)
            allMarkers.push([{{ $donor->latitude }}, {{ $donor->longitude }}]);
        @endif
    @endforeach
    @foreach($requests as $request)
        @if($request->latitude && $request->longitude)
            allMarkers.push([{{ $request->latitude }}, {{ $request->longitude }}]);
        @endif
    @endforeach

    if (allMarkers.length > 0) {
        const bounds = L.latLngBounds(allMarkers);
        map.fitBounds(bounds, { padding: [50, 50] });
    }
});
</script>
@endpush
@endsection