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
        Schema::create('providers', function (Blueprint $table) {
            $table->id();
            
            $table->foreignId('provider_account_id')->constrained()->onDelete('cascade');

            $table->string('name');
            $table->text('address');
            $table->string('postal_code');
            $table->string('city');
    
            $table->string('phone')->nullable();
            
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');

            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('providers');
    }
};
