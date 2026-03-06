@extends('layout.app')

@section('title', 'Donor Details - LifeLink')

@section('content')
<div class="bg-gray-50 min-h-screen py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="mb-6">
            <a href="{{ route('admin.donors') }}" class="text-sm text-gray-600">&larr; Back to donors</a>
            <h1 class="text-2xl font-bold text-gray-900 mt-2">Donor Details</h1>
            <p class="text-gray-600 mt-1">View donor profile and attached files</p>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <h2 class="text-sm text-gray-500">Name</h2>
                    <p class="text-lg font-medium">{{ $donor->user->name }}</p>
                </div>
                <div>
                    <h2 class="text-sm text-gray-500">Email</h2>
                    <p class="text-lg">{{ $donor->user->email }}</p>
                </div>
                <div>
                    <h2 class="text-sm text-gray-500">Phone</h2>
                    <p class="text-lg">{{ $donor->phone ?? '-' }}</p>
                </div>
                <div>
                    <h2 class="text-sm text-gray-500">Blood Type</h2>
                    <p class="text-lg">{{ $donor->blood_type ?? '-' }}</p>
                </div>
                <div class="sm:col-span-2">
                    <h2 class="text-sm text-gray-500">Address</h2>
                    <p class="text-lg">{{ $donor->address ?? '-' }}</p>
                </div>
            </div>

            <div class="mt-6 border-t pt-4">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-semibold">Attached Files</h3>
                    <div>
                        <span class="inline-block text-sm px-3 py-1 rounded-full {{ $donor->is_verified ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                            {{ $donor->is_verified ? 'Verified' : 'Pending Verification' }}
                        </span>
                    </div>
                </div>
                <p class="text-sm text-gray-600 mb-3">Files uploaded by the donor (verification documents).</p>

                @if($donor->verification_document_path)
                    <div class="flex items-center justify-between bg-gray-50 p-3 rounded">
                        <div>
                            <p class="font-medium">Verification Document</p>
                            <p class="text-sm text-gray-500">Uploaded on: {{ optional($donor->updated_at)->format('M d, Y H:i') }}</p>
                        </div>
                        <div>
                            <a href="{{ route('admin.donors.download', $donor) }}" class="px-3 py-2 bg-green-600 text-white rounded">Download</a>
                        </div>
                        <div class="flex items-center gap-2">
                            @if(!$donor->is_verified)
                                <form method="POST" action="{{ route('admin.donors.approve', $donor) }}">
                                    @csrf
                                    <button type="submit" class="px-3 py-2 bg-blue-600 text-white rounded">Approve</button>
                                </form>
                                <form method="POST" action="{{ route('admin.donors.reject', $donor) }}" class="w-full">
                                    @csrf
                                    <div class="mb-2">
                                        <label class="text-sm text-gray-600">Rejection reason (optional)</label>
                                        <textarea name="reason" rows="3" class="w-full mt-1 border rounded p-2 text-sm" placeholder="Explain why this registration is rejected"></textarea>
                                    </div>
                                    <button type="submit" class="px-3 py-2 bg-red-600 text-white rounded">Reject</button>
                                </form>
                            @else
                                <span class="text-sm text-gray-600">Already verified</span>
                            @endif
                        </div>
                    </div>
                @else
                    <p class="text-sm text-gray-500">No files attached by this donor.</p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
