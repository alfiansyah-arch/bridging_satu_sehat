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

    <h1>Create New Encounter</h1>

    <form action="{{ route('encounter.store') }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="status">Status:</label>
            <input type="text" class="form-control" id="status" name="status" value="arrived" required>
        </div>
        <div class="form-group">
            <label for="class_code">Class Code:</label>
            <input type="text" class="form-control" id="class_code" name="class_code" value="AMB" required>
        </div>
        <div class="form-group">
            <label for="class_display">Class Display:</label>
            <input type="text" class="form-control" id="class_display" name="class_display" value="ambulatory">
        </div>
        <div class="form-group">
            <label for="subject_reference">Subject Reference:</label>
            <input type="text" class="form-control" id="subject_reference" name="subject_reference" value="100000030009" required>
        </div>
        <div class="form-group">
            <label for="subject_display">Subject Display:</label>
            <input type="text" class="form-control" id="subject_display" name="subject_display" value="Budi Santoso" required>
        </div>
        <div class="form-group">
            <label for="participant_type_code">Participant Type Code:</label>
            <input type="text" class="form-control" id="participant_type_code" name="participant_type_code" value="ATND" required>
        </div>
        <div class="form-group">
            <label for="participant_type_display">Participant Type Display:</label>
            <input type="text" class="form-control" id="participant_type_display" name="participant_type_display" value="attender">
        </div>
        <div class="form-group">
            <label for="individual_reference">Individual Reference:</label>
            <input type="text" class="form-control" id="individual_reference" name="individual_reference" value="N10000001" required>
        </div>
        <div class="form-group">
            <label for="individual_display">Individual Display:</label>
            <input type="text" class="form-control" id="individual_display" name="individual_display" value="Dokter Bronsig" required>
        </div>
        <div class="form-group">
            <label for="period_start">Period Start:</label>
            <input type="text" class="form-control" id="period_start" name="period_start" value="2022-06-14T07:00:00+07:00" required>
        </div>
        <div class="form-group">
            <label for="location_reference">Location Reference:</label>
            <input type="text" class="form-control" id="location_reference" name="location_reference" value="b017aa54-f1df-4ec2-9d84-8823815d7228" required>
        </div>
        <div class="form-group">
            <label for="location_display">Location Display:</label>
            <input type="text" class="form-control" id="location_display" name="location_display" value="Ruang 1A, Poliklinik Bedah Rawat Jalan Terpadu, Lantai 2, Gedung G" required>
        </div>
        <div class="form-group">
            <label for="statusHistory_status">Status History Status:</label>
            <input type="text" class="form-control" id="statusHistory_status" name="statusHistory_status" value="arrived" required>
        </div>
        <div class="form-group">
            <label for="statusHistory_period_start">Status History Period Start:</label>
            <input type="text" class="form-control" id="statusHistory_period_start" name="statusHistory_period_start" value="2022-06-14T07:00:00+07:00" required>
        </div>
        <div class="form-group">
            <label for="serviceProvider_reference">Service Provider Reference:</label>
            @php
            $id_organization = env('SATUSEHAT_ORGANIZATION_ID');
            @endphp
            <input type="text" class="form-control" id="serviceProvider_reference" name="serviceProvider_reference" value="<?php echo $id_organization ?>" required>
        </div>
        <div class="form-group">
            <label for="identifier_system">Identifier System:</label>
            <input type="text" class="form-control" id="identifier_system" name="identifier_system" value="<?php echo $id_organization ?>" required>
        </div>
        <div class="form-group">
            <label for="identifier_value">Identifier Value:</label>
            <input type="text" class="form-control" id="identifier_value" name="identifier_value" value="P20240002" required>
        </div>
        <button type="submit" class="btn btn-primary">Create</button>
    </form>
</div>
@endsection