@extends('layouts')

@section('content')
<div class="container">
    <h1>Detail Organization</h1>
    <div class="card">
        <div class="card-body">
            <h2>{{ $organization['name'] ?? 'N/A' }}</h2>
            <p><strong>ID:</strong> {{ $organization['id'] }}</p>
            <p><strong>Active:</strong> {{ $organization['active'] ? 'Yes' : 'No' }}</p>
            <p><strong>Type:</strong>
                @if(isset($organization['type'][0]['coding'][0]['display']))
                    {{ $organization['type'][0]['coding'][0]['display'] }}
                @else
                    N/A
                @endif
            </p>
            <p><strong>Part Of:</strong>
                @if(isset($organization['partOf']['reference']))
                    {{ $organization['partOf']['reference'] }}
                @else
                    N/A
                @endif
            </p>
            <p><strong>Meta:</strong> 
                <br><strong>Last Updated:</strong> {{ $organization['meta']['lastUpdated'] ?? 'N/A' }}
                <br><strong>Version ID:</strong> {{ $organization['meta']['versionId'] ?? 'N/A' }}
            </p>
            <p><strong>Address:</strong>
                @if(isset($organization['address'][0]))
                    <br><strong>Line:</strong> {{ $organization['address'][0]['line'][0] ?? 'N/A' }}
                    <br><strong>City:</strong> {{ $organization['address'][0]['city'] ?? 'N/A' }}
                    <br><strong>Postal Code:</strong> {{ $organization['address'][0]['postalCode'] ?? 'N/A' }}
                    <br><strong>Country:</strong> {{ $organization['address'][0]['country'] ?? 'N/A' }}
                    <br><strong>Type:</strong> {{ $organization['address'][0]['type'] ?? 'N/A' }}
                    <br><strong>Use:</strong> {{ $organization['address'][0]['use'] ?? 'N/A' }}
                    <br><strong>Province:</strong> {{ $organization['address'][0]['extension'][0]['extension'][0]['valueCode'] ?? 'N/A' }}
                    <br><strong>City Code:</strong> {{ $organization['address'][0]['extension'][0]['extension'][1]['valueCode'] ?? 'N/A' }}
                    <br><strong>District:</strong> {{ $organization['address'][0]['extension'][0]['extension'][2]['valueCode'] ?? 'N/A' }}
                    <br><strong>Village:</strong> {{ $organization['address'][0]['extension'][0]['extension'][3]['valueCode'] ?? 'N/A' }}
                @endif
            </p>
            <p><strong>Identifier:</strong>
                @if(isset($organization['identifier'][0]))
                    <br><strong>System:</strong> {{ $organization['identifier'][0]['system'] ?? 'N/A' }}
                    <br><strong>Use:</strong> {{ $organization['identifier'][0]['use'] ?? 'N/A' }}
                    <br><strong>Value:</strong> {{ $organization['identifier'][0]['value'] ?? 'N/A' }}
                @endif
            </p>
            <p><strong>Telecom:</strong>
                @if(isset($organization['telecom']))
                    @foreach($organization['telecom'] as $telecom)
                        <br><strong>{{ ucfirst($telecom['system']) }}:</strong> {{ $telecom['value'] }} ({{ $telecom['use'] }})
                    @endforeach
                @else
                    N/A
                @endif
            </p>
        </div>
    </div>
    <a href="{{ url()->previous() }}" class="btn btn-danger text-xs mb-3">Kembali</a>
</div>
@endsection
