<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    protected $connection = 'landlord';

    public function up(): void
    {
        Schema::create('tenant_users', function (Blueprint $table) {
            $table->id();

            $table->foreignId('tenant_id')->constrained('tenants')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();

            $table->string('role')->default('member');

            $table->timestamps();

            // Sprečava duple dodele istog user-a istom tenant-u
            $table->unique(['tenant_id', 'user_id']);

            // Performanse za tipične upite
            $table->index('user_id');
            $table->index('tenant_id');

        });

        Schema::create('tenant_modules', function (Blueprint $table) {
            $table->id();

            $table->foreignId('tenant_id')->constrained('tenants')->cascadeOnDelete();
            $table->foreignId('module_id')->constrained('modules')->cascadeOnDelete();

            // Podržava vremensko ograničenje modula po tenant-u (opciono)
            $table->timestamp('expires_at')->nullable()->index();

            $table->timestamps();

            // Sprečava duple dodele istog modula istom tenant-u
            $table->unique(['tenant_id', 'module_id']);

            // Performanse
            $table->index('tenant_id');
            $table->index('module_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tenant_modules');
        Schema::dropIfExists('tenant_users');
    }
};
