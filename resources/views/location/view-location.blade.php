@extends('layouts')

@section('content')
<div class="container">
    <h1>Detail Informasi Lokasi</h1>
    @if(isset($location))
    <div class="card mt-4">
        <div class="card-body">
            <h5 class="card-title">{{ $location['name'] ?? 'N/A' }}</h5>
            <ul class="list-group">
                <li class="list-group-item">ID: <b>{{ $location['id'] ?? 'N/A' }}</b></li>
                <li class="list-group-item">Status: <b>{{ $location['status'] ?? 'N/A' }}</b></li>
                <li class="list-group-item">Mode: <b>{{ $location['mode'] ?? 'N/A' }}</b></li>
                <li class="list-group-item">Resource Type: <b>{{ $location['resourceType'] ?? 'N/A' }}</b></li>
                <li class="list-group-item">Description: <b>{{ $location['description'] ?? 'N/A' }}</b></li>
                <li class="list-group-item">Managing Organization: <b>{{ $location['managingOrganization']['reference'] ?? 'N/A' }}</b></li>
                <li class="list-group-item">
                    <h6>Address:</h6>
                    Line: {{ $location['address']['line'][0] ?? 'N/A' }}<br>
                    City: {{ $location['address']['city'] ?? 'N/A' }}<br>
                    Country: {{ $location['address']['country'] ?? 'N/A' }}<br>
                    Postal Code: {{ $location['address']['postalCode'] ?? 'N/A' }}<br>
                    <h6>Administrative Codes:</h6>
                    Province Code: {{ $location['address']['extension'][0]['extension'][0]['valueCode'] ?? 'N/A' }}<br>
                    City Code: {{ $location['address']['extension'][0]['extension'][1]['valueCode'] ?? 'N/A' }}<br>
                    District Code: {{ $location['address']['extension'][0]['extension'][2]['valueCode'] ?? 'N/A' }}<br>
                    Village Code: {{ $location['address']['extension'][0]['extension'][3]['valueCode'] ?? 'N/A' }}<br>
                    RT: {{ $location['address']['extension'][0]['extension'][4]['valueCode'] ?? 'N/A' }}<br>
                    RW: {{ $location['address']['extension'][0]['extension'][5]['valueCode'] ?? 'N/A' }}
                </li>
                <li class="list-group-item">
                    <h6>Telecom:</h6>
                    Phone: {{ $location['telecom'][0]['value'] ?? 'N/A' }}<br>
                    Fax: {{ $location['telecom'][1]['value'] ?? 'N/A' }}<br>
                    Email: {{ $location['telecom'][2]['value'] ?? 'N/A' }}<br>
                    URL: {{ $location['telecom'][3]['value'] ?? 'N/A' }}
                </li>
                <li class="list-group-item">
                    <h6>Position:</h6>
                    Latitude: {{ $location['position']['latitude'] ?? 'N/A' }}<br>
                    Longitude: {{ $location['position']['longitude'] ?? 'N/A' }}<br>
                    Altitude: {{ $location['position']['altitude'] ?? 'N/A' }}
                </li>
                <li class="list-group-item">
                    <h6>Meta:</h6>
                    Last Updated: {{ $location['meta']['lastUpdated'] ?? 'N/A' }}<br>
                    Version ID: {{ $location['meta']['versionId'] ?? 'N/A' }}
                </li>
            </ul>
            <a href="{{ url()->previous() }}" class="btn btn-danger text-xs mt-3">Kembali</a>
        </div>
    </div>
    @else
    <div class="card mt-4">
        <div class="card-body">
            <p>Data Tidak Ditemukan</p>
            <a href="{{ url()->previous() }}" class="btn btn-danger text-xs mt-3">Kembali</a>
        </div>
    </div>
    @endif
</div>
@endsection
