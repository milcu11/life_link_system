<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Donor Profile - Blood Donation System</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="bg-gray-50">
    <!-- Navigation -->
    <nav class="bg-white border-b border-gray-200 sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-3 md:px-4 lg:px-8">
            <div class="flex justify-between items-center h-14 md:h-16">
                <a href="{{ route('dashboard') }}" class="flex items-center gap-1 md:gap-2">
                    <img src="{{ asset('images/lifelink-logo.svg') }}" alt="LifeLink Logo" class="lifelink-logo-nav" />
                </a>
                <div class="flex items-center gap-2 md:gap-4">
                    <a href="{{ route('donor.messages.index') }}" class="inline-flex items-center gap-2 text-gray-600 hover:text-red-600 font-medium px-3 py-2 rounded-md hover:bg-gray-100 transition text-xs md:text-base">
                        <i class="fas fa-envelope"></i>
                    </a>
                    <div style="width: 1px; height: 24px; background-color: #999;"></div>
                    <div class="relative pl-0">
                        <button id="profileDropdown" class="text-gray-600 hover:text-red-600 font-medium flex items-center gap-1 md:gap-2 focus:outline-none text-xs md:text-base cursor-pointer">
                            <i class="fas fa-user-circle"></i>
                            <span class="hidden sm:inline">{{ auth()->user()->name }}</span>
                            <i class="fas fa-chevron-down text-xs"></i>
                        </button>
                        <div id="profileMenu" class="absolute right-0 mt-2 w-40 md:w-48 bg-white rounded-md shadow-lg z-50 hidden">
                            <a href="{{ route('donor.profile') }}" class="block px-3 md:px-4 py-2 text-xs md:text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900">
                                <i class="fas fa-user-edit mr-2"></i>Edit Profile
                            </a>
                            <hr class="border-gray-200">
                            <form action="{{ route('logout') }}" method="POST" style="display: block;">
                                @csrf
                                <button type="submit" class="w-full text-left px-3 md:px-4 py-2 text-xs md:text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900">
                                    Sign Out
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <!-- Page Container -->
    <div class="min-h-screen py-8 md:py-12 px-3 md:px-4 lg:px-8">
        <div class="max-w-2xl mx-auto">
            <!-- Back Arrow Row - Simple, Non-intrusive -->
            <div class="back-row">
                <button onclick="window.history.back()" class="back-button text-red-600 hover:text-red-500 inline-flex cursor-pointer transition duration-200 hover:scale-110">
                    <i class="fas fa-arrow-left"></i>
                </button>
            </div>

            <!-- Form Card - Unchanged Layout and Sizing -->
            <div class="form-card bg-white rounded-lg shadow-md overflow-hidden -mt-4">
                <form action="{{ route('donor.profile.update') }}" method="POST" data-testid="profile-form" class="space-y-4 md:space-y-6 p-4 md:p-8" enctype="multipart/form-data">
                    <!-- Header -->
                    <div class="mb-10 md:mb-12 text-center pb-8 md:pb-10 border-b">
                        <div class="flex justify-center mb-4 md:mb-6">
                            <img src="{{ asset('images/lifelink-logo.svg') }}" alt="LifeLink Logo" class="lifelink-logo-form" />
                        </div>
                        <h1 class="text-2xl md:text-3xl font-bold text-gray-900">Donor Profile</h1>
                        <p class="text-gray-600 mt-1 md:mt-2 text-sm md:text-base">Manage your personal information and donation preferences</p>
                    </div>

                    @csrf

                    @if ($errors->any())
                        <div class="bg-red-50 border border-red-200 text-red-700 px-3 md:px-4 py-3 rounded text-xs md:text-sm">
                            <ul class="list-disc list-inside">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <!-- Personal Information Section -->
                    <div class="mt-12 md:mt-16 border-b pb-4 md:pb-6">
                        <h3 class="text-base md:text-lg font-semibold text-gray-900 mb-3 md:mb-4">Personal Information</h3>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3 md:gap-4">
                            <!-- Blood Type -->
                            <div>
                                <label for="blood_type" class="block text-xs md:text-sm font-medium text-gray-700">Blood Type *</label>
                                <select name="blood_type" id="blood_type" class="mt-1 block w-full px-3 py-2 text-xs md:text-sm border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-red-500 focus:border-red-500" required data-testid="blood-type-select">
                                    <option value="">Select Blood Type</option>
                                    @foreach(['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'] as $type)
                                        <option value="{{ $type }}" {{ old('blood_type', $donor?->blood_type ?? '') == $type ? 'selected' : '' }}>
                                            {{ $type }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('blood_type')
                                    <span class="text-red-600 text-xs md:text-sm mt-1 block">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Date of Birth -->
                            <div>
                                <label for="date_of_birth" class="block text-xs md:text-sm font-medium text-gray-700">Date of Birth *</label>
                                <input type="date" name="date_of_birth" id="date_of_birth" class="mt-1 block w-full px-3 py-2 text-xs md:text-sm border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-red-500 focus:border-red-500" 
                                       value="{{ old('date_of_birth', $donor?->date_of_birth?->format('Y-m-d') ?? '') }}" 
                                       required data-testid="dob-input">
                                @error('date_of_birth')
                                    <span class="text-red-600 text-xs md:text-sm mt-1 block">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Gender -->
                            <div>
                                <label for="gender" class="block text-xs md:text-sm font-medium text-gray-700">Gender *</label>
                                <select name="gender" id="gender" class="mt-1 block w-full px-3 py-2 text-xs md:text-sm border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-red-500 focus:border-red-500" required data-testid="gender-select">
                                    <option value="">Select Gender</option>
                                    @foreach(['male', 'female', 'other'] as $gender)
                                        <option value="{{ $gender }}" {{ old('gender', $donor?->gender ?? '') == $gender ? 'selected' : '' }}>
                                            {{ ucfirst($gender) }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('gender')
                                    <span class="text-red-600 text-xs md:text-sm mt-1 block">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Phone -->
                            <div>
                                <label for="phone" class="block text-xs md:text-sm font-medium text-gray-700">Phone Number *</label>
                                <input type="tel" name="phone" id="phone" class="mt-1 block w-full px-3 py-2 text-xs md:text-sm border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-red-500 focus:border-red-500" 
                                       value="{{ old('phone', $donor?->phone ?? '') }}" 
                                       placeholder="+63 9xx xxx xxxx" required data-testid="phone-input">
                                @error('phone')
                                    <span class="text-red-600 text-xs md:text-sm mt-1 block">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <!-- Address -->
                        <div class="mt-3 md:mt-4">
                            <label for="address" class="block text-xs md:text-sm font-medium text-gray-700">Address *</label>
                            <textarea name="address" id="address" class="mt-1 block w-full px-3 py-2 text-xs md:text-sm border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-red-500 focus:border-red-500" rows="3" 
                                      placeholder="Enter your complete address" required data-testid="address-input">{{ old('address', $donor?->address ?? '') }}</textarea>
                            @error('address')
                                <span class="text-red-600 text-xs md:text-sm mt-1 block">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <!-- Location Details Section -->
                    <div class="mt-12 md:mt-16 border-b pb-4 md:pb-6">
                        <h3 class="text-base md:text-lg font-semibold text-gray-900 mb-1 md:mb-2">Location Details</h3>
                        <p class="text-gray-600 text-xs md:text-sm mb-3 md:mb-4">Help us match you with nearby blood requests</p>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3 md:gap-4 mb-3 md:mb-4">
                            <!-- Latitude -->
                            <div>
                                <label for="latitude" class="block text-xs md:text-sm font-medium text-gray-700">Latitude</label>
                                <input type="number" step="any" name="latitude" id="latitude" class="mt-1 block w-full px-3 py-2 text-xs md:text-sm border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-red-500 focus:border-red-500" 
                                       value="{{ old('latitude', $donor?->latitude ?? '') }}" 
                                       placeholder="e.g., 14.5995" data-testid="latitude-input">
                                @error('latitude')
                                    <span class="text-red-600 text-xs md:text-sm mt-1 block">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Longitude -->
                            <div>
                                <label for="longitude" class="block text-xs md:text-sm font-medium text-gray-700">Longitude</label>
                                <input type="number" step="any" name="longitude" id="longitude" class="mt-1 block w-full px-3 py-2 text-xs md:text-sm border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-red-500 focus:border-red-500" 
                                       value="{{ old('longitude', $donor?->longitude ?? '') }}" 
                                       placeholder="e.g., 120.9842" data-testid="longitude-input">
                                @error('longitude')
                                    <span class="text-red-600 text-xs md:text-sm mt-1 block">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <button type="button" class="w-full px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 font-medium text-xs md:text-sm focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500" onclick="getLocation()" data-testid="get-location-btn">
                            <svg class="inline w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.42 0-8-3.58-8-8s3.58-8 8-8 8 3.58 8 8-3.58 8-8 8zm0-13c-2.76 0-5 2.24-5 5s2.24 5 5 5 5-2.24 5-5-2.24-5-5-5z"/></svg>
                            Use My Current Location
                        </button>
                    </div>

                    <!-- Additional Information Section -->
                    <div class="mt-12 md:mt-16 pb-4 md:pb-6">
                        <h3 class="text-base md:text-lg font-semibold text-gray-900 mb-3 md:mb-4">Additional Information</h3>
                        
                        <!-- Emergency Contact -->
                        <div class="mb-3 md:mb-4">
                            <label for="emergency_contact" class="block text-xs md:text-sm font-medium text-gray-700">Emergency Contact</label>
                            <input type="tel" name="emergency_contact" id="emergency_contact" class="mt-1 block w-full px-3 py-2 text-xs md:text-sm border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-red-500 focus:border-red-500" 
                                   value="{{ old('emergency_contact', $donor?->emergency_contact ?? '') }}" 
                                   placeholder="Emergency contact number" data-testid="emergency-contact-input">
                            @error('emergency_contact')
                                <span class="text-red-600 text-xs md:text-sm mt-1 block">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Medical Conditions -->
                        <div>
                            <label for="medical_conditions" class="block text-xs md:text-sm font-medium text-gray-700">Medical Conditions</label>
                            <textarea name="medical_conditions" id="medical_conditions" class="mt-1 block w-full px-3 py-2 text-xs md:text-sm border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-red-500 focus:border-red-500" rows="4" 
                                      placeholder="List any medical conditions or medications" data-testid="medical-conditions-input">{{ old('medical_conditions', $donor?->medical_conditions ?? '') }}</textarea>
                            @error('medical_conditions')
                                <span class="text-red-600 text-xs md:text-sm mt-1 block">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <!-- Blood Type Verification Section -->
                    <div class="mt-12 md:mt-16 pb-4 md:pb-6 border-b">
                        <h3 class="text-base md:text-lg font-semibold text-gray-900 mb-3 md:mb-4">Blood Type Verification</h3>
                        <p class="text-gray-600 text-xs md:text-sm mb-3 md:mb-4">Upload a blood test report or medical document to verify your blood type information</p>
                        
                        @if ($donor?->verification_document_path)
                            <div class="mb-3 md:mb-4 p-3 md:p-4 bg-blue-50 border border-blue-200 rounded-md">
                                <div class="flex items-start gap-3">
                                    <svg class="w-5 h-5 text-blue-600 mt-0.5 shrink-0" fill="currentColor" viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8l-6-6z"/></svg>
                                    <div class="flex-1">
                                        <p class="text-xs md:text-sm font-medium text-blue-900">Verification Document Uploaded</p>
                                        @if ($donor->is_verified)
                                            <p class="text-xs md:text-sm text-blue-700 mt-1">
                                                <span class="inline-flex items-center gap-1">
                                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41L9 16.17z"/></svg>
                                                    Verified on {{ $donor->verified_at?->format('M d, Y') }}
                                                </span>
                                            </p>
                                        @else
                                            <p class="text-xs md:text-sm text-blue-700 mt-1">
                                                <span class="inline-flex items-center gap-1">
                                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M1 21h22L12 2 1 21zm12-3h-2v-2h2v2zm0-4h-2v-4h2v4z"/></svg>
                                                    Pending verification by admin
                                                </span>
                                            </p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endif

                        <div>
                            <label for="verification_document" class="block text-xs md:text-sm font-medium text-gray-700 mb-2">Upload Verification Document *</label>
                            <div class="mt-1 flex justify-center px-3 md:px-6 pt-4 md:pt-5 pb-4 md:pb-6 border-2 border-gray-300 border-dashed rounded-md hover:border-red-400 transition" data-testid="document-upload-area">
                                <div class="space-y-1 text-center">
                                    <svg class="mx-auto h-8 md:h-12 w-8 md:w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48" aria-hidden="true">
                                        <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-12l-3.172-3.172a4 4 0 00-5.656 0L28 20M9 20l3.172-3.172a4 4 0 015.656 0L28 20" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                    <div class="flex flex-col md:flex-row md:justify-center text-xs md:text-sm text-gray-600 gap-1">
                                        <label for="verification_document" class="relative cursor-pointer bg-white rounded-md font-medium text-red-600 hover:text-red-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-red-500">
                                            <span>Click to upload</span>
                                            <input id="verification_document" name="verification_document" type="file" class="sr-only" accept="image/*,.pdf" data-testid="verification-document-input">
                                        </label>
                                        <p class="hidden md:inline">or drag and drop</p>
                                    </div>
                                    <p class="text-xs text-gray-500">PDF, JPG, PNG up to 10MB</p>
                                </div>
                            </div>
                            <div id="fileName" class="mt-2 text-xs md:text-sm text-gray-600"></div>
                            @error('verification_document')
                                <span class="text-red-600 text-xs md:text-sm mt-1 block">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <!-- Form Actions -->
                    <div class="flex flex-col md:flex-row gap-3 md:gap-4 pt-4">
                        <button type="submit" class="flex-1 px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 font-medium text-xs md:text-sm focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 cursor-pointer transition duration-200" data-testid="save-profile-btn">
                            <svg class="inline w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 24 24"><path d="M17 3H5c-1.11 0-2 .9-2 2v14c0 1.1.89 2 2 2h14c1.1 0 2-.9 2-2V7l-4-4zm-5 16c-1.66 0-3-1.34-3-3s1.34-3 3-3 3 1.34 3 3-1.34 3-3 3zm3-10H5V5h10v4z"/></svg>
                            Save Profile
                        </button>
                        <a href="{{ route('dashboard') }}" class="flex-1 px-4 py-2 border border-gray-300 text-gray-700 rounded-md hover:bg-gray-50 font-medium text-xs md:text-sm focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 text-center">
                            Cancel
                        </a>
                    </div>
                </form>
            </div>
            </div>
        </div>

    </div>

    <script>
        function goBack() {
            window.history.back();
        }

        // Dropdown toggle functionality
        const profileDropdown = document.getElementById('profileDropdown');
        const profileMenu = document.getElementById('profileMenu');

        profileDropdown.addEventListener('click', function(e) {
            e.stopPropagation();
            profileMenu.classList.toggle('hidden');
        });

        // Close dropdown when clicking outside
        document.addEventListener('click', function(e) {
            if (!profileDropdown.contains(e.target) && !profileMenu.contains(e.target)) {
                profileMenu.classList.add('hidden');
            }
        });

        // Close dropdown on escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                profileMenu.classList.add('hidden');
            }
        });

        function getLocation() {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(function(position) {
                    document.getElementById('latitude').value = position.coords.latitude;
                    document.getElementById('longitude').value = position.coords.longitude;
                }, function(error) {
                    alert('Unable to get your location. Please enter manually.');
                });
            } else {
                alert('Geolocation is not supported by your browser.');
            }
        }

        // Handle file input
        document.getElementById('verification_document').addEventListener('change', function(e) {
            const fileName = e.target.files[0]?.name || '';
            const fileNameDiv = document.getElementById('fileName');
            if (fileName) {
                fileNameDiv.textContent = '✓ Selected: ' + fileName;
                fileNameDiv.classList.add('text-green-600', 'font-medium');
            } else {
                fileNameDiv.textContent = '';
                fileNameDiv.classList.remove('text-green-600', 'font-medium');
            }
        });

        // Drag and drop functionality
        const uploadArea = document.querySelector('[data-testid="document-upload-area"]');
        if (uploadArea) {
            ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
                uploadArea.addEventListener(eventName, preventDefaults, false);
            });

            function preventDefaults(e) {
                e.preventDefault();
                e.stopPropagation();
            }

            ['dragenter', 'dragover'].forEach(eventName => {
                uploadArea.addEventListener(eventName, highlight, false);
            });

            ['dragleave', 'drop'].forEach(eventName => {
                uploadArea.addEventListener(eventName, unhighlight, false);
            });

            function highlight(e) {
                uploadArea.classList.add('border-red-400', 'bg-red-50');
            }

            function unhighlight(e) {
                uploadArea.classList.remove('border-red-400', 'bg-red-50');
            }

            uploadArea.addEventListener('drop', handleDrop, false);

            function handleDrop(e) {
                const dt = e.dataTransfer;
                const files = dt.files;
                document.getElementById('verification_document').files = files;
                
                // Trigger change event
                const event = new Event('change', { bubbles: true });
                document.getElementById('verification_document').dispatchEvent(event);
            }
        }
    </script>
</body>
</html>


