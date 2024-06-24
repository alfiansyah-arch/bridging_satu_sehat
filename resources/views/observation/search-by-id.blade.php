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
    <h1>Data Information Observation TTV!</h1>
    <div class="dropdown show">
        <a class="btn btn-primary dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            Cari Berdasarkan
        </a>

        <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
            <a class="dropdown-item" href="{{route('observation.search-by-id')}}">ID</a>
            <a class="dropdown-item" href="{{route('observation.search-by-subject')}}">Subject</a>
            <a class="dropdown-item" href="{{route('observation.search-by-subject-encounter')}}">Subject & Encounter</a>
            <a class="dropdown-item" href="{{route('observation.search-by-encounter')}}">Encounter</a>
            <!-- <a class="dropdown-item" href="{{route('observation.search-by-service-request')}}">Service Request</a> -->
        </div>
    </div>
    <br>
    <div class="card mb-3">
        <div class="card-body">
            <form action="{{ route('observation.search-by-id') }}" method="GET">
                <div class="form-group">
                    <label for="id">Search by ID:</label>
                    <input type="text" class="form-control" id="id" name="id" placeholder="Enter observation ID">
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
                            <th>Category</th>
                            <th>Effective Date Time</th>
                            <th>Encounter</th>
                            <th>Issued</th>
                            <th>Performer</th>
                            <th>Subject</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                        @if(isset($observationId))
                            @if($observation)
                            <td>{{$observation['id'] ?? 'N/A'}}</td>
                            <td>{{$observation['category'][0]['coding'][0]['display'] ?? 'N/A'}}</td>
                            <td>{{$observation['effectiveDateTime'] ?? 'N/A'}}</td>
                            <td>{{$observation['encounter']['display'] ?? 'N/A'}}</td>
                            <td>{{$observation['issued'] ?? 'N/A'}}</td>
                            <td>
                            <a href="{{route('practitioner.view',str_replace('Practitioner/', '', $observation['performer'][0]['reference'] ?? 'N/A' ?? ''))}}" target="_blank">
                            {{$observation['performer'][0]['reference'] ?? 'N/A'}}
                            </a>   
                            </td>
                            <td>
                            <a href="{{env('SATUSEHAT_FHIR_URL')}}{{$observation['subject']['reference'] ??'N/A'}}" target="_blank">
                            {{$observation['subject']['reference'] ??'N/A'}}
                            </a>   
                            </td>
                            @else
                            <td colspan="8" class="text-center">Data Tidak Ditemukan</td>
                        @endif
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