<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('referrals', function (Blueprint $table): void {
            $table->string('service_type', 50)->nullable()->after('service_id')->index();
            $table->string('service_title')->nullable()->after('service_type');
        });
    }

    public function down(): void
    {
        Schema::table('referrals', function (Blueprint $table): void {
            $table->dropIndex(['service_type']);
            $table->dropColumn(['service_type', 'service_title']);
        });
    }
};
