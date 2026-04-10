<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Blood Stock - LifeLink</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="bg-gray-50">
    <nav class="bg-white border-b border-gray-200 sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <div class="flex items-center gap-2">
                    <a href="{{ route('dashboard') }}" class="flex items-center gap-2 hover:opacity-80 transition" title="LifeLink - Blood Donation System">
                        <img src="{{ asset('images/lifelink-logo.svg') }}" alt="LifeLink Logo" class="lifelink-logo-nav" />
                    </a>
                </div>
                <div class="flex items-center gap-4">
                    <a href="{{ route('hospital.inventory.index') }}" class="text-gray-600 hover:text-red-600 font-medium">
                        <i class="fas fa-arrow-left mr-2"></i>Back to Inventory
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <div class="min-h-screen py-8">
        <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="mb-4">
                <button onclick="window.history.back()" class="back-button text-red-600 hover:text-red-500 inline-flex items-center cursor-pointer transition duration-200 hover:scale-110">
                    <i class="fas fa-arrow-left"></i>
                </button>
            </div>
            <div class="bg-white rounded-lg shadow p-6">
                <h1 class="text-2xl font-bold text-gray-900 mb-6">Add Blood Stock</h1>

                <form action="{{ route('hospital.inventory.store') }}" method="POST">
                    @csrf

                    <div class="mb-4">
                        <label for="blood_type" class="block text-sm font-medium text-gray-700 mb-2">Blood Type</label>
                        <select id="blood_type" name="blood_type" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-red-500 focus:border-red-500" required>
                            <option value="">Select blood type</option>
                            @foreach($bloodTypes as $type)
                                <option value="{{ $type }}" {{ old('blood_type') == $type ? 'selected' : '' }}>{{ $type }}</option>
                            @endforeach
                        </select>
                        @error('blood_type')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="quantity" class="block text-sm font-medium text-gray-700 mb-2">Quantity (Units)</label>
                        <input type="number" id="quantity" name="quantity" value="{{ old('quantity') }}" min="1" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-red-500 focus:border-red-500" required>
                        @error('quantity')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-6">
                        <label for="expiration_date" class="block text-sm font-medium text-gray-700 mb-2">Expiration Date</label>
                        <input type="date" id="expiration_date" name="expiration_date" value="{{ old('expiration_date') }}" min="{{ date('Y-m-d', strtotime('+1 day')) }}" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-red-500 focus:border-red-500" required>
                        @error('expiration_date')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex justify-end gap-4">
                        <a href="{{ route('hospital.inventory.index') }}" class="px-4 py-2 text-gray-600 hover:text-gray-800">Cancel</a>
                        <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700">
                            <i class="fas fa-save mr-2"></i>Add Stock
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
<script>
    function goBack() {
        window.history.back();
    }
</script>
</html>