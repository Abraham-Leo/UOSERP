<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Modules\CRM\LeadController;
use App\Http\Controllers\Modules\CRM\OpportunityController;
use App\Http\Controllers\Modules\CRM\CustomerController;
use App\Http\Controllers\Modules\Sales\QuoteController;
use App\Http\Controllers\Modules\Sales\OrderController;
use App\Http\Controllers\Modules\Sales\InvoiceController;
use App\Http\Controllers\Modules\Purchasing\PurchaseOrderController;
use App\Http\Controllers\Modules\Purchasing\VendorController;
use App\Http\Controllers\Modules\Purchasing\ReceivingController;
use App\Http\Controllers\Modules\Inventory\PartController;
use App\Http\Controllers\Modules\Inventory\BomController;
use App\Http\Controllers\Modules\Inventory\WarehouseController;
use App\Http\Controllers\Modules\Inventory\StockController;
use App\Http\Controllers\Modules\Production\WorkOrderController;
use App\Http\Controllers\Modules\Production\SchedulingController;
use App\Http\Controllers\Modules\Production\MrpController;
use App\Http\Controllers\Modules\Finance\AccountingController;
use App\Http\Controllers\Modules\Finance\AccountsPayableController;
use App\Http\Controllers\Modules\Finance\AccountsReceivableController;
use App\Http\Controllers\Modules\Finance\ReportController;
use App\Http\Controllers\Modules\QMS\NcrController;
use App\Http\Controllers\Modules\QMS\EcoController;
use App\Http\Controllers\Modules\QMS\InspectionController;
use App\Http\Controllers\Modules\HR\UserController;
use App\Http\Controllers\Modules\HR\RoleController;
use App\Http\Controllers\Modules\Tools\AssetController;
use App\Http\Controllers\Modules\Shipping\ShipmentController;
use App\Http\Controllers\Modules\Shipping\RmaController;
use App\Http\Controllers\Modules\Documents\DocumentController;

// Auth Routes
Route::get('/', function () { 
    return redirect('/login'); 
});

