<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Donor Profile - Blood Donation System</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50">
    <div class="min-h-screen py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-2xl mx-auto">
            <!-- Header -->
            <div class="mb-8">
                <div class="flex justify-center mb-6">
                    <svg class="h-12 w-12 text-red-600" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/>
                    </svg>
                </div>
                <h1 class="text-center text-3xl font-bold text-gray-900">Donor Profile</h1>
                <p class="text-center text-gray-600 mt-2">Manage your personal information and donation preferences</p>
            </div>

            <!-- Form Card -->
            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                <form action="{{ route('donor.profile.update') }}" method="POST" data-testid="profile-form" class="space-y-6 p-8" enctype="multipart/form-data">
                    @csrf

                    @if ($errors->any())
                        <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded">
                            <ul class="list-disc list-inside text-sm">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <!-- Personal Information Section -->
                    <div class="border-b pb-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Personal Information</h3>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <!-- Blood Type -->
                            <div>
                                <label for="blood_type" class="block text-sm font-medium text-gray-700">Blood Type *</label>
                                <select name="blood_type" id="blood_type" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-red-500 focus:border-red-500 sm:text-sm" required data-testid="blood-type-select">
                                    <option value="">Select Blood Type</option>
                                    @foreach(['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'] as $type)
                                        <option value="{{ $type }}" {{ old('blood_type', $donor?->blood_type ?? '') == $type ? 'selected' : '' }}>
                                            {{ $type }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('blood_type')
                                    <span class="text-red-600 text-sm mt-1 block">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Date of Birth -->
                            <div>
                                <label for="date_of_birth" class="block text-sm font-medium text-gray-700">Date of Birth *</label>
                                <input type="date" name="date_of_birth" id="date_of_birth" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-red-500 focus:border-red-500 sm:text-sm" 
                                       value="{{ old('date_of_birth', $donor?->date_of_birth?->format('Y-m-d') ?? '') }}" 
                                       required data-testid="dob-input">
                                @error('date_of_birth')
                                    <span class="text-red-600 text-sm mt-1 block">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Gender -->
                            <div>
                                <label for="gender" class="block text-sm font-medium text-gray-700">Gender *</label>
                                <select name="gender" id="gender" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-red-500 focus:border-red-500 sm:text-sm" required data-testid="gender-select">
                                    <option value="">Select Gender</option>
                                    @foreach(['male', 'female', 'other'] as $gender)
                                        <option value="{{ $gender }}" {{ old('gender', $donor?->gender ?? '') == $gender ? 'selected' : '' }}>
                                            {{ ucfirst($gender) }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('gender')
                                    <span class="text-red-600 text-sm mt-1 block">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Phone -->
                            <div>
                                <label for="phone" class="block text-sm font-medium text-gray-700">Phone Number *</label>
                                <input type="tel" name="phone" id="phone" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-red-500 focus:border-red-500 sm:text-sm" 
                                       value="{{ old('phone', $donor?->phone ?? '') }}" 
                                       placeholder="+1 234 567 8900" required data-testid="phone-input">
                                @error('phone')
                                    <span class="text-red-600 text-sm mt-1 block">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <!-- Address -->
                        <div class="mt-4">
                            <label for="address" class="block text-sm font-medium text-gray-700">Address *</label>
                            <textarea name="address" id="address" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-red-500 focus:border-red-500 sm:text-sm" rows="3" 
                                      placeholder="Enter your complete address" required data-testid="address-input">{{ old('address', $donor?->address ?? '') }}</textarea>
                            @error('address')
                                <span class="text-red-600 text-sm mt-1 block">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <!-- Location Details Section -->
                    <div class="border-b pb-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">Location Details</h3>
                        <p class="text-gray-600 text-sm mb-4">Help us match you with nearby blood requests</p>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                            <!-- Latitude -->
                            <div>
                                <label for="latitude" class="block text-sm font-medium text-gray-700">Latitude</label>
                                <input type="number" step="any" name="latitude" id="latitude" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-red-500 focus:border-red-500 sm:text-sm" 
                                       value="{{ old('latitude', $donor?->latitude ?? '') }}" 
                                       placeholder="e.g., 14.5995" data-testid="latitude-input">
                                @error('latitude')
                                    <span class="text-red-600 text-sm mt-1 block">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Longitude -->
                            <div>
                                <label for="longitude" class="block text-sm font-medium text-gray-700">Longitude</label>
                                <input type="number" step="any" name="longitude" id="longitude" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-red-500 focus:border-red-500 sm:text-sm" 
                                       value="{{ old('longitude', $donor?->longitude ?? '') }}" 
                                       placeholder="e.g., 120.9842" data-testid="longitude-input">
                                @error('longitude')
                                    <span class="text-red-600 text-sm mt-1 block">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <button type="button" class="w-full px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 font-medium text-sm focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500" onclick="getLocation()" data-testid="get-location-btn">
                            <svg class="inline w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.42 0-8-3.58-8-8s3.58-8 8-8 8 3.58 8 8-3.58 8-8 8zm0-13c-2.76 0-5 2.24-5 5s2.24 5 5 5 5-2.24 5-5-2.24-5-5-5z"/></svg>
                            Use My Current Location
                        </button>
                    </div>

                    <!-- Additional Information Section -->
                    <div class="pb-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Additional Information</h3>
                        
                        <!-- Emergency Contact -->
                        <div class="mb-4">
                            <label for="emergency_contact" class="block text-sm font-medium text-gray-700">Emergency Contact</label>
                            <input type="tel" name="emergency_contact" id="emergency_contact" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-red-500 focus:border-red-500 sm:text-sm" 
                                   value="{{ old('emergency_contact', $donor?->emergency_contact ?? '') }}" 
                                   placeholder="Emergency contact number" data-testid="emergency-contact-input">
                            @error('emergency_contact')
                                <span class="text-red-600 text-sm mt-1 block">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Medical Conditions -->
                        <div>
                            <label for="medical_conditions" class="block text-sm font-medium text-gray-700">Medical Conditions</label>
                            <textarea name="medical_conditions" id="medical_conditions" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-red-500 focus:border-red-500 sm:text-sm" rows="4" 
                                      placeholder="List any medical conditions or medications" data-testid="medical-conditions-input">{{ old('medical_conditions', $donor?->medical_conditions ?? '') }}</textarea>
                            @error('medical_conditions')
                                <span class="text-red-600 text-sm mt-1 block">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <!-- Blood Type Verification Section -->
                    <div class="pb-6 border-b">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Blood Type Verification</h3>
                        <p class="text-gray-600 text-sm mb-4">Upload a blood test report or medical document to verify your blood type information</p>
                        
                        @if ($donor?->verification_document_path)
                            <div class="mb-4 p-4 bg-blue-50 border border-blue-200 rounded-md">
                                <div class="flex items-start gap-3">
                                    <svg class="w-5 h-5 text-blue-600 mt-0.5 shrink-0" fill="currentColor" viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8l-6-6z"/></svg>
                                    <div class="flex-1">
                                        <p class="text-sm font-medium text-blue-900">Verification Document Uploaded</p>
                                        @if ($donor->is_verified)
                                            <p class="text-sm text-blue-700 mt-1">
                                                <span class="inline-flex items-center gap-1">
                                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41L9 16.17z"/></svg>
                                                    Verified on {{ $donor->verified_at?->format('M d, Y') }}
                                                </span>
                                            </p>
                                        @else
                                            <p class="text-sm text-blue-700 mt-1">
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
                            <label for="verification_document" class="block text-sm font-medium text-gray-700 mb-2">Upload Verification Document *</label>
                            <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-md hover:border-red-400 transition" data-testid="document-upload-area">
                                <div class="space-y-1 text-center">
                                    <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48" aria-hidden="true">
                                        <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-12l-3.172-3.172a4 4 0 00-5.656 0L28 20M9 20l3.172-3.172a4 4 0 015.656 0L28 20" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                    <div class="flex text-sm text-gray-600">
                                        <label for="verification_document" class="relative cursor-pointer bg-white rounded-md font-medium text-red-600 hover:text-red-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-red-500">
                                            <span>Click to upload</span>
                                            <input id="verification_document" name="verification_document" type="file" class="sr-only" accept="image/*,.pdf" data-testid="verification-document-input">
                                        </label>
                                        <p class="pl-1">or drag and drop</p>
                                    </div>
                                    <p class="text-xs text-gray-500">PDF, JPG, PNG up to 10MB</p>
                                </div>
                            </div>
                            <div id="fileName" class="mt-2 text-sm text-gray-600"></div>
                            @error('verification_document')
                                <span class="text-red-600 text-sm mt-1 block">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <!-- Form Actions -->
                    <div class="flex gap-4 pt-4">
                        <button type="submit" class="flex-1 px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 font-medium focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500" data-testid="save-profile-btn">
                            <svg class="inline w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 24 24"><path d="M17 3H5c-1.11 0-2 .9-2 2v14c0 1.1.89 2 2 2h14c1.1 0 2-.9 2-2V7l-4-4zm-5 16c-1.66 0-3-1.34-3-3s1.34-3 3-3 3 1.34 3 3-1.34 3-3 3zm3-10H5V5h10v4z"/></svg>
                            Save Profile
                        </button>
                        <a href="{{ route('dashboard') }}" class="flex-1 px-4 py-2 border border-gray-300 text-gray-700 rounded-md hover:bg-gray-50 font-medium focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 text-center">
                            Cancel
                        </a>
                    </div>
                </form>
            </div>

            <!-- Footer Link -->
            <div class="text-center mt-6">
                <a href="{{ route('dashboard') }}" class="text-sm text-red-600 hover:text-red-500">
                    ← Back to Dashboard
                </a>
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