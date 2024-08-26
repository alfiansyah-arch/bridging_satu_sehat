@extends('layouts')

@section('content')
<div class="container">
    <h1>Create Medication</h1>
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
    <form action="{{ route('medication.store') }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="identifier_value">Identifier Value:</label>
            <input type="text" class="form-control" id="identifier_value" name="identifier_value" required>
        </div>
        <div class="form-group">
            <label for="code">Code:</label>
            <input type="number" class="form-control" id="code" name="code" maxlength="8" required>
        </div>
        <div class="form-group">
            <label for="display">Display:</label>
            <input type="text" class="form-control" id="display" name="display" required>
        </div>
        <div class="form-group">
            <label for="manufacturer_reference">Manufacturer Reference:</label>
            <input type="text" class="form-control" id="manufacturer_reference" name="manufacturer_reference" required>
        </div>
        <div class="form-group">
            <label for="form_code">Form Code:</label>
            <input type="text" class="form-control" id="form_code" name="form_code" required>
        </div>
        <div class="form-group">
            <label for="form_display">Form Display:</label>
            <input type="text" class="form-control" id="form_display" name="form_display" required>
        </div>
        <div class="ingredient-group">
            <label for="ingredient_code">Ingredient:</label>
            <div class="ingredient-item">
                <div class="form-row">
                    <div class="col">
                        <input type="text" class="form-control" id="ingredient_code" name="ingredient_code[]" placeholder="Code" required>
                    </div>
                    <div class="col">
                        <input type="text" class="form-control" id="ingredient_display" name="ingredient_display[]" placeholder="Display" required>
                    </div>
                    <div class="col">
                        <input type="number" class="form-control" id="ingredient_strength" name="ingredient_strength[]" placeholder="Strength" required>
                    </div>
                </div>
            </div>
            <button type="button" class="btn btn-sm btn-primary mt-2" id="addIngredient">Add Ingredient</button>
        </div>
        <div class="form-group">
            <label for="extension_code">Extension Code:</label>
            <input type="text" class="form-control" id="extension_code" name="extension_code" required>
        </div>
        <div class="form-group">
            <label for="extension_display">Extension Display:</label>
            <input type="text" class="form-control" id="extension_display" name="extension_display" required>
        </div>
        <button type="submit" class="btn btn-primary">Create Medication</button>
    </form>
    @if(session('request_body') && session('response_body'))
    <div class="mt-5">
        <h2>Request Body</h2>
        <pre>{{ session('request_body') }}</pre>

        <h2>Response Body</h2>
        <pre>{{ session('response_body') }}</pre>
    </div>
    @endif
</div>

<script>
    document.getElementById('addIngredient').addEventListener('click', function () {
        var ingredientGroup = document.querySelector('.ingredient-group');
        var ingredientItem = document.createElement('div');
        ingredientItem.classList.add('ingredient-item');
        ingredientItem.innerHTML = `
            <div class="form-row">
                <div class="col">
                    <input type="text" class="form-control" name="ingredient_code[]" placeholder="Code" required>
                </div>
                <div class="col">
                    <input type="text" class="form-control" name="ingredient_display[]" placeholder="Display" required>
                </div>
                <div class="col">
                <input type="number" class="form-control" name="ingredient_strength[]" placeholder="Strength" required>
                </div>
            </div>
        `;
        ingredientGroup.appendChild(ingredientItem);
    });
</script>
@endsection