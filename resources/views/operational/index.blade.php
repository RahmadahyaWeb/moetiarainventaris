@extends('layouts.app')
@section('content')
    <div class="row">
        <div class="col">
            <div class="card">
                <div class="card-header fw-bold">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            Operational
                        </div>
                        @if (Auth::user()->role_id === 1)
                            <div>
                                <a href="{{ route('operationals.create') }}" class="btn btn-sm btn-success fw-bold">
                                    <i class='bx bx-plus fw-bold'></i>
                                    <div class="ms-1">
                                        Create Data
                                    </div>
                                </a>
                            </div>
                        @endif
                    </div>
                    <hr>
                </div>
                <div class="card-body">
                    @livewire('operational-table')
                </div>
            </div>
        </div>
    </div>
@endsection
