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
    <h1>Data Information Organization!</h1>
    <div class="dropdown show">
        <a class="btn btn-primary dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            Cari Berdasarkan
        </a>

        <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
            <a class="dropdown-item" href="{{route('organization.search-by-id')}}">ID</a>
            <a class="dropdown-item" href="{{route('organization.search-by-name')}}">Name</a>
            <a class="dropdown-item" href="{{route('organization.search-by-partof')}}">Partof</a>
        </div>
    </div>
    <br>
    <div class="card mb-3">
        <div class="card-body">
            <form action="{{ route('organization.search-by-id') }}" method="GET">
                <div class="form-group">
                    <label for="id">Search by ID:</label>
                    <input type="text" class="form-control" id="id" name="id" placeholder="Enter organization ID">
                </div>
                <button type="submit" class="btn btn-primary">Search</button>
            </form>
        </div>
    </div>
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table id="organizations-table" class="table display">
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
                            <th>Tipe</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                        @if(isset($organizationId))
                            @if($organization)
                            <td>{{ $organization['id'] ?? 'N/A' }}</td>
                            <td>{{ $organization['name'] ?? 'N/A' }}</td>
                            <td>{{ $organization['address'][0]['country'] ?? 'N/A' }}</td>
                            <td>{{ $organization['address'][0]['city'] ?? 'N/A' }}</td>
                            <td>
                                {{ $organization['address'][0]['line'][0] ?? 'N/A' }}
                                , {{ $organization['address'][0]['extension'][0]['extension'][3]['valueCode'] ?? 'N/A' }}
                                , {{ $organization['address'][0]['extension'][0]['extension'][2]['valueCode'] ?? 'N/A' }}
                                , {{ $organization['address'][0]['extension'][0]['extension'][1]['valueCode'] ?? 'N/A' }}
                                , {{ $organization['address'][0]['extension'][0]['extension'][0]['valueCode'] ?? 'N/A' }}
                                , {{ $organization['address'][0]['postalCode'] ?? 'N/A' }}
                            </td>
                            <td>{{ $organization['telecom'][0]['value'] ?? 'N/A' }}</td>
                            <td>{{ $organization['telecom'][1]['value'] ?? 'N/A' }}</td>
                            <td>{{ $organization['telecom'][2]['value'] ?? 'N/A' }}</td>
                            <td>{{ $organization['type'][0]['coding'][0]['display'] ?? 'N/A' }}</td>
                            <td>
                                <div class="dropdown show">
                                    <a class="btn btn-secondary dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        Aksi
                                    </a>

                                    <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                                        <a class="dropdown-item" href="{{ route('organization.view', $organization['id']) }}">View Detail</a>
                                        <a class="dropdown-item" href="{{route('organization.edit', $organization['id'])}}">Edit</a>
                                    </div>
                                </div>
                            </td>
                            @endif
                            @else
                            <td colspan="8" class="text-center">Data Tidak Ditemukan</td>
                        @endif
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Tabel Kedua data diambil dari database lokal -->
    <h2>Data Local Organizations</h2>
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table id="local-organizations-table" class="table display">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>ID</th>
                            <th>Identifier</th>
                            <th>Name</th>
                            <th>Address</th>
                            <th>Phone</th>
                            <th>Email</th>
                            <th>Website</th>
                            <th>Part Of</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no=0; ?>
                        @foreach($organizations as $localOrganization)
                        <?php $no++ ?>
                        <tr>
                            <td>{{ $no }}</td>
                            <td>{{ $localOrganization->id_organization }}</td>
                            <td>{{ $localOrganization->identifier_value }}</td>
                            <td>{{ $localOrganization->name }}</td>
                            <td>{{ $localOrganization->address_line }}, {{ $localOrganization->address_city }}, {{ $localOrganization->address_postal_code }}, {{ $localOrganization->address_country }}</td>
                            <td>{{ $localOrganization->telecom_phone }}</td>
                            <td>{{ $localOrganization->telecom_email }}</td>
                            <td>{{ $localOrganization->telecom_url }}</td>
                            <td>{{ $localOrganization->part_of_id }}</td>
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
