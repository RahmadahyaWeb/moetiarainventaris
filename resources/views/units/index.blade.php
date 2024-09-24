@extends('layouts.app')
@section('content')
    <div class="row">
        <div class="col">
            <div class="card">
                <div class="card-header fw-bold">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            Units
                        </div>
                        <div>
                            <a href="{{ route('units.create') }}" class="btn btn-sm btn-success fw-bold">
                                <i class='bx bx-plus fw-bold'></i>
                                <div class="ms-1">
                                    Create Unit
                                </div>
                            </a>
                        </div>
                    </div>
                    <hr>
                </div>
                <div class="card-body">
                    @livewire('unit-table')
                </div>
            </div>
        </div>
    </div>
@endsection
