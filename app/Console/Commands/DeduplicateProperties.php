<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class DeduplicateProperties extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'properties:deduplicate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Remove duplicate properties based on title and host_id';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info("Starting Deduplication...");

        $duplicatesQuery = \App\Models\Property::select('title', 'host_id', \Illuminate\Support\Facades\DB::raw('COUNT(*) as count'))
            ->whereNotNull('title')
            ->groupBy('title', 'host_id')
            ->having('count', '>', 1)
            ->get();

        $totalDeleted = 0;

        foreach ($duplicatesQuery as $duplicate) {
            $query = \App\Models\Property::where('title', $duplicate->title);

            if (is_null($duplicate->host_id)) {
                $query->whereNull('host_id');
            }
            else {
                $query->where('host_id', $duplicate->host_id);
            }

            $properties = $query->orderBy('updated_at', 'desc')->get();

            if ($properties->count() <= 1)
                continue;

            $keepId = $properties->first()->id;
            $this->info("Found {$properties->count()} duplicates for: '{$duplicate->title}'. Keeping ID: {$keepId}");

            $deleteIds = $properties->slice(1)->pluck('id')->toArray();

            \Illuminate\Support\Facades\DB::beginTransaction();
            try {
                \App\Models\PropertyImages::whereIn('propertys_id', $deleteIds)->delete();
                \App\Models\PropertyLegalImage::whereIn('propertys_id', $deleteIds)->delete();
                \App\Models\AssignParameters::whereIn('property_id', $deleteIds)->delete();
                \App\Models\AssignedOutdoorFacilities::whereIn('property_id', $deleteIds)->delete();
                \App\Models\Favourite::whereIn('property_id', $deleteIds)->delete();
                \App\Models\PropertysInquiry::whereIn('propertys_id', $deleteIds)->delete();

                $deletedCount = \App\Models\Property::whereIn('id', $deleteIds)->delete();
                $totalDeleted += $deletedCount;

                \Illuminate\Support\Facades\DB::commit();
                $this->line("--> Deleted {$deletedCount} old records.");
            }
            catch (\Exception $e) {
                \Illuminate\Support\Facades\DB::rollBack();
                $this->error("Error deleting duplicates for '{$duplicate->title}': " . $e->getMessage());
            }
        }

        $this->info("Deduplication completed! Total deleted: {$totalDeleted}");
        $this->info("Total Properties remaining: " . \App\Models\Property::count());
    }
}