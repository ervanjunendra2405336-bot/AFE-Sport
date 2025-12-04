<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->string('booking_code', 50)->nullable()->after('id');
            $table->string('no_hp', 20)->nullable()->after('email');
            $table->foreignId('user_id')->nullable()->after('id')->constrained('users')->onDelete('set null');
        });

        // Generate booking codes for existing records
        DB::statement("UPDATE bookings SET booking_code = 'TAPEM-' || strftime('%Y%m%d', created_at) || '-' || substr(hex(randomblob(2)), 1, 4) WHERE booking_code IS NULL");
        DB::statement("UPDATE bookings SET no_hp = telepon WHERE no_hp IS NULL");

        // Make booking_code unique after populating
        Schema::table('bookings', function (Blueprint $table) {
            $table->string('booking_code', 50)->unique()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropColumn(['booking_code', 'no_hp']);
            $table->dropForeign(['user_id']);
            $table->dropColumn('user_id');
        });
    }
};
