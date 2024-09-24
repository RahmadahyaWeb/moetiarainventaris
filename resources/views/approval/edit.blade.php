@extends('layouts.app')
@section('content')
    <div class="row">
        <div class="col-md-6 col-12">
            <div class="card">
                <div class="card-header fw-bold">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            Approval
                        </div>
                    </div>
                    <hr>
                </div>
                <div class="card-body">
                    <form id="formEdit"
                        action="{{ route('approvals.update', ['kode_barang' => $item->kode_barang, 'code_id' => $item->code_id, 'id' => $id, 'qty' => $item->qty, 'type' => $item->type]) }}"
                        method="POST">
                        @csrf
                        @method('put')
                        <div class="row">
                            <div class="col-12 mb-3">
                                <label for="kode_barang" class="form-label">Item Code</label>
                                <input type="text" id="kode_barang" class="form-control"
                                    value="{{ old('kode_barang', $item->kode_barang) }}" disabled>
                            </div>
                            <div class="col-12 mb-3">
                                <label for="nama_barang" class="form-label">Item Name</label>
                                <input type="text" id="nama_barang" class="form-control"
                                    value="{{ old('nama_barang', $item->nama_barang) }}" disabled>
                            </div>
                            <div class="col-6 mb-3">
                                <label for="qty" class="form-label">Quantity</label>
                                <input type="text" id="qty" class="form-control"
                                    value="{{ old('qty', $item->qty) }}" disabled>
                            </div>
                            <div class="col-6 mb-3">
                                <label for="type" class="form-label">Category</label>
                                <input type="text" id="type" class="form-control"
                                    value="{{ old('type', $item->type) }}" disabled>
                            </div>
                            <div class="col-12 mb-3">
                                <label for="division" class="form-label">Division</label>
                                <input type="text" id="division" class="form-control"
                                    value="{{ old('division', $division) }}" disabled>
                            </div>
                            <div class="col-12 mb-3">
                                <label for="input_by" class="form-label">Input By</label>
                                <input type="text" id="input_by" class="form-control"
                                    value="{{ old('input_by', $input_by) }}" disabled>
                            </div>
                            <div class="col-12 mb-3">
                                <label for="status" class="form-label">Status</label>
                                <select name="status" id="status"
                                    class="form-select @error('status') is-invalid @enderror">
                                    <option value="0" @selected(old('status', $item->status) == 0)>
                                        Pending
                                    </option>
                                    <option value="1" @selected(old('status', $item->status) == 1)>
                                        Approved
                                    </option>
                                    <option value="-1" @selected(old('status', $item->status) == -1)>
                                        Rejected
                                    </option>
                                </select>
                                @error('status')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div class="col-12 mb-3">
                                <label for="description" class="form-label">Description</label>
                                <textarea name="description" id="description" class="form-control @error('description') is-invalid @enderror">{{ old('description', $item->description) }}</textarea>
                                @error('description')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                            </div>
                            <div class="col-12 mb-3 d-flex justify-content-end">
                                <a href="{{ route('approvals.index') }}" class="btn btn-secondary mx-2">Back</a>
                                <div>
                                    <button id="submitButton" type="submit" class="btn btn-primary">
                                        <div id="submitText">
                                            Submit
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
