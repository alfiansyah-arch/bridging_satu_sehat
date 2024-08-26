@extends('layouts')

@section('content')
<div class="container">
@if(session()->has('success'))
    <div class="alert alert-success">
        {{ session()->get('success') }}
    </div>
@endif
@if(session()->has('error'))
    <div class="alert alert-danger">
        {{ session()->get('error') }}
    </div>
@endif
    @if(isset($accessToken))
        <div class="card mt-4">
            <div class="card-body">
                <h5 class="card-title">Informasi Akses Token</h5>
                <ul class="list-group">
                    <li class="list-group-item">Token: <b>{{ $accessToken }}</b></li>
                    @if(isset($accessTokenExpiry))
                    <li class="list-group-item">
                        <p>Akses token akan kadaluarsa pada: <b>{{ $accessTokenExpiry }}</b></p>
                        <p>Hitung mundur: <span id="token-expiry-countdown">{{ $accessTokenExpiry }}</span></p>
                    </li>
                    @endif
                </ul>
            </div>
        </div>
    @else
    <div class="card mt-4">
        <div class="card-body">
            <a href="{{route('generate-token')}}" class="btn btn-success text-xs">Generate Token</a>
        </div>
    </div>
    @endif

    <h1>Cari Lokasi Berdasarkan Nama</h1>
    <div class="dropdown show">
        <a class="btn btn-primary dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            Cari Berdasarkan
        </a>

        <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
            <a class="dropdown-item" href="{{route('location.search-by-id')}}">ID</a>
            <a class="dropdown-item" href="{{route('location.search-by-name')}}">Nama</a>
            <a class="dropdown-item" href="{{route('location.search-by-org-id')}}">Organization ID</a>
        </div>
    </div>
    <br>
    <div class="card mb-3">
        <div class="card-body">
            <form action="{{ route('location.search-by-name') }}" method="GET">
                <div class="form-group">
                    <label for="name">Search by Name:</label>
                    <input type="text" class="form-control" id="name" name="name" placeholder="Enter location name">
                </div>
                <button type="submit" class="btn btn-primary">Search</button>
            </form>
        </div>
    </div>
    @if(isset($location))
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table id="location-table" class="table display">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Country</th>
                            <th>City</th>
                            <th>Address</th>
                            <th>Phone</th>
                            <th>Email</th>
                            <th>Website</th>
                            <th>Type</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if(isset($location['entry']) && count($location['entry']) > 0)
                            @foreach($location['entry'] as $entry)
                                @php
                                    $loc = $entry['resource'];
                                @endphp
                                <tr>
                                    <td>{{ $loc['id'] ?? 'N/A' }}</td>
                                    <td>{{ $loc['name'] ?? 'N/A' }}</td>
                                    <td>{{ $loc['address']['country'] ?? 'N/A' }}</td>
                                    <td>{{ $loc['address']['city'] ?? 'N/A' }}</td>
                                    <td>
                                        {{ $loc['address']['line'][0] ?? 'N/A' }},
                                        {{ $loc['address']['postalCode'] ?? 'N/A' }}
                                    </td>
                                    <td>{{ $loc['telecom'][0]['value'] ?? 'N/A' }}</td>
                                    <td>{{ $loc['telecom'][1]['value'] ?? 'N/A' }}</td>
                                    <td>{{ $loc['telecom'][2]['value'] ?? 'N/A' }}</td>
                                    <td>{{ $loc['physicalType']['coding'][0]['display'] ?? 'N/A' }}</td>
                                    <td>
                                        <div class="dropdown show">
                                            <a class="btn btn-secondary dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                Aksi
                                            </a>
                                            <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                                                <a class="dropdown-item" href="{{ route('location.view', $loc['id']) }}">View Detail</a>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="10" class="text-center">Data Tidak Ditemukan</td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @endif

    <!-- Tabel Kedua -->
    <h1>Data Informasi Lokasi (Dari Database)</h1>
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
            <table id="database-location-table" class="table display">
                <thead>
                    <tr>
                        <th>No.</th>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Identifier Value</th>
                        <th>Status</th>
                        <th>Country</th>
                        <th>City</th>
                        <th>Address</th>
                        <th>Phone</th>
                        <th>Fax</th>
                        <th>Email</th>
                        <th>Website</th>
                        <th>Description</th>
                        <th>Latitude</th>
                        <th>Longitude</th>
                        <th>Altitude</th>
                    </tr>
                </thead>
                <tbody>
                <?php $no=0; ?>
                    @foreach($locations as $location)
                    @php
                        // Check if 'identifier' is an array or JSON string
                        $identifier = is_array($location->identifier) ? $location->identifier : json_decode($location->identifier, true);
                        $telecom = is_array($location->telecom) ? $location->telecom : json_decode($location->telecom, true);
                        $address = is_array($location->address) ? $location->address : json_decode($location->address, true);
                    @endphp
                    <tr>
                        <?php $no++ ?>
                        <td>{{ $no }}</td>
                        <td>{{ $location->id_location }}</td>
                        <td>{{ $location->name }}</td>
                        <td>{{ isset($identifier[0]['value']) ? $identifier[0]['value'] : 'N/A' }}</td>
                        <td>{{ $location->status }}</td>
                        <td>{{ $address['country'] ?? 'N/A' }}</td>
                        <td>{{ $address['city'] ?? 'N/A' }}</td>
                        <td>{{ $address['line'][0] ?? 'N/A' }}</td>
                        <td>{{ isset($telecom[0]['value']) ? $telecom[0]['value'] : 'N/A' }}</td>
                        <td>{{ isset($telecom[1]['value']) ? $telecom[1]['value'] : 'N/A' }}</td>
                        <td>{{ isset($telecom[2]['value']) ? $telecom[2]['value'] : 'N/A' }}</td>
                        <td>{{ isset($telecom[3]['value']) ? $telecom[3]['value'] : 'N/A' }}</td>
                        <td>{{ $location->description }}</td>
                        <td>{{ $location->position_latitude }}</td>
                        <td>{{ $location->position_longitude }}</td>
                        <td>{{ $location->position_altitude }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            </div>
        </div>
    </div>
</div>
<script>
    var countDownDate = new Date('{{ $accessTokenExpiry }}').getTime();

    var x = setInterval(function() {
        var now = new Date().getTime();
        var distance = countDownDate - now;

        var days = Math.floor(distance / (1000 * 60 * 60 * 24));
        var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
        var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
        var seconds = Math.floor((distance % (1000 * 60)) / 1000);

        document.getElementById("token-expiry-countdown").innerHTML = days + "d " + hours + "h " + minutes + "m " + seconds + "s ";

        if (distance < 0) {
            clearInterval(x);
            document.getElementById("token-expiry-countdown").innerHTML = "Token Kadaluarsa, Refresh halaman untuk mendapatkan token baru.";
        }
    }, 1000);
</script>
@endsection
