@extends('layouts')

@section('content')
<div class="container">
    <h1>Create New Location</h1>
    
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

    <form action="{{ route('location.post') }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="org_id">Organization ID</label>
            <input type="text" class="form-control" id="org_id" name="org_id" value="{{env('SATUSEHAT_ORGANIZATION_ID')}}" required>
        </div>
        <div class="form-group">
            <label for="identifier_value">Identifier Value</label>
            <input type="text" class="form-control" id="identifier_value" name="identifier_value" value="G-2-R-1A" required>
        </div>
        <div class="form-group">
            <label for="status">Status</label>
            <input type="text" name="status" id="status" class="form-control" value="active">
            <!-- <select name="status" id="status" class="form-control">
                <option value="" selected disabled>-- Select Status --</option>
                <option value="active">Active</option>
                <option value="inactive">Inactive</option>
            </select> -->
        </div>
        <div class="form-group">
            <label for="name">Name</label>
            <input type="text" class="form-control" id="name" name="name" value="Ruang 1A IRJT" required>
        </div>
        <div class="form-group">
            <label for="description">Description</label>
            <textarea class="form-control" id="description" name="description" required>Ruang 1A, Poliklinik Bedah Rawat Jalan Terpadu, Lantai 2, Gedung G
            </textarea>
        </div>
        <div class="form-group">
            <label for="mode">Mode</label>
            <input type="text" class="form-control" id="mode" name="mode" value="instance" required>
        </div>
        <div class="form-group">
            <label for="telecom_phone">Phone</label>
            <input type="text" class="form-control" id="telecom_phone" name="telecom_phone" value="2328" required>
        </div>
        <div class="form-group">
            <label for="telecom_fax">Fax</label>
            <input type="text" class="form-control" id="telecom_fax" name="telecom_fax" value="2329" required>
        </div>
        <div class="form-group">
            <label for="telecom_email">Email</label>
            <input type="email" class="form-control" id="telecom_email" name="telecom_email" value="klinik.mmc@gmail.com" required>
        </div>
        <div class="form-group">
            <label for="telecom_url">URL</label>
            <input type="url" class="form-control" id="telecom_url" name="telecom_url" value="http://sampleorg.com/southwing" required>
        </div>
        <div class="form-group">
            <label for="address_line">Address Line</label>
            <input type="text" class="form-control" id="address_line" name="address_line" value="Gd. Prof. Dr. Sujudi Lt.5, Jl. H.R. Rasuna Said Blok X5 Kav. 4-9 Kuningan" required>
        </div>
        <div class="form-group">
            <label for="address_city">City</label>
            <input type="text" class="form-control" id="address_city" name="address_city" value="Jakarta" required>
        </div>
        <div class="form-group">
            <label for="address_postalCode">Postal Code</label>
            <input type="text" class="form-control" id="address_postalCode" name="address_postalCode" value="12950" required>
        </div>
        <div class="form-group">
            <label for="address_country">Country</label>
            <input type="text" class="form-control" id="address_country" name="address_country" value="ID" required>
        </div>
        <div class="form-group">
            <label for="address_province">Province</label>
            <input type="text" class="form-control" id="address_province" name="address_province" value="10" required>
        </div>
        <div class="form-group">
            <label for="address_city_code">City Code</label>
            <input type="text" class="form-control" id="address_city_code" name="address_city_code" value="1010" required>
        </div>
        <div class="form-group">
            <label for="address_district">District</label>
            <input type="text" class="form-control" id="address_district" name="address_district" value="1010101" required>
        </div>
        <div class="form-group">
            <label for="address_village">Village</label>
            <input type="text" class="form-control" id="address_village" name="address_village" value="1010101101" required>
        </div>
        <div class="form-group">
            <label for="address_rt">RT</label>
            <input type="text" class="form-control" id="address_rt" name="address_rt" value="1" required>
        </div>
        <div class="form-group">
            <label for="address_rw">RW</label>
            <input type="text" class="form-control" id="address_rw" name="address_rw" value="2" required>
        </div>
        <div class="form-group">
            <label for="position_longitude">Longitude</label>
            <input type="text" class="form-control" id="position_longitude" name="position_longitude" value="-6.23115426275766"  required>
        </div>
        <div class="form-group">
            <label for="position_latitude">Latitude</label>
            <input type="text" class="form-control" id="position_latitude" name="position_latitude" value="106.83239885393944"  required>
        </div>
        <div class="form-group">
            <label for="position_altitude">Altitude</label>
            <input type="text" class="form-control" id="position_altitude" name="position_altitude" value="0" required>
        </div>
        <button type="submit" class="btn btn-primary">Create Location</button>
    </form>
</div>
@endsection
