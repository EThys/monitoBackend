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
        Schema::create('TAgencies', function (Blueprint $table) {
            $table->bigIncrements('AgencyId');
            $table->string(column: 'AgencyName');
            $table->string('AgencyAddress');
            $table->string('AgencyPhone');
            $table->unsignedBigInteger('PlanId');
            $table->enum('Status', ['active', 'inactive'])->default('active');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('TAgencies');
    }
};
