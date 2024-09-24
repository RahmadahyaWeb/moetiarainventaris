@extends('layouts.app')
@section('content')
    <div class="row">
        <div class="col-md-6 col-12">
            <div class="card">
                <div class="card-header fw-bold">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            Create Data
                        </div>
                    </div>
                    <hr>
                </div>
                <div class="card-body">
                    <form id="formCreate" action="{{ route('bakeries.insert') }}" method="POST">
                        @csrf
                        <div class="row gap-3">
                            <div class="col-12">
                                <label for="nama_barang" class="form-label">Name of Item</label>
                                <input type="text" id="nama_barang"
                                    class="form-control @error('nama_barang') is-invalid @enderror" name="nama_barang"
                                    placeholder="Enter Name of Item" value="{{ old('nama_barang') }}">
                                @error('nama_barang')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div class="col-12">
                                <label for="price" class="form-label">Price</label>
                                <input type="number" id="price"
                                    class="form-control @error('price') is-invalid @enderror" name="price"
                                    placeholder="Enter Item Price" value="{{ old('price') }}">
                                @error('price')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div class="col-12">
                                <label for="initial_stock" class="form-label">Initial Stock</label>
                                <input type="number" id="initial_stock"
                                    class="form-control @error('initial_stock') is-invalid @enderror" name="initial_stock"
                                    placeholder="Enter Initial Stock" value="{{ old('initial_stock') }}">
                                @error('initial_stock')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div class="col-12">
                                <label for="unit_id" class="form-label">Unit of Item</label>
                                <select name="unit_id" id="unit_id"
                                    class="form-select @error('unit_id') is-invalid @enderror">
                                    <option selected disabled>Select Unit of Item</option>
                                    @foreach ($units as $unit)
                                        <option value="{{ $unit->id }}" @selected($unit->id == old('unit_id'))>{{ $unit->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('unit_id')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div class="col-12">
                                <label for="tanggal" class="form-label">Date</label>
                                <input type="date" id="tanggal"
                                    class="form-control @error('tanggal') is-invalid @enderror" name="tanggal"
                                    value="{{ old('tanggal') }}">
                                @error('tanggal')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div class="col-12">
                                <label for="priority" class="form-label">Priority of Item</label>
                                <select name="priority" id="priority"
                                    class="form-select @error('priority') is-invalid @enderror">
                                    <option selected disabled>Select Priority of Item</option>
                                    <option value="low" @selected(old('priority') == 'low')>Low</option>
                                    <option value="high" @selected(old('priority') == 'high')>High</option>
                                </select>
                                @error('priority')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div class="col-12">
                                <label for="minimum" class="form-label">Minimum Stock</label>
                                <input type="number" id="minimum"
                                    class="form-control @error('minimum') is-invalid @enderror" name="minimum"
                                    placeholder="Enter Minimum Stock" value="{{ old('minimum') }}">
                                @error('minimum')
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
                                            Create
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
        document.getElementById('formCreate').addEventListener('submit', function() {
            var button = document.getElementById('submitButton');
            button.disabled = true;

            var text = document.getElementById('submitText');
            text.classList.add('d-none');


            var loading = document.getElementById('loading');
            loading.classList.remove('d-none');
        });
    </script>
@endsection
