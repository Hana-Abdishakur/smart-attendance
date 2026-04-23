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
    Schema::create('payments', function (Blueprint $table) {
        $table->id();
        $table->foreignId('user_id')->constrained()->onDelete('cascade');
        
        // Cadadka lacagta (USD mise UGX)
        $table->decimal('amount', 12, 2); // 12,2 waa ka fiican yahay 8,2 maadaama UGX ay tahay lacag weyn (sida 190,000)
        $table->string('currency')->default('USD'); // USD ama UGX

        // Xogta lacag bixinta
        $table->string('payment_method'); // mobile_money ama card
        $table->string('phone_number')->nullable(); // Nambarka lacagta laga soo diray
        $table->string('transaction_id')->nullable(); 
        
        $table->string('status')->default('completed'); // Maadaama aan hadda tijaabo ku jirno
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
