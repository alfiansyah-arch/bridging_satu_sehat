@extends('layouts')

@section('content')
<div class="container">
    <h2>Edit Encounter</h2>
    
    @if(session()->has('error'))
        <div class="alert alert-danger">
            {{ session()->get('error') }}
        </div>
    @endif

    <form action="{{ route('encounter.update', ['id' => $encounter['id']]) }}" method="POST">
        @csrf
        @method('PUT')

        <!-- Add your form fields here -->
        
        <div class="form-group">
            <label for="status">Status</label>
            <input type="text" class="form-control" id="status" name="status" value="{{ $encounter['status'] }}" required>
        </div>
        
        <div class="form-group">
            <label for="class_code">Class Code</label>
            <input type="text" class="form-control" id="class_code" name="class_code" value="{{ $encounter['class']['code'] }}" required>
        </div>

        <div class="form-group">
            <label for="class_display">Class Display</label>
            <input type="text" class="form-control" id="class_display" name="class_display" value="{{ $encounter['class']['display'] }}" required>
        </div>

        <div class="form-group">
            <label for="subject_reference">Subject Reference</label>
            <input type="text" class="form-control" id="subject_reference" name="subject_reference" value="{{ str_replace('Patient/', '', $encounter['subject']['reference'] ?? '') }}" required>
        </div>

        <div class="form-group">
            <label for="subject_display">Subject Display</label>
            <input type="text" class="form-control" id="subject_display" name="subject_display" value="{{ $encounter['subject']['display'] }}" required>
        </div>

        <div class="form-group">
            <label for="participant_type_code">Participant Type Code</label>
            <input type="text" class="form-control" id="participant_type_code" name="participant_type_code" value="{{ $encounter['participant'][0]['type'][0]['coding'][0]['code'] }}" required>
        </div>

        <div class="form-group">
            <label for="participant_type_display">Participant Type Display</label>
            <input type="text" class="form-control" id="participant_type_display" name="participant_type_display" value="{{ $encounter['participant'][0]['type'][0]['coding'][0]['display'] }}" required>
        </div>

        <div class="form-group">
            <label for="individual_reference">Individual Reference</label>
            <input type="text" class="form-control" id="individual_reference" name="individual_reference" value="{{ str_replace('Practitioner/', '', $encounter['participant'][0]['individual']['reference'] ?? '') }}" required>
        </div>

        <div class="form-group">
            <label for="individual_display">Individual Display</label>
            <input type="text" class="form-control" id="individual_display" name="individual_display" value="{{ $encounter['participant'][0]['individual']['display'] }}" required>
        </div>

        <div class="form-group">
            <label for="period_start">Period Start</label>
            <input type="text" class="form-control" id="period_start" name="period_start" value="{{ \Carbon\Carbon::parse($encounter['period']['start'])->format('Y-m-d\TH:i') }}" required>
        </div>

        <div class="form-group">
            <label for="location_reference">Location Reference</label>
            <input type="text" class="form-control" id="location_reference" name="location_reference" value="{{ str_replace('Location/', '', $encounter['location'][0]['location']['reference'] ?? '') }}" required>
        </div>

        <div class="form-group">
            <label for="location_display">Location Display</label>
            <input type="text" class="form-control" id="location_display" name="location_display" value="{{ $encounter['location'][0]['location']['display'] }}" required>
        </div>

        <div class="form-group">
            <label for="statusHistory_status">Status History Status</label>
            <input type="text" class="form-control" id="statusHistory_status" name="statusHistory_status" value="{{ $encounter['statusHistory'][0]['status'] }}" required>
        </div>

        <div class="form-group">
            <label for="statusHistory_period_start">Status History Period Start</label>
            <input type="text" class="form-control" id="statusHistory_period_start" name="statusHistory_period_start" value="{{ \Carbon\Carbon::parse($encounter['statusHistory'][0]['period']['start'])->format('Y-m-d\TH:i') }}" required>
        </div>

        <div class="form-group">
            <label for="serviceProvider_reference">Service Provider Reference</label>
            <input type="text" class="form-control" id="serviceProvider_reference" name="serviceProvider_reference" value="{{ str_replace('Organization/', '', $encounter['serviceProvider']['reference'] ?? '') }}" required>
        </div>

        <div class="form-group">
            <label for="identifier_system">Identifier System</label>
            <input type="text" class="form-control" id="identifier_system" name="identifier_system" value="{{ str_replace('http://sys-ids.kemkes.go.id/encounter/', '', $encounter['identifier'][0]['system'] ?? '') }}" required>
        </div>

        <div class="form-group">
            <label for="identifier_value">Identifier Value</label>
            <input type="text" class="form-control" id="identifier_value" name="identifier_value" value="{{ $encounter['identifier'][0]['value'] }}" required>
        </div>

        <button type="submit" class="btn btn-primary">Update Encounter</button>
    </form>
</div>
@endsection