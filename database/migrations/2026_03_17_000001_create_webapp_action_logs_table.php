<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('webapp_action_logs', function (Blueprint $table) {
            $table->id();
            $table->string('subject_type');           // 'property' | 'lead' | 'deal'
            $table->unsignedBigInteger('subject_id');
            $table->string('subject_title')->nullable(); // snapshot tên BĐS/Lead tại thời điểm log
            $table->unsignedBigInteger('actor_id')->nullable();
            $table->foreign('actor_id')->references('id')->on('customers')->nullOnDelete();
            $table->string('action');                 // 'call' | 'share' | 'edit' | 'view' | 'create' | 'delete'
            $table->json('metadata')->nullable();     // dữ liệu bổ sung tùy action
            $table->timestamps();

            $table->index(['subject_type', 'subject_id']);
            $table->index(['actor_id', 'created_at']);
            $table->index('action');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('webapp_action_logs');
    }
};
