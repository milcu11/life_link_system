@extends('layout.app')

@section('title','Appeals')

@section('content')
<div class="bg-gray-50 min-h-screen py-8">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <h1 class="text-2xl font-bold mb-4">Donor Appeals</h1>
    <div class="bg-white rounded shadow overflow-hidden">
        <table class="w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="p-3 text-left">ID</th>
                    <th class="p-3 text-left">Donor</th>
                    <th class="p-3 text-left">Submitted</th>
                    <th class="p-3 text-left">Status</th>
                    <th class="p-3">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($appeals as $appeal)
                <tr class="border-t">
                    <td class="p-3">{{ $appeal->id }}</td>
                    <td class="p-3">{{ $appeal->donor->user->name ?? '—' }}</td>
                    <td class="p-3">{{ $appeal->created_at->toDayDateTimeString() }}</td>
                    <td class="p-3">{{ ucfirst($appeal->status) }}</td>
                    <td class="p-3 text-center"><a href="{{ route('admin.appeals.show', $appeal) }}" class="text-blue-600">Review</a></td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="mt-4">{{ $appeals->links() }}</div>
</div>
@endsection
