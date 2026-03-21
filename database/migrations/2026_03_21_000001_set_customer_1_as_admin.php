<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('customers')->where('id', 1)->update([
            'role'     => 'admin',
            'isActive' => 1,
        ]);
    }

    public function down(): void
    {
        DB::table('customers')->where('id', 1)->update([
            'role' => 'customer',
        ]);
    }
};
