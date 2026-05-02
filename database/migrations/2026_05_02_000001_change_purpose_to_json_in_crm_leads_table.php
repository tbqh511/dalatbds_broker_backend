<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Convert existing string values to JSON arrays before changing column type
        DB::table('crm_leads')->whereNotNull('purpose')->get()->each(function ($lead) {
            $value = $lead->purpose;
            // If not already JSON array, wrap it
            if ($value && ! str_starts_with(trim($value), '[')) {
                DB::table('crm_leads')
                    ->where('id', $lead->id)
                    ->update(['purpose' => json_encode([$value])]);
            }
        });

        Schema::table('crm_leads', function (Blueprint $table) {
            $table->json('purpose')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('crm_leads', function (Blueprint $table) {
            $table->string('purpose', 255)->nullable()->change();
        });
    }
};
