@extends('layout.app')

@section('title', 'Application Review Result')

@section('content')
<div class="max-w-4xl mx-auto py-8 px-4">
    <div class="bg-white rounded-lg p-6 shadow">
        @php
            $donor = auth()->user()->donor ?? null;
            $isPending = $donor && !$donor->is_verified && !$donor->rejection_reason;
            $isRejected = $donor && $donor->rejection_reason;
        @endphp
        
        @if(session('success'))
            <div class="mb-4 p-3 bg-green-50 border border-green-200 text-green-800 rounded">{{ session('success') }}</div>
        @endif
        @if(session('info'))
            <div class="mb-4 p-3 bg-blue-50 border border-blue-200 text-blue-800 rounded">{{ session('info') }}</div>
        @endif
        
        @if($isPending)
            <div class="flex items-start gap-4">
                <div class="text-4xl text-yellow-600">⏳</div>
                <div>
                    <h1 class="text-2xl font-bold">Application pending</h1>
                    <p class="text-gray-600 mt-2">Your donor registration is under review. An admin will approve or deny your application soon.</p>
                </div>
            </div>
        @elseif($isRejected)
            <div class="flex items-start gap-4">
                <div class="text-4xl text-red-600">&#10060;</div>
                <div>
                    <h1 class="text-2xl font-bold">Application not approved</h1>
                    <p class="text-gray-600 mt-2">We reviewed your donor application and, unfortunately, it isn't approved at this time.</p>
                </div>
            </div>
        @endif

        <div class="mt-6">
            <h2 class="font-semibold">What this means</h2>
            <p class="text-gray-600">@if($isPending)You will receive a notification once a decision is made.@elseif($isRejected)You cannot be scheduled for donations while the status is not approved. The reason: <strong>{{ $donor->rejection_reason }}</strong>@endif This decision may be temporary you can appeal below or contact support for clarification.</p>
        </div>

        <div class="mt-6 grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <h3 class="font-semibold">Appeal the decision</h3>
                <p class="text-gray-600">If you believe this decision is in error, submit an appeal and attach any supporting documents (ID, medical clearance, etc.).</p>
            </div>
            <div>
                <style>
                    input[type="file"] {
                        cursor: pointer;
                    }
                    input[type="file"]::file-selector-button {
                        background: transparent;
                        border: none;
                        padding: 0;
                        margin: 0;
                        margin-right: 0.5rem;
                        cursor: pointer;
                    }
                    input[type="file"]::file-selector-button:hover {
                        color: red;
                    }
                    button[type="submit"] {
                        cursor: pointer;
                    }
                </style>
                <form method="POST" action="{{ route('donor.appeal.store') }}" enctype="multipart/form-data" class="space-y-3">
                    @csrf
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Message (optional)</label>
                        <textarea name="message" rows="3" class="mt-1 block w-full border border-gray-300 rounded p-2">{{ old('message') }}</textarea>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Attach document (pdf, jpg, png)</label>
                        <div class="mt-1">
                            <label for="attachment" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 cursor-pointer transition">
                                <i class="fas fa-file-upload mr-2"></i>
                                Choose File
                            </label>
                            <input id="attachment" type="file" name="attachment" class="sr-only" />
                            <span id="selectedFile" class="ml-3 text-sm text-gray-600"></span>
                        </div>
                    </div>
                    <script>
                        document.getElementById('attachment').addEventListener('change', function(e) {
                            const fileName = e.target.files[0]?.name || 'No file chosen';
                            document.getElementById('selectedFile').textContent = fileName;
                        });
                    </script>
                    <div>
                        <button type="submit" class="inline-flex items-center justify-center px-4 py-2 rounded-md border border-transparent text-sm font-semibold text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition">
                            <i class="fas fa-cloud-upload-alt mr-2"></i> Submit Appeal
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <div class="mt-6">
            <h3 class="font-semibold">Need help?</h3>
            <p class="text-gray-600">Contact our support team at <a href="mailto:support@lifelink.local" class="text-blue-600">support@lifelink.local</a> or call (000) 000-0000.</p>
        </div>
    </div>
</div>
@endsection
