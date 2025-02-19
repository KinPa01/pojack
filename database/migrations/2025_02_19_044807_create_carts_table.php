<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCartsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Ensure the 'tables' table exists before creating the 'carts' table
        if (!Schema::hasTable('tables')) {
            Schema::create('tables', function (Blueprint $table) {
                $table->id();
                $table->integer('number')->unique();
                $table->enum('status', ['available', 'occupied']);
                $table->timestamps();
            });
        }

        // Ensure the 'foods' table exists before creating the 'carts' table
        if (!Schema::hasTable('foods')) {
            Schema::create('foods', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->decimal('price', 8, 2);
                $table->timestamps();
            });
        }

        // Check if the 'carts' table already exists
        if (!Schema::hasTable('carts')) {
            Schema::create('carts', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('table_id');
                $table->unsignedBigInteger('food_id');
                $table->integer('quantity');
                $table->decimal('price', 8, 2);
                $table->timestamps();

                $table->foreign('table_id')->references('id')->on('tables')->onDelete('cascade');
                $table->foreign('food_id')->references('id')->on('foods')->onDelete('cascade');
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('carts');
    }
}