Route::get('/login', [LoginController::class, 'showLogin'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.post');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// ERP Core Routes (Authenticated)
Route::middleware(['auth'])->group(function () {

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // =========== SETTINGS ===========
    Route::get('/settings', [App\Http\Controllers\SettingsController::class, 'index'])->name('settings');
    Route::put('/settings', [App\Http\Controllers\SettingsController::class, 'update'])->name('settings.update');
    Route::put('/settings/password', [App\Http\Controllers\SettingsController::class, 'updatePassword'])->name('settings.password');

     // =========== CRM MODULE ===========
    Route::prefix('crm')->name('crm.')->group(function () {
        Route::resource('customers', CustomerController::class);  // <-- UNCOMMENT
        Route::resource('leads', LeadController::class);
        Route::resource('opportunities', OpportunityController::class);
        
        Route::get('customers/{customer}/orders', [CustomerController::class, 'orders'])->name('customers.orders');  // <-- UNCOMMENT
        Route::get('customers/{customer}/statement', [CustomerController::class, 'statement'])->name('customers.statement');  // <-- UNCOMMENT
        Route::get('pipeline', [LeadController::class, 'pipeline'])->name('pipeline');
    });

    // =========== SALES MODULE ===========
    Route::prefix('sales')->name('sales.')->group(function () {
        Route::resource('quotes', QuoteController::class);
        Route::resource('orders', OrderController::class);
        Route::resource('invoices', InvoiceController::class);
        
        Route::post('quotes/{quote}/convert', [QuoteController::class, 'convertToOrder'])->name('quotes.convert');
        Route::get('quotes/{quote}/pdf', [QuoteController::class, 'pdf'])->name('quotes.pdf');
        Route::get('orders/{order}/pdf', [OrderController::class, 'pdf'])->name('orders.pdf');
        Route::get('invoices/{invoice}/pdf', [InvoiceController::class, 'pdf'])->name('invoices.pdf');
        Route::post('invoices/{invoice}/send', [InvoiceController::class, 'send'])->name('invoices.send');
        Route::post('orders/{order}/release', [OrderController::class, 'release'])->name('orders.release');
        Route::get('dashboard', [OrderController::class, 'dashboard'])->name('dashboard');
    });

    // =========== PURCHASING MODULE ===========
    Route::prefix('purchasing')->name('purchasing.')->group(function () {
        Route::resource('vendors', VendorController::class);
        Route::resource('purchase-orders', PurchaseOrderController::class);
        Route::resource('receiving', ReceivingController::class);
        
        Route::get('purchase-orders/{po}/pdf', [PurchaseOrderController::class, 'pdf'])->name('purchase-orders.pdf');
        Route::post('purchase-orders/{po}/send', [PurchaseOrderController::class, 'send'])->name('purchase-orders.send');
        Route::post('purchase-orders/{po}/acknowledge', [PurchaseOrderController::class, 'acknowledge'])->name('purchase-orders.acknowledge');
        Route::post('receiving/{po}/receive', [ReceivingController::class, 'receive'])->name('receiving.receive');
        Route::get('dashboard', [PurchaseOrderController::class, 'dashboard'])->name('dashboard');
    });

    // =========== INVENTORY MODULE ===========
    Route::prefix('inventory')->name('inventory.')->group(function () {
        Route::resource('parts', PartController::class);
        Route::resource('boms', BomController::class);
        Route::resource('warehouses', WarehouseController::class);
        
        Route::get('stock', [StockController::class, 'index'])->name('stock.index');
        Route::post('stock/adjust', [StockController::class, 'adjust'])->name('stock.adjust');
        Route::post('stock/transfer', [StockController::class, 'transfer'])->name('stock.transfer');
        Route::get('cycle-count', [StockController::class, 'cycleCount'])->name('cycle-count');
        Route::post('cycle-count/submit', [StockController::class, 'submitCount'])->name('cycle-count.submit');
        Route::get('parts/{part}/history', [PartController::class, 'history'])->name('parts.history');
        Route::get('dashboard', [StockController::class, 'dashboard'])->name('dashboard');
    });

    // =========== PRODUCTION / MES MODULE ===========
    Route::prefix('production')->name('production.')->group(function () {
        Route::resource('work-orders', WorkOrderController::class);
        Route::get('scheduling', [SchedulingController::class, 'index'])->name('scheduling.index');
        Route::get('mrp', [MrpController::class, 'index'])->name('mrp.index');
        Route::post('mrp/run', [MrpController::class, 'run'])->name('mrp.run');
        
        Route::post('work-orders/{wo}/release', [WorkOrderController::class, 'release'])->name('work-orders.release');
        Route::post('work-orders/{wo}/complete', [WorkOrderController::class, 'complete'])->name('work-orders.complete');
        Route::post('work-orders/{wo}/clock-in', [WorkOrderController::class, 'clockIn'])->name('work-orders.clock-in');
        Route::post('work-orders/{wo}/clock-out', [WorkOrderController::class, 'clockOut'])->name('work-orders.clock-out');
        Route::get('shop-floor', [WorkOrderController::class, 'shopFloor'])->name('shop-floor');
        Route::get('traveler/{wo}', [WorkOrderController::class, 'traveler'])->name('traveler');
        Route::get('dashboard', [WorkOrderController::class, 'dashboard'])->name('dashboard');
    });

    // =========== FINANCE MODULE ===========
    Route::prefix('finance')->name('finance.')->group(function () {
        Route::get('dashboard', [AccountingController::class, 'dashboard'])->name('dashboard');
        Route::resource('accounts-payable', AccountsPayableController::class);
        Route::resource('accounts-receivable', AccountsReceivableController::class);
        
        Route::post('accounts-payable/{voucher}/pay', [AccountsPayableController::class, 'pay'])->name('ap.pay');
        Route::post('accounts-receivable/{ar}/collect', [AccountsReceivableController::class, 'collect'])->name('ar.collect');
        
        Route::get('general-ledger', [AccountingController::class, 'generalLedger'])->name('gl');
        Route::get('reports/profit-loss', [ReportController::class, 'profitLoss'])->name('reports.pl');
        Route::get('reports/balance-sheet', [ReportController::class, 'balanceSheet'])->name('reports.bs');
        Route::get('reports/cash-flow', [ReportController::class, 'cashFlow'])->name('reports.cf');
        Route::get('reports/ar-aging', [ReportController::class, 'arAging'])->name('reports.ar-aging');
        Route::get('reports/ap-aging', [ReportController::class, 'apAging'])->name('reports.ap-aging');
        Route::get('bank/reconcile', [AccountingController::class, 'bankReconcile'])->name('bank.reconcile');
    });

    // =========== QMS MODULE ===========
    Route::prefix('qms')->name('qms.')->group(function () {
        Route::resource('ncr', NcrController::class);
        Route::resource('eco', EcoController::class);
        Route::resource('inspections', InspectionController::class);
        
        Route::post('ncr/{ncr}/escalate', [NcrController::class, 'escalate'])->name('ncr.escalate');
        Route::post('ncr/{ncr}/close', [NcrController::class, 'close'])->name('ncr.close');
        Route::post('eco/{eco}/approve', [EcoController::class, 'approve'])->name('eco.approve');
        Route::get('dashboard', [NcrController::class, 'dashboard'])->name('dashboard');
    });

    // =========== HR / USER MANAGEMENT ===========
    Route::prefix('hr')->name('hr.')->group(function () {
        Route::resource('users', UserController::class);
        Route::resource('roles', RoleController::class);
        Route::post('users/{user}/assign-role', [UserController::class, 'assignRole'])->name('users.assign-role');
    });

    // =========== TOOLS / ASSETS ===========
    Route::prefix('tools')->name('tools.')->group(function () {
        Route::resource('assets', AssetController::class);
        Route::post('assets/{asset}/checkout', [AssetController::class, 'checkout'])->name('assets.checkout');
        Route::post('assets/{asset}/checkin', [AssetController::class, 'checkin'])->name('assets.checkin');
        Route::post('assets/{asset}/maintenance', [AssetController::class, 'logMaintenance'])->name('assets.maintenance');
    });

    // =========== SHIPPING MODULE ===========
    Route::prefix('shipping')->name('shipping.')->group(function () {
        Route::resource('shipments', ShipmentController::class);
        Route::resource('rma', RmaController::class);
        
        Route::get('shipments/{shipment}/label', [ShipmentController::class, 'label'])->name('shipments.label');
        Route::post('shipments/{shipment}/ship', [ShipmentController::class, 'ship'])->name('shipments.ship');
    });

    // =========== DOCUMENTS MODULE ===========
    Route::prefix('documents')->name('documents.')->group(function () {
        // PERBAIKAN: Ganti '/' dengan 'index' atau gunakan Route::view
        Route::get('/', [DocumentController::class, 'index'])->name('index');
        Route::get('create', [DocumentController::class, 'create'])->name('create');
        Route::post('/', [DocumentController::class, 'store'])->name('store');
        Route::get('{document}', [DocumentController::class, 'show'])->name('show');
        Route::get('{document}/edit', [DocumentController::class, 'edit'])->name('edit');
        Route::put('{document}', [DocumentController::class, 'update'])->name('update');
        Route::delete('{document}', [DocumentController::class, 'destroy'])->name('destroy');
        
        Route::post('upload', [DocumentController::class, 'upload'])->name('upload');
        Route::get('{document}/download', [DocumentController::class, 'download'])->name('download');
        Route::post('{document}/approve', [DocumentController::class, 'approve'])->name('approve');
    });

    // API endpoints for AJAX/DataTables
    Route::prefix('api')->name('api.')->group(function () {
        Route::get('parts/search', [PartController::class, 'search'])->name('parts.search');
        // SEMENTARA: Comment dulu sampai CustomerController dibuat
        // Route::get('customers/search', [CustomerController::class, 'search'])->name('customers.search');
        Route::get('vendors/search', [VendorController::class, 'search'])->name('vendors.search');
        Route::get('stock/{part}', [StockController::class, 'getStock'])->name('stock.get');
        Route::get('dashboard/stats', [DashboardController::class, 'stats'])->name('dashboard.stats');
    });
});