<?php

use App\Models\Order;
use App\Models\Product;
use App\Models\ProductOption;
use App\Models\Rating;
use App\Models\User;
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
        // make the id values like 5d99059a, fc1d90aa, 5f140659
        Schema::create('orders', function (Blueprint $table) {
            $table->string('id', 8)->primary(); // Random 8-character string
            $table->foreignIdFor(User::class)->constrained()->cascadeOnDelete();
            $table->enum('status', ['pending', 'processing', 'completed', 'cancelled', 'received', 'rated'])->default('pending');
            $table->string('type')->default('normal');
            $table->integer('quantity')->default(12);
            $table->decimal('total_amount', 10, 2);
            $table->string('note')->nullable();
            $table->foreignIdFor(Product::class)->constrained()->cascadeOnDelete();
            $table->foreignIdFor(ProductOption::class, 'option_id')->nullable()->default(null)->constrained()->cascadeOnDelete();
            $table->foreignIdFor(Rating::class)->nullable();
            $table->timestamps();
        });
        // Schema::create('order_product', function (Blueprint $table) {
        //     $table->id();
        //     $table->foreignIdFor(Order::class)->constrained()->cascadeOnDelete();
        //     $table->foreignIdFor(Product::class)->constrained()->cascadeOnDelete();
        //     $table->foreignIdFor(ProductOption::class, 'option_id')->nullable()->default(null)->constrained()->cascadeOnDelete();
        //     $table->timestamps();
        // });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
        // Schema::dropIfExists('order_product');
    }
};
