<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;

class StockReportService
{
    /**
     * Create a new class instance.
     */
    public function regenerateCache(): bool
    {
        try {
            // ❗ Run TRUNCATE outside the transaction
            DB::statement("TRUNCATE TABLE cached_variation_sales");

            // ✅ Begin transaction for the insert only
            DB::beginTransaction();

            DB::statement("
                INSERT INTO cached_variation_sales (variation_id, total_sold, total_transfered, total_adjusted, updated_at)
                SELECT
                    variations.id,
                    COALESCE(SUM(CASE WHEN t.type = 'sell' THEN tsl.quantity - tsl.quantity_returned ELSE 0 END), 0) AS total_sold,
                    COALESCE(SUM(CASE WHEN t.type = 'sell_transfer' THEN tsl.quantity ELSE 0 END), 0) AS total_transfered,
                    COALESCE(SUM(CASE WHEN t.type = 'stock_adjustment' THEN sal.quantity ELSE 0 END), 0) AS total_adjusted,
                    NOW()
                FROM variations
                LEFT JOIN transaction_sell_lines tsl ON tsl.variation_id = variations.id
                LEFT JOIN stock_adjustment_lines sal ON sal.variation_id = variations.id
                LEFT JOIN transactions t ON t.id = tsl.transaction_id OR t.id = sal.transaction_id
                WHERE t.status = 'final'
                GROUP BY variations.id
            ");
            DB::commit();
            return true;
        } catch (\Throwable $e) {
            if (DB::transactionLevel() > 0) {
                DB::rollBack();
            }
            report($e); // Optional: log the error for debugging
            return false;
        }
    }
}
