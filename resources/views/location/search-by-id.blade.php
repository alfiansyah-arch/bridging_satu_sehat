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
                <a href="{{route('satusehat.index')}}" class="btn btn-success text-xs">Generate Token</a>
            </div>
        </div>
    @endif

    <h1>Data Informasi Lokasi</h1>
    <div class="dropdown show">
        <a class="btn btn-primary dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            Cari Berdasarkan
        </a>

        <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
            <a class="dropdown-item" href="{{route('location.search-by-id')}}">ID</a>
            <a class="dropdown-item" href="{{route('location.search-by-name')}}">Nama</a>
        </div>
    </div>
    <br>
    <div class="card mb-3">
        <div class="card-body">
            <form action="{{ route('location.search-by-id') }}" method="GET">
                <div class="form-group">
                    <label for="id">Search by ID:</label>
                    <input type="text" class="form-control" id="id" name="id" placeholder="Enter location ID">
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
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                        @if(isset($locationId))
                            @if($location)
                            <td>{{ $location['id'] ?? 'N/A' }}</td>
                            <td>{{ $location['name'] ?? 'N/A' }}</td>
                            <td>{{ $location['status'] ?? 'N/A' }}</td>
                            <td>{{ $location['address']['country'] ?? 'N/A' }}</td>
                            <td>{{ $location['address']['city'] ?? 'N/A' }}</td>
                            <td>
                                {{ $location['address']['line'][0] ?? 'N/A' }}<br>
                                Province Code: {{ $location['address']['extension'][0]['extension'][0]['valueCode'] ?? 'N/A' }}<br>
                                City Code: {{ $location['address']['extension'][0]['extension'][1]['valueCode'] ?? 'N/A' }}<br>
                                District Code: {{ $location['address']['extension'][0]['extension'][2]['valueCode'] ?? 'N/A' }}<br>
                                Village Code: {{ $location['address']['extension'][0]['extension'][3]['valueCode'] ?? 'N/A' }}<br>
                                RT: {{ $location['address']['extension'][0]['extension'][4]['valueCode'] ?? 'N/A' }}<br>
                                RW: {{ $location['address']['extension'][0]['extension'][5]['valueCode'] ?? 'N/A' }}<br>
                                Postal Code: {{ $location['address']['postalCode'] ?? 'N/A' }}
                            </td>
                            <td>{{ $location['telecom'][0]['value'] ?? 'N/A' }}</td>
                            <td>{{ $location['telecom'][1]['value'] ?? 'N/A' }}</td>
                            <td>{{ $location['telecom'][2]['value'] ?? 'N/A' }}</td>
                            <td>{{ $location['telecom'][3]['value'] ?? 'N/A' }}</td>
                            <td>{{ $location['description'] ?? 'N/A' }}</td>
                            <td>{{ $location['position']['latitude'] ?? 'N/A' }}</td>
                            <td>{{ $location['position']['longitude'] ?? 'N/A' }}</td>
                            <td>{{ $location['position']['altitude'] ?? 'N/A' }}</td>
                            <td>
                                <div class="dropdown show">
                                    <a class="btn btn-secondary dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        Aksi
                                    </a>
                                    <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                                        <a class="dropdown-item" href="{{ route('location.view', $location['id'] ?? 'N/A') }}">View Detail</a>
                                        <a class="dropdown-item" href="{{ route('location.edit', $location['id'] ?? 'N/A') }}">Edit Location</a>
                                    </div>
                                </div>
                            </td>
                            @endif
                        @endif
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @else
    <div class="card">
        <div class="card-body">
            <p>Data Tidak Ditemukan</p>
        </div>
    </div>
    @endif
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
