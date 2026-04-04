<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Blood Donation System</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="h-screen overflow-hidden bg-gray-100">
    <div class="h-screen flex items-center justify-center py-8 px-4 sm:px-6 lg:px-8 relative">
        <a href="{{ route('home') }}" class="absolute top-4 left-4 text-red-600 hover:text-red-500">
            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
            </svg>
        </a>
        <div class="max-w-md w-full overflow-auto" style="max-height: calc(100vh - 2rem);">
            <div class="bg-white p-6 sm:p-8 rounded-xl shadow-lg border border-gray-200">
                <div class="flex justify-center">
                    <img src="{{ asset('images/lifelink-logo.svg') }}" alt="LifeLink Logo" class="lifelink-logo-form" />
                </div>
                <h2 class="mt-4 text-center text-2xl sm:text-3xl font-bold text-gray-900">Create your account</h2>

                <form class="mt-6 space-y-4" method="POST" action="{{ route('register') }}">
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

                <div class="space-y-4">
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700">Full Name</label>
                        <input id="name" name="name" type="text" required 
                               class="mt-1 appearance-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-md focus:outline-none focus:ring-red-500 focus:border-red-500 sm:text-sm" 
                               placeholder="First & Last Name" value="{{ old('name') }}">
                    </div>

                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700">Email Address</label>
                        <input id="email" name="email" type="email" required 
                               class="mt-1 appearance-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-md focus:outline-none focus:ring-red-500 focus:border-red-500 sm:text-sm" 
                               placeholder="yourname@example.com" value="{{ old('email') }}">
                    </div>

                    <div>
                        <label for="role" class="block text-sm font-medium text-gray-700">Register As</label>
                        <select id="role" name="role" required 
                                class="mt-1 block w-full px-3 py-2 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-red-500 focus:border-red-500 sm:text-sm">
                            <option value="donor" {{ old('role') == 'donor' ? 'selected' : '' }}>Blood Donor</option>
                            <option value="hospital" {{ old('role') == 'hospital' ? 'selected' : '' }}>Hospital / Medical Facility</option>
                        </select>
                    </div>

                    <div class="relative">
                        <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                        <input id="password" name="password" type="password" required 
                               class="peer mt-1 appearance-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-md focus:outline-none focus:ring-red-500 focus:border-red-500 sm:text-sm" 
                               placeholder="••••••••" aria-describedby="passwordRequirement">
                        <div id="passwordRequirement" role="tooltip" class="invisible opacity-0 absolute left-0 top-full z-10 mt-2 w-full rounded-md bg-gray-900 p-2 text-xs text-white shadow-lg ring-1 ring-black/20 transition-all duration-150 peer-focus:visible peer-focus:opacity-100 peer-focus:translate-y-0 peer-focus:scale-100 peer-focus:-translate-y-0">
                            Password must be at least 8 characters and contain at least one lowercase letter, one uppercase letter, one number, and one special character (@$!%*#?&).
                        </div>
                    </div>

                    <div>
                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Confirm Password</label>
                        <input id="password_confirmation" name="password_confirmation" type="password" required 
                               class="mt-1 appearance-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-md focus:outline-none focus:ring-red-500 focus:border-red-500 sm:text-sm" 
                               placeholder="••••••••">
                    </div>
                </div>

                <div>
                    <button type="submit" 
                            class="group relative w-full flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 cursor-pointer transition duration-200">
                        Create Account
                    </button>
                </div>

                <div class="text-center">
                    <button type="button" onclick="window.location.href='{{ route('login') }}'" class="group relative w-full flex justify-center py-2 px-4 border border-red-600 text-sm font-medium rounded-md text-red-600 bg-white hover:bg-red-50 hover:text-red-700 hover:border-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 cursor-pointer transition duration-200">
                        I already have an account
                    </button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>


