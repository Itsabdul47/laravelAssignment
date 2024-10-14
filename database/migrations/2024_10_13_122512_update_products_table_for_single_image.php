<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateProductsTableForSingleImage extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('products', function (Blueprint $table) {
            // Add the 'image' column to store the path of a single image
            $table->string('image')->nullable()->after('price');
            
            // If you have an 'images' column (for multiple images), drop it
            if (Schema::hasColumn('products', 'images')) {
                $table->dropColumn('images');
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('products', function (Blueprint $table) {
            // Add 'images' column back if you need to revert the migration
            $table->json('images')->nullable(); // Assuming it was JSON for multiple images

            // Remove the 'image' column
            $table->dropColumn('image');
        });
    }
}

