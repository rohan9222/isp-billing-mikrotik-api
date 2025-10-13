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
        Schema::create('log_tables', function (Blueprint $table) {
            $table->id();
            $table->string('table_name'); // যেই টেবিলের জন্য লগ করা হচ্ছে তার নাম
            $table->string('action'); // কার্যকলাপের ধরন (যেমন আপডেট, ডিলিট)
            $table->unsignedBigInteger('record_id')->nullable(); // সংশ্লিষ্ট রেকর্ডের ID
            $table->json('old_data')->nullable(); // পূর্বের ডেটা
            $table->json('new_data')->nullable(); // নতুন ডেটা
            $table->unsignedBigInteger('user_id')->nullable(); // যিনি পরিবর্তন করেছেন তার ID
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('log_tables');
    }
};
