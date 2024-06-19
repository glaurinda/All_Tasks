

@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Domain Information</h1>
        <p><strong>Domain Name:</strong> {{ $domain->name }}</p>
        <p><strong>Created Date:</strong> {{ $domain->created_date }}</p>
        <p><strong>Updated Date:</strong> {{ $domain->updated_date }}</p>
        <p><strong>Expires Date:</strong> {{ $domain->expires_date }}</p>

        <a href="{{ url('/enter-domain') }}">Enter Another Domain</a>
    </div>
@endsection
