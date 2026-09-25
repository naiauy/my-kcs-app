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
        Schema::table('product_options', function (Blueprint $table) {
            $table->decimal('price_modifier', 10, 2)->default(0.00)->after('option_value');
            $table->string('option_image')->nullable()->after('price_modifier');
        });
    }

    public function down(): void
    {
        Schema::table('product_options', function (Blueprint $table) {
            $table->dropColumn(['price_modifier', 'option_image']);
        });
    }
};
