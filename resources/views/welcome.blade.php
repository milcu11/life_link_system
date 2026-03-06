<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blood Donation System - Save Lives</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .hero-bg {
            background: linear-gradient(135deg, #991B1B 0%, #DC2626 100%);
        }
        .card-hover:hover {
            transform: translateY(-4px);
            box-shadow: 0 10px 25px rgba(220, 38, 38, 0.2);
        }
    </style>
</head>
<body class="bg-gray-50">
    <!-- Navigation -->
    <nav class="bg-white shadow-md">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <svg class="h-8 w-8 text-red-600" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/>
                    </svg>
                    <span class="ml-2 text-xl font-bold text-gray-900">Blood Donation System</span>
                </div>
                <div class="flex items-center space-x-4">
                    @auth
                        <a href="{{ route('dashboard') }}" class="text-gray-700 hover:text-red-600 px-3 py-2 rounded-md font-medium">Dashboard</a>
                        <form method="POST" action="{{ route('logout') }}" class="inline">
                            @csrf
                            <button type="submit" class="text-gray-700 hover:text-red-600 px-3 py-2 rounded-md font-medium">Logout</button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="text-gray-700 hover:text-red-600 px-3 py-2 rounded-md font-medium">Login</a>
                        <a href="{{ route('register') }}" class="bg-red-600 text-white px-4 py-2 rounded-md font-medium hover:bg-red-700 transition">Register</a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <div class="hero-bg">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20">
            <div class="grid md:grid-cols-2 gap-12 items-center">
                <div class="text-white">
                    <h1 class="text-5xl font-bold mb-6">Save Lives Through Blood Donation</h1>
                    <p class="text-xl mb-8 text-red-100">Connect donors with those in need. Real-time matching, emergency alerts, and location-based donor tracking.</p>
                    <div class="flex space-x-4">
                        <a href="{{ route('register') }}" class="bg-white text-red-600 px-8 py-3 rounded-lg font-semibold hover:bg-red-50 transition">Become a Donor</a>
                        <a href="{{ route('login') }}" class="border-2 border-white text-white px-8 py-3 rounded-lg font-semibold hover:bg-white hover:text-red-600 transition">Hospital Login</a>
                    </div>
                </div>
                <div class="hidden md:block">
                    <img src="https://mgx-backend-cdn.metadl.com/generate/images/619966/2026-01-29/52854561-c9f3-4b7b-be10-42273bfdb102.png" alt="Blood Donation" class="rounded-lg shadow-2xl">
                </div>
            </div>
        </div>
    </div>

    <!-- Features Section -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
        <div class="text-center mb-12">
            <h2 class="text-4xl font-bold text-gray-900 mb-4">Platform Features</h2>
            <p class="text-xl text-gray-600">Advanced technology to connect donors and save lives</p>
        </div>

        <div class="grid md:grid-cols-3 gap-8">
            <!-- Feature 1 -->
            <div class="bg-white p-6 rounded-lg shadow-md card-hover transition-all duration-300">
                <div class="bg-red-100 w-16 h-16 rounded-full flex items-center justify-center mb-4">
                    <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-2">Location Tracking</h3>
                <p class="text-gray-600">Real-time geomapping to find the nearest available donors for urgent requests.</p>
            </div>

            <!-- Feature 2 -->
            <div class="bg-white p-6 rounded-lg shadow-md card-hover transition-all duration-300">
                <div class="bg-red-100 w-16 h-16 rounded-full flex items-center justify-center mb-4">
                    <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-2">Smart Matching</h3>
                <p class="text-gray-600">Automated blood type compatibility checking and donor-recipient matching.</p>
            </div>

            <!-- Feature 3 -->
            <div class="bg-white p-6 rounded-lg shadow-md card-hover transition-all duration-300">
                <div class="bg-red-100 w-16 h-16 rounded-full flex items-center justify-center mb-4">
                    <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-2">Emergency Alerts</h3>
                <p class="text-gray-600">Instant notifications via SMS, email, and in-app alerts for urgent blood needs.</p>
            </div>

            <!-- Feature 4 -->
            <div class="bg-white p-6 rounded-lg shadow-md card-hover transition-all duration-300">
                <div class="bg-red-100 w-16 h-16 rounded-full flex items-center justify-center mb-4">
                    <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-2">Donor Management</h3>
                <p class="text-gray-600">Complete profile management with donation history and availability tracking.</p>
            </div>

            <!-- Feature 5 -->
            <div class="bg-white p-6 rounded-lg shadow-md card-hover transition-all duration-300">
                <div class="bg-red-100 w-16 h-16 rounded-full flex items-center justify-center mb-4">
                    <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-2">Analytics & Reports</h3>
                <p class="text-gray-600">Comprehensive reporting and analytics for system monitoring and planning.</p>
            </div>

            <!-- Feature 6 -->
            <div class="bg-white p-6 rounded-lg shadow-md card-hover transition-all duration-300">
                <div class="bg-red-100 w-16 h-16 rounded-full flex items-center justify-center mb-4">
                    <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-2">Secure Access</h3>
                <p class="text-gray-600">Role-based authentication ensuring data privacy and system security.</p>
            </div>
        </div>
    </div>

    <!-- Stats Section -->
    <div class="bg-red-600 py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid md:grid-cols-4 gap-8 text-center text-white">
                <div>
                    <div class="text-5xl font-bold mb-2">1000+</div>
                    <div class="text-xl text-red-100">Registered Donors</div>
                </div>
                <div>
                    <div class="text-5xl font-bold mb-2">500+</div>
                    <div class="text-xl text-red-100">Lives Saved</div>
                </div>
                <div>
                    <div class="text-5xl font-bold mb-2">50+</div>
                    <div class="text-xl text-red-100">Partner Hospitals</div>
                </div>
                <div>
                    <div class="text-5xl font-bold mb-2">24/7</div>
                    <div class="text-xl text-red-100">Emergency Support</div>
                </div>
            </div>
        </div>
    </div>

<!-- CTA Section -->
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
    <div class="bg-linear-to-r from-red-50 to-red-100 rounded-2xl p-12 text-center">
        <h2 class="text-4xl font-bold text-gray-900 mb-4">Ready to Make a Difference?</h2>
        <p class="text-xl text-gray-600 mb-8">Join our community of life-savers today</p>
        <a href="{{ route('register') }}" class="inline-block bg-red-600 text-white px-8 py-4 rounded-lg font-semibold text-lg hover:bg-red-700 transition shadow-lg">
            Register Now
        </a>
    </div>
</div>


    <!-- Footer -->
    <footer class="bg-gray-900 text-white py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <p>&copy; 2024 Blood Donation System. All rights reserved.</p>
            <p class="text-gray-400 mt-2">Saving lives through technology and compassion</p>
        </div>
    </footer>
</body>
</html>