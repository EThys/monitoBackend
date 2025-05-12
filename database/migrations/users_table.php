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
        Schema::create('TUsers', function (Blueprint $table) {
            $table->bigIncrements('UserId');
            $table->unsignedBigInteger('AgencyId');
            $table->string('UserName');
            $table->string('UserEmail')->unique();
            $table->string('UserPassword');
            $table->enum('Role', ['admin', 'manager'])->default('manager');
        });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('UserEmail')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('TUsers');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};
