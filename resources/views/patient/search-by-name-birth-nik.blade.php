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
            <form action="{{ route('patient.search-by-name-birth-nik') }}" method="GET">
                <div class="form-group">
                    <label for="nik">Search by</label>
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
                        <label for="nik">NIK</label>
                        <input type="text" name="nik" id="nik" class="form-control" placeholder="Enter NIK">
                    </div>
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
                            <th>Active</th>
                            <th>Name</th>
                            <th>Address</th>
                            <th>Birthdate</th>
                            <th>Gender</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                        @if(isset($patientNik))
                            @if($patient)
                            <td>{{ $patient['entry'][0]['resource']['id'] ?? 'N/A' }}</td>
                            @if($patient['entry'][0]['resource']['active'] == true)
                            <td>Active</td>
                            @elseif($patient['entry'][0]['resource']['active'] == false)
                            <td>Inactive</td>
                            @else
                            <td>{{ $patient['entry'][0]['resource']['active'] ?? 'N/A' }}</td>
                            @endif
                            <td>{{ $patient['entry'][0]['resource']['name'][0]['text'] ?? 'N/A' }}</td>
                            <td>
                            {{ $patient['entry'][0]['resource']['address'][0]['line'][0] ?? 'N/A' }},
                            {{ $patient['entry'][0]['resource']['address'][0]['city'] ?? 'N/A' }},
                            {{ $patient['entry'][0]['resource']['address'][0]['country'] ?? 'N/A' }}
                            </td>
                            <td>{{ $patient['entry'][0]['resource']['birthDate'] ?? 'N/A' }}</td>
                            <td>{{ $patient['entry'][0]['resource']['gender'] ?? 'N/A' }}</td>
                            <td>
                            <form action="{{ route('patient.view') }}" method="GET">
                                <div class="form-group" hidden>
                                    <label for="nik">Search by</label>
                                </div>
                                <div class="d-flex" hidden>
                                    <div class="form-group" hidden>
                                        <input type="text" name="name" id="name" value="{{$patientName}}" class="form-control" placeholder="Enter Name">
                                    </div>
                                    <div class="form-group mx-4" hidden>
                                        <input type="date" name="birthdate" id="birthdate" value="{{$patientBirth}}" class="form-control" placeholder="Enter BirthDate">
                                    </div>
                                    <div class="form-group mx-4" hidden>
                                        <input type="text" name="nik" id="nik" class="form-control" value="{{$patientNik}}" placeholder="Enter NIK">
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-success text-xs">View Detail</button>
                            </form>
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