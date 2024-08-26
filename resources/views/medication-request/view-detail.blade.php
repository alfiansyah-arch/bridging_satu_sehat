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
    <h1>Detail Informasi Pasien</h1>
    <div class="card mb-3">
        <div class="card-body">
            <h2>Informasi Pasien</h2>
            @if(isset($patient['entry'][0]['resource']))
                @php
                    $resource = $patient['entry'][0]['resource'];
                @endphp
                <p><strong>ID:</strong> {{ $resource['id'] ?? 'N/A' }}</p>
                <p><strong>Nama:</strong> {{ $resource['name'][0]['text'] ?? 'N/A' }}</p>
                <p><strong>Jenis Kelamin:</strong> {{ $resource['gender'] ?? 'N/A' }}</p>
                <p><strong>Tanggal Lahir:</strong> {{ $resource['birthDate'] ?? 'N/A' }}</p>
                <p><strong>Alamat:</strong></p>
                <ul>
                    <li>
                        {{ $resource['address'][0]['line'][0] ?? 'N/A' }},
                        {{ $resource['address'][0]['city'] ?? 'N/A' }},
                        {{ $resource['address'][0]['country'] ?? 'N/A' }}
                    </li>
                </ul>
                <p><strong>Kode Administratif:</strong></p>
                <ul>
                    @foreach ($resource['address'][0]['extension'][0]['extension'] as $ext)
                        <li>{{ ucfirst($ext['url']) }}: {{ $ext['valueCode'] }}</li>
                    @endforeach
                </ul>
                <p><strong>Komunikasi:</strong></p>
                <ul>
                    @foreach ($resource['communication'] as $communication)
                        <li>
                            Bahasa: {{ $communication['language']['text'] ?? 'N/A' }},
                            Preferensi: {{ $communication['preferred'] ? 'Ya' : 'Tidak' }}
                        </li>
                    @endforeach
                </ul>
                <p><strong>Identifikasi:</strong></p>
                <ul>
                    @foreach ($resource['identifier'] as $identifier)
                        <li>
                            Sistem: {{ $identifier['system'] ?? 'N/A' }},
                            Nilai: {{ $identifier['value'] ?? 'N/A' }}
                        </li>
                    @endforeach
                </ul>
                <p><strong>Status Kewarganegaraan:</strong> {{ $resource['extension'][0]['valueCode'] ?? 'N/A' }}</p>
                <p><strong>Terakhir Diperbarui:</strong> {{ $resource['meta']['lastUpdated'] ?? 'N/A' }}</p>
            @else
                <p>Data tidak ditemukan</p>
            @endif
        </div>
        <a href="{{ url()->previous() }}" class="btn btn-danger text-xs mt-3">Kembali</a>
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
