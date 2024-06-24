@extends('layouts')

@section('content')
<div class="container">
    <h1>Buat Observasi Baru</h1>
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

    <form action="{{ route('observation.create') }}" method="POST" onsubmit="return validateForm()">
        @csrf
        <div class="form-group">
            <label for="status">Status:</label>
            <input type="text" class="form-control" id="status" name="status" value="final">
        </div>
        <div class="form-group">
            <label for="category">Category Code:</label>
            <input type="text" class="form-control" id="category" name="category" value="vital-signs">
        </div>
        <div class="form-group">
            <label for="category_display">Category Display:</label>
            <input type="text" class="form-control" id="category_display" name="category_display" value="Vital Signs">
        </div>
        <div class="form-group">
            <label for="code">Code:</label>
            <input type="text" class="form-control" id="code" name="code" value="8867-4">
        </div>
        <div class="form-group">
            <label for="display">Display:</label>
            <input type="text" class="form-control" id="display" name="display" value="Heart rate">
        </div>
        <div class="form-group">
            <label for="subject">Subject:</label>
            <input type="text" class="form-control" id="subject" name="subject" value="100000030009">
        </div>
        <div class="form-group">
            <label for="performer">Performer:</label>
            <input type="text" class="form-control" id="performer" name="performer" value="N10000001">
        </div>
        <div class="form-group">
            <label for="encounter">Encounter:</label>
            <input type="text" class="form-control" id="encounter" name="encounter" placeholder="Masukkan Encounter UUID">
        </div>
        <div class="form-group">
            <label for="effectiveDateTime">Effective Date Time:</label>
            <input type="text" class="form-control" id="effectiveDateTime" name="effectiveDateTime" placeholder="YYYY-MM-DDThh:mm:ss+00:00 cth. 2022-07-14T00:00:00+00:00">
        </div>
        <div class="form-group">
            <label for="issued">Issued:</label>
            <input type="text" class="form-control" id="issued" name="issued" placeholder="YYYY-MM-DDThh:mm:ss+00:00 cth. 2022-07-14T00:00:00+00:00">
        </div>
        <div class="form-group">
            <label for="value">Value:</label>
            <input type="number" class="form-control" id="value" name="value" placeholder="Masukkan nilai heart rate">
        </div>
        <button type="submit" class="btn btn-primary">Submit</button>
    </form>
</div>

<script>
    function validateForm() {
        const effectiveDateTime = document.getElementById('effectiveDateTime').value;
        const issued = document.getElementById('issued').value;
        const dateTimeFormat = /^\d{4}-\d{2}-\d{2}T\d{2}:\d{2}:\d{2}\+\d{2}:\d{2}$/;

        if (!dateTimeFormat.test(effectiveDateTime)) {
            alert('Effective Date Time harus dalam format YYYY-MM-DDThh:mm:ss+00:00');
            return false;
        }

        if (!dateTimeFormat.test(issued)) {
            alert('Issued Date Time harus dalam format YYYY-MM-DDThh:mm:ss+00:00');
            return false;
        }

        return true;
    }
</script>
@endsection
