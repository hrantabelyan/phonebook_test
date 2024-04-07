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
        Schema::create('phone_book_item_numbers', function (Blueprint $table) {
            $table->id();
            $table->string('number', 30);
            $table->unsignedBigInteger('phone_book_item_id');
            $table->dateTime('deleted_at')->nullable();
            $table->dateTime('created_at');
            $table->dateTime('updated_at');

            $table->foreign('phone_book_item_id')->references('id')->on('phone_book_items')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('phone_book_item_numbers');
    }
};
