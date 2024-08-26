@extends('layouts')

@section('content')
<div class="container">
    <h1>Bridging Satu Sehat!</h1>

    <!-- Success Message -->
    @if(isset($successMessage))    
        @if($successMessage)
            <div class="alert alert-success">{{ $successMessage }}</div>
        @endif
    @endif

    <!-- Error Message -->
    @if(isset($errorMessage))    
        @if($errorMessage)
            <div class="alert alert-success">{{ $errorMessage }}</div>
        @endif
    @endif

    <!-- Token Information -->
        <div class="card mt-4">
            @if(isset($accessToken))
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
                @endif
                <div class="card-footer">
                    <a href="{{route('generate-token')}}" class="btn btn-success">Generate Token</a>
            </div>
        </div>
        @php
            use App\Models\AccessToken;
            $accessToken = AccessToken::find(1);
        @endphp
        @if($accessToken && $accessToken->expired >= now())
        <div class="row">
            <div class="col-sm-3">
                <div class="card">
                <div class="card-body">
                    <a href="{{route('practitioner.search-by-id')}}" class="btn btn-success">Practitioners</a>
                </div>
                </div>
            </div>
            <div class="col-sm-3">
                <div class="card">
                <div class="card-body">
                    <div class="dropdown">
                        <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Organizations
                        </button>
                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                            <a class="dropdown-item" href="{{ route('organization.search-by-id') }}">Search</a>
                            <a class="dropdown-item" href="{{ route('organization.create') }}">Create</a>
                        </div>
                    </div>
                </div>
                </div>
            </div>
            <div class="col-sm-3">
                <div class="card">
                <div class="card-body">
                    <div class="dropdown">
                        <button class="btn btn-info dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Location
                        </button>
                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                            <a class="dropdown-item" href="{{ route('location.search-by-id') }}">Search</a>
                            <a class="dropdown-item" href="{{ route('location.create') }}">Create</a>
                        </div>
                    </div>
                </div>
                </div>
            </div>
            <div class="col-sm-3">
                <div class="card">
                <div class="card-body">
                    <div class="dropdown">
                        <button class="btn btn-warning dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Encounter
                        </button>
                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                            <a class="dropdown-item" href="{{ route('encounter.search-by-id') }}">Search</a>
                            <a class="dropdown-item" href="{{ route('encounter.create') }}">Create</a>
                        </div>
                    </div>
                </div>
                </div>
            </div>
            <div class="col-sm-3">
                <div class="card">
                <div class="card-body">
                    <div class="dropdown">
                        <button class="btn btn-danger dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Condition
                        </button>
                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                            <a class="dropdown-item" href="{{ route('condition.search-by-subject') }}">Search</a>
                            <a class="dropdown-item" href="{{ route('condition.create') }}">Create</a>
                        </div>
                    </div>
                </div>
                </div>
            </div>
            <div class="col-sm-3">
                <div class="card">
                <div class="card-body">
                    <div class="dropdown">
                        <button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Composition
                        </button>
                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                            <a class="dropdown-item" href="{{ route('composition.search-by-id') }}">Search</a>
                            <a class="dropdown-item" href="{{ route('composition.create') }}">Create</a>
                        </div>
                    </div>
                </div>
                </div>
            </div>
            <div class="col-sm-3">
                <div class="card">
                <div class="card-body">
                    <div class="dropdown">
                        <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Patient
                        </button>
                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                            <a class="dropdown-item" href="{{ route('patient.search-by-id') }}">Search</a>
                            <a class="dropdown-item" href="{{ route('patient.create-by-nik') }}">Create by NIK</a>
                            <a class="dropdown-item" href="{{ route('patient.create-by-nik') }}">Create by Mother's NIK (Newborn)</a>
                        </div>
                    </div>
                </div>
                </div>
            </div>
            <div class="col-sm-3">
                <div class="card">
                <div class="card-body">
                    <div class="dropdown">
                        <button class="btn btn-info dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Observation - TTV
                        </button>
                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                            <a class="dropdown-item" href="{{ route('observation.search-by-id') }}">Search</a>
                            <a class="dropdown-item" href="{{ route('observation.create') }}">Create</a>
                        </div>
                    </div>
                </div>
                </div>
            </div>
            <div class="col-sm-3">
                <div class="card">
                <div class="card-body">
                    <div class="dropdown">
                        <button class="btn btn-info dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Procedure
                        </button>
                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                            <a class="dropdown-item" href="{{ route('location.search-by-id') }}">Search</a>
                            <a class="dropdown-item" href="{{ route('location.create') }}">Create</a>
                        </div>
                    </div>
                </div>
                </div>
            </div>
            <div class="col-sm-3">
                <div class="card">
                <div class="card-body">
                    <div class="dropdown">
                        <button class="btn btn-success dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Medication
                        </button>
                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                            <a class="dropdown-item" href="{{ route('medication.search-by-id') }}">Search</a>
                            <a class="dropdown-item" href="{{ route('medication.create') }}">Create</a>
                        </div>
                    </div>
                </div>
                </div>
            </div>
            <div class="col-sm-3">
                <div class="card">
                <div class="card-body">
                    <div class="dropdown">
                        <button class="btn btn-warning dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Medication Request
                        </button>
                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                            <a class="dropdown-item" href="{{ route('medication.search-by-id') }}">Search</a>
                            <a class="dropdown-item" href="{{ route('medication.create') }}">Create</a>
                        </div>
                    </div>
                </div>
                </div>
            </div>
        </div>
    @endif
</div>

<!-- Countdown Timer Script -->
@if(isset($accessTokenExpiry))    
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
@endif
@endsection
