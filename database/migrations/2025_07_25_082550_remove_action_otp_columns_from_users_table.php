<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['action_otp_code', 'action_otp_expires_at', 'action_otp_purpose', 'require_otp_for_actions']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('action_otp_code', 6)->nullable();
            $table->timestamp('action_otp_expires_at')->nullable();
            $table->string('action_otp_purpose')->nullable();
            $table->boolean('require_otp_for_actions')->default(true);
        });
    }
};
