<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password - Blood Donation System</title>
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
                <h2 class="mt-4 text-center text-2xl sm:text-3xl font-bold text-gray-900">Create a new password</h2>
                <p class="mt-2 text-center text-sm text-gray-600">
                    For account: <span class="font-semibold text-gray-900">{{ $email }}</span>
                </p>

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
                        <ul class="list-disc list-inside space-y-1">
                            @foreach ($errors->all() as $error)
                                <li><span class="text-xs md:text-sm">{{ $error }}</span></li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form class="mt-6 space-y-4" method="POST" action="{{ route('password.reset.confirm') }}" id="resetPasswordForm">
                @csrf
                <input type="hidden" name="email" value="{{ $email }}">
                <input type="hidden" name="code" value="{{ $code }}">
                
                <!-- Password Requirements Box -->
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                    <p class="text-sm font-semibold text-blue-900 mb-2">Password Requirements:</p>
                    <ul class="space-y-1 text-xs text-blue-800">
                        <li class="flex items-center">
                            <span id="min-length" class="requirement-check text-gray-400 mr-2">○</span>
                            At least 8 characters
                        </li>
                        <li class="flex items-center">
                            <span id="uppercase" class="requirement-check text-gray-400 mr-2">○</span>
                            One uppercase letter (A-Z)
                        </li>
                        <li class="flex items-center">
                            <span id="lowercase" class="requirement-check text-gray-400 mr-2">○</span>
                            One lowercase letter (a-z)
                        </li>
                        <li class="flex items-center">
                            <span id="number" class="requirement-check text-gray-400 mr-2">○</span>
                            One number (0-9)
                        </li>
                        <li class="flex items-center">
                            <span id="special" class="requirement-check text-gray-400 mr-2">○</span>
                            One special character (@$!%*?&)
                        </li>
                    </ul>
                </div>

                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700">New Password</label>
                    <div class="mt-1 relative">
                        <input 
                            id="password" 
                            name="password" 
                            type="password" 
                            required 
                            autocomplete="new-password"
                            class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-red-500 focus:border-red-500 sm:text-sm transition-colors"
                            placeholder="Enter new password"
                        >
                        <button 
                            type="button" 
                            onclick="togglePasswordVisibility('password')"
                            class="absolute right-3 top-2.5 text-gray-500 hover:text-gray-700"
                        >
                            <svg id="eye-password" class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                            </svg>
                        </button>
                    </div>
                </div>

                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Confirm Password</label>
                    <div class="mt-1 relative">
                        <input 
                            id="password_confirmation" 
                            name="password_confirmation" 
                            type="password" 
                            required 
                            autocomplete="new-password"
                            class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-red-500 focus:border-red-500 sm:text-sm transition-colors"
                            placeholder="Confirm new password"
                        >
                        <button 
                            type="button" 
                            onclick="togglePasswordVisibility('password_confirmation')"
                            class="absolute right-3 top-2.5 text-gray-500 hover:text-gray-700"
                        >
                            <svg id="eye-password_confirmation" class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                            </svg>
                        </button>
                    </div>
                    <span id="matchError" class="hidden text-red-500 text-xs mt-1">Passwords do not match</span>
                </div>

                <!-- Progress Indicator -->
                <div class="flex items-center justify-center space-x-2">
                    <span id="progress-text" class="text-xs text-gray-600">Password strength: <span id="strength-text" class="font-semibold text-gray-400">Weak</span></span>
                    <div class="flex-1 max-w-xs h-1.5 bg-gray-300 rounded-full overflow-hidden ml-2">
                        <div id="progress-bar" class="h-full bg-red-400 rounded-full transition-all" style="width: 0%"></div>
                    </div>
                </div>

                <div>
                    <button 
                        type="submit" 
                        id="submitBtn"
                        disabled
                        class="group relative w-full flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-all disabled:opacity-50 disabled:cursor-not-allowed"
                    >
                        <span id="submitText">Reset Password</span>
                        <span id="submitLoader" class="hidden ml-2">
                            <svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                        </span>
                    </button>
                </div>
            </form>

            <!-- Back to Forgot Password -->
            <div class="text-center">
                <a href="{{ route('password.forgot') }}" class="font-medium text-red-600 hover:text-red-500">
                    Start over
                </a>
            </div>
        </div>
    </div>
