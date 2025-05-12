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
        Schema::create('TSubscriptions', function (Blueprint $table) {
            $table->bigIncrements('SubscriptionId');
            $table->unsignedBigInteger('AgencyId');
            $table->unsignedBigInteger('PlanId');
            $table->date('StartDate');
            $table->date('EndDate');
            $table->enum('Status', ['active', 'expired', 'canceled'])->default('active');
            $table->json('PaymentDetails')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('TSubscriptions');
    }
};
