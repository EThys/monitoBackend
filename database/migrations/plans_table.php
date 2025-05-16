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
        Schema::create('TPlans', function (Blueprint $table) {

            $table->bigIncrements('PlanId');
            $table->string('PlanName');
            $table->text('PlanDescription')->nullable();
            $table->decimal('PlanPrice', 10, 2);
            $table->integer('PlanTotal');
            $table->string(column: 'PlanSpeed');
            $table->boolean('PlanStatus')->default(true);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('TPlans');
    }
};
