<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    public function up(): void
    {
        Schema::connection('landlord')->create('activity_logs', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('tenant_id')->nullable()->constrained('tenants')->nullOnDelete();

            $table->string('action', 120)->index();

            $table->string('subject_type', 200)->nullable()->index();
            $table->unsignedBigInteger('subject_id')->nullable()->index();

            $table->json('meta')->nullable();

            $table->timestamps();

            $table->index(['tenant_id', 'created_at']);
            $table->index(['user_id', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::connection('landlord')->dropIfExists('activity_logs');
    }
};
