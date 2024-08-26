@extends('layouts')

@section('content')
@if(session()->has('success'))
    <div class="alert alert-success">
        {{ session()->get('success') }}
    </div>
@endif
@if(session()->has('error'))
    <div class="alert alert-danger">
        {!! nl2br(e(session()->get('error'))) !!}
    </div>
@endif
<div class="container">
    <h2>Add Patient by NIK</h2>
    <form action="{{ route('patient.store-by-nik') }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="nik">NIK:</label>
            <input type="text" class="form-control" id="nik" name="nik" value="3174031002890009" required>
        </div>
        <div class="form-group">
            <label for="passport">Passport:</label>
            <input type="text" class="form-control" id="passport" name="passport" value="A01111222">
        </div>
        <div class="form-group">
            <label for="kk">KK:</label>
            <input type="text" class="form-control" id="kk" name="kk" value="367400001111111" required>
        </div>
        <div class="form-group">
            <label for="name">Name:</label>
            <input type="text" class="form-control" id="name" name="name" value="John Smith" required>
        </div>
        <div class="form-group">
            <label for="phone_mobile">Mobile Phone:</label>
            <input type="text" class="form-control" id="phone_mobile" name="phone_mobile" value="08123456789" required>
        </div>
        <div class="form-group">
            <label for="phone_home">Home Phone:</label>
            <input type="text" class="form-control" id="phone_home" name="phone_home" value="+622123456789">
        </div>
        <div class="form-group">
            <label for="email">Email:</label>
            <input type="email" class="form-control" id="email" name="email" value="john.smith@xyz.com">
        </div>
        <div class="form-group">
            <label for="gender">Gender:</label>
            <select class="form-control" id="gender" name="gender" required>
                <option value="male">Male</option>
                <option value="female">Female</option>
            </select>
        </div>
        <div class="form-group">
            <label for="birthdate">Birthdate:</label>
            <input type="date" class="form-control" id="birthdate" name="birthdate" required>
        </div>
        <div class="form-group">
            <label for="address_line">Address Line:</label>
            <input type="text" class="form-control" id="address_line" name="address_line" value="Gd. Prof. Dr. Sujudi Lt.5, Jl. H.R. Rasuna Said Blok X5 Kav. 4-9 Kuningan" required>
        </div>
        <div class="form-group">
            <label for="city">City:</label>
            <input type="text" class="form-control" id="city" name="city" value="Jakarta" required>
        </div>
        <div class="form-group">
            <label for="postal_code">Postal Code:</label>
            <input type="text" class="form-control" id="postal_code" name="postal_code" value="12950" required>
        </div>
        <div class="form-group">
            <label for="province_code">Province Code:</label>
            <input type="text" class="form-control" id="province_code" name="province_code" value="10" required>
        </div>
        <div class="form-group">
            <label for="city_code">City Code:</label>
            <input type="text" class="form-control" id="city_code" name="city_code" value="1010" required>
        </div>
        <div class="form-group">
            <label for="district_code">District Code:</label>
            <input type="text" class="form-control" id="district_code" name="district_code" value="1010101" required>
        </div>
        <div class="form-group">
            <label for="village_code">Village Code:</label>
            <input type="text" class="form-control" id="village_code" name="village_code" value="1010101101" required>
        </div>
        <div class="form-group">
            <label for="rt">RT:</label>
            <input type="text" class="form-control" id="rt" name="rt" value="2" required>
        </div>
        <div class="form-group">
            <label for="rw">RW:</label>
            <input type="text" class="form-control" id="rw" name="rw" value="2" required>
        </div>
        <div class="form-group">
            <label for="marital_status">Marital Status:</label>
            <select class="form-control" id="marital_status" name="marital_status" required>
                <option value="single">Single</option>
                <option value="married">Married</option>
            </select>
        </div>
        <div class="form-group">
            <label for="contact_name">Contact Name:</label>
            <input type="text" class="form-control" id="contact_name" name="contact_name" value="Jane Smith" required>
        </div>
        <div class="form-group">
            <label for="contact_phone">Contact Phone:</label>
            <input type="text" class="form-control" id="contact_phone" name="contact_phone" value="0690383372" required>
        </div>
        <div class="form-group">
            <label for="language">Language:</label>
            <input type="text" class="form-control" id="language" name="language" value="Indonesian" required>
        </div>
        <div class="form-group">
            <label for="birth_place_city">Birth Place City:</label>
            <input type="text" class="form-control" id="birth_place_city" name="birth_place_city" value="Bandung" required>
        </div>
        <div class="form-group">
            <label for="citizenship_status">Citizenship Status:</label>
            <input type="text" class="form-control" id="citizenship_status" name="citizenship_status" value="WNI" required>
        </div>
        <button type="submit" class="btn btn-primary">Submit</button>
    </form>
</div>
@endsection
