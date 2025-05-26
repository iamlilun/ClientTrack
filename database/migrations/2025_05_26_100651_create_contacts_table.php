<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('contacts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')->constrained()->onDelete('cascade')->comment('客戶 ID');
            $table->string('contact_type')->comment('連絡方式'); // e.g., 'phone', 'email'
            $table->text('content')->comment('連絡內容');
            $table->timestamp('contacted_at')->default(DB::raw('CURRENT_TIMESTAMP'))->comment('聯絡時間');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contacts');
    }
};
