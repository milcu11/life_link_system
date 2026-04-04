<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify Code - Blood Donation System</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="h-screen overflow-hidden bg-gray-100">
    <div class="h-screen flex items-center justify-center py-8 px-4 sm:px-6 lg:px-8 relative">
        <a href="{{ route('password.forgot') }}" class="absolute top-4 left-4 text-red-600 hover:text-red-500">
            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
            </svg>
        </a>
        <div class="max-w-md w-full overflow-auto" style="max-height: calc(100vh - 2rem);">
            <div class="bg-white p-6 sm:p-8 rounded-xl shadow-lg border border-gray-200">
                <div class="flex justify-center">
                    <img src="{{ asset('images/lifelink-logo.svg') }}" alt="LifeLink Logo" class="lifelink-logo-form" />
                </div>
                <h2 class="mt-4 text-center text-2xl sm:text-3xl font-bold text-gray-900">Verify your identity</h2>
                <p class="mt-2 text-center text-sm text-gray-600">
                    Enter the verification code sent to<br>
                    <span class="font-semibold text-gray-900">{{ $email }}</span>
                </p>

                <!-- Success Message -->
                @if (session('success'))
                    <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded relative">
                        <span class="block sm:inline">{{ session('success') }}</span>
                    </div>
                @endif

                <!-- Error Message -->
                @if (session('error'))
                    <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded relative">
                        <span class="block sm:inline">{{ session('error') }}</span>
                    </div>
                @endif

                <!-- Validation Errors -->
                @if ($errors->any())
                    <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded">
                        <p class="font-semibold mb-2">Please fix the following errors:</p>
                        <ul class="list-disc list-inside">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form class="mt-6 space-y-4" method="POST" action="{{ route('password.verify.check') }}" id="verifyCodeForm">
                @csrf
                
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                    <div class="mt-1">
                        <input 
                            id="email" 
                            name="email" 
                            type="email" 
                            required 
                            readonly
                            class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md bg-gray-100 text-gray-900 sm:text-sm"
                            value="{{ $email }}"
                        >
                    </div>
                </div>

                <div>
                    <label for="code" class="block text-sm font-medium text-gray-700">Verification Code</label>
                    <p class="mt-1 text-xs text-gray-600">6-digit code sent to your email</p>
                    <div class="mt-2 relative">
                        <input 
                            id="code" 
                            name="code" 
                            type="text" 
                            required 
                            maxlength="6"
                            pattern="[0-9]{6}"
                            inputmode="numeric"
                            autocomplete="off"
                            class="appearance-none block w-full px-3 py-3 text-center text-3xl tracking-widest border-2 border-gray-300 rounded-md placeholder-gray-400 text-gray-900 focus:outline-none focus:ring-red-500 focus:border-red-500 transition-colors font-mono"
                            placeholder="000000"
                            value="{{ old('code') }}"
                        >
                        <span id="codeError" class="hidden text-red-500 text-xs mt-1"></span>
                    </div>
                    <p class="mt-2 text-xs text-gray-600 text-center">The code will expire in 10 minutes</p>
                </div>

                <div>
                    <button 
                        type="submit" 
                        id="submitBtn"
                        class="group relative w-full flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-all disabled:opacity-50 disabled:cursor-not-allowed"
                    >
                        <span id="submitText">Verify Code</span>
                        <span id="submitLoader" class="hidden ml-2">
                            <svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                        </span>
                    </button>
                </div>
            </form>

            <!-- Resend Code -->
            <div class="text-center">
                <p class="text-sm text-gray-600 mb-2">Didn't receive the code?</p>
                <form method="POST" action="{{ route('password.resend') }}" id="resendForm" class="inline">
                    @csrf
                    <input type="hidden" name="email" value="{{ $email }}">
                    <button 
                        type="submit" 
                        id="resendBtn"
                        class="font-medium text-red-600 hover:text-red-500 transition-colors disabled:opacity-50 disabled:cursor-not-allowed"
                    >
                        <span id="resendText">Resend Code</span>
                        <span id="resendTimer" class="hidden"> in <span id="timerValue">60</span>s</span>
                    </button>
                </form>
            </div>

            <!-- Back to Forgot Password -->
            <div class="text-center">
                <a href="{{ route('password.forgot') }}" class="font-medium text-red-600 hover:text-red-500">
                    Try a different email
                </a>
            </div>
        </div>
    </div>
</div>

    <script>
        // Auto-format code input to accept only digits
        const codeInput = document.getElementById('code');
        
        codeInput.addEventListener('input', function(e) {
            this.value = this.value.replace(/[^0-9]/g, '').substring(0, 6);
            document.getElementById('codeError').classList.add('hidden');
        });

        codeInput.addEventListener('keypress', function(e) {
            if (!/[0-9]/.test(String.fromCharCode(e.which))) {
                e.preventDefault();
            }
        });

        // Form submission
        document.getElementById('verifyCodeForm').addEventListener('submit', function(e) {
            const code = document.getElementById('code').value;
            if (code.length !== 6) {
                e.preventDefault();
                document.getElementById('codeError').textContent = 'Code must be 6 digits';
                document.getElementById('codeError').classList.remove('hidden');
                return;
            }

            const submitBtn = document.getElementById('submitBtn');
            const submitText = document.getElementById('submitText');
            const submitLoader = document.getElementById('submitLoader');

            submitBtn.disabled = true;
            submitText.classList.add('hidden');
            submitLoader.classList.remove('hidden');
        });

        // Resend code timer
        let resendTimer = localStorage.getItem('resend_timer_' + '{{ $email }}');
        if (resendTimer && parseInt(resendTimer) > 0) {
            startResendTimer(parseInt(resendTimer));
        }

        function startResendTimer(remainingSeconds) {
            const resendBtn = document.getElementById('resendBtn');
            const resendText = document.getElementById('resendText');
            const resendTimer = document.getElementById('resendTimer');
            const timerValue = document.getElementById('timerValue');

            resendBtn.disabled = true;
            resendText.classList.add('hidden');
            resendTimer.classList.remove('hidden');

            const interval = setInterval(() => {
                remainingSeconds--;
                timerValue.textContent = remainingSeconds;
                localStorage.setItem('resend_timer_' + '{{ $email }}', remainingSeconds);

                if (remainingSeconds <= 0) {
                    clearInterval(interval);
                    resendBtn.disabled = false;
                    resendText.classList.remove('hidden');
                    resendTimer.classList.add('hidden');
                    localStorage.removeItem('resend_timer_' + '{{ $email }}');
                }
            }, 1000);
        }

        document.getElementById('resendForm').addEventListener('submit', function(e) {
            if (document.getElementById('resendBtn').disabled) {
                e.preventDefault();
            }
        });
    </script>
</body>
</html>


