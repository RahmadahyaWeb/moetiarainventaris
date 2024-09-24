@extends('layouts.app')
@section('content')
    <div class="row">
        <div class="col">
            <div class="card">
                <div class="card-header fw-bold">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            Barista History
                        </div>
                        @if (Auth::user()->role_id == 1)
                            <div>
                                <a href="" id="excelLink" class="btn btn-success">
                                    Excel
                                </a>
                            </div>
                        @endif
                    </div>
                    <hr>
                </div>
                <div class="card-body">
                    @livewire('barista-history')
                </div>
            </div>
        </div>
    </div>
@endsection
