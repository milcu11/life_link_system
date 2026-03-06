@extends('layout.app')

@section('content')
<div style="padding:20px; font-family: Poppins, sans-serif;">
    <h2>We received your appeal</h2>
    <p>Hi {{ $appeal->donor->user->name }},</p>
    <p>Thanks — we received your appeal (ID: {{ $appeal->id }}). Our team will review the documents and update you within 3 business days.</p>
    <p>If you have more documents to share, please reply to this message or re-submit through your account.</p>
    <p>Reference: {{ $appeal->id }}</p>
    <p>— The LifeLink Team</p>
</div>
@endsection
