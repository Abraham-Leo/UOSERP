<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ===== PERMISSIONS & ROLES (Spatie) =====
        $roles = ['admin', 'sales', 'production', 'purchasing', 'finance', 'quality', 'warehouse'];
        foreach ($roles as $role) {
            \Spatie\Permission\Models\Role::firstOrCreate(['name' => $role]);
        }

        // ===== USERS =====
        $users = [
            ['name' => 'System Admin',    'email' => 'admin@erp.com',   'role' => 'admin'],
            ['name' => 'Sarah Sales',     'email' => 'sales@erp.com',   'role' => 'sales'],
            ['name' => 'Pete Production', 'email' => 'prod@erp.com',    'role' => 'production'],
            ['name' => 'Frank Finance',   'email' => 'finance@erp.com', 'role' => 'finance'],
            ['name' => 'Qara Quality',    'email' => 'quality@erp.com', 'role' => 'quality'],
            ['name' => 'Will Warehouse',  'email' => 'wh@erp.com',      'role' => 'warehouse'],
            ['name' => 'Pablo Purchasing','email' => 'purchase@erp.com','role' => 'purchasing'],
        ];

        foreach ($users as $u) {
            $user = \App\Models\User::firstOrCreate(
                ['email' => $u['email']],
                [
                    'name'     => $u['name'],
                    'password' => Hash::make('password'),
                    'email_verified_at' => now(),
                ]
            );
            $user->assignRole($u['role']);
        }

        // ===== WAREHOUSES =====
        DB::table('warehouses')->insertOrIgnore([
            ['code' => 'MAIN', 'name' => 'Main Warehouse', 'is_default' => true, 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['code' => 'WIP',  'name' => 'WIP Storage',    'is_default' => false,'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['code' => 'FG',   'name' => 'Finished Goods', 'is_default' => false,'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
        ]);

        // ===== GL ACCOUNTS (Chart of Accounts) =====
        $accounts = [
            ['1000', 'Cash — Checking',         'asset',     'current'],
            ['1100', 'Accounts Receivable',      'asset',     'current'],
            ['1200', 'Inventory Asset',          'asset',     'current'],
            ['1500', 'Fixed Assets',             'asset',     'fixed'],
            ['2000', 'Accounts Payable',         'liability', 'current'],
            ['2100', 'Accrued A/P Liabilities',  'liability', 'current'],
            ['2500', 'Long-Term Debt',           'liability', 'long_term'],
            ['3000', 'Owner Equity',             'equity',    null],
            ['3100', 'Retained Earnings',        'equity',    null],
            ['4000', 'Sales Revenue',            'revenue',   null],
            ['4100', 'Service Revenue',          'revenue',   null],
            ['5000', 'Cost of Goods Sold',       'expense',   'cogs'],
            ['5100', 'Direct Labor',             'expense',   'cogs'],
            ['5200', 'Manufacturing Overhead',   'expense',   'cogs'],
            ['6000', 'Salaries & Wages',         'expense',   'operating'],
            ['6100', 'Rent & Facilities',        'expense',   'operating'],
            ['6200', 'Utilities',                'expense',   'operating'],
            ['6300', 'Marketing & Advertising',  'expense',   'operating'],
            ['6400', 'Shipping & Freight',       'expense',   'operating'],
            ['6500', 'Depreciation',             'expense',   'operating'],
        ];

        foreach ($accounts as $a) {
            DB::table('gl_accounts')->insertOrIgnore([
                'account_number' => $a[0],
                'name'           => $a[1],
                'type'           => $a[2],
                'sub_type'       => $a[3],
                'is_active'      => true,
                'balance'        => 0,
                'created_at'     => now(),
                'updated_at'     => now(),
            ]);
        }

        // ===== CUSTOMERS =====
        $customers = [
            ['CUST-00001', 'Acme Industries',     'acme@acme.com',       '555-100-0001', 'Net 30'],
            ['CUST-00002', 'TechCorp LLC',         'orders@techcorp.com', '555-100-0002', 'Net 45'],
            ['CUST-00003', 'Global Manufacturing', 'ap@globalMFG.com',    '555-100-0003', 'Net 30'],
            ['CUST-00004', 'Pacific Steel Co',     'po@pacsteel.com',     '555-100-0004', 'Net 60'],
            ['CUST-00005', 'Nexus Parts Inc',      'buy@nexusparts.com',  '555-100-0005', 'Net 15'],
            ['CUST-00006', 'Delta Systems',        'eng@deltasys.com',    '555-100-0006', 'Net 30'],
            ['CUST-00007', 'Precision Tools Ltd',  'ops@prectools.com',   '555-100-0007', 'COD'],
        ];

        foreach ($customers as $c) {
            DB::table('customers')->insertOrIgnore([
                'customer_number'  => $c[0],
                'name'             => $c[1],
                'email'            => $c[2],
                'phone'            => $c[3],
                'payment_terms'    => $c[4],
                'account_type'     => 'customer',
                'billing_country'  => 'US',
                'shipping_country' => 'US',
                'taxable'          => true,
                'is_active'        => true,
                'currency'         => 'USD',
                'created_at'       => now(),
                'updated_at'       => now(),
            ]);
        }

        // ===== VENDORS =====
        $vendors = [
            ['VEND-00001', 'DigiKey Corp',       'orders@digikey.com',  'Net 30'],
            ['VEND-00002', 'Acme Electronics',   'ap@acmeelec.com',     'Net 45'],
            ['VEND-00003', 'MetalMart Inc',      'po@metalmart.com',    'Net 30'],
            ['VEND-00004', 'Global Bearings',    'sales@gbearings.com', 'Net 30'],
            ['VEND-00005', 'Wire Works LLC',     'info@wireworks.com',  'Net 15'],
            ['VEND-00006', 'Contract Fab Co',    'ops@contractfab.com', 'Net 45'],
            ['VEND-00007', 'Mouser Electronics', 'po@mouser.com',       'Net 30'],
        ];

        foreach ($vendors as $v) {
            DB::table('vendors')->insertOrIgnore([
                'vendor_number'  => $v[0],
                'name'           => $v[1],
                'email'          => $v[2],
                'payment_terms'  => $v[3],
                'billing_country'=> 'US',
                'is_active'      => true,
                'currency'       => 'USD',
                'rating'         => 4.5,
                'created_at'     => now(),
                'updated_at'     => now(),
            ]);
        }

        // ===== PARTS =====
        $parts = [
            ['COMP-0042', 'Capacitor 100uF 25V SMD',      'component',     'buy',  0.12,  0.10,  'EA'],
            ['COMP-0091', 'IC Chip STM32F407VGT6',        'component',     'buy',  8.45,  7.80,  'EA'],
            ['COMP-0055', 'Resistor 10kΩ 0402 1%',        'component',     'buy',  0.008, 0.006, 'EA'],
            ['COMP-0078', 'Transformer 24V 2A',           'component',     'buy',  12.50, 11.00, 'EA'],
            ['COMP-0102', 'MOSFET IRF540N',               'component',     'buy',  1.85,  1.60,  'EA'],
            ['MECH-0033', 'Bearing 6204 2RS Sealed',      'component',     'buy',  2.85,  2.50,  'EA'],
            ['MECH-0019', 'Aluminum Bracket L40×40',      'component',     'buy',  3.50,  3.00,  'EA'],
            ['RAW-0012',  'Steel Sheet 304 SS 2mm',       'raw_material',  'buy',  4.20,  3.80,  'SQFT'],
            ['RAW-0008',  'Copper Wire 18AWG',            'raw_material',  'buy',  0.85,  0.75,  'FT'],
            ['SUB-0018',  'Control Board Rev C',          'subassembly',   'make', 145.00,120.00, 'EA'],
            ['FG-0001',   'Motor Controller Unit v3',     'finished_good', 'make', 482.00,400.00, 'EA'],
            ['FG-0002',   'PCB Assembly X72',             'finished_good', 'make', 284.00,220.00, 'EA'],
        ];

        foreach ($parts as $p) {
            DB::table('parts')->insertOrIgnore([
                'part_number'    => $p[0],
                'description'    => $p[1],
                'type'           => $p[2],
                'make_buy'       => $p[3],
                'standard_cost'  => $p[4],
                'unit_cost'      => $p[4],
                'average_cost'   => $p[4],
                'last_cost'      => $p[5],
                'unit_price'     => $p[4] * 1.35,
                'unit_of_measure'=> $p[6],
                'is_active'      => true,
                'is_purchaseable'=> $p[3] === 'buy',
                'is_saleable'    => $p[2] === 'finished_good',
                'is_manufactured'=> $p[3] === 'make',
                'lead_time_days' => rand(5, 30),
                'reorder_point'  => rand(10, 100),
                'created_at'     => now(),
                'updated_at'     => now(),
            ]);
        }

        $this->command->info('✅ ERP Database seeded successfully!');
        $this->command->info('');
        $this->command->info('Demo Accounts:');
        $this->command->info('  admin@erp.com   / password (Admin)');
        $this->command->info('  sales@erp.com   / password (Sales)');
        $this->command->info('  prod@erp.com    / password (Production)');
        $this->command->info('  finance@erp.com / password (Finance)');
    }
}
