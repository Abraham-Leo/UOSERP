<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // Summary stats — replace with real DB queries once migrated
        $stats = [
            'open_orders'  => $this->safeCount('orders', ['status' => ['new', 'in_progress', 'shipped']]),
            'work_orders'  => $this->safeCount('work_orders', ['status' => ['open', 'released', 'in_progress']]),
            'shortages'    => 12, // from MRP calculation
            'ar_balance'   => $this->safeSum('invoices', 'balance_due', ['status' => ['sent', 'overdue']]),
            'inv_value'    => $this->safeSum('inventory', 'qty_on_hand'), // simplified
        ];

        $recentOrders  = $this->getRecentOrders();

        return view('dashboard', compact('stats', 'recentOrders'));
    }

    public function stats()
    {
        return response()->json([
            'open_orders'  => 184,
            'work_orders'  => 47,
            'revenue_mtd'  => 847200,
            'ar_balance'   => 124000,
        ]);
    }

    // ===== Helpers (safe because tables might not exist yet) =====
    private function safeCount(string $table, array $whereIn = []): int
    {
        try {
            $q = DB::table($table);
            foreach ($whereIn as $col => $vals) {
                $q->whereIn($col, $vals);
            }
            return (int) $q->count();
        } catch (\Exception $e) {
            return 0;
        }
    }

    private function safeSum(string $table, string $col, array $whereIn = []): float
    {
        try {
            $q = DB::table($table);
            foreach ($whereIn as $c => $vals) {
                $q->whereIn($c, $vals);
            }
            return (float) $q->sum($col);
        } catch (\Exception $e) {
            return 0;
        }
    }

    private function getRecentOrders(): array
    {
        try {
            return DB::table('orders')
                ->join('customers', 'orders.customer_id', '=', 'customers.id')
                ->select('orders.*', 'customers.name as customer_name')
                ->orderByDesc('orders.created_at')
                ->limit(5)
                ->get()
                ->toArray();
        } catch (\Exception $e) {
            return [];
        }
    }
}
