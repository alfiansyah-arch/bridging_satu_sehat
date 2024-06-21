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

    <h1>Data Informasi Condition</h1>
    <div class="dropdown show">
        <a class="btn btn-primary dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            Cari Berdasarkan
        </a>

        <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
            <a class="dropdown-item" href="{{route('condition.search-by-id')}}">ID</a>
            <a class="dropdown-item" href="{{route('condition.search-by-subject')}}">Subject</a>
            <a class="dropdown-item" href="{{route('condition.search-by-subject-encounter')}}">Subject & Encounter</a>
            <a class="dropdown-item" href="{{route('condition.search-by-encounter')}}">Encounter</a>
        </div>
    </div>
    <br>
    <div class="card mb-3">
        <div class="card-body">
            <form action="{{ route('condition.search-by-subject') }}" method="GET">
                <div class="form-group">
                    <label for="subject">Search by Subject:</label>
                    <input type="text" class="form-control" id="subject" name="subject" placeholder="Enter condition subject">
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
                            <th>No.</th>
                            <th>Resource Type</th>
                            <th>Severity</th>
                            <th>Code</th>
                            <th>Details</th>
                            <th>Diagnostics</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if (isset($condition))
                            @if (!empty($condition['entry']))
                                <?php $no = 1; ?>
                                @foreach ($condition['entry'] as $entry)
                                    <tr>
                                        <td>{{ $no++ }}</td>
                                        <td>{{ $entry['resource']['resourceType'] ?? 'N/A' }}</td>
                                        <td>{{ $entry['resource']['issue'][0]['severity'] ?? 'N/A' }}</td>
                                        <td>{{ $entry['resource']['issue'][0]['code'] ?? 'N/A' }}</td>
                                        <td>{{ $entry['resource']['issue'][0]['details']['text'] ?? 'N/A' }}</td>
                                        <td>{{ $entry['resource']['issue'][0]['diagnostics'] ?? 'N/A' }}</td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="6" style="text-align:center;">Data Tidak Ditemukan</td>
                                </tr>
                            @endif
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
