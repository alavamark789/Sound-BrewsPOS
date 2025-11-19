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
        Schema::create('orders', function (Blueprint $table) {
            $table->id('OrderID');
            $table->foreignId('CustomerID')
                ->nullable()
                ->constrained('customers', 'CustomerID')
                ->nullOnDelete();
            $table->foreignId('EmployeeID')
                ->nullable()
                ->constrained('employees', 'EmployeeID')
                ->nullOnDelete();
            $table->dateTime('OrderDate')->useCurrent();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
