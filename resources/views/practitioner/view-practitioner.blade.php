@extends('layouts')

@section('content')
<div class="container">
    <h1>Detail Practitioner</h1>
    <div class="card">
        <div class="card-body">
            <h2>{{ $practitioner['name'][0]['text'] ?? 'N/A' }}</h2>
            <p><strong>ID:</strong> {{ $practitioner['id'] }}</p>
            <p><strong>NIK:</strong> {{ $practitioner['identifier'][1]['value'] }}</p>
            <p><strong>Gender:</strong> {{ $practitioner['gender'] ?? 'N/A' }}</p>
            <p><strong>Birth Date:</strong> {{ $practitioner['birthDate'] ?? 'N/A' }}</p>
            <p><strong>Address:</strong> 
                @if(isset($practitioner['address'][0]))
                    {{ $practitioner['address'][0]['line'][0] ?? 'N/A' }}, {{ $practitioner['address'][0]['city'] ?? 'N/A' }}
                @endif
            </p>
            <p><strong>Phone:</strong> 
                @foreach($practitioner['telecom'] as $telecom)
                    @if($telecom['system'] == 'phone')
                        {{ $telecom['value'] }} ({{ $telecom['use'] }})<br>
                    @endif
                @endforeach
            </p>
            <p><strong>Email:</strong> 
                @foreach($practitioner['telecom'] as $telecom)
                    @if($telecom['system'] == 'email')
                        {{ $telecom['value'] }} ({{ $telecom['use'] }})<br>
                    @endif
                @endforeach
            </p>
            <p><strong>Qualification:</strong> 
                @foreach($practitioner['qualification'] as $qualification)
                    {{ $qualification['code']['coding'][0]['display'] ?? 'N/A' }} - {{ $qualification['identifier'][0]['value'] ?? 'N/A' }}<br>
                    <strong>Issuer:</strong> {{ $qualification['issuer']['display'] ?? 'N/A' }}<br>
                    <strong>Period:</strong> {{ $qualification['period']['start'] ?? 'N/A' }} - {{ $qualification['period']['end'] ?? 'N/A' }}<br>
                @endforeach
            </p>
        </div>
    </div>
    <a href="{{ url()->previous() }}" class="btn btn-danger text-xs mb-3">Kembali</a>
</div>
@endsection
