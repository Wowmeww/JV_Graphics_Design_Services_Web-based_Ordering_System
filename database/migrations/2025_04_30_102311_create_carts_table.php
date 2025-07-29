<?php

use App\Models\Cart;
use App\Models\Product;
use App\Models\ProductOption;
use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('carts', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(User::class)->constrained()->cascadeOnDelete();
            $table->timestamps();
        });

        Schema::create('cart_product', function (Blueprint $table) {
            $table->id();
            $table->enum('type', ['custom', 'normal'])->default('normal');

            $table->unsignedBigInteger('cart_id');
            $table->foreign('cart_id')->references('id')->on('carts')->onDelete('cascade');

            $table->unsignedBigInteger('product_id');
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');

            $table->unsignedBigInteger('option_id')->nullable();
            $table->foreign('option_id')->references('id')->on('product_options')->onDelete('cascade');

            $table->integer('quantity')->default(1);
            $table->integer('total_amount');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cart_product');
        Schema::dropIfExists('carts');
    }
};
