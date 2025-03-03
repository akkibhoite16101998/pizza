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
        Schema::create('fund_expenses', function (Blueprint $table) {
            
            $table->id(); 
            $table->unsignedBigInteger('u_id'); 
            $table->decimal('amount', 10, 2);
            $table->enum('mode', ['Cash', 'UPI', 'Bank Transfer'])->default('UPI');
            $table->date('date'); 
            $table->text('reason')->nullable();
            $table->string('expense_image')->nullable();
            $table->timestamp('created_at')->useCurrent();
            // Foreign Key Constraint
            $table->foreign('u_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fund_expenses');
    }
};
