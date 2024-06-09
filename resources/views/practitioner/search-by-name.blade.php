@extends('layouts')

@section('content')
<div class="container">
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
                <a href="{{ route('generate-token') }}" class="btn btn-success text-xs">Generate Token</a>
            </div>
        </div>
    @endif

    <h1>Data Information Practitioners!</h1>
    <div class="dropdown show">
        <a class="btn btn-primary dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            Cari Berdasarkan
        </a>

        <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
            <a class="dropdown-item" href="{{ route('practitioner.search-by-id') }}">ID</a>
            <a class="dropdown-item" href="{{ route('practitioner.search-by-nik') }}">NIK</a>
            <a class="dropdown-item" href="{{ route('practitioner.search-by-name') }}">Name, Gender, and Birthdate</a>
        </div>
    </div>
    <br>
    <div class="card mb-3">
        <div class="card-body">
            <form action="{{ route('practitioner.search-by-name') }}" method="GET">
                <div class="d-flex">
                    <div class="col">
                        <div class="form-group">
                            <label for="name">Name:</label>
                            <input type="text" class="form-control" id="name" name="name" placeholder="Enter practitioner name" required>
                        </div>
                    </div>
                    <div class="col">
                        <div class="form-group">
                            <label for="gender">Gender:</label>
                            <select name="gender" id="gender" class="form-control" required>
                                <option value="" selected disabled>-- Pilih Gender --</option>
                                <option value="male">Male</option>
                                <option value="female">Female</option>
                            </select>
                        </div>
                    </div>
                    <div class="col">
                        <div class="form-group">
                            <label for="birthdate">Birthdate:</label>
                            <input type="text" class="form-control" id="birthdate" name="birthdate" placeholder="Enter practitioner birthdate" required>
                        </div>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary">Search</button>
            </form>
        </div>
    </div>
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table id="practitioners-table" class="table display">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Gender</th>
                            <th>Birth Date</th>
                            <th>Address</th>
                            <th>Phone</th>
                            <th>Email</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                    @if(isset($practitioner['entry']))
                        @foreach($practitioner['entry'] as $entry)
                            <tr>
                                <td>{{ $entry['resource']['id'] ?? 'N/A' }}</td>
                                <td>
                                    @isset($entry['resource']['name'][0]['prefix'])
                                        @foreach($entry['resource']['name'][0]['prefix'] as $prefix)
                                            {{ $prefix }} 
                                        @endforeach
                                    @endisset
                                    {{ $entry['resource']['name'][0]['text'] ?? 'N/A' }} 
                                    @isset($entry['resource']['name'][0]['suffix'])
                                        @foreach($entry['resource']['name'][0]['suffix'] as $suffix)
                                            {{ $suffix }} 
                                        @endforeach
                                    @endisset
                                </td>
                                <td>{{ $entry['resource']['gender'] ?? 'N/A' }}</td>
                                <td>{{ $entry['resource']['birthDate'] ?? 'N/A' }}</td>
                                <td>{{ $entry['resource']['address'][0]['line'][0] ?? 'N/A' }}, {{ $entry['resource']['address'][0]['city'] ?? 'N/A' }}</td>
                                <td>{{ $entry['resource']['telecom'][0]['value'] ?? 'N/A' }}</td>
                                <td>{{ $entry['resource']['telecom'][2]['value'] ?? 'N/A' }}</td>
                                <td>
                                    <div class="dropdown show">
                                        <a class="btn btn-secondary dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            Actions
                                        </a>
                                        <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                                            <a class="dropdown-item" href="{{ route('practitioner.view', $entry['resource']['id'] ?? 'N/A') }}">View Detail</a>
                                            <a class="dropdown-item" href="#">Another action</a>
                                            <a class="dropdown-item" href="#">Something else here</a>
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
