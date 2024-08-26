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

    <h1>Data Informasi Procedure</h1>
    <div class="dropdown show">
        <a class="btn btn-primary dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            Cari Berdasarkan
        </a>

        <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
            <a class="dropdown-item" href="{{route('procedure.search-by-id')}}">ID</a>
            <a class="dropdown-item" href="{{route('procedure.search-by-subject')}}">Subject</a>
            <a class="dropdown-item" href="{{route('procedure.search-by-subject-encounter')}}">Subject & Encounter</a>
            <a class="dropdown-item" href="{{route('procedure.search-by-subject')}}">Encounter</a>
        </div>
    </div>
    <br>
    <div class="card mb-3">
        <div class="card-body">
            <form action="{{ route('procedure.search-by-id') }}" method="GET">
                <div class="form-group">
                    <label for="id">Search by ID:</label>
                    <input type="text" class="form-control" id="id" name="id" placeholder="Enter procedure ID">
                </div>
                <button type="submit" class="btn btn-primary">Search</button>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table id="procedure-table" class="table display">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Body Site</th>
                            <th>Category</th>
                            <th>Code</th>
                            <th>Encounter</th>
                            <th>Subject</th>
                            <th>Status</th>
                            <th>Performer</th>
                            <th>Reason Code</th>
                            <th>Note</th>
                            <th>Performed Period Start</th>
                            <th>Performed Period End</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if (isset($procedureId))
                        @if ($procedure)
                        <tr>
                            <td>{{ $procedure['id'] ?? 'N/A' }}</td>
                            <td>
                                @foreach ($procedure['bodySite'] as $bodySite)
                                    @foreach ($bodySite['coding'] as $coding)
                                        {{ $coding['display'] }} ({{ $coding['code'] }})<br>
                                    @endforeach
                                @endforeach
                            </td>
                            <td>{{ $procedure['category']['text'] ?? 'N/A' }}</td>
                            <td>{{ $procedure['code']['coding'][0]['display'] ?? 'N/A' }} ({{ $procedure['code']['coding'][0]['code'] ?? 'N/A' }})</td>
                            <td>{{ $procedure['encounter']['display'] ?? 'N/A' }}</td>
                            <td>{{ $procedure['subject']['display'] ?? 'N/A' }}</td>
                            <td>{{ $procedure['status'] ?? 'N/A' }}</td>
                            <td>
                                @foreach ($procedure['performer'] as $performer)
                                    {{ $performer['actor']['display'] ?? 'N/A' }}<br>
                                @endforeach
                            </td>
                            <td>
                                @foreach ($procedure['reasonCode'] as $reason)
                                    @foreach ($reason['coding'] as $coding)
                                        {{ $coding['display'] }} ({{ $coding['code'] }})<br>
                                    @endforeach
                                @endforeach
                            </td>
                            <td>
                                @foreach ($procedure['note'] as $note)
                                    {{ $note['text'] ?? 'N/A' }}<br>
                                @endforeach
                            </td>
                            <td>{{ $procedure['performedPeriod']['start'] ?? 'N/A' }}</td>
                            <td>{{ $procedure['performedPeriod']['end'] ?? 'N/A' }}</td>
                            <td>
                                <div class="dropdown show">
                                    <a class="btn btn-secondary dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        Aksi
                                    </a>
                                    <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                                        <a class="dropdown-item" href="{{ route('procedure.view', $procedure['id']) }}">View Detail</a>
                                        <a class="dropdown-item" href="{{ route('procedure.edit', $procedure['id']) }}">Edit</a>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        @endif
                        @else
                        <tr>
                            <td colspan="13" style="text-align:center;">Data Tidak Ditemukan</td>
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
