<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::table('purchases', function (Blueprint $table) {
        $table->unsignedBigInteger('size_id')->nullable()->after('price');

        // Optional: Add foreign key constraint if a sizes table exists
        // $table->foreign('size_id')->references('id')->on('sizes')->onDelete('set null');
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('purchases', function (Blueprint $table) {
            //
        });
    }
};
