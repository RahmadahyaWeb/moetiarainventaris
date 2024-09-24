@php
    $bakeriesCount = DB::table('bakeries')
        ->selectRaw(
            "
        COUNT(CASE WHEN priority = 'high' AND last_stock > minimum THEN 1 END) AS safeitem,
        COUNT(CASE WHEN priority = 'high' AND last_stock = minimum THEN 1 END) AS warningitem,
        COUNT(CASE WHEN priority = 'high' AND last_stock < minimum THEN 1 END) AS dangeritem,
        COUNT(CASE WHEN priority = 'high' THEN 1 END) AS total
    ",
        )
        ->first();

    $baristasCount = DB::table('baristas')
        ->selectRaw(
            "
        COUNT(CASE WHEN priority = 'high' AND last_stock > minimum THEN 1 END) AS safeitem,
        COUNT(CASE WHEN priority = 'high' AND last_stock = minimum THEN 1 END) AS warningitem,
        COUNT(CASE WHEN priority = 'high' AND last_stock < minimum THEN 1 END) AS dangeritem,
        COUNT(CASE WHEN priority = 'high' THEN 1 END) AS total
    ",
        )
        ->first();

    $kitchensCount = DB::table('kitchens')
        ->selectRaw(
            "
        COUNT(CASE WHEN priority = 'high' AND last_stock > minimum THEN 1 END) AS safeitem,
        COUNT(CASE WHEN priority = 'high' AND last_stock = minimum THEN 1 END) AS warningitem,
        COUNT(CASE WHEN priority = 'high' AND last_stock < minimum THEN 1 END) AS dangeritem,
        COUNT(CASE WHEN priority = 'high' THEN 1 END) AS total
    ",
        )
        ->first();

    $cashiersCount = DB::table('cashiers')
        ->selectRaw(
            "
        COUNT(CASE WHEN priority = 'high' AND last_stock > minimum THEN 1 END) AS safeitem,
        COUNT(CASE WHEN priority = 'high' AND last_stock = minimum THEN 1 END) AS warningitem,
        COUNT(CASE WHEN priority = 'high' AND last_stock < minimum THEN 1 END) AS dangeritem,
        COUNT(CASE WHEN priority = 'high' THEN 1 END) AS total
    ",
        )
        ->first();

    $operationalsCount = DB::table('operationals')
        ->selectRaw(
            "
        COUNT(CASE WHEN priority = 'high' AND last_stock > minimum THEN 1 END) AS safeitem,
        COUNT(CASE WHEN priority = 'high' AND last_stock = minimum THEN 1 END) AS warningitem,
        COUNT(CASE WHEN priority = 'high' AND last_stock < minimum THEN 1 END) AS dangeritem,
        COUNT(CASE WHEN priority = 'high' THEN 1 END) AS total
    ",
        )
        ->first();

    $waitersCount = DB::table('waiters')
        ->selectRaw(
            "
        COUNT(CASE WHEN priority = 'high' AND last_stock > minimum THEN 1 END) AS safeitem,
        COUNT(CASE WHEN priority = 'high' AND last_stock = minimum THEN 1 END) AS warningitem,
        COUNT(CASE WHEN priority = 'high' AND last_stock < minimum THEN 1 END) AS dangeritem,
        COUNT(CASE WHEN priority = 'high' THEN 1 END) AS total
    ",
        )
        ->first();

    $bakeries = DB::table('bakeries_history')->get();
    $baristas = DB::table('baristas_history')->get();
    $kitchens = DB::table('kitchens_history')->get();
    $operationals = DB::table('operationals_history')->get();
    $cashiers = DB::table('cashiers_history')->get();
    $waiters = DB::table('waiters_history')->get();

    $combined = $bakeries
        ->concat($baristas)
        ->concat($kitchens)
        ->concat($operationals)
        ->concat($cashiers)
        ->concat($waiters);

    $filtered = $combined->filter(function ($item) {
        return $item->status == 0;
    });

@endphp

