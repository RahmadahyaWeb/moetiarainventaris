@extends('layouts.app')
@section('content')
    <div class="row">
        <div class="col-md-6 col-12">
            <div class="card">
                <div class="card-header fw-bold">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            Edit User
                        </div>
                    </div>
                    <hr>
                </div>
                <div class="card-body">
                    <form id="formEdit" action="{{ route('users.update', $user) }}" method="POST">
                        @csrf
                        @method('put')
                        <div class="row gap-3">
                            <div class="col-12">
                                <label for="name" class="form-label">Name</label>
                                <input type="text" id="name"
                                    class="form-control @error('name') is-invalid @enderror" name="name"
                                    placeholder="Enter user name" value="{{ old('name', $user->name) }}">
                                @error('name')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div class="col-12">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" id="email"
                                    class="form-control @error('email') is-invalid @enderror" name="email"
                                    placeholder="Enter user email" value="{{ old('email', $user->email) }}">
                                @error('email')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div class="col-12">
                                <label for="role_id" class="form-label">Role</label>
                                <select id="role_id" name="role_id"
                                    class="form-select @error('role_id') is-invalid @enderror">
                                    <option selected disabled>Select user role</option>
                                    @foreach ($roles as $role)
                                        <option value="{{ $role->id }}" @selected($role->id == old('role_id', $user->role_id))>
                                            {{ $role->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('role_id')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div class="col-12 d-flex justify-content-end">
                                <a href="{{ route('users.index') }}" class="btn btn-secondary mx-2">Back</a>
                                <div>
                                    <button id="submitButton" type="submit" class="btn btn-primary">
                                        <div id="submitText">
                                            Save
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
