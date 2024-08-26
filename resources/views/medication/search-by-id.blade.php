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
                <a href="{{ route('generate-token') }}" class="btn btn-success text-xs">Generate Token</a>
            </div>
        </div>
    @endif

    <h1>Data Information Medication!</h1>
    <br>
    <div class="card mb-3">
        <div class="card-body">
            <form action="{{ route('medication.search-by-id') }}" method="GET">
                <div class="form-group">
                    <label for="id">Search by ID:</label>
                    <input type="text" class="form-control" id="id" name="id" placeholder="Enter medication ID">
                </div>
                <button type="submit" class="btn btn-primary">Search</button>
            </form>
        </div>
    </div>

    @if(isset($medication))
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table id="medication-table" class="table display">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Status</th>
                                <th>Identifier Use</th>
                                <th>Identifier Value</th>
                                <th>Code</th>
                                <th>Display</th>
                                <th>Form Code</th>
                                <th>Form Display</th>
                                <th>Ingredients</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>{{ $medication['id'] ?? 'N/A' }}</td>
                                <td>{{ $medication['status'] ?? 'N/A' }}</td>
                                <td>{{ $medication['identifier'][0]['use'] ?? 'N/A' }}</td>
                                <td>{{ $medication['identifier'][0]['value'] ?? 'N/A' }}</td>
                                <td>{{ $medication['code']['coding'][0]['code'] ?? 'N/A' }}</td>
                                <td>{{ $medication['code']['coding'][0]['display'] ?? 'N/A' }}</td>
                                <td>{{ $medication['form']['coding'][0]['code'] ?? 'N/A' }}</td>
                                <td>{{ $medication['form']['coding'][0]['display'] ?? 'N/A' }}</td>
                                <td>
                                    @if(isset($medication['ingredient']))
                                    <ul>
                                        @foreach($medication['ingredient'] as $ingredient)
                                            <li>
                                                {{ $ingredient['itemCodeableConcept']['coding'][0]['display'] }}:
                                                {{ $ingredient['strength']['numerator']['value'] }}
                                                {{ $ingredient['strength']['numerator']['code'] }}
                                            </li>
                                        @endforeach
                                    </ul>
                                    @else
                                    <p>Tidak ada bahan</p>
                                    @endif
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @else
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table id="medication-table" class="table display">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Status</th>
                                <th>Identifier Use</th>
                                <th>Identifier Value</th>
                                <th>Code</th>
                                <th>Display</th>
                                <th>Form Code</th>
                                <th>Form Display</th>
                                <th>Ingredients</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td colspan="9" class="text-center">Data Tidak Ditemukan</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @endif
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
