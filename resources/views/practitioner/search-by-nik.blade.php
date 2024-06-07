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
                    <a href="{{route('generate-token')}}" class="btn btn-success text-xs">Generate Token</a>
                </div>
            </div>
    @endif
    <h1>Data Information Practitioners!</h1>
    <div class="dropdown show">
        <a class="btn btn-primary dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            Cari Berdasarkan
        </a>

        <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
            <a class="dropdown-item" href="{{route('practitioner.search-by-id')}}">ID</a>
            <a class="dropdown-item" href="{{route('practitioner.search-by-nik')}}">NIK</a>
            <a class="dropdown-item" href="{{route('practitioner.search-by-name')}}">Name, Gender, and Birthdate</a>
        </div>
    </div>
    <br>
    <div class="card mb-3">
        <div class="card-body">
            <form action="{{ route('practitioner.search-by-nik') }}" method="GET">
                <div class="form-group">
                    <label for="nik">Search by NIK:</label>
                    <input type="text" class="form-control" id="nik" name="nik" placeholder="Enter practitioner NIK">
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
                            <th>NIK</th>
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
                        <tr>
                        @if(isset($practitionerNik))
                            @if($practitioner)
                            <td>{{ $practitioner['entry'][0]['resource']['id'] }}</td>
                            <td>{{ $practitioner['entry'][0]['resource']['identifier'][1]['value'] ?? 'N/A' }}</td>
                            <td>{{ $practitioner['entry'][0]['resource']['name'][0]['text'] ?? 'N/A' }}</td>
                            <td>{{ $practitioner['entry'][0]['resource']['gender'] ?? 'N/A' }}</td>
                            <td>{{ $practitioner['entry'][0]['resource']['birthDate'] ?? 'N/A' }}</td>
                            <td>{{ $practitioner['entry'][0]['resource']['address'][0]['line'][0] ?? 'N/A' }}, {{ $practitioner['entry'][0]['resource']['address'][0]['city'] ?? 'N/A' }}</td>
                            <td>{{ $practitioner['entry'][0]['resource']['telecom'][0]['value'] ?? 'N/A' }}</td>
                            <td>{{ $practitioner['entry'][0]['resource']['telecom'][2]['value'] ?? 'N/A' }}</td>
                            <td>
                                <div class="dropdown show">
                                    <a class="btn btn-secondary dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        Aksi
                                    </a>

                                    <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                                        <a class="dropdown-item" href="{{ route('practitioner.view', $practitioner['entry'][0]['resource']['id']) }}">View Detail</a>
                                        <a class="dropdown-item" href="#">Another action</a>
                                        <a class="dropdown-item" href="#">Something else here</a>
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