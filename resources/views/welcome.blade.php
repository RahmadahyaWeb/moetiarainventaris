@extends('layouts.app')
@section('content')
    <div class="row">
        <div class="col mb-6 order-0">
            <div class="card">
                <div class="d-flex align-items-start row">
                    <div class="col-sm-7">
                        <div class="card-body">
                            <h5 class="card-title text-primary mb-3">Welcome {{ Auth::user()->name }} 👋</h5>
                            <p class="mb-1">
                                <q>{{ $quote['content'] }}</q>
                            </p>
                            <p class="mb-6">
                                <i><b>- {{ $quote['author'] }}</b></i>
                            </p>
                        </div>
                    </div>
                    <div class="col-sm-5 text-center text-sm-left d-none d-md-block">
                        <div class="card-body pb-0 px-0 px-md-6">
                            <img src="{{ asset('img/MS.png') }}" height="175" class="scaleX-n1-rtl"
                                alt="View Badge User" />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if (Auth::user()->role_id == 1)
        <div class="row mb-6">
            <div class="col">
                <div class="card">
                    <div class="card-body">
                        @livewire('chart-table')
                    </div>
                </div>
            </div>
        </div>
    @endif

    @if (Auth::user()->role_id == 1)
        <div class="row mb-6">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        Most Inbound Item
                    </div>
                    <div class="card-body">
                        @livewire('chart-top-item-table')
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        Most Outbond Item
                    </div>
                    <div class="card-body">
                        @livewire('chart-top-out-item-table')
                    </div>
                </div>
            </div>
        </div>
    @endif

    @if (Auth::user()->role_id == 3)
        <div class="row">
            <div class="col mb-3">
                <div class="card">
                    <div class="card-header fw-bold">
                        Bakery
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <div class="thead">
                                    <tr>
                                        <th>Safe Items</th>
                                        <th>Warning Items</th>
                                        <th>Danger Items</th>
                                        <th>Total Items</th>
                                    </tr>
                                </div>
                                <tbody>
                                    <tr>
                                        <td>{{ $bakeriesCount->safeitem }}</td>
                                        <td>{{ $bakeriesCount->warningitem }}</td>
                                        <td>{{ $bakeriesCount->dangeritem }}</td>
                                        <td>{{ $bakeriesCount->total }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    @if (Auth::user()->role_id == 6)
        <div class="row">
            <div class="col mb-3">
                <div class="card">
                    <div class="card-header fw-bold">
                        Barista
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <div class="thead">
                                    <tr>
                                        <th>Safe Items</th>
                                        <th>Warning Items</th>
                                        <th>Danger Items</th>
                                        <th>Total Items</th>
                                    </tr>
                                </div>
                                <tbody>
                                    <tr>
                                        <td>{{ $baristasCount->safeitem }}</td>
                                        <td>{{ $baristasCount->warningitem }}</td>
                                        <td>{{ $baristasCount->dangeritem }}</td>
                                        <td>{{ $baristasCount->total }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    @if (Auth::user()->role_id == 7)
        <div class="row">
            <div class="col mb-3">
                <div class="card">
                    <div class="card-header fw-bold">
                        Kitchen
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <div class="thead">
                                    <tr>
                                        <th>Safe Items</th>
                                        <th>Warning Items</th>
                                        <th>Danger Items</th>
                                        <th>Total Items</th>
                                    </tr>
                                </div>
                                <tbody>
                                    <tr>
                                        <td>{{ $kitchensCount->safeitem }}</td>
                                        <td>{{ $kitchensCount->warningitem }}</td>
                                        <td>{{ $kitchensCount->dangeritem }}</td>
                                        <td>{{ $kitchensCount->total }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    @if (Auth::user()->role_id == 8)
        <div class="row">
            <div class="col mb-3">
                <div class="card">
                    <div class="card-header fw-bold">
                        Cashier
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <div class="thead">
                                    <tr>
                                        <th>Safe Items</th>
                                        <th>Warning Items</th>
                                        <th>Danger Items</th>
                                        <th>Total Items</th>
                                    </tr>
                                </div>
                                <tbody>
                                    <tr>
                                        <td>{{ $cashiersCount->safeitem }}</td>
                                        <td>{{ $cashiersCount->warningitem }}</td>
                                        <td>{{ $cashiersCount->dangeritem }}</td>
                                        <td>{{ $cashiersCount->total }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    @if (Auth::user()->role_id == 4)
        <div class="row">
            <div class="col mb-3">
                <div class="card">
                    <div class="card-header fw-bold">
                        Operational
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <div class="thead">
                                    <tr>
                                        <th>Safe Items</th>
                                        <th>Warning Items</th>
                                        <th>Danger Items</th>
                                        <th>Total Items</th>
                                    </tr>
                                </div>
                                <tbody>
                                    <tr>
                                        <td>{{ $operationalsCount->safeitem }}</td>
                                        <td>{{ $operationalsCount->warningitem }}</td>
                                        <td>{{ $operationalsCount->dangeritem }}</td>
                                        <td>{{ $operationalsCount->total }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    @if (Auth::user()->role_id == 5)
        <div class="row">
            <div class="col mb-3">
                <div class="card">
                    <div class="card-header fw-bold">
                        Waiter
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <div class="thead">
                                    <tr>
                                        <th>Safe Items</th>
                                        <th>Warning Items</th>
                                        <th>Danger Items</th>
                                        <th>Total Items</th>
                                    </tr>
                                </div>
                                <tbody>
                                    <tr>
                                        <td>{{ $waitersCount->safeitem }}</td>
                                        <td>{{ $waitersCount->warningitem }}</td>
                                        <td>{{ $waitersCount->dangeritem }}</td>
                                        <td>{{ $waitersCount->total }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    @if (Auth::user()->role_id == 1 || Auth::user()->role_id == 2)

        <div class="row mb-6">
            <div class="col-md-4 mb-3">
                <div class="card">
                    <div class="card-header fw-bold">
                        Bakery
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <div class="thead">
                                    <tr>
                                        <th>Safe Items</th>
                                        <th>Warning Items</th>
                                        <th>Danger Items</th>
                                        <th>Total Items</th>
                                    </tr>
                                </div>
                                <tbody>
                                    <tr>
                                        <td>{{ $bakeriesCount->safeitem }}</td>
                                        <td>{{ $bakeriesCount->warningitem }}</td>
                                        <td>{{ $bakeriesCount->dangeritem }}</td>
                                        <td>{{ $bakeriesCount->total }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-3">
                <div class="card">
                    <div class="card-header fw-bold">
                        Barista
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <div class="thead">
                                    <tr>
                                        <th>Safe Items</th>
                                        <th>Warning Items</th>
                                        <th>Danger Items</th>
                                        <th>Total Items</th>
                                    </tr>
                                </div>
                                <tbody>
                                    <tr>
                                        <td>{{ $baristasCount->safeitem }}</td>
                                        <td>{{ $baristasCount->warningitem }}</td>
                                        <td>{{ $baristasCount->dangeritem }}</td>
                                        <td>{{ $baristasCount->total }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-3">
                <div class="card">
                    <div class="card-header fw-bold">
                        Kitchen
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <div class="thead">
                                    <tr>
                                        <th>Safe Items</th>
                                        <th>Warning Items</th>
                                        <th>Danger Items</th>
                                        <th>Total Items</th>
                                    </tr>
                                </div>
                                <tbody>
                                    <tr>
                                        <td>{{ $kitchensCount->safeitem }}</td>
                                        <td>{{ $kitchensCount->warningitem }}</td>
                                        <td>{{ $kitchensCount->dangeritem }}</td>
                                        <td>{{ $kitchensCount->total }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-3">
                <div class="card">
                    <div class="card-header fw-bold">
                        Cashier
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <div class="thead">
                                    <tr>
                                        <th>Safe Items</th>
                                        <th>Warning Items</th>
                                        <th>Danger Items</th>
                                        <th>Total Items</th>
                                    </tr>
                                </div>
                                <tbody>
                                    <tr>
                                        <td>{{ $cashiersCount->safeitem }}</td>
                                        <td>{{ $cashiersCount->warningitem }}</td>
                                        <td>{{ $cashiersCount->dangeritem }}</td>
                                        <td>{{ $cashiersCount->total }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-3">
                <div class="card">
                    <div class="card-header fw-bold">
                        Operational
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <div class="thead">
                                    <tr>
                                        <th>Safe Items</th>
                                        <th>Warning Items</th>
                                        <th>Danger Items</th>
                                        <th>Total Items</th>
                                    </tr>
                                </div>
                                <tbody>
                                    <tr>
                                        <td>{{ $operationalsCount->safeitem }}</td>
                                        <td>{{ $operationalsCount->warningitem }}</td>
                                        <td>{{ $operationalsCount->dangeritem }}</td>
                                        <td>{{ $operationalsCount->total }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-3">
                <div class="card">
                    <div class="card-header fw-bold">
                        Waiter
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <div class="thead">
                                    <tr>
                                        <th>Safe Items</th>
                                        <th>Warning Items</th>
                                        <th>Danger Items</th>
                                        <th>Total Items</th>
                                    </tr>
                                </div>
                                <tbody>
                                    <tr>
                                        <td>{{ $waitersCount->safeitem }}</td>
                                        <td>{{ $waitersCount->warningitem }}</td>
                                        <td>{{ $waitersCount->dangeritem }}</td>
                                        <td>{{ $waitersCount->total }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <div class="row">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        Lastest Update
                    </div>
                    <div class="card-body">
                        <ul class="list-group">
                            @foreach ($data as $item)
                                @php
                                    $division = '';
                                    if ($item->division->code == 'BKR') {
                                        $division = 'Bakery';
                                    }
                                    if ($item->division->code == 'BRS') {
                                        $division = 'Barista';
                                    }
                                    if ($item->division->code == 'KTH') {
                                        $division = 'Kitchen';
                                    }
                                    if ($item->division->code == 'OPR') {
                                        $division = 'Operational';
                                    }
                                    if ($item->division->code == 'WTS') {
                                        $division = 'Waiters';
                                    }
                                    if ($item->division->code == 'KSR') {
                                        $division = 'Cashier';
                                    }
                                @endphp
                                <li class="list-group-item">
                                    <b>{{ $item->user->name }}</b> melakukan <b>{{ $item->type }}bound</b> pada divisi
                                    <b>{{ $division }}</b>
                                    <p class="m-0">
                                        <i>{{ \Carbon\Carbon::parse($item->created_at)->diffForHumans() }}</i>
                                    </p>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    @endif


@endsection
