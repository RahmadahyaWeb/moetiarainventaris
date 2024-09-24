<?php

use App\Http\Controllers\ApprovalController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BakeryController;
use App\Http\Controllers\BaristasController;
use App\Http\Controllers\CashierController;
use App\Http\Controllers\HistoryExportController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\KitchenController;
use App\Http\Controllers\OperationalController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\UnitController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WaiterController;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Route;

// DASHBOARD
Route::get('/', function () {
    $urlQuotes = "http://api.quotable.io/random";

    $response = Http::get($urlQuotes);

    if ($response->successful()) {
        $quote = $response->json();
    } else {
        $quote = ['error' => 'Failed to retrieve quotes'];
    }

    $bakeries = DB::table('bakeries_history')->get();
    $baristas = DB::table('baristas_history')->get();
    $kitchens = DB::table('kitchens_history')->get();
    $operationals = DB::table('operationals_history')->get();
    $cashiers = DB::table('cashiers_history')->get();
    $waiters = DB::table('waiters_history')->get();

    $data = $bakeries->concat($baristas)
        ->concat($kitchens)
        ->concat($operationals)
        ->concat($cashiers)
        ->concat($waiters);

    // Count occurrences of each item
    $itemsCount = $data->groupBy('kode_barang')
        ->map(function ($group) {
            return [
                'in' => $group->where('type', 'in')->count(),
                'out' => $group->where('type', 'out')->count(),
            ];
        });

    // Add 'kode_barang' to each count for reference
    $itemsWithKode = $data->groupBy('kode_barang')
        ->map(function ($group) {
            return [
                'in' => $group->where('type', 'in')->count(),
                'out' => $group->where('type', 'out')->count(),
                'kode_barang' => $group->first()->kode_barang,
                'nama_barang' => $group->first()->nama_barang,
                'code_id' => $group->first()->code_id,
            ];
        });

    $data = $data->sortByDesc('created_at')->take(5);

    $userIds = $data->pluck('user_id')->unique();

    $users = DB::table('users')->whereIn('id', $userIds)->get();

    $dataWithUsers = $data->map(function ($item) use ($users) {
        $item->user = $users->firstWhere('id', $item->user_id);
        return $item;
    });

    $divisiIds = $dataWithUsers->pluck('code_id')->unique();

    $divisions = DB::table('codes')->whereIn('id', $divisiIds)->get();

    $dataWithDivisions = $data->map(function ($item) use ($divisions) {
        $item->division = $divisions->firstWhere('id', $item->code_id);
        return $item;
    });

    $data = $dataWithDivisions;

    $bakeriesCount = DB::table('bakeries')
        ->selectRaw("
        COUNT(CASE WHEN last_stock > minimum THEN 1 END) AS safeitem,
        COUNT(CASE WHEN last_stock = minimum THEN 1 END) AS warningitem,
        COUNT(CASE WHEN last_stock < minimum THEN 1 END) AS dangeritem,
        COUNT(*) AS total
    ")
        ->first();

    $baristasCount = DB::table('baristas')
        ->selectRaw("
        COUNT(CASE WHEN last_stock > minimum THEN 1 END) AS safeitem,
        COUNT(CASE WHEN last_stock = minimum THEN 1 END) AS warningitem,
        COUNT(CASE WHEN last_stock < minimum THEN 1 END) AS dangeritem,
        COUNT(*) AS total
    ")
        ->first();

    $kitchensCount = DB::table('kitchens')
        ->selectRaw("
        COUNT(CASE WHEN last_stock > minimum THEN 1 END) AS safeitem,
        COUNT(CASE WHEN last_stock = minimum THEN 1 END) AS warningitem,
        COUNT(CASE WHEN last_stock < minimum THEN 1 END) AS dangeritem,
        COUNT(*) AS total
    ")
        ->first();

    $cashiersCount = DB::table('cashiers')
        ->selectRaw("
        COUNT(CASE WHEN last_stock > minimum THEN 1 END) AS safeitem,
        COUNT(CASE WHEN last_stock = minimum THEN 1 END) AS warningitem,
        COUNT(CASE WHEN last_stock < minimum THEN 1 END) AS dangeritem,
        COUNT(*) AS total
    ")
        ->first();

    $operationalsCount = DB::table('operationals')
        ->selectRaw("
        COUNT(CASE WHEN last_stock > minimum THEN 1 END) AS safeitem,
        COUNT(CASE WHEN last_stock = minimum THEN 1 END) AS warningitem,
        COUNT(CASE WHEN last_stock < minimum THEN 1 END) AS dangeritem,
        COUNT(*) AS total
    ")
        ->first();

    $waitersCount = DB::table('waiters')
        ->selectRaw("
        COUNT(CASE WHEN last_stock > minimum THEN 1 END) AS safeitem,
        COUNT(CASE WHEN last_stock = minimum THEN 1 END) AS warningitem,
        COUNT(CASE WHEN last_stock < minimum THEN 1 END) AS dangeritem,
        COUNT(*) AS total
    ")
        ->first();

    return view('welcome', compact(
        'quote',
        'data',
        'bakeriesCount',
        'baristasCount',
        'kitchensCount',
        'cashiersCount',
        'operationalsCount',
        'waitersCount',
    ));
})->middleware(['auth']);

// LOGIN
Route::middleware(['guest'])->group(function () {
    Route::get('login', [AuthController::class, 'index'])->name('login');
    Route::post('login', [AuthController::class, 'login']);
});

// CHANGE PASSWORD
Route::get('password/reset', [AuthController::class, 'showLinkRequestForm'])->name('password.request');
Route::post('password/email', [AuthController::class, 'sendResetLinkEmail'])->name('password.email');
route::get('password/reset/{token}', [AuthController::class, 'showResetForm'])->name('password.reset');
Route::post('password/update', [AuthController::class, 'update'])->name('password.update');

// LOGOUT
Route::get('logout', [AuthController::class, 'logout'])->name('logout')->middleware(['auth']);

// APPROVAL
Route::middleware(['auth'])->group(function () {
    Route::get('approvals', [ApprovalController::class, 'index'])->name('approvals.index');
    Route::get('approvals/edit/{kode_barang}/{code_id}/{id}/{division}/{input_by}', [ApprovalController::class, 'edit'])->name('approvals.edit');
    Route::put('approvals/update/{kode_barang}/{code_id}/{id}/{qty}/{type}', [ApprovalController::class, 'update'])->name('approvals.update');
});

// USERS
Route::middleware(['auth'])->group(function () {
    Route::get('users', [UserController::class, 'index'])->name('users.index');
    Route::get('users/create', [UserController::class, 'create'])->name('users.create');
    Route::post('users/insert', [UserController::class, 'insert'])->name('users.insert');
    Route::get('users/edit/{user}', [UserController::class, 'edit'])->name('users.edit');
    Route::put('users/update/{user}', [UserController::class, 'update'])->name('users.update');
    Route::get('users/reset-password/{user}', [UserController::class, 'reset'])->name('users.reset-password');
    Route::delete('users/destroy/{user}', [UserController::class, 'destroy'])->name('users.destroy');
});

// BAKERY
Route::middleware(['auth'])->group(function () {
    Route::get('bakeries', [BakeryController::class, 'index'])->name('bakeries.index');
    Route::get('bakeries/create', [BakeryController::class, 'create'])->name('bakeries.create');
    Route::post('bakeries/insert', [BakeryController::class, 'insert'])->name('bakeries.insert');
    Route::get('bakeries/edit/{bakery}', [BakeryController::class, 'edit'])->name('bakeries.edit');
    Route::put('bakeries/incresase/{bakery}', [BakeryController::class, 'incresase'])->name('bakeries.incresase');
    Route::put('bakeries/decrease/{bakery}', [BakeryController::class, 'decrease'])->name('bakeries.decrease');
    Route::delete('bakeries/destroy/{bakery}', [BakeryController::class, 'destroy'])->name('bakeries.destroy');
    Route::get('bakeries/history', [BakeryController::class, 'history'])->name('bakeries.history');
    Route::get('bakeries/report', [BakeryController::class, 'report'])->name('bakeries.report');
    Route::post('bakeries/pdf', [BakeryController::class, 'pdf'])->name('bakeries.pdf');
    Route::post('bakeries/excel', [BakeryController::class, 'excel'])->name('bakeries.excel');
});

// BARISTA
Route::middleware(['auth'])->group(function () {
    Route::get('baristas', [BaristasController::class, 'index'])->name('baristas.index');
    Route::get('baristas/create', [BaristasController::class, 'create'])->name('baristas.create');
    Route::post('baristas/insert', [BaristasController::class, 'insert'])->name('baristas.insert');
    Route::get('baristas/edit/{barista}', [BaristasController::class, 'edit'])->name('baristas.edit');
    Route::put('baristas/incresase/{barista}', [BaristasController::class, 'incresase'])->name('baristas.incresase');
    Route::put('baristas/decrease/{barista}', [BaristasController::class, 'decrease'])->name('baristas.decrease');
    Route::delete('baristas/destroy/{barista}', [BaristasController::class, 'destroy'])->name('baristas.destroy');
    Route::get('baristas/history', [BaristasController::class, 'history'])->name('baristas.history');
    Route::get('baristas/report', [BaristasController::class, 'report'])->name('baristas.report');
    Route::post('baristas/pdf', [BaristasController::class, 'pdf'])->name('baristas.pdf');
    Route::post('baristas/excel', [BaristasController::class, 'excel'])->name('baristas.excel');
});

// KITCHEN
Route::middleware(['auth'])->group(function () {
    Route::get('kitchens', [KitchenController::class, 'index'])->name('kitchens.index');
    Route::get('kitchens/create', [KitchenController::class, 'create'])->name('kitchens.create');
    Route::post('kitchens/insert', [KitchenController::class, 'insert'])->name('kitchens.insert');
    Route::get('kitchens/edit/{kitchen}', [KitchenController::class, 'edit'])->name('kitchens.edit');
    Route::put('kitchens/incresase/{kitchen}', [KitchenController::class, 'incresase'])->name('kitchens.incresase');
    Route::put('kitchens/decrease/{kitchen}', [KitchenController::class, 'decrease'])->name('kitchens.decrease');
    Route::delete('kitchens/destroy/{kitchen}', [KitchenController::class, 'destroy'])->name('kitchens.destroy');
    Route::get('kitchens/history', [KitchenController::class, 'history'])->name('kitchens.history');
    Route::get('kitchens/report', [KitchenController::class, 'report'])->name('kitchens.report');
    Route::post('kitchens/pdf', [KitchenController::class, 'pdf'])->name('kitchens.pdf');
    Route::post('kitchens/excel', [KitchenController::class, 'excel'])->name('kitchens.excel');
});

// CASHIER
Route::middleware(['auth'])->group(function () {
    Route::get('cashiers', [CashierController::class, 'index'])->name('cashiers.index');
    Route::get('cashiers/create', [CashierController::class, 'create'])->name('cashiers.create');
    Route::post('cashiers/insert', [CashierController::class, 'insert'])->name('cashiers.insert');
    Route::get('cashiers/edit/{cashier}', [CashierController::class, 'edit'])->name('cashiers.edit');
    Route::put('cashiers/incresase/{cashier}', [CashierController::class, 'incresase'])->name('cashiers.incresase');
    Route::put('cashiers/decrease/{cashier}', [CashierController::class, 'decrease'])->name('cashiers.decrease');
    Route::delete('cashiers/destroy/{cashier}', [CashierController::class, 'destroy'])->name('cashiers.destroy');
    Route::get('cashiers/history', [CashierController::class, 'history'])->name('cashiers.history');
    Route::get('cashiers/report', [CashierController::class, 'report'])->name('cashiers.report');
    Route::post('cashiers/pdf', [CashierController::class, 'pdf'])->name('cashiers.pdf');
    Route::post('cashiers/excel', [CashierController::class, 'excel'])->name('cashiers.excel');
});

// OPERATIONAL
Route::middleware(['auth'])->group(function () {
    Route::get('operationals', [OperationalController::class, 'index'])->name('operationals.index');
    Route::get('operationals/create', [OperationalController::class, 'create'])->name('operationals.create');
    Route::post('operationals/insert', [OperationalController::class, 'insert'])->name('operationals.insert');
    Route::get('operationals/edit/{operational}', [OperationalController::class, 'edit'])->name('operationals.edit');
    Route::put('operationals/incresase/{operational}', [OperationalController::class, 'incresase'])->name('operationals.incresase');
    Route::put('operationals/decrease/{operational}', [OperationalController::class, 'decrease'])->name('operationals.decrease');
    Route::delete('operationals/destroy/{operational}', [OperationalController::class, 'destroy'])->name('operationals.destroy');
    Route::get('operationals/history', [OperationalController::class, 'history'])->name('operationals.history');
    Route::get('operationals/report', [OperationalController::class, 'report'])->name('operationals.report');
    Route::post('operationals/pdf', [OperationalController::class, 'pdf'])->name('operationals.pdf');
    Route::post('operationals/excel', [OperationalController::class, 'excel'])->name('operationals.excel');
});

// WAITER
Route::middleware(['auth'])->group(function () {
    Route::get('waiters', [WaiterController::class, 'index'])->name('waiters.index');
    Route::get('waiters/create', [WaiterController::class, 'create'])->name('waiters.create');
    Route::post('waiters/insert', [WaiterController::class, 'insert'])->name('waiters.insert');
    Route::get('waiters/edit/{waiter}', [WaiterController::class, 'edit'])->name('waiters.edit');
    Route::put('waiters/incresase/{waiter}', [WaiterController::class, 'incresase'])->name('waiters.incresase');
    Route::put('waiters/decrease/{waiter}', [WaiterController::class, 'decrease'])->name('waiters.decrease');
    Route::delete('waiters/destroy/{waiter}', [WaiterController::class, 'destroy'])->name('waiters.destroy');
    Route::get('waiters/history', [WaiterController::class, 'history'])->name('waiters.history');
    Route::get('waiters/report', [WaiterController::class, 'report'])->name('waiters.report');
    Route::post('waiters/pdf', [WaiterController::class, 'pdf'])->name('waiters.pdf');
    Route::post('waiters/excel', [WaiterController::class, 'excel'])->name('waiters.excel');
});

// UNITS
Route::middleware(['auth'])->group(function () {
    Route::get('units', [UnitController::class, 'index'])->name('units.index');
    Route::get('units/create', [UnitController::class, 'create'])->name('units.create');
    Route::post('units/insert', [UnitController::class, 'insert'])->name('units.insert');
    Route::get('units/edit/{unit}', [UnitController::class, 'edit'])->name('units.edit');
    Route::put('units/update/{unit}', [UnitController::class, 'update'])->name('units.update');
    Route::delete('units/destroy/{unit}', [UnitController::class, 'destroy'])->name('units.destroy');
});

// ITEMS
Route::middleware(['auth'])->group(function () {
    Route::get('items', [ItemController::class, 'index'])->name('items.index');
    Route::get('items/edit/{kode_barang}/{code_id}', [ItemController::class, 'edit'])->name('items.edit');
    Route::put('items/{kode_barang}/{code_id}', [ItemController::class, 'update'])->name('items.update');
});

// REPORT
Route::post('reports/{division}', [ReportController::class, 'pdf'])->name('reports.pdf');
Route::get('reports/excel/{fromDate?}/{toDate?}/{division?}', [ReportController::class, 'excel'])->name('reports.excel');

Route::get('history/report/{fromDate?}/{toDate?}/{division?}', [HistoryExportController::class, 'export'])->name('history.export');
