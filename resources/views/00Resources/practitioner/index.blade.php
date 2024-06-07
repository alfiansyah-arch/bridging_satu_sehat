@extends('layouts')

@section('content')
<div class="container">
    <h2>Search Practitioners</h2>
    <form method="GET" action="{{ route('practitioner.search') }}">
        <div class="form-group">
            <label for="id">ID:</label>
            <input type="text" name="id" id="id" class="form-control">
        </div>
        <div class="form-group">
            <label for="nik">NIK:</label>
            <input type="text" name="nik" id="nik" class="form-control">
        </div>
        <div class="form-group">
            <label for="name">Name:</label>
            <input type="text" name="name" id="name" class="form-control">
        </div>
        <div class="form-group">
            <label for="gender">Gender:</label>
            <select name="gender" id="gender" class="form-control">
                <option value="male">Male</option>
                <option value="female">Female</option>
            </select>
        </div>
        <div class="form-group">
            <label for="birthdate">Birthdate:</label>
            <input type="date" name="birthdate" id="birthdate" class="form-control">
        </div>
        <button type="submit" class="btn btn-primary">Search</button>
    </form>

    @if(isset($practitioner))
        <h3>Practitioner Details</h3>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>NIK</th>
                    <th>Name</th>
                    <th>Gender</th>
                    <th>Birthdate</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>{{ $practitioner['id'] }}</td>
                    <td>{{ $practitioner['identifier'][0]['value'] }}</td>
                    <td>{{ $practitioner['name'][0]['text'] }}</td>
                    <td>{{ $practitioner['gender'] }}</td>
                    <td>{{ $practitioner['birthDate'] }}</td>
                </tr>
            </tbody>
        </table>
    @elseif(isset($error))
        <p class="text-danger">{{ $error }}</p>
    @endif
</div>
@endsection
