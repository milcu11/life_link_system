@extends('layout.app')

@section('title','Appeal #'.$appeal->id)

@section('content')
<div class="max-w-3xl mx-auto py-8">
    <h1 class="text-2xl font-bold mb-4">Appeal #{{ $appeal->id }}</h1>
    <div class="bg-white rounded shadow p-6">
        <p><strong>Donor:</strong> {{ $appeal->donor->user->name ?? '—' }}</p>
        <p><strong>Submitted:</strong> {{ $appeal->created_at->toDayDateTimeString() }}</p>
        <p class="mt-4"><strong>Message:</strong><br>{{ $appeal->message }}</p>
        @if($appeal->attachment_path)
            <p class="mt-4"><strong>Attachment:</strong> <a href="{{ asset('storage/'.$appeal->attachment_path) }}" target="_blank" class="text-blue-600">Download</a></p>
        @endif

        <div class="mt-6">
            <form method="POST" action="{{ route('admin.appeals.review', $appeal) }}">
                @csrf
                <label class="block">Admin note</label>
                <textarea name="admin_note" class="w-full border rounded p-2 mt-1">{{ $appeal->admin_note }}</textarea>
                <div class="mt-3 flex gap-2">
                    <button name="action" value="approve" class="px-4 py-2 bg-green-600 text-white rounded">Approve</button>
                    <button name="action" value="reject" class="px-4 py-2 bg-red-600 text-white rounded">Reject</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
