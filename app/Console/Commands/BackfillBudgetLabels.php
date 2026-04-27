<?php

namespace App\Console\Commands;

use App\Models\CrmLead;
use Illuminate\Console\Command;

class BackfillBudgetLabels extends Command
{
    protected $signature = 'crm:backfill-budget-labels';
    protected $description = 'Backfill budget_label for existing CrmLead records';

    private array $ranges = [
        [0,            0,            'Thỏa thuận'],
        [0,            1000000000,   'Dưới 1 tỷ'],
        [1000000000,   3000000000,   '1 - 3 tỷ'],
        [3000000000,   5000000000,   '3 - 5 tỷ'],
        [5000000000,   10000000000,  '5 - 10 tỷ'],
        [10000000000,  20000000000,  '10 - 20 tỷ'],
        [20000000000,  50000000000,  '20 - 50 tỷ'],
        [50000000000,  999999999999, 'Trên 50 tỷ'],
    ];

    public function handle(): int
    {
        $leads = CrmLead::whereNull('budget_label')->orWhere('budget_label', '')->get();
        $this->info("Found {$leads->count()} leads without budget_label.");

        $updated = 0;
        foreach ($leads as $lead) {
            $min = (float) ($lead->demand_rate_min ?? 0);
            $max = (float) ($lead->demand_rate_max ?? 0);

            $label = $this->resolveLabel($min, $max);
            $lead->budget_label = $label;
            $lead->saveQuietly();
            $updated++;
        }

        $this->info("Updated {$updated} leads.");
        return 0;
    }

    private function resolveLabel(float $min, float $max): string
    {
        foreach ($this->ranges as [$rMin, $rMax, $rLabel]) {
            if ($min == $rMin && $max == $rMax) {
                return $rLabel;
            }
        }

        if ($min > 0 && $max > 0) return format_vnd($min) . ' – ' . format_vnd($max);
        if ($max > 0) return 'đến ' . format_vnd($max);
        if ($min > 0) return 'từ ' . format_vnd($min);
        return 'Thỏa thuận';
    }
}