<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
    <div class="app-brand demo">
        <a href="/" class="app-brand-link">
            <span class="app-brand-text demo menu-text fw-semibold">Inventaris</span>

        </a>

        <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto d-block d-xl-none">
            <i class="bx bx-chevron-left bx-sm d-flex align-items-center justify-content-center"></i>
        </a>
    </div>

    <div class="menu-inner-shadow"></div>

    <ul class="menu-inner py-1">
        <!-- Dashboards -->
        <li class="menu-item {{ Request::is('/') ? 'active' : '' }}">
            <a href="/" class="menu-link">
                <i class="menu-icon tf-icons bx bxs-dashboard"></i>
                <div class="text-truncate">Dashboard</div>
            </a>
        </li>

        <li class="menu-header small text-uppercase"><span class="menu-header-text">Master Data</span></li>

        <!-- Approval -->
        @if (Auth::user()->role_id == 1 || Auth::user()->role_id == 2)
            <li class="menu-item {{ Request::is('approvals*') ? 'active' : '' }}">
                <a href="{{ route('approvals.index') }}" class="menu-link">
                    <i class="menu-icon tf-icons bx bxs-check-circle"></i>
                    <div class="text-truncate">
                        Approval
                    </div>
                    @if (count($filtered) > 0)
                        <span class="badge rounded-pill bg-danger ms-auto">
                            {{ count($filtered) }}
                        </span>
                    @endif

                </a>
            </li>
        @endif

        <!-- Bakery -->
        @if (Auth::user()->role_id === 1 || Auth::user()->role_id === 2 || Auth::user()->role_id === 3)
            <li class="menu-item {{ Request::is('bakeries*') ? 'active open' : '' }}">
                <a href="" class="menu-link menu-toggle">
                    <i class="menu-icon tf-icons bx bxs-baguette"></i>
                    <div class="text-truncate">Bakery</div>
                    @if ($bakeriesCount->dangeritem != 0)
                        <span class="badge rounded-pill bg-danger ms-auto">
                            {{ $bakeriesCount->dangeritem }}
                        </span>
                    @endif
                </a>
                <ul class="menu-sub">
                    <li
                        class="menu-item {{ Request::is('bakeries*') && !Request::is('bakeries/history') && !Request::is('bakeries/report') ? 'active' : '' }}">
                        <a href="{{ route('bakeries.index') }}" class="menu-link">
                            <div class="text-truncate">Data</div>
                            @if ($bakeriesCount->dangeritem != 0)
                                <span class="badge rounded-pill bg-danger ms-auto">
                                    {{ $bakeriesCount->dangeritem }}
                                </span>
                            @endif
                        </a>
                    </li>
                    <li class="menu-item  {{ Request::is('bakeries/history') ? 'active' : '' }}">
                        <a href="{{ route('bakeries.history') }}" class="menu-link">
                            <div class="text-truncate">History</div>
                        </a>
                    </li>
                    @if (Auth::user()->role_id == 1)
                        <li class="menu-item  {{ Request::is('bakeries/report') ? 'active' : '' }}">
                            <a href="{{ route('bakeries.report') }}" class="menu-link">
                                <div class="text-truncate">Report</div>
                            </a>
                        </li>
                    @endif
                </ul>
            </li>
        @endif

        <!-- Barista -->
        @if (Auth::user()->role_id === 1 || Auth::user()->role_id === 2 || Auth::user()->role_id === 6)
            <li class="menu-item {{ Request::is('baristas*') ? 'active open' : '' }}">
                <a href="" class="menu-link menu-toggle">
                    <i class="menu-icon tf-icons bx bxs-coffee"></i>
                    <div class="text-truncate">Barista</div>
                    @if ($baristasCount->dangeritem != 0)
                        <span class="badge rounded-pill bg-danger ms-auto">
                            {{ $baristasCount->dangeritem }}
                        </span>
                    @endif
                </a>
                <ul class="menu-sub">
                    <li
                        class="menu-item {{ Request::is('baristas*') && !Request::is('baristas/history') && !Request::is('baristas/report') ? 'active' : '' }}">
                        <a href="{{ route('baristas.index') }}" class="menu-link">
                            <div class="text-truncate">Data</div>
                            @if ($baristasCount->dangeritem != 0)
                                <span class="badge rounded-pill bg-danger ms-auto">
                                    {{ $baristasCount->dangeritem }}
                                </span>
                            @endif
                        </a>

                    </li>
                    <li class="menu-item  {{ Request::is('baristas/history') ? 'active' : '' }}">
                        <a href="{{ route('baristas.history') }}" class="menu-link">
                            <div class="text-truncate">History</div>
                        </a>
                    </li>
                    @if (Auth::user()->role_id == 1)
                        <li class="menu-item  {{ Request::is('baristas/report') ? 'active' : '' }}">
                            <a href="{{ route('baristas.report') }}" class="menu-link">
                                <div class="text-truncate">Report</div>
                            </a>
                        </li>
                    @endif
                </ul>
            </li>
        @endif

        <!-- Kitchen -->
        @if (Auth::user()->role_id === 1 || Auth::user()->role_id === 2 || Auth::user()->role_id === 7)
            <li class="menu-item {{ Request::is('kitchens*') ? 'active open' : '' }}">
                <a href="" class="menu-link menu-toggle">
                    <i class="menu-icon tf-icons bx bx-knife"></i>
                    <div class="text-truncate">Kitchen</div>
                    @if ($kitchensCount->dangeritem != 0)
                        <span class="badge rounded-pill bg-danger ms-auto">
                            {{ $kitchensCount->dangeritem }}
                        </span>
                    @endif
                </a>
                <ul class="menu-sub">
                    <li
                        class="menu-item {{ Request::is('kitchens*') && !Request::is('kitchens/history') && !Request::is('kitchens/report') ? 'active' : '' }}">
                        <a href="{{ route('kitchens.index') }}" class="menu-link">
                            <div class="text-truncate">Data</div>
                            @if ($kitchensCount->dangeritem != 0)
                                <span class="badge rounded-pill bg-danger ms-auto">
                                    {{ $kitchensCount->dangeritem }}
                                </span>
                            @endif
                        </a>
                    </li>
                    <li class="menu-item  {{ Request::is('kitchens/history') ? 'active' : '' }}">
                        <a href="{{ route('kitchens.history') }}" class="menu-link">
                            <div class="text-truncate">History</div>
                        </a>
                    </li>
                    @if (Auth::user()->role_id == 1)
                        <li class="menu-item  {{ Request::is('kitchens/report') ? 'active' : '' }}">
                            <a href="{{ route('kitchens.report') }}" class="menu-link">
                                <div class="text-truncate">Report</div>
                            </a>
                        </li>
                    @endif
                </ul>
            </li>
        @endif

        <!-- Kasir -->
        @if (Auth::user()->role_id === 1 || Auth::user()->role_id === 2 || Auth::user()->role_id === 8)
            <li class="menu-item {{ Request::is('cashiers*') ? 'active open' : '' }}">
                <a href="" class="menu-link menu-toggle">
                    <i class="menu-icon tf-icons bx bx-credit-card"></i>
                    <div class="text-truncate">Cashier</div>
                    @if ($cashiersCount->dangeritem != 0)
                        <span class="badge rounded-pill bg-danger ms-auto">
                            {{ $cashiersCount->dangeritem }}
                        </span>
                    @endif
                </a>
                <ul class="menu-sub">
                    <li
                        class="menu-item {{ Request::is('cashiers*') && !Request::is('cashiers/history') && !Request::is('cashiers/report') ? 'active' : '' }}">
                        <a href="{{ route('cashiers.index') }}" class="menu-link">
                            <div class="text-truncate">Data</div>
                            @if ($cashiersCount->dangeritem != 0)
                                <span class="badge rounded-pill bg-danger ms-auto">
                                    {{ $cashiersCount->dangeritem }}
                                </span>
                            @endif
                        </a>
                    </li>
                    <li class="menu-item  {{ Request::is('cashiers/history') ? 'active' : '' }}">
                        <a href="{{ route('cashiers.history') }}" class="menu-link">
                            <div class="text-truncate">History</div>
                        </a>
                    </li>
                    @if (Auth::user()->role_id == 1)
                        <li class="menu-item  {{ Request::is('cashiers/report') ? 'active' : '' }}">
                            <a href="{{ route('cashiers.report') }}" class="menu-link">
                                <div class="text-truncate">Report</div>
                            </a>
                        </li>
                    @endif
                </ul>
            </li>
        @endif

        <!-- Operasional -->
        @if (Auth::user()->role_id === 1 || Auth::user()->role_id === 2 || Auth::user()->role_id === 4)
            <li class="menu-item {{ Request::is('operationals*') ? 'active open' : '' }}">
                <a href="" class="menu-link menu-toggle">
                    <i class="menu-icon tf-icons bx bxs-cog"></i>
                    <div class="text-truncate">Operational</div>
                    @if ($operationalsCount->dangeritem != 0)
                        <span class="badge rounded-pill bg-danger ms-auto">
                            {{ $operationalsCount->dangeritem }}
                        </span>
                    @endif
                </a>
                <ul class="menu-sub">
                    <li
                        class="menu-item {{ Request::is('operationals*') && !Request::is('operationals/history') && !Request::is('operationals/report') ? 'active' : '' }}">
                        <a href="{{ route('operationals.index') }}" class="menu-link">
                            <div class="text-truncate">Data</div>
                            @if ($operationalsCount->dangeritem != 0)
                                <span class="badge rounded-pill bg-danger ms-auto">
                                    {{ $operationalsCount->dangeritem }}
                                </span>
                            @endif
                        </a>
                    </li>
                    <li class="menu-item  {{ Request::is('operationals/history') ? 'active' : '' }}">
                        <a href="{{ route('operationals.history') }}" class="menu-link">
                            <div class="text-truncate">History</div>
                        </a>
                    </li>
                    @if (Auth::user()->role_id == 1)
                        <li class="menu-item  {{ Request::is('operationals/report') ? 'active' : '' }}">
                            <a href="{{ route('operationals.report') }}" class="menu-link">
                                <div class="text-truncate">Report</div>
                            </a>
                        </li>
                    @endif
                </ul>
            </li>
        @endif

        <!-- Waiters -->
        @if (Auth::user()->role_id === 1 || Auth::user()->role_id === 2 || Auth::user()->role_id === 5)
            <li class="menu-item {{ Request::is('waiters*') ? 'active open' : '' }}">
                <a href="" class="menu-link menu-toggle">
                    <i class="menu-icon tf-icons bx bxs-dish"></i>
                    <div class="text-truncate">Waiter</div>
                    @if ($waitersCount->dangeritem != 0)
                        <span class="badge rounded-pill bg-danger ms-auto">
                            {{ $waitersCount->dangeritem }}
                        </span>
                    @endif
                </a>
                <ul class="menu-sub">
                    <li
                        class="menu-item {{ Request::is('waiters*') && !Request::is('waiters/history') && !Request::is('waiters/report') ? 'active' : '' }}">
                        <a href="{{ route('waiters.index') }}" class="menu-link">
                            <div class="text-truncate">Data</div>
                            @if ($waitersCount->dangeritem != 0)
                                <span class="badge rounded-pill bg-danger ms-auto">
                                    {{ $waitersCount->dangeritem }}
                                </span>
                            @endif
                        </a>
                    </li>
                    <li class="menu-item  {{ Request::is('waiters/history') ? 'active' : '' }}">
                        <a href="{{ route('waiters.history') }}" class="menu-link">
                            <div class="text-truncate">History</div>
                        </a>
                    </li>
                    @if (Auth::user()->role_id == 1)
                        <li class="menu-item  {{ Request::is('waiters/report') ? 'active' : '' }}">
                            <a href="{{ route('waiters.report') }}" class="menu-link">
                                <div class="text-truncate">Report</div>
                            </a>
                        </li>
                    @endif
                </ul>
            </li>
        @endif

        @if (Auth::user()->role_id === 1 || Auth::user()->role_id === 2)
            <li class="menu-header small text-uppercase"><span class="menu-header-text">Setting</span></li>

            <!-- Units -->
            <li class="menu-item {{ Request::is('units*') ? 'active' : '' }}">
                <a href="{{ route('units.index') }}" class="menu-link">
                    <i class="menu-icon tf-icons bx bx-table"></i>
                    <div class="text-truncate">
                        Units
                    </div>
                </a>
            </li>

            <!-- Items -->
            <li class="menu-item {{ Request::is('items*') ? 'active' : '' }}">
                <a href="{{ route('items.index') }}" class="menu-link">
                    <i class="menu-icon tf-icons bx bxs-package"></i>
                    <div class="text-truncate">
                        Items
                    </div>
                </a>
            </li>

            <!-- Users -->
            <li class="menu-item {{ Request::is('users*') ? 'active' : '' }}">
                <a href="{{ route('users.index') }}" class="menu-link">
                    <i class="menu-icon tf-icons bx bxs-user"></i>
                    <div class="text-truncate">
                        Users
                    </div>
                </a>
            </li>
        @endif
    </ul>
</aside>
