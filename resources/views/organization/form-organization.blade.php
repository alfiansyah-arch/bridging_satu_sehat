@extends('layouts')

@section('content')
<div class="container">
    <h1>Create Organization</h1>

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

    <form action="{{ route('organization.create') }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="identifier_value">Identifier Value</label>
            <input type="text" class="form-control" id="identifier_value" name="identifier_value" value="Pos Imunisasi LUBUK BATANG" required>
        </div>
        <div class="form-group">
            <label for="name">Organization Name</label>
            <input type="text" class="form-control" id="name" name="name" value="Pos Imunisasi" required>
        </div>
        <div class="form-group">
            <label for="phone">Phone</label>
            <input type="text" class="form-control" id="phone" name="phone" value="+6221-783042654" required>
        </div>
        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" class="form-control" id="email" name="email" value="rs-satusehat@gmail.com" required>
        </div>
        <div class="form-group">
            <label for="url">URL</label>
            <input type="text" class="form-control" id="url" name="url" value="www.rs-satusehat@gmail.com" required>
        </div>
        <div class="form-group">
            <label for="line">Address Line</label>
            <input type="text" class="form-control" id="line" name="line" value="Jalan Jati Asih" required>
        </div>
        <div class="form-group">
            <label for="city">City</label>
            <input type="text" class="form-control" id="city" name="city" value="Jakarta" required>
        </div>
        <div class="form-group">
            <label for="postalCode">Postal Code</label>
            <input type="text" class="form-control" id="postalCode" name="postalCode" value="55292" required>
        </div>
        <div class="form-group">
            <label for="province">Province Code</label>
            <input type="text" class="form-control" id="province" name="province" value="31" required>
        </div>
        <div class="form-group">
            <label for="city_code">City Code</label>
            <input type="text" class="form-control" id="city_code" name="city_code" value="3171" required>
        </div>
        <div class="form-group">
            <label for="district">District Code</label>
            <input type="text" class="form-control" id="district" name="district" value="317101" required>
        </div>
        <div class="form-group">
            <label for="village">Village Code</label>
            <input type="text" class="form-control" id="village" name="village" value="31710101" required>
        </div>
        <div class="form-group">
            <label for="partOf">Part Of (Organization ID)</label>
            @php
            $organization_id = env('SATUSEHAT_ORGANIZATION_ID');
            @endphp
            <input type="text" class="form-control" id="partOf" name="partOf" value="<?php echo $organization_id ?>" required>
        </div>
        <button type="submit" class="btn btn-primary">Submit</button>
    </form>
</div>
@endsection