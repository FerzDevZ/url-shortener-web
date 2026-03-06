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
        Schema::table('links', function (Blueprint $table) {
            $table->string('fb_pixel_id')->nullable()->after('click_count');
            $table->string('gtm_id')->nullable()->after('fb_pixel_id');
            $table->string('qr_color')->default('#000000')->after('gtm_id');
            $table->string('qr_logo_path')->nullable()->after('qr_color');
            $table->foreignId('workspace_id')->nullable()->after('user_id')->constrained()->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('links', function (Blueprint $table) {
            $table->dropForeign(['workspace_id']);
            $table->dropColumn(['fb_pixel_id', 'gtm_id', 'qr_color', 'qr_logo_path', 'workspace_id']);
        });
    }
};
