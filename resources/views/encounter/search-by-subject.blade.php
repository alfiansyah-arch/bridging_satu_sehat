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

    <h1>Data Informasi Encounter</h1>
    <div class="dropdown show">
        <a class="btn btn-primary dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            Cari Berdasarkan
        </a>

        <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
            <a class="dropdown-item" href="{{route('encounter.search-by-id')}}">ID</a>
            <a class="dropdown-item" href="{{route('encounter.search-by-subject')}}">Subject</a>
        </div>
    </div>
    <br>
    <div class="card mb-3">
        <div class="card-body">
            <form action="{{ route('encounter.search-by-subject') }}" method="GET">
                <div class="form-group">
                    <label for="subject">Search by Subject:</label>
                    <input type="text" class="form-control" id="subject" name="subject" placeholder="Enter encounter subject">
                </div>
                <button type="submit" class="btn btn-primary">Search</button>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table id="encounter-table" class="table display">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>ID</th>
                            <th>Class Code</th>
                            <th>Class Display</th>
                            <th>Identifier</th>
                            <th>Location</th>
                            <th>Practitioner</th>
                            <th>Type Code</th>
                            <th>Type Display</th>
                            <th>Subject</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if (isset($encounterSubject))
                        @if ($encounter)
                        <?php $no=0; ?>
                            @foreach ($encounter['entry'] as $entry)
                            <?php $no++ ?>
                            <tr>
                                <td>{{ $no }}</td>
                                <td>{{ $entry['resource']['id'] ?? 'N/A' }}</td>
                                <td>{{ $entry['resource']['class']['code'] ?? 'N/A' }}</td>
                                <td>{{ $entry['resource']['class']['display'] ?? 'N/A' }}</td>
                                <td>{{ $entry['resource']['identifier'][0]['value'] ?? 'N/A' }}</td>
                                <td>{{ $entry['resource']['location'][0]['location']['display'] ?? 'N/A' }}</td>
                                <td>{{ $entry['resource']['participant'][0]['individual']['display'] ?? 'N/A' }}</td>
                                <td>{{ $entry['resource']['participant'][0]['type'][0]['coding'][0]['code'] ?? 'N/A' }}</td>
                                <td>{{ $entry['resource']['participant'][0]['type'][0]['coding'][0]['display'] ?? 'N/A' }}</td>
                                <td>{{ $entry['resource']['subject']['display'] ?? 'N/A' }}</td>
                                <td>{{ $entry['resource']['status'] ?? 'N/A' }}</td>
                                <td>
                                    <div class="dropdown show">
                                        <a class="btn btn-secondary dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            Aksi
                                        </a>

                                        <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                                            <a class="dropdown-item" href="{{ route('encounter.view', $entry['resource']['id']) }}">View Detail</a>
                                            <a class="dropdown-item" href="{{route('encounter.edit', $entry['resource']['id'])}}">Edit</a>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="11" style="text-align:center;">Data Tidak Ditemukan</td>
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
