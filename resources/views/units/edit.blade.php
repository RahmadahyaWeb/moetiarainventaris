@extends('layouts.app')
@section('content')
    <div class="row">
        <div class="col-md-6 col-12">
            <div class="card">
                <div class="card-header fw-bold">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            Edit Unit
                        </div>
                    </div>
                    <hr>
                </div>
                <div class="card-body">
                    <form id="formEdit" action="{{ route('units.update', $unit) }}" method="POST">
                        @csrf
                        @method('put')
                        <div class="row gap-3">
                            <div class="col-12">
                                <label for="code" class="form-label">Code of Unit</label>
                                <input type="text" id="code"
                                    class="form-control @error('code') is-invalid @enderror" name="code"
                                    placeholder="Enter code of unit" value="{{ old('code', $unit->code) }}">
                                @error('code')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div class="col-12">
                                <label for="name" class="form-label">Unit Name</label>
                                <input type="name" id="name"
                                    class="form-control @error('name') is-invalid @enderror" name="name"
                                    placeholder="Enter unit name" value="{{ old('name', $unit->name) }}">
                                @error('name')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div class="col-12 d-flex justify-content-end">
                                <a href="{{ route('units.index') }}" class="btn btn-secondary mx-2">Back</a>
                                <div>
                                    <button id="submitButton" type="submit" class="btn btn-primary">
                                        <div id="submitText">
                                            Edit
                                        </div>
                                        <div id="loading" class="d-none">
                                            <span class="spinner-border spinner-border-sm" aria-hidden="true"></span>
                                        </div>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('formEdit').addEventListener('submit', function() {
            var button = document.getElementById('submitButton');
            button.disabled = true;

            var text = document.getElementById('submitText');
            text.classList.add('d-none');


            var loading = document.getElementById('loading');
            loading.classList.remove('d-none');
        });
    </script>
@endsection
