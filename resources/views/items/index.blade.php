@extends('layouts.app')
@section('content')
    <div class="row">
        <div class="col">
            <div class="card">
                <div class="card-header fw-bold">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            Items
                        </div>
                    </div>
                    <hr>
                </div>
                <div class="card-body">
                    @livewire('item-table')
                </div>
            </div>
        </div>
    </div>
@endsection
