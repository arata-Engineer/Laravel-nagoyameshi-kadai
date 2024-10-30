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
        Schema::table('users', function (Blueprint $table) {
            $table->string('kana')->nullable(); // kanaカラムを追加
            $table->string('postal_code')->nullable(); // postal_codeカラムを追加
            $table->string('address')->nullable(); // addressカラムを追加
            $table->string('phone_number')->nullable(); // phone_numberカラムを追加
            $table->date('birthday')->nullable(); // birthdayカラムを追加
            $table->string('occupation')->nullable(); // occupationカラムを追加
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['kana', 'postal_code', 'address', 'phone_number', 'birthday', 'occupation']); // 追加したカラムを削除
        });
    }
};
