@extends('layouts.app')
@section('content')
    <div class="row">
        <div class="col-md-6 col-12">
            <div class="card">
                <div class="card-header fw-bold">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            Edit Stock Data
                        </div>
                    </div>
                    <hr>
                </div>
                <div class="card-body">
                    <ul class="nav nav-pills nav-fill mb-3" id="pills-tab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="pills-increase-tab" data-bs-toggle="pill"
                                data-bs-target="#pills-increase" type="button" role="tab"
                                aria-controls="pills-increase" aria-selected="true">Increase Stock</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="pills-decrease-tab" data-bs-toggle="pill"
                                data-bs-target="#pills-decrease" type="button" role="tab"
                                aria-controls="pills-decrease" aria-selected="true">Decrease
                                Stock</button>
                        </li>

                    </ul>
                    <div class="tab-content" id="pills-tabContent">
                        {{-- INCREASE --}}
                        <div class="tab-pane fade show active" id="pills-increase" role="tabpanel"
                            aria-labelledby="pills-increase-tab" tabindex="0">

                            <form id="formEdit" action="{{ route('bakeries.incresase', $item) }}" method="POST">
                                @csrf
                                @method('put')
                                <div class="row">
                                    <div class="col-6 mb-3">
                                        <label for="kode_barang" class="form-label">Code of Item</label>
                                        <input type="text" id="kode_barang" class="form-control"
                                            value="{{ old('kode_barang', $item->kode_barang) }}" disabled>
                                    </div>
                                    <div class="col-6 mb-3">
                                        <label for="initial_stock" class="form-label">Last Stock</label>
                                        <input type="number" id="initial_stock" class="form-control"
                                            value="{{ old('initial_stock', $item->last_stock) }}" disabled>
                                    </div>
                                    <div class="col-12 mb-3">
                                        <label for="nama_barang_decreased" class="form-label">
                                            Name of Item
                                        </label>
                                        <input type="text"
                                            class="form-control @error('nama_barang_decreased') is-invalid @enderror"
                                            id="nama_barang_decreased" placeholder="Name of Item"
                                            value="{{ old('nama_barang_decreased', $item->nama_barang) }}" disabled>
                                    </div>
                                    <div class="col-12 mb-3">
                                        <label for="qty" class="form-label">
                                            Quantity
                                        </label>
                                        <input type="number" class="form-control @error('qty') is-invalid @enderror"
                                            id="qty" name="qty" placeholder="Quantity"
                                            value="{{ old('qty') }}">
                                        @error('qty')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                    <div class="col-12 mb-3">
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

                                    <div class="col-12 d-flex justify-content-end mb-3">
                                        <div>
                                            <button id="submitButton" type="submit" class="btn btn-primary">
                                                <div id="submitText">
                                                    Submit
                                                </div>
                                                <div id="loading" class="d-none">
                                                    <span class="spinner-border spinner-border-sm"
                                                        aria-hidden="true"></span>
                                                </div>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </form>

                        </div>
                        {{-- DECREASE --}}
                        <div class="tab-pane fade" id="pills-decrease" role="tabpanel" aria-labelledby="pills-decrease-tab"
                            tabindex="0">
                            <form id="formEdit2" action="{{ route('bakeries.decrease', $item) }}" method="POST">
                                @csrf
                                @method('put')
                                <div class="row">
                                    <div class="col-6 mb-3">
                                        <label for="kode_barang" class="form-label">Code of Item</label>
                                        <input type="text" id="kode_barang" class="form-control"
                                            value="{{ old('kode_barang', $item->kode_barang) }}" disabled>
                                    </div>
                                    <div class="col-6 mb-3">
                                        <label for="initial_stock" class="form-label">Last Stock</label>
                                        <input type="number" id="initial_stock" class="form-control"
                                            value="{{ old('initial_stock', $item->last_stock) }}" disabled>
                                    </div>
                                    <div class="col-12 mb-3">
                                        <label for="nama_barang_decreased" class="form-label">
                                            Name of Item
                                        </label>
                                        <input type="text"
                                            class="form-control @error('nama_barang_decreased') is-invalid @enderror"
                                            id="nama_barang_decreased" placeholder="Name of Item"
                                            value="{{ old('nama_barang_decreased', $item->nama_barang) }}" disabled>
                                    </div>
                                    <div class="col-12 mb-3">
                                        <label for="qty_decreased" class="form-label">
                                            Quantity
                                        </label>
                                        <input type="number"
                                            class="form-control @error('qty_decreased') is-invalid @enderror"
                                            id="qty_decreased" name="qty_decreased" placeholder="Quantity"
                                            value="{{ old('qty_decreased') }}">
                                        @error('qty_decreased')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                    <div class="col-12 mb-3">
                                        <label for="tanggal_decreased" class="form-label">Date</label>
                                        <input type="date" id="tanggal_decreased"
                                            class="form-control @error('tanggal_decreased') is-invalid @enderror"
                                            name="tanggal_decreased" value="{{ old('tanggal_decreased') }}">
                                        @error('tanggal_decreased')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>

                                    <div class="col-12 d-flex justify-content-end mb-3">
                                        <div>
                                            <button id="submitButton2" type="submit" class="btn btn-primary">
                                                <div id="submitText2">
                                                    Submit
                                                </div>
                                                <div id="loading2" class="d-none">
                                                    <span class="spinner-border spinner-border-sm"
                                                        aria-hidden="true"></span>
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
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var activeTab = localStorage.getItem('activeTab');

            if (activeTab) {
                var tabButton = document.querySelector(`button[data-bs-target="${activeTab}"]`);
                if (tabButton) {
                    var tabInstance = new bootstrap.Tab(tabButton);
                    tabInstance.show();
                }
            }

            var tabButtons = document.querySelectorAll('[data-bs-toggle="pill"]');
            tabButtons.forEach(function(button) {
                button.addEventListener('click', function() {
                    var target = this.getAttribute('data-bs-target');
                    localStorage.setItem('activeTab', target);
                });
            });
        });


        document.getElementById('formEdit').addEventListener('submit', function() {
            var button = document.getElementById('submitButton');
            button.disabled = true;

            var text = document.getElementById('submitText');
            text.classList.add('d-none');


            var loading = document.getElementById('loading');
            loading.classList.remove('d-none');
        });

        document.getElementById('formEdit2').addEventListener('submit', function() {
            var button = document.getElementById('submitButton2');
            button.disabled = true;

            var text = document.getElementById('submitText2');
            text.classList.add('d-none');


            var loading = document.getElementById('loading2');
            loading.classList.remove('d-none');
        });
    </script>
@endsection
