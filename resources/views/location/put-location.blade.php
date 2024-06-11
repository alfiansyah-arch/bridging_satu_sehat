@extends('layouts')

@section('content')
<div class="container">
    <h1>Edit Location</h1>
    
    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif
    
    @if (session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    <form action="{{ route('location.update', ['id' => $location['id']]) }}" method="POST">
    @csrf
    @method('PUT')
        <div class="form-group">
            <label for="org_id">Organization ID</label>
            <input type="text" class="form-control" id="org_id" name="org_id" value="{{ str_replace('Organization/', '', $location['managingOrganization']['reference'] ?? '') }}" required>
        </div>
        <div class="form-group">
            <label for="identifier_value">Identifier Value</label>
            <input type="text" class="form-control" id="identifier_value" name="identifier_value" value="{{ $location['identifier'][0]['value'] ?? '' }}" required>
        </div>
        <div class="form-group">
            <label for="status">Status</label>
            <input type="text" name="status" id="status" class="form-control" value="{{ $location['status'] ?? '' }}">
        </div>
        <div class="form-group">
            <label for="name">Name</label>
            <input type="text" class="form-control" id="name" name="name" value="{{ $location['name'] ?? '' }}" required>
        </div>
        <div class="form-group">
            <label for="description">Description</label>
            <textarea class="form-control" id="description" name="description" required>{{ $location['description'] ?? '' }}</textarea>
        </div>
        <div class="form-group">
            <label for="mode">Mode</label>
            <input type="text" class="form-control" id="mode" name="mode" value="{{ $location['mode'] ?? '' }}" required>
        </div>
        <div class="form-group">
            <label for="telecom_phone">Phone</label>
            <input type="text" class="form-control" id="telecom_phone" name="telecom_phone" value="{{ $location['telecom'][0]['value'] ?? '' }}" required>
        </div>
        <div class="form-group">
            <label for="telecom_fax">Fax</label>
            <input type="text" class="form-control" id="telecom_fax" name="telecom_fax" value="{{ $location['telecom'][1]['value'] ?? '' }}" required>
        </div>
        <div class="form-group">
            <label for="telecom_email">Email</label>
            <input type="email" class="form-control" id="telecom_email" name="telecom_email" value="{{ $location['telecom'][2]['value'] ?? '' }}" required>
        </div>
        <div class="form-group">
            <label for="telecom_url">URL</label>
            <input type="url" class="form-control" id="telecom_url" name="telecom_url" value="{{ $location['telecom'][3]['value'] ?? '' }}" required>
        </div>
        <div class="form-group">
            <label for="address_line">Address Line</label>
            <input type="text" class="form-control" id="address_line" name="address_line" value="{{ $location['address']['line'][0] ?? '' }}" required>
        </div>
        <div class="form-group">
            <label for="address_city">City</label>
            <input type="text" class="form-control" id="address_city" name="address_city" value="{{ $location['address']['city'] ?? '' }}" required>
        </div>
        <div class="form-group">
            <label for="address_postalCode">Postal Code</label>
            <input type="text" class="form-control" id="address_postalCode" name="address_postalCode" value="{{ $location['address']['postalCode'] ?? '' }}" required>
        </div>
        <div class="form-group">
            <label for="address_country">Country</label>
            <input type="text" class="form-control" id="address_country" name="address_country" value="{{ $location['address']['country'] ?? '' }}" required>
        </div>
        <div class="form-group">
            <label for="address_province">Province Code</label>
            <input type="text" class="form-control" id="address_province" name="address_province" value="{{ $location['address']['extension'][0]['extension'][0]['valueCode'] ?? '' }}" required>
        </div>
        <div class="form-group">
            <label for="address_city_code">City Code</label>
            <input type="text" class="form-control" id="address_city_code" name="address_city_code" value="{{ $location['address']['extension'][0]['extension'][1]['valueCode'] ?? '' }}" required>
        </div>
        <div class="form-group">
            <label for="address_district">District Code</label>
            <input type="text" class="form-control" id="address_district" name="address_district" value="{{ $location['address']['extension'][0]['extension'][2]['valueCode'] ?? '' }}" required>
        </div>
        <div class="form-group">
            <label for="address_village">Village Code</label>
            <input type="text" class="form-control" id="address_village" name="address_village" value="{{ $location['address']['extension'][0]['extension'][3]['valueCode'] ?? '' }}" required>
        </div>
        <div class="form-group">
            <label for="address_rt">RT</label>
            <input type="text" class="form-control" id="address_rt" name="address_rt" value="{{ $location['address']['extension'][0]['extension'][4]['valueCode'] ?? '' }}" required>
        </div>
        <div class="form-group">
            <label for="address_rw">RW</label>
            <input type="text" class="form-control" id="address_rw" name="address_rw" value="{{ $location['address']['extension'][0]['extension'][5]['valueCode'] ?? '' }}" required>
        </div>
        <div class="form-group">
            <label for="position_longitude">Longitude</label>
            <input type="text" class="form-control" id="position_longitude" name="position_longitude" value="{{ $location['position']['longitude'] ?? '' }}"  required>
        </div>
        <div class="form-group">
            <label for="position_latitude">Latitude</label>
            <input type="text" class="form-control" id="position_latitude" name="position_latitude" value="{{ $location['position']['latitude'] ?? '' }}"  required>
        </div>
        <div class="form-group">
            <label for="position_altitude">Altitude</label>
            <input type="text" class="form-control" id="position_altitude" name="position_altitude" value="{{ $location['position']['altitude'] ?? '' }}" required>
        </div>
        <!-- Other fields and input elements go here -->
        <button type="submit" class="btn btn-primary">Update Location</button>
    </form>
</div>
@endsection
