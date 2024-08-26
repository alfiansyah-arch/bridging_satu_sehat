@extends('layouts')

@section('content')
<div class="container">
    <h1>Edit Organization</h1>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    <form action="{{ route('organization.update', ['id' => $organization['id']]) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="form-group">
            <label for="identifier_value">Identifier Value</label>
            <input type="text" class="form-control" id="identifier_value" name="identifier_value" value="{{ $organization['identifier'][0]['value'] ?? ''}}" required>
        </div>
        <div class="form-group">
            <label for="name">Organization Name</label>
            <input type="text" class="form-control" id="name" name="name" value="{{ $organization['name'] ?? ''}}" required>
        </div>
        <div class="form-group">
            <label for="phone">Phone</label>
            <input type="text" class="form-control" id="phone" name="phone" value="{{ $organization['telecom'][0]['value'] ?? '' }}" required>
        </div>
        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" class="form-control" id="email" name="email" value="{{ $organization['telecom'][1]['value'] ?? '' }}" required>
        </div>
        <div class="form-group">
            <label for="url">URL</label>
            <input type="text" class="form-control" id="url" name="url" value="{{ $organization['telecom'][2]['value'] ?? '' }}" required>
        </div>
        <div class="form-group">
            <label for="line">Address Line</label>
            <input type="text" class="form-control" id="line" name="line" value="{{ $organization['address'][0]['line'][0] ?? '' }}" required>
        </div>
        <div class="form-group">
            <label for="city">City</label>
            <input type="text" class="form-control" id="city" name="city" value="{{ $organization['address'][0]['city'] ?? '' }}" required>
        </div>
        <div class="form-group">
            <label for="postalCode">Postal Code</label>
            <input type="text" class="form-control" id="postalCode" name="postalCode" value="{{ $organization['address'][0]['postalCode'] ?? '' }}" required>
        </div>
        <div class="form-group">
            <label for="province">Province Code</label>
            <input type="text" class="form-control" id="province" name="province" value="{{ $organization['address'][0]['extension'][0]['extension'][0]['valueCode'] ?? '' }}" required>
        </div>
        <div class="form-group">
            <label for="city_code">City Code</label>
            <input type="text" class="form-control" id="city_code" name="city_code" value="{{ $organization['address'][0]['extension'][0]['extension'][1]['valueCode'] ?? '' }}" required>
        </div>
        <div class="form-group">
            <label for="district">District Code</label>
            <input type="text" class="form-control" id="district" name="district" value="{{ $organization['address'][0]['extension'][0]['extension'][2]['valueCode'] ?? '' }}" required>
        </div>
        <div class="form-group">
            <label for="village">Village Code</label>
            <input type="text" class="form-control" id="village" name="village" value="{{ $organization['address'][0]['extension'][0]['extension'][3]['valueCode'] ?? '' }}" required>
        </div>
        <div class="form-group">
            <label for="partOf">Part Of (Organization ID)</label>
            <input type="text" class="form-control" id="partOf" name="partOf" value="{{ str_replace('Organization/', '', $organization['partOf']['reference'] ?? '') }}" required>
        </div>
        <button type="submit" class="btn btn-primary">Submit</button>
    </form>
</div>
@endsection
