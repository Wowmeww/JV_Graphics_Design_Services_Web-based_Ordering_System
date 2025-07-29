<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('product_option_product_option_image', function (Blueprint $table) {
            $table->id();

            $table->foreignId('product_option_id')
                ->constrained()
                ->onDelete('cascade')
                ->name('ppo_option_fk'); // 👈 custom short name

            $table->foreignId('product_option_image_id')
                ->constrained()
                ->onDelete('cascade')
                ->name('ppo_image_fk'); // 👈 custom short name

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_option_product_option_image');
    }
};
