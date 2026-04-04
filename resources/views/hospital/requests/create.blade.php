<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Blood Request - Hospital</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="bg-gray-50">
    <nav class="bg-white border-b border-gray-200 sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <a href="{{ route('dashboard') }}" class="flex items-center gap-2">
                    <img src="{{ asset('images/lifelink-logo.svg') }}" alt="LifeLink Logo" class="lifelink-logo-nav" />
                </a>
                <div class="flex items-center gap-4">
                    <a href="{{ route('hospital.messages.index') }}" class="inline-flex items-center gap-2 text-gray-600 hover:text-red-600 font-medium px-3 py-2 rounded-md hover:bg-gray-100 transition">
                        <i class="fas fa-envelope"></i>
                    </a>
                    <div style="width: 1px; height: 24px; background-color: #999;"></div>
                    <div class="relative pl-0">
                        <button id="menuDropdown" class="text-gray-600 hover:text-red-600 font-medium flex items-center gap-2 focus:outline-none cursor-pointer">
                            <i class="fas fa-bars"></i>Menu
                            <i class="fas fa-chevron-down text-xs"></i>
                        </button>
                        <div id="menuDropdownContent" class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg z-50 hidden">
                            <a href="{{ route('hospital.requests.index') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900">
                                <i class="fas fa-list mr-2"></i>My Requests
                            </a>
                            <a href="{{ route('dashboard') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900">
                                <i class="fas fa-tachometer-alt mr-2"></i>Dashboard
                            </a>
                            <a href="{{ route('hospital.inventory.index') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900">
                                <i class="fas fa-boxes mr-2"></i>Blood Inventory
                            </a>
                            <a href="{{ route('hospital.drives.index') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900">
                                <i class="fas fa-calendar-alt mr-2"></i>Blood Drives
                            </a>
                            <a href="{{ route('hospital.reports.index') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900">
                                <i class="fas fa-chart-bar mr-2"></i>Analytics & Reports
                            </a>
                            <hr class="border-gray-200">
                            <form action="{{ route('logout') }}" method="POST" style="display: block;">
                                @csrf
                                <button type="submit" class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900">
                                    Sign Out
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <div class="min-h-screen py-6 md:py-8">
        <div class="max-w-3xl mx-auto px-3 md:px-4 lg:px-8">
            <!-- Page Header -->
            <div class="mb-6 md:mb-8">
                <h1 class="text-2xl md:text-3xl font-bold text-gray-900">Create Blood Request</h1>
                <p class="text-sm md:text-base text-gray-600 mt-2">Submit a new blood donation request</p>
            </div>

            <!-- Form Card -->
            <div class="bg-white rounded-lg shadow-md p-4 md:p-8">
                <form action="{{ route('hospital.requests.store') }}" method="POST" class="space-y-8">
                    @csrf

                    <!-- Blood Requirements Section -->
                    <div>
                        <h3 class="text-base md:text-lg font-semibold text-gray-900 mb-3 md:mb-4 flex items-center gap-2">
                            <i class="fas fa-droplet text-red-600"></i> Blood Requirements
                        </h3>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3 md:gap-4">
                            <!-- Blood Type -->
                            <div>
                                <label for="blood_type" class="block text-xs md:text-sm font-medium text-gray-700 mb-1">
                                    Blood Type <span class="text-red-600">*</span>
                                </label>
                                <select name="blood_type" id="blood_type" class="w-full px-2 md:px-3 py-2 text-xs md:text-sm border rounded-md focus:outline-none focus:ring-2 focus:ring-red-500 {{ $errors->has('blood_type') ? 'border-red-500' : 'border-gray-300' }}" required>
                                    <option value="">Select Blood Type</option>
                                    @foreach(['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'] as $type)
                                        <option value="{{ $type }}" {{ old('blood_type') == $type ? 'selected' : '' }}>{{ $type }}</option>
                                    @endforeach
                                </select>
                                @error('blood_type')
                                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Quantity -->
                            <div>
                                <label for="quantity" class="block text-xs md:text-sm font-medium text-gray-700 mb-1">
                                    Quantity (units) <span class="text-red-600">*</span>
                                </label>
                                <input type="number" name="quantity" id="quantity" class="w-full px-2 md:px-3 py-2 text-xs md:text-sm border rounded-md focus:outline-none focus:ring-2 focus:ring-red-500 {{ $errors->has('quantity') ? 'border-red-500' : 'border-gray-300' }}" 
                                       value="{{ old('quantity', 1) }}" min="1" required>
                                @error('quantity')
                                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Urgency Level -->
                            <div>
                                <label for="urgency_level" class="block text-xs md:text-sm font-medium text-gray-700 mb-1">
                                    Urgency Level <span class="text-red-600">*</span>
                                </label>
                                <select name="urgency_level" id="urgency_level" class="w-full px-2 md:px-3 py-2 text-xs md:text-sm border rounded-md focus:outline-none focus:ring-2 focus:ring-red-500 {{ $errors->has('urgency_level') ? 'border-red-500' : 'border-gray-300' }}" required>
                                    <option value="">Select Urgency</option>
                                    <option value="low" {{ old('urgency_level') == 'low' ? 'selected' : '' }}>Low</option>
                                    <option value="medium" {{ old('urgency_level') == 'medium' ? 'selected' : '' }}>Medium</option>
                                    <option value="high" {{ old('urgency_level') == 'high' ? 'selected' : '' }}>High</option>
                                    <option value="critical" {{ old('urgency_level') == 'critical' ? 'selected' : '' }}>Critical</option>
                                </select>
                                @error('urgency_level')
                                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Needed By Date -->
                            <div>
                                <label for="needed_by" class="block text-xs md:text-sm font-medium text-gray-700 mb-1">
                                    Needed By (Optional)
                                </label>
                                <input type="date" name="needed_by" id="needed_by" class="w-full px-2 md:px-3 py-2 text-xs md:text-sm border rounded-md focus:outline-none focus:ring-2 focus:ring-red-500 {{ $errors->has('needed_by') ? 'border-red-500' : 'border-gray-300' }}" 
                                       value="{{ old('needed_by') }}">
                                @error('needed_by')
                                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <hr class="border-gray-200">

                    <!-- Location Details Section -->
                    <div>
                        <h3 class="text-base md:text-lg font-semibold text-gray-900 mb-3 md:mb-4 flex items-center gap-2">
                            <i class="fas fa-map-marker-alt text-red-600"></i> Location Details
                        </h3>

                        <!-- Location Address -->
                        <div class="mb-3 md:mb-4">
                            <label for="location" class="block text-xs md:text-sm font-medium text-gray-700 mb-1">
                                Location/Address <span class="text-red-600">*</span>
                            </label>
                            <textarea name="location" id="location" rows="3" class="w-full px-2 md:px-3 py-2 text-xs md:text-sm border rounded-md focus:outline-none focus:ring-2 focus:ring-red-500 {{ $errors->has('location') ? 'border-red-500' : 'border-gray-300' }}" 
                                      placeholder="Enter hospital address or location details" required>{{ old('location') }}</textarea>
                            @error('location')
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Coordinates Grid -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3 md:gap-4 mb-3 md:mb-4">
                            <div>
                                <label for="latitude" class="block text-xs md:text-sm font-medium text-gray-700 mb-1">
                                    Latitude <span class="text-red-600">*</span>
                                </label>
                                <input type="number" step="any" name="latitude" id="latitude" class="w-full px-2 md:px-3 py-2 text-xs md:text-sm border rounded-md focus:outline-none focus:ring-2 focus:ring-red-500 {{ $errors->has('latitude') ? 'border-red-500' : 'border-gray-300' }}" 
                                       value="{{ old('latitude') }}" placeholder="e.g., 14.5995" required>
                                @error('latitude')
                                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="longitude" class="block text-xs md:text-sm font-medium text-gray-700 mb-1">
                                    Longitude <span class="text-red-600">*</span>
                                </label>
                                <input type="number" step="any" name="longitude" id="longitude" class="w-full px-2 md:px-3 py-2 text-xs md:text-sm border rounded-md focus:outline-none focus:ring-2 focus:ring-red-500 {{ $errors->has('longitude') ? 'border-red-500' : 'border-gray-300' }}" 
                                       value="{{ old('longitude') }}" placeholder="e.g., 120.9842" required>
                                @error('longitude')
                                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Get Location Button -->
                        <button type="button" onclick="getLocation()" class="flex items-center gap-2 px-3 md:px-4 py-2 text-xs md:text-sm bg-red-100 text-red-700 rounded-md hover:bg-red-200 transition">
                            <i class="fas fa-compass"></i> Use Current Location
                        </button>
                    </div>

                    <hr class="border-gray-200">

                    <!-- Contact Information Section -->
                    <div>
                        <h3 class="text-base md:text-lg font-semibold text-gray-900 mb-3 md:mb-4 flex items-center gap-2">
                            <i class="fas fa-phone text-red-600"></i> Contact Information
                        </h3>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3 md:gap-4 mb-3 md:mb-4">
                            <!-- Contact Person -->
                            <div>
                                <label for="contact_person" class="block text-xs md:text-sm font-medium text-gray-700 mb-1">
                                    Contact Person <span class="text-red-600">*</span>
                                </label>
                                <input type="text" name="contact_person" id="contact_person" class="w-full px-2 md:px-3 py-2 text-xs md:text-sm border rounded-md focus:outline-none focus:ring-2 focus:ring-red-500 {{ $errors->has('contact_person') ? 'border-red-500' : 'border-gray-300' }}" 
                                       value="{{ old('contact_person') }}" placeholder="Name of contact person" required>
                                @error('contact_person')
                                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Contact Phone -->
                            <div>
                                <label for="contact_phone" class="block text-xs md:text-sm font-medium text-gray-700 mb-1">
                                    Contact Phone <span class="text-red-600">*</span>
                                </label>
                                <input type="tel" name="contact_phone" id="contact_phone" class="w-full px-2 md:px-3 py-2 text-xs md:text-sm border rounded-md focus:outline-none focus:ring-2 focus:ring-red-500 {{ $errors->has('contact_phone') ? 'border-red-500' : 'border-gray-300' }}" 
                                       value="{{ old('contact_phone') }}" placeholder="+1 234 567 8900" required>
                                @error('contact_phone')
                                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Patient Name -->
                        <div>
                            <label for="patient_name" class="block text-xs md:text-sm font-medium text-gray-700 mb-1">
                                Patient Name (Optional)
                            </label>
                            <input type="text" name="patient_name" id="patient_name" class="w-full px-2 md:px-3 py-2 text-xs md:text-sm border rounded-md focus:outline-none focus:ring-2 focus:ring-red-500 {{ $errors->has('patient_name') ? 'border-red-500' : 'border-gray-300' }}" 
                                   value="{{ old('patient_name') }}" placeholder="Patient name">
                            @error('patient_name')
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <hr class="border-gray-200">

                    <!-- Additional Information Section -->
                    <div>
                        <h3 class="text-base md:text-lg font-semibold text-gray-900 mb-3 md:mb-4 flex items-center gap-2">
                            <i class="fas fa-sticky-note text-red-600"></i> Additional Information
                        </h3>

                        <div>
                            <label for="notes" class="block text-xs md:text-sm font-medium text-gray-700 mb-1">
                                Notes/Special Instructions (Optional)
                            </label>
                            <textarea name="notes" id="notes" rows="4" class="w-full px-2 md:px-3 py-2 text-xs md:text-sm border rounded-md focus:outline-none focus:ring-2 focus:ring-red-500 {{ $errors->has('notes') ? 'border-red-500' : 'border-gray-300' }}" 
                                      placeholder="Any additional information or special requirements">{{ old('notes') }}</textarea>
                            @error('notes')
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Form Actions -->
                    <div class="flex flex-col md:flex-row gap-2 md:gap-3 pt-4">
                        <button type="submit" class="flex items-center justify-center gap-2 px-4 md:px-6 py-2 bg-red-600 text-white text-sm md:text-base rounded-md hover:bg-red-700 transition font-medium">
                            <i class="fas fa-paper-plane"></i> Submit Request
                        </button>
                        <a href="{{ route('hospital.requests.index') }}" class="flex items-center justify-center px-4 md:px-6 py-2 border border-gray-300 rounded-md text-gray-700 text-sm md:text-base hover:bg-gray-50 transition font-medium">
                            Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
    function getLocation() {
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(function(position) {
                document.getElementById('latitude').value = position.coords.latitude.toFixed(6);
                document.getElementById('longitude').value = position.coords.longitude.toFixed(6);
            }, function(error) {
                alert('Unable to get your location. Please enter manually.');
            });
        } else {
            alert('Geolocation is not supported by your browser.');
        }
    }

    // Dropdown toggle functionality
    const menuDropdown = document.getElementById('menuDropdown');
    const menuDropdownContent = document.getElementById('menuDropdownContent');

    menuDropdown.addEventListener('click', function(e) {
        e.stopPropagation();
        menuDropdownContent.classList.toggle('hidden');
    });

    // Close dropdown when clicking outside
    document.addEventListener('click', function(e) {
        if (!menuDropdown.contains(e.target) && !menuDropdownContent.contains(e.target)) {
            menuDropdownContent.classList.add('hidden');
        }
    });

    // Close dropdown on escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            menuDropdownContent.classList.add('hidden');
        }
    });
    </script>
</body>
</html>


