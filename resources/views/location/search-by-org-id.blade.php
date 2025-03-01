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

    <h1>Data Informasi Lokasi Berdasarkan Organization ID</h1>
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
            <form action="{{ route('location.search-by-org-id') }}" method="GET">
                <div class="form-group">
                    <label for="organization_id">Search by Organization ID:</label>
                    <input type="text" class="form-control" id="organization_id" name="organization_id" placeholder="Enter organization ID">
                </div>
                <button type="submit" class="btn btn-primary">Search</button>
            </form>
        </div>
    </div>
    @if(isset($locations['entry']))
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
                        @foreach($locations['entry'] as $location)
                        <tr>
                            <td>{{ $location['resource']['id'] ?? 'N/A' }}</td>
                            <td>{{ $location['resource']['name'] ?? 'N/A' }}</td>
                            <td>{{ $location['resource']['status'] ?? 'N/A' }}</td>
                            <td>{{ $location['resource']['address']['country'] ?? 'N/A' }}</td>
                            <td>{{ $location['resource']['address']['city'] ?? 'N/A' }}</td>
                            <td>
                                {{ $location['resource']['address']['line'][0] ?? 'N/A' }}<br>
                                Province Code: {{ $location['resource']['address']['extension'][0]['extension'][0]['valueCode'] ?? 'N/A' }}<br>
                                City Code: {{ $location['resource']['address']['extension'][0]['extension'][1]['valueCode'] ?? 'N/A' }}<br>
                                District Code: {{ $location['resource']['address']['extension'][0]['extension'][2]['valueCode'] ?? 'N/A' }}<br>
                                Village Code: {{ $location['resource']['address']['extension'][0]['extension'][3]['valueCode'] ?? 'N/A' }}<br>
                                RT: {{ $location['resource']['address']['extension'][0]['extension'][4]['valueCode'] ?? 'N/A' }}<br>
                                RW: {{ $location['resource']['address']['extension'][0]['extension'][5]['valueCode'] ?? 'N/A' }}<br>
                                Postal Code: {{ $location['resource']['address']['postalCode'] ?? 'N/A' }}
                            </td>
                            <td>{{ $location['resource']['telecom'][0]['value'] ?? 'N/A' }}</td>
                            <td>{{ $location['resource']['telecom'][1]['value'] ?? 'N/A' }}</td>
                            <td>{{ $location['resource']['telecom'][2]['value'] ?? 'N/A' }}</td>
                            <td>{{ $location['resource']['telecom'][3]['value'] ?? 'N/A' }}</td>
                            <td>{{ $location['resource']['description'] ?? 'N/A' }}</td>
                            <td>{{ $location['resource']['position']['latitude'] ?? 'N/A' }}</td>
                            <td>{{ $location['resource']['position']['longitude'] ?? 'N/A' }}</td>
                            <td>{{ $location['resource']['position']['altitude'] ?? 'N/A' }}</td>
                            <td>
                                <div class="dropdown show">
                                    <a class="btn btn-secondary dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        Aksi
                                    </a>
                                    <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                                        <a class="dropdown-item" href="{{ route('location.view', $location['resource']['id'] ?? 'N/A') }}">View Detail</a>
                                        <a class="dropdown-item" href="{{ route('location.edit', $location['resource']['id'] ?? 'N/A') }}">Edit Location</a>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        @endforeach
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
