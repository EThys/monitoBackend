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
            $table->string('AgencyAddress')->nullable();
            $table->string('AgencyPhone')->nullable();
            $table->string('AgencyCity');
            $table->string('AgencyRegion');
            $table->unsignedBigInteger('PlanId');
            $table->enum('AgencyStatus', ['active', 'inactive', 'pending'])->default('active');
            $table->date('AgencyStartDate');
            $table->date('AgencyEndDate');
            $table->integer('AgencyUsed')->nullable();
            $table->integer('AgencyDuration')->nullable();
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
