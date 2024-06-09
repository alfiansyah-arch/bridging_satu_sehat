@extends('layouts')

@section('content')
<div class="container">
    <h1>Bridging Satu Sehat!</h1>

    <!-- Success Message -->
    @if($successMessage)
        <div class="alert alert-success">{{ $successMessage }}</div>
    @endif

    <!-- Error Message -->
    @if($errorMessage)
        <div class="alert alert-danger">{{ $errorMessage }}</div>
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
        </div>
</div>

<!-- Countdown Timer Script -->
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
