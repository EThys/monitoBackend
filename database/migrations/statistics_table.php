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
        Schema::create('TStatistics', function (Blueprint $table) {

            $table->bigIncrements('StatisticId');
            $table->unsignedBigInteger('AgencyId');
            $table->unsignedBigInteger('PlanId');
            $table->decimal('VolumeConsumed', 10, 2); // en Mo ou Go
            $table->decimal('AmountSpent', 10, 2); // montant dépensé
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('TStatistics');
    }
};