</div>

    <script>
        const passwordInput = document.getElementById('password');
        const confirmPasswordInput = document.getElementById('password_confirmation');
        const submitBtn = document.getElementById('submitBtn');
        
        function togglePasswordVisibility(fieldId) {
            const input = document.getElementById(fieldId);
            const eye = document.getElementById('eye-' + fieldId);
            
            if (input.type === 'password') {
                input.type = 'text';
                eye.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"></path>';
            } else {
                input.type = 'password';
                eye.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>';
            }
        }

        function checkPasswordRequirements() {
            const password = passwordInput.value;
            
            // Check requirements
            const hasMinLength = password.length >= 8;
            const hasUppercase = /[A-Z]/.test(password);
            const hasLowercase = /[a-z]/.test(password);
            const hasNumber = /\d/.test(password);
            const hasSpecial = /[@$!%*?&]/.test(password);
            
            // Update requirement indicators
            updateRequirement('min-length', hasMinLength);
            updateRequirement('uppercase', hasUppercase);
            updateRequirement('lowercase', hasLowercase);
            updateRequirement('number', hasNumber);
            updateRequirement('special', hasSpecial);
            
            // Calculate strength
            const strength = [hasMinLength, hasUppercase, hasLowercase, hasNumber, hasSpecial].filter(Boolean).length;
            
            // Update progress bar
            const progressBar = document.getElementById('progress-bar');
            const strengthText = document.getElementById('strength-text');
            
            const progressPercent = (strength / 5) * 100;
            progressBar.style.width = progressPercent + '%';
            
            if (strength === 0) {
                progressBar.className = 'h-full bg-gray-400 rounded-full transition-all';
                strengthText.textContent = 'Weak';
                strengthText.className = 'font-semibold text-gray-600';
            } else if (strength < 3) {
                progressBar.className = 'h-full bg-red-400 rounded-full transition-all';
                strengthText.textContent = 'Weak';
                strengthText.className = 'font-semibold text-red-600';
            } else if (strength < 5) {
                progressBar.className = 'h-full bg-yellow-400 rounded-full transition-all';
                strengthText.textContent = 'Medium';
                strengthText.className = 'font-semibold text-yellow-600';
            } else {
                progressBar.className = 'h-full bg-green-400 rounded-full transition-all';
                strengthText.textContent = 'Strong';
                strengthText.className = 'font-semibold text-green-600';
            }
            
            checkFormValidity();
        }

        function updateRequirement(id, met) {
            const element = document.getElementById(id);
            if (met) {
                element.textContent = '✓';
                element.className = 'requirement-check text-green-500 mr-2 font-bold';
            } else {
                element.textContent = '○';
                element.className = 'requirement-check text-gray-400 mr-2';
            }
        }

        function checkFormValidity() {
            const password = passwordInput.value;
            const hasMinLength = password.length >= 8;
            const hasUppercase = /[A-Z]/.test(password);
            const hasLowercase = /[a-z]/.test(password);
            const hasNumber = /\d/.test(password);
            const hasSpecial = /[@$!%*?&]/.test(password);
            const passwordsMatch = password === confirmPasswordInput.value && password !== '';
            
            const allRequirementsMet = hasMinLength && hasUppercase && hasLowercase && hasNumber && hasSpecial;
            
            // Show/hide match error
            if (confirmPasswordInput.value && !passwordsMatch) {
                document.getElementById('matchError').classList.remove('hidden');
            } else {
                document.getElementById('matchError').classList.add('hidden');
            }
            
            submitBtn.disabled = !(allRequirementsMet && passwordsMatch);
        }

        passwordInput.addEventListener('input', checkPasswordRequirements);
        confirmPasswordInput.addEventListener('input', checkFormValidity);

        // Form submission
        document.getElementById('resetPasswordForm').addEventListener('submit', function(e) {
            const submitBtn = document.getElementById('submitBtn');
            const submitText = document.getElementById('submitText');
            const submitLoader = document.getElementById('submitLoader');

            submitBtn.disabled = true;
            submitText.classList.add('hidden');
            submitLoader.classList.remove('hidden');
        });
    </script>
</body>
</html>


