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
                <a href="{{route('satusehat.index')}}" class="btn btn-success text-xs">Generate Token</a>
            </div>
        </div>
    @endif

    <h1>Detail Encounter</h1>
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table id="encounter-detail-table" class="table display">
                    <thead>
                        <tr>
                            <th>Key</th>
                            <th>Value</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Class Code</td>
                            <td>{{ $encounter['class']['code'] ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <td>Class Display</td>
                            <td>{{ $encounter['class']['display'] ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <td>ID</td>
                            <td>{{ $encounter['id'] ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <td>Identifier</td>
                            <td>{{ $encounter['identifier'][0]['value'] ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <td>Location</td>
                            <td>{{ $encounter['location'][0]['location']['display'] ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <td>Practitioner</td>
                            <td>{{ $encounter['participant'][0]['individual']['display'] ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <td>Type Code</td>
                            <td>{{ $encounter['participant'][0]['type'][0]['coding'][0]['code'] ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <td>Type Display</td>
                            <td>{{ $encounter['participant'][0]['type'][0]['coding'][0]['display'] ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <td>Subject</td>
                            <td>{{ $encounter['subject']['display'] ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <td>Status</td>
                            <td>{{ $encounter['status'] ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <td>Meta</td>
                            <td>Last Updated: {{ $encounter['meta']['lastUpdated'] ?? 'N/A' }}, Version ID: {{ $encounter['meta']['versionId'] ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <td>Service Provider</td>
                            <td>{{ $encounter['serviceProvider']['reference'] ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <td>Period</td>
                            <td>Start: {{ $encounter['period']['start'] ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <td>Status History</td>
                            <td>
                                @if(isset($encounter['statusHistory']))
                                    @foreach ($encounter['statusHistory'] as $status)
                                        Start: {{ $status['period']['start'] ?? 'N/A' }}, Status: {{ $status['status'] ?? 'N/A' }}<br>
                                    @endforeach
                                @else
                                    N/A
                                @endif
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <a href="{{ url()->previous() }}" class="btn btn-danger text-xs mt-3">Kembali</a>
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
