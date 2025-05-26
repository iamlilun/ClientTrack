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
        Schema::create('clients', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade')->comment('所屬用戶 ID');
            $table->string('name')->comment('客戶名稱');
            $table->string('email')->nullable()->comment('客戶電子郵件');
            $table->string('phone')->nullable()->comment('客戶電話');
            $table->text('notes')->nullable()->comment('客戶備註');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clients');
    }
};
