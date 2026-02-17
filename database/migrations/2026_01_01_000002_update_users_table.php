<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    protected $connection = 'landlord';

    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->enum('type', ['super_admin', 'client'])->default('client')->after('password');
            $table->foreignId('default_tenant_id')->nullable()->after('active');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['type', 'default_tenant_id']);
        });
    }
};
