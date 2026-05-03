<?php
// =====================================================================
// MIGRATION: 2024_01_01_000001_create_erp_tables.php
// Run: php artisan migrate
// =====================================================================
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {

        // ===== USERS (extends default Laravel users table) =====
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('employee_id')->nullable()->unique();
            $table->string('title')->nullable();
            $table->string('department')->nullable();
            $table->string('phone')->nullable();
            $table->string('mobile')->nullable();
            $table->text('email_signature')->nullable();
            $table->string('avatar')->nullable();
            $table->boolean('shop_floor_only')->default(false);
            $table->boolean('is_active')->default(true);
            $table->string('default_warehouse')->nullable();
            $table->json('preferences')->nullable();
            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes();
        });

        // ===== CUSTOMERS =====
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->string('customer_number')->unique();
            $table->string('name');
            $table->string('company_name')->nullable();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('fax')->nullable();
            $table->string('website')->nullable();
            $table->string('account_type')->default('customer'); // lead, prospect, customer
            // Billing Address
            $table->string('billing_address1')->nullable();
            $table->string('billing_address2')->nullable();
            $table->string('billing_city')->nullable();
            $table->string('billing_state')->nullable();
            $table->string('billing_zip')->nullable();
            $table->string('billing_country')->default('US');
            // Shipping Address
            $table->string('shipping_address1')->nullable();
            $table->string('shipping_address2')->nullable();
            $table->string('shipping_city')->nullable();
            $table->string('shipping_state')->nullable();
            $table->string('shipping_zip')->nullable();
            $table->string('shipping_country')->default('US');
            // Settings
            $table->string('payment_terms')->default('Net 30');
            $table->string('currency')->default('USD');
            $table->boolean('taxable')->default(true);
            $table->decimal('tax_rate', 5, 4)->default(0);
            $table->string('ship_via')->nullable();
            $table->string('shipping_account')->nullable();
            $table->decimal('credit_limit', 15, 2)->default(0);
            $table->decimal('commission_rate', 5, 4)->default(0);
            $table->unsignedBigInteger('sales_rep_id')->nullable();
            $table->text('notes')->nullable();
            $table->boolean('is_active')->default(true);
            $table->date('date_kit_audit')->nullable();
            $table->string('qc_inspector')->nullable();
            $table->string('date_code_format')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->index('customer_number');
        });

        // ===== CUSTOMER CONTACTS =====
        Schema::create('customer_contacts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->string('title')->nullable();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('mobile')->nullable();
            $table->boolean('primary_contact')->default(false);
            $table->boolean('billing_contact')->default(false);
            $table->boolean('shipping_contact')->default(false);
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        // ===== VENDORS =====
        Schema::create('vendors', function (Blueprint $table) {
            $table->id();
            $table->string('vendor_number')->unique();
            $table->string('name');
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('website')->nullable();
            $table->string('billing_address1')->nullable();
            $table->string('billing_city')->nullable();
            $table->string('billing_state')->nullable();
            $table->string('billing_zip')->nullable();
            $table->string('billing_country')->default('US');
            $table->string('payment_terms')->default('Net 30');
            $table->string('currency')->default('USD');
            $table->boolean('taxable')->default(false);
            $table->string('tax_id')->nullable();
            $table->string('vat_id')->nullable();
            $table->string('fob')->nullable();
            $table->string('ship_via')->nullable();
            $table->decimal('minimum_order', 15, 2)->default(0);
            $table->unsignedBigInteger('buyer_id')->nullable();
            $table->boolean('on_hold')->default(false);
            $table->text('hold_notes')->nullable();
            $table->decimal('rating', 3, 2)->default(5.0);
            $table->boolean('is_active')->default(true);
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        // ===== WAREHOUSES =====
        Schema::create('warehouses', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->string('name');
            $table->string('address1')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('zip')->nullable();
            $table->string('country')->default('US');
            $table->boolean('is_default')->default(false);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // ===== BIN LOCATIONS =====
        Schema::create('bin_locations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('warehouse_id')->constrained()->cascadeOnDelete();
            $table->string('code');
            $table->string('description')->nullable();
            $table->string('zone')->nullable();
            $table->string('aisle')->nullable();
            $table->string('row')->nullable();
            $table->string('level')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->unique(['warehouse_id', 'code']);
        });

        // ===== PARTS / ITEMS =====
        Schema::create('parts', function (Blueprint $table) {
            $table->id();
            $table->string('part_number')->unique();
            $table->string('description');
            $table->string('category')->nullable();
            $table->string('type')->default('component'); // component, subassembly, finished_good, raw_material, service, kit
            $table->string('unit_of_measure')->default('EA');
            $table->decimal('unit_cost', 15, 4)->default(0);
            $table->decimal('standard_cost', 15, 4)->default(0);
            $table->decimal('last_cost', 15, 4)->default(0);
            $table->decimal('average_cost', 15, 4)->default(0);
            $table->decimal('unit_price', 15, 4)->default(0);
            $table->decimal('weight', 10, 4)->nullable();
            $table->string('weight_unit')->default('LB');
            $table->decimal('lead_time_days', 8, 2)->default(0);
            $table->decimal('reorder_point', 15, 4)->default(0);
            $table->decimal('economic_order_qty', 15, 4)->default(0);
            $table->decimal('safety_stock', 15, 4)->default(0);
            $table->string('preferred_vendor_id')->nullable();
            $table->string('make_buy')->default('buy'); // make, buy, either
            $table->boolean('is_active')->default(true);
            $table->boolean('is_purchaseable')->default(true);
            $table->boolean('is_saleable')->default(false);
            $table->boolean('is_manufactured')->default(false);
            $table->boolean('track_serial')->default(false);
            $table->boolean('track_lot')->default(false);
            $table->string('revision')->nullable();
            $table->text('notes')->nullable();
            $table->json('custom_fields')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->index('part_number');
        });

        // ===== INVENTORY =====
        Schema::create('inventory', function (Blueprint $table) {
            $table->id();
            $table->foreignId('part_id')->constrained()->cascadeOnDelete();
            $table->foreignId('warehouse_id')->constrained()->cascadeOnDelete();
            $table->foreignId('bin_location_id')->nullable()->constrained()->nullOnDelete();
            $table->decimal('qty_on_hand', 15, 4)->default(0);
            $table->decimal('qty_reserved', 15, 4)->default(0);
            $table->decimal('qty_on_order', 15, 4)->default(0);
            $table->decimal('unit_cost', 15, 4)->default(0);
            $table->timestamps();
            $table->unique(['part_id', 'warehouse_id', 'bin_location_id']);
        });

        // ===== BOMs =====
        Schema::create('boms', function (Blueprint $table) {
            $table->id();
            $table->foreignId('parent_part_id')->constrained('parts')->cascadeOnDelete();
            $table->string('revision')->default('A');
            $table->string('status')->default('active'); // active, inactive, draft
            $table->text('description')->nullable();
            $table->decimal('labor_estimate_hours', 8, 4)->default(0);
            $table->decimal('overhead_rate', 8, 4)->default(0);
            $table->boolean('is_current')->default(true);
            $table->timestamp('effective_date')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        // ===== BOM LINES =====
        Schema::create('bom_lines', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bom_id')->constrained()->cascadeOnDelete();
            $table->foreignId('part_id')->constrained()->cascadeOnDelete();
            $table->decimal('quantity', 15, 4)->default(1);
            $table->string('unit_of_measure')->default('EA');
            $table->integer('sort_order')->default(0);
            $table->string('reference_designator')->nullable();
            $table->text('notes')->nullable();
            $table->boolean('is_phantom')->default(false); // flatten in BOM
            $table->boolean('substitute_allowed')->default(false);
            $table->timestamps();
        });

        // ===== BOM OPERATIONS (Router) =====
        Schema::create('bom_operations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bom_id')->constrained()->cascadeOnDelete();
            $table->integer('sequence')->default(10);
            $table->string('operation_name');
            $table->string('work_center')->nullable();
            $table->decimal('setup_time_hrs', 8, 4)->default(0);
            $table->decimal('run_time_hrs', 8, 4)->default(0);
            $table->text('work_instructions')->nullable();
            $table->boolean('outsource')->default(false);
            $table->string('outsource_vendor')->nullable();
            $table->decimal('outsource_lead_days', 8, 2)->default(0);
            $table->boolean('machine_setup')->default(false);
            $table->timestamps();
        });

        // ===== QUOTES =====
        Schema::create('quotes', function (Blueprint $table) {
            $table->id();
            $table->string('quote_number')->unique();
            $table->foreignId('customer_id')->constrained();
            $table->unsignedBigInteger('contact_id')->nullable();
            $table->unsignedBigInteger('sales_rep_id')->nullable();
            $table->string('status')->default('draft'); // draft, sent, won, lost, expired
            $table->date('quote_date');
            $table->date('expiry_date')->nullable();
            $table->string('customer_po')->nullable();
            $table->string('payment_terms')->default('Net 30');
            $table->string('ship_via')->nullable();
            $table->string('shipping_account')->nullable();
            $table->decimal('shipping_cost', 15, 2)->default(0);
            $table->decimal('tax_rate', 5, 4)->default(0);
            $table->decimal('discount_pct', 5, 4)->default(0);
            $table->decimal('subtotal', 15, 2)->default(0);
            $table->decimal('tax_amount', 15, 2)->default(0);
            $table->decimal('total', 15, 2)->default(0);
            $table->text('notes')->nullable();
            $table->text('internal_notes')->nullable();
            $table->string('currency')->default('USD');
            $table->decimal('probability', 5, 2)->default(50);
            $table->unsignedBigInteger('converted_order_id')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->index('quote_number');
        });

        // ===== ORDERS =====
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_number')->unique();
            $table->foreignId('customer_id')->constrained();
            $table->unsignedBigInteger('quote_id')->nullable();
            $table->unsignedBigInteger('sales_rep_id')->nullable();
            $table->string('type')->default('stock'); // stock, charge, work_order, build_to_stock
            $table->string('status')->default('new'); // new, in_progress, shipped, invoiced, cancelled
            $table->date('order_date');
            $table->date('due_date')->nullable();
            $table->date('ship_date')->nullable();
            $table->date('work_start_date')->nullable();
            $table->string('customer_po')->nullable();
            $table->string('payment_terms')->default('Net 30');
            $table->string('ship_via')->nullable();
            $table->string('shipping_account')->nullable();
            $table->string('ship_to_name')->nullable();
            $table->string('ship_to_address1')->nullable();
            $table->string('ship_to_city')->nullable();
            $table->string('ship_to_state')->nullable();
            $table->string('ship_to_zip')->nullable();
            $table->string('ship_to_country')->default('US');
            $table->decimal('shipping_cost', 15, 2)->default(0);
            $table->decimal('tax_rate', 5, 4)->default(0);
            $table->decimal('discount_pct', 5, 4)->default(0);
            $table->decimal('subtotal', 15, 2)->default(0);
            $table->decimal('tax_amount', 15, 2)->default(0);
            $table->decimal('total', 15, 2)->default(0);
            $table->decimal('paid', 15, 2)->default(0);
            $table->text('notes')->nullable();
            $table->text('internal_notes')->nullable();
            $table->string('currency')->default('USD');
            $table->foreignId('warehouse_id')->nullable()->constrained()->nullOnDelete();
            $table->boolean('released')->default(false);
            $table->timestamp('released_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->index('order_number');
            $table->index('status');
        });

        // ===== ORDER LINES =====
        Schema::create('order_lines', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->cascadeOnDelete();
            $table->foreignId('part_id')->constrained();
            $table->integer('line_number')->default(1);
            $table->decimal('quantity', 15, 4)->default(1);
            $table->decimal('qty_shipped', 15, 4)->default(0);
            $table->decimal('qty_invoiced', 15, 4)->default(0);
            $table->decimal('unit_cost', 15, 4)->default(0);
            $table->decimal('unit_price', 15, 4)->default(0);
            $table->decimal('discount_pct', 5, 4)->default(0);
            $table->decimal('line_total', 15, 2)->default(0);
            $table->string('status')->default('open');
            $table->date('due_date')->nullable();
            $table->text('line_notes')->nullable();
            $table->text('shop_notes')->nullable();
            $table->unsignedBigInteger('work_order_id')->nullable();
            $table->timestamps();
        });

        // ===== PURCHASE ORDERS =====
        Schema::create('purchase_orders', function (Blueprint $table) {
            $table->id();
            $table->string('po_number')->unique();
            $table->foreignId('vendor_id')->constrained();
            $table->unsignedBigInteger('buyer_id')->nullable();
            $table->string('type')->default('standard'); // standard, outsource, internal
            $table->string('status')->default('draft'); // draft, sent, acknowledged, partial, received, closed
            $table->date('po_date');
            $table->date('requested_date')->nullable();
            $table->date('acknowledged_date')->nullable();
            $table->string('vendor_po_number')->nullable();
            $table->string('fob')->nullable();
            $table->string('ship_via')->nullable();
            $table->foreignId('warehouse_id')->nullable()->constrained()->nullOnDelete();
            $table->string('payment_terms')->default('Net 30');
            $table->decimal('subtotal', 15, 2)->default(0);
            $table->decimal('tax_amount', 15, 2)->default(0);
            $table->decimal('total', 15, 2)->default(0);
            $table->decimal('amount_billed', 15, 2)->default(0);
            $table->decimal('amount_paid', 15, 2)->default(0);
            $table->text('notes')->nullable();
            $table->string('currency')->default('USD');
            $table->unsignedBigInteger('work_order_id')->nullable();
            $table->boolean('acknowledged')->default(false);
            $table->timestamps();
            $table->softDeletes();
            $table->index('po_number');
        });

        // ===== PO LINES =====
        Schema::create('po_lines', function (Blueprint $table) {
            $table->id();
            $table->foreignId('purchase_order_id')->constrained()->cascadeOnDelete();
            $table->foreignId('part_id')->constrained();
            $table->integer('line_number')->default(1);
            $table->decimal('quantity', 15, 4)->default(1);
            $table->decimal('qty_received', 15, 4)->default(0);
            $table->decimal('qty_billed', 15, 4)->default(0);
            $table->decimal('unit_cost', 15, 4)->default(0);
            $table->decimal('line_total', 15, 2)->default(0);
            $table->date('commit_date')->nullable();
            $table->string('status')->default('open');
            $table->string('vendor_part_number')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        // ===== RECEIPTS =====
        Schema::create('receipts', function (Blueprint $table) {
            $table->id();
            $table->string('receipt_number')->unique();
            $table->foreignId('purchase_order_id')->constrained();
            $table->foreignId('vendor_id')->constrained();
            $table->date('receipt_date');
            $table->string('packing_slip')->nullable();
            $table->text('notes')->nullable();
            $table->string('received_by')->nullable();
            $table->string('status')->default('pending'); // pending, inspected, stocked
            $table->timestamps();
        });

        // ===== RECEIPT LINES =====
        Schema::create('receipt_lines', function (Blueprint $table) {
            $table->id();
            $table->foreignId('receipt_id')->constrained()->cascadeOnDelete();
            $table->foreignId('po_line_id')->constrained()->cascadeOnDelete();
            $table->foreignId('part_id')->constrained();
            $table->foreignId('warehouse_id')->constrained();
            $table->foreignId('bin_location_id')->nullable()->constrained()->nullOnDelete();
            $table->decimal('quantity', 15, 4)->default(0);
            $table->decimal('unit_cost', 15, 4)->default(0);
            $table->string('lot_number')->nullable();
            $table->string('date_code')->nullable();
            $table->string('revision')->nullable();
            $table->string('serial_number')->nullable();
            $table->string('inspection_status')->default('accepted'); // accepted, rejected, pending
            $table->integer('qty_accepted')->default(0);
            $table->integer('qty_rejected')->default(0);
            $table->text('inspection_notes')->nullable();
            $table->timestamps();
        });

        // ===== WORK ORDERS =====
        Schema::create('work_orders', function (Blueprint $table) {
            $table->id();
            $table->string('wo_number')->unique();
            $table->foreignId('part_id')->constrained();
            $table->unsignedBigInteger('order_id')->nullable();
            $table->unsignedBigInteger('order_line_id')->nullable();
            $table->unsignedBigInteger('bom_id')->nullable();
            $table->string('type')->default('customer'); // customer, build_to_stock, rework
            $table->string('status')->default('open'); // open, released, in_progress, complete, cancelled
            $table->decimal('quantity', 15, 4)->default(1);
            $table->decimal('qty_complete', 15, 4)->default(0);
            $table->decimal('qty_scrapped', 15, 4)->default(0);
            $table->date('order_date');
            $table->date('due_date')->nullable();
            $table->date('work_start_date')->nullable();
            $table->date('completed_date')->nullable();
            $table->decimal('unit_cost_estimate', 15, 4)->default(0);
            $table->decimal('unit_cost_actual', 15, 4)->default(0);
            $table->decimal('labor_hrs_estimate', 8, 4)->default(0);
            $table->decimal('labor_hrs_actual', 8, 4)->default(0);
            $table->decimal('material_cost_actual', 15, 4)->default(0);
            $table->decimal('labor_cost_actual', 15, 4)->default(0);
            $table->decimal('overhead_cost_actual', 15, 4)->default(0);
            $table->decimal('outsource_cost_actual', 15, 4)->default(0);
            $table->text('notes')->nullable();
            $table->foreignId('warehouse_id')->nullable()->constrained()->nullOnDelete();
            $table->boolean('released')->default(false);
            $table->timestamp('released_at')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->index('wo_number');
            $table->index('status');
        });

        // ===== WORK ORDER MATERIAL (Pick List) =====
        Schema::create('wo_materials', function (Blueprint $table) {
            $table->id();
            $table->foreignId('work_order_id')->constrained()->cascadeOnDelete();
            $table->foreignId('part_id')->constrained();
            $table->foreignId('bom_line_id')->nullable()->constrained()->nullOnDelete();
            $table->decimal('qty_required', 15, 4)->default(0);
            $table->decimal('qty_picked', 15, 4)->default(0);
            $table->decimal('qty_consumed', 15, 4)->default(0);
            $table->decimal('qty_scrapped', 15, 4)->default(0);
            $table->decimal('unit_cost', 15, 4)->default(0);
            $table->string('status')->default('open'); // open, picked, consumed
            $table->string('lot_number')->nullable();
            $table->string('serial_number')->nullable();
            $table->foreignId('bin_location_id')->nullable()->constrained()->nullOnDelete();
            $table->timestamps();
        });

        // ===== WORK ORDER OPERATIONS =====
        Schema::create('wo_operations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('work_order_id')->constrained()->cascadeOnDelete();
            $table->integer('sequence')->default(10);
            $table->string('operation_name');
            $table->string('work_center')->nullable();
            $table->decimal('setup_time_est', 8, 4)->default(0);
            $table->decimal('run_time_est', 8, 4)->default(0);
            $table->decimal('setup_time_actual', 8, 4)->default(0);
            $table->decimal('run_time_actual', 8, 4)->default(0);
            $table->string('status')->default('open'); // open, in_progress, complete
            $table->text('work_instructions')->nullable();
            $table->boolean('outsource')->default(false);
            $table->string('assigned_to')->nullable();
            $table->timestamp('started_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();
        });

        // ===== LABOR TRACKING =====
        Schema::create('labor_entries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('work_order_id')->constrained()->cascadeOnDelete();
            $table->foreignId('wo_operation_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('user_id')->constrained();
            $table->timestamp('clock_in');
            $table->timestamp('clock_out')->nullable();
            $table->decimal('hours', 8, 4)->default(0);
            $table->decimal('overtime_hours', 8, 4)->default(0);
            $table->decimal('labor_rate', 8, 4)->default(0);
            $table->decimal('labor_cost', 15, 4)->default(0);
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        // ===== INVOICES =====
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_number')->unique();
            $table->foreignId('customer_id')->constrained();
            $table->foreignId('order_id')->nullable()->constrained()->nullOnDelete();
            $table->string('status')->default('draft'); // draft, sent, paid, overdue, cancelled
            $table->date('invoice_date');
            $table->date('due_date')->nullable();
            $table->decimal('subtotal', 15, 2)->default(0);
            $table->decimal('tax_amount', 15, 2)->default(0);
            $table->decimal('shipping', 15, 2)->default(0);
            $table->decimal('total', 15, 2)->default(0);
            $table->decimal('amount_paid', 15, 2)->default(0);
            $table->decimal('balance_due', 15, 2)->default(0);
            $table->string('payment_terms')->default('Net 30');
            $table->text('notes')->nullable();
            $table->timestamp('sent_at')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->string('currency')->default('USD');
            $table->timestamps();
            $table->softDeletes();
            $table->index('invoice_number');
        });

        // ===== ACCOUNTS PAYABLE =====
        Schema::create('ap_vouchers', function (Blueprint $table) {
            $table->id();
            $table->string('voucher_number')->unique();
            $table->foreignId('vendor_id')->constrained();
            $table->foreignId('purchase_order_id')->nullable()->constrained()->nullOnDelete();
            $table->string('vendor_invoice_number')->nullable();
            $table->string('status')->default('pending'); // pending, approved, paid, cancelled
            $table->date('invoice_date');
            $table->date('due_date');
            $table->decimal('amount', 15, 2)->default(0);
            $table->decimal('amount_paid', 15, 2)->default(0);
            $table->decimal('balance', 15, 2)->default(0);
            $table->text('notes')->nullable();
            $table->string('gl_account')->nullable();
            $table->timestamps();
        });

        // ===== PAYMENTS =====
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->string('payment_number')->unique();
            $table->string('type'); // ar_payment, ap_payment
            $table->morphs('payable'); // customer or vendor
            $table->date('payment_date');
            $table->decimal('amount', 15, 2)->default(0);
            $table->string('payment_method')->default('check'); // check, wire, ach, credit_card
            $table->string('reference_number')->nullable();
            $table->string('bank_account')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        // ===== LEADS =====
        Schema::create('leads', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('company')->nullable();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('status')->default('new'); // new, contacted, qualified, converted, lost
            $table->string('source')->nullable();
            $table->string('program')->nullable();
            $table->unsignedBigInteger('assigned_to')->nullable();
            $table->date('follow_up_date')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        // ===== NCR (Non-Conformance Reports) =====
        Schema::create('ncrs', function (Blueprint $table) {
            $table->id();
            $table->string('ncr_number')->unique();
            $table->string('title');
            $table->text('description');
            $table->string('status')->default('open'); // open, review, mrb, closed
            $table->string('source')->default('receiving'); // receiving, production, customer, audit
            $table->string('disposition')->nullable(); // scrap, rework, use_as_is, return_to_vendor
            $table->foreignId('part_id')->nullable()->constrained()->nullOnDelete();
            $table->unsignedBigInteger('order_id')->nullable();
            $table->unsignedBigInteger('receipt_id')->nullable();
            $table->unsignedBigInteger('vendor_id')->nullable();
            $table->decimal('quantity', 15, 4)->default(0);
            $table->decimal('cost_impact', 15, 2)->default(0);
            $table->string('containment_area')->nullable();
            $table->unsignedBigInteger('assigned_to')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->date('due_date')->nullable();
            $table->timestamp('closed_at')->nullable();
            $table->text('resolution')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        // ===== ECO/ECR =====
        Schema::create('ecos', function (Blueprint $table) {
            $table->id();
            $table->string('eco_number')->unique();
            $table->string('title');
            $table->string('type')->default('eco'); // eco, ecr
            $table->string('status')->default('draft'); // draft, review, approved, closed
            $table->text('description')->nullable();
            $table->text('risk_mitigation')->nullable();
            $table->decimal('cost_impact', 15, 2)->default(0);
            $table->foreignId('part_id')->nullable()->constrained()->nullOnDelete();
            $table->string('rev_from')->nullable();
            $table->string('rev_to')->nullable();
            $table->unsignedBigInteger('initiated_by')->nullable();
            $table->unsignedBigInteger('assigned_to')->nullable();
            $table->date('due_date')->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        // ===== DOCUMENTS =====
        Schema::create('documents', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('file_name');
            $table->string('file_path');
            $table->string('file_type')->nullable();
            $table->bigInteger('file_size')->default(0);
            $table->string('category')->nullable();
            $table->string('revision')->nullable();
            $table->string('status')->default('draft'); // draft, review, approved, rev_control
            $table->text('description')->nullable();
            $table->json('tags')->nullable();
            $table->morphs('documentable');
            $table->unsignedBigInteger('uploaded_by')->nullable();
            $table->unsignedBigInteger('approved_by')->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        // ===== ASSETS / TOOLS =====
        Schema::create('assets', function (Blueprint $table) {
            $table->id();
            $table->string('asset_id')->unique();
            $table->string('name');
            $table->string('type')->nullable(); // tool, machine, equipment
            $table->string('serial_number')->nullable();
            $table->string('model_number')->nullable();
            $table->string('manufacturer')->nullable();
            $table->unsignedBigInteger('purchase_order_id')->nullable();
            $table->decimal('purchase_value', 15, 2)->default(0);
            $table->date('purchase_date')->nullable();
            $table->string('bin_location')->nullable();
            $table->string('owner')->nullable();
            $table->string('status')->default('available'); // available, checked_out, maintenance
            $table->date('next_maintenance_date')->nullable();
            $table->integer('maintenance_frequency_days')->default(365);
            $table->text('notes')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });

        // ===== RMAs =====
        Schema::create('rmas', function (Blueprint $table) {
            $table->id();
            $table->string('rma_number')->unique();
            $table->foreignId('customer_id')->constrained();
            $table->foreignId('order_id')->nullable()->constrained()->nullOnDelete();
            $table->string('type')->default('return'); // return, repair, replacement, refund
            $table->string('status')->default('open'); // open, received, processing, closed
            $table->date('rma_date');
            $table->decimal('handling_charges', 15, 2)->default(0);
            $table->decimal('credit_amount', 15, 2)->default(0);
            $table->text('reason')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        // ===== SHIPMENTS =====
        Schema::create('shipments', function (Blueprint $table) {
            $table->id();
            $table->string('shipment_number')->unique();
            $table->foreignId('order_id')->constrained();
            $table->foreignId('customer_id')->constrained();
            $table->string('status')->default('pending'); // pending, shipped, delivered
            $table->date('ship_date');
            $table->string('carrier')->nullable();
            $table->string('tracking_number')->nullable();
            $table->string('service')->nullable();
            $table->decimal('weight', 10, 4)->nullable();
            $table->string('dimensions')->nullable();
            $table->decimal('freight_cost', 15, 2)->default(0);
            $table->decimal('freight_charge', 15, 2)->default(0);
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        // ===== NOTIFICATIONS / ALERTS =====
        Schema::create('erp_notifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('type');
            $table->string('title');
            $table->text('message');
            $table->string('link')->nullable();
            $table->boolean('is_read')->default(false);
            $table->timestamps();
        });

        // ===== ACTIVITY LOG =====
        Schema::create('activity_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('action');
            $table->string('model_type')->nullable();
            $table->unsignedBigInteger('model_id')->nullable();
            $table->json('old_values')->nullable();
            $table->json('new_values')->nullable();
            $table->string('ip_address')->nullable();
            $table->text('description')->nullable();
            $table->timestamps();
            $table->index(['model_type', 'model_id']);
        });

        // ===== GENERAL LEDGER =====
        Schema::create('gl_accounts', function (Blueprint $table) {
            $table->id();
            $table->string('account_number')->unique();
            $table->string('name');
            $table->string('type'); // asset, liability, equity, revenue, expense
            $table->string('sub_type')->nullable();
            $table->boolean('is_active')->default(true);
            $table->decimal('balance', 15, 2)->default(0);
            $table->text('description')->nullable();
            $table->timestamps();
        });

        Schema::create('gl_transactions', function (Blueprint $table) {
            $table->id();
            $table->string('reference_number')->nullable();
            $table->string('transaction_type');
            $table->date('transaction_date');
            $table->text('description')->nullable();
            $table->morphs('transactionable');
            $table->timestamps();
        });

        Schema::create('gl_entries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('gl_transaction_id')->constrained()->cascadeOnDelete();
            $table->foreignId('gl_account_id')->constrained();
            $table->decimal('debit', 15, 4)->default(0);
            $table->decimal('credit', 15, 4)->default(0);
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void {
        $tables = [
            'gl_entries','gl_transactions','gl_accounts',
            'activity_logs','erp_notifications','shipments','rmas',
            'assets','documents','ecos','ncrs','labor_entries',
            'wo_operations','wo_materials','work_orders','ap_vouchers',
            'payments','invoices','receipt_lines','receipts',
            'po_lines','purchase_orders','order_lines','orders',
            'quotes','leads','bom_operations','bom_lines','boms',
            'inventory','parts','bin_locations','warehouses',
            'customer_contacts','vendors','customers','users'
        ];
        foreach ($tables as $table) {
            Schema::dropIfExists($table);
        }
    }
};
