@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Domain Information</h1>
    <ul>
        <li>Creation Date: {{ $info['created_at'] }}</li>
        <li>Updated Date: {{ $info['updated_at'] }}</li>
        <li>Expiry Date: {{ $info['expires_at'] }}</li>
    </ul>
</div>
@endsection
