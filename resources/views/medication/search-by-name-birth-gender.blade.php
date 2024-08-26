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

    <h1>Data Information Patient!</h1>
    <div class="dropdown show">
        <a class="btn btn-primary dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            Cari Berdasarkan
        </a>
        <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
            <a class="dropdown-item" href="{{route('patient.search-by-id')}}">ID</a>
            <a class="dropdown-item" href="{{route('patient.search-by-nik')}}">NIK</a>
            <a class="dropdown-item" href="{{route('patient.search-by-bayi')}}">Bayi by NIK Ibu</a>
            <a class="dropdown-item" href="{{route('patient.search-by-name-birth-nik')}}">Name, Birth, and NIK</a>
            <a class="dropdown-item" href="{{route('patient.search-by-name-birth-gender')}}">Name, Birth, and Gender</a>
        </div>
    </div>
    <br>
    <div class="card mb-3">
        <div class="card-body">
            <form action="{{ route('patient.search-by-name-birth-gender') }}" method="GET">
                <div class="form-group">
                    <label for="search">Search by</label>
                </div>
                <div class="d-flex">
                    <div class="form-group">
                        <label for="name">Name</label>
                        <input type="text" name="name" id="name" class="form-control" placeholder="Enter Name">
                    </div>
                    <div class="form-group mx-4">
                        <label for="birthdate">Birthdate</label>
                        <input type="date" name="birthdate" id="birthdate" class="form-control" placeholder="Enter BirthDate">
                    </div>
                    <div class="form-group mx-4">
                        <label for="gender">Gender</label>
                        <select class="form-control" name="gender" id="gender">
                            <option value="" selected disabled>-- Pilih Gender --</option>
                            <option value="male">Laki-laki</option>
                            <option value="female">Perempuan</option>
                        </select>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary">Search</button>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table id="patients-table" class="table display">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Active</th>
                            <th>Name</th>
                            <th>Address</th>
                            <th>Birthdate</th>
                            <th>Gender</th>
                            <th>Deceased</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if(isset($patient['entry']))
                            @foreach($patient['entry'] as $entry)
                                <tr>
                                    <td>{{ $entry['resource']['id'] ?? 'N/A' }}</td>
                                    <td>{{ $entry['resource']['active'] ? 'Active' : 'Inactive' }}</td>
                                    <td>{{ $entry['resource']['name'][0]['text'] ?? 'N/A' }}</td>
                                    <td>
                                        @if(isset($entry['resource']['address'][0]))
                                            {{ $entry['resource']['address'][0]['line'][0] ?? '' }},
                                            {{ $entry['resource']['address'][0]['city'] ?? '' }},
                                            {{ $entry['resource']['address'][0]['country'] ?? '' }}
                                        @else
                                            N/A
                                        @endif
                                    </td>
                                    <td>{{ $entry['resource']['birthDate'] ?? 'N/A' }}</td>
                                    <td>{{ $entry['resource']['gender'] ?? 'N/A' }}</td>
                                    <td>{{ $entry['resource']['deceasedBoolean'] ? 'Yes' : 'No' }}</td>
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
