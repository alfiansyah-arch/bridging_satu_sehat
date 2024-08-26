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

    <h1>Data Informasi Organisasi!</h1>
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
            <form action="{{ route('organization.search-by-name') }}" method="GET">
                <div class="form-group">
                    <label for="name">Search by Name:</label>
                    <input type="text" class="form-control" id="name" name="name" placeholder="Enter organization name">
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
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if(isset($organization['entry']))
                            @foreach($organization['entry'] as $entry)
                                <tr>
                                    <td>{{ $entry['resource']['id'] ?? 'N/A' }}</td>
                                    <td>{{ $entry['resource']['name'] ?? 'N/A' }}</td>
                                    <td>{{ $entry['resource']['address'][0]['country'] ?? 'N/A' }}</td>
                                    <td>{{ $entry['resource']['address'][0]['city'] ?? 'N/A' }}</td>
                                    <td>
                                        {{ $entry['resource']['address'][0]['line'][0] ?? 'N/A' }}
                                    </td>
                                    <td>
                                        @if(isset($entry['resource']['telecom']))
                                            @foreach($entry['resource']['telecom'] as $telecom)
                                                @if($telecom['system'] == 'phone')
                                                    {{ $telecom['value'] }}
                                                @endif
                                            @endforeach
                                        @else
                                            N/A
                                        @endif
                                    </td>
                                    <td>
                                        @if(isset($entry['resource']['telecom']))
                                            @foreach($entry['resource']['telecom'] as $telecom)
                                                @if($telecom['system'] == 'email')
                                                    {{ $telecom['value'] }}
                                                @endif
                                            @endforeach
                                        @else
                                            N/A
                                        @endif
                                    </td>
                                    <td>
                                    <div class="dropdown show">
                                        <a class="btn btn-secondary dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            Aksi
                                        </a>

                                        <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                                            <a class="dropdown-item" href="{{ route('organization.view', $entry['resource']['id']) }}">View Detail</a>
                                        </div>
                                    </div>
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="8" class="text-center">Data Tidak Ditemukan</td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>

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
