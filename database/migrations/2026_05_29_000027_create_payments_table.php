<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained('orders');
            $table->foreignId('payment_account_id')->constrained('payment_accounts');
            $table->foreignId('approved_by')->nullable()->constrained('admins');
            $table->foreignId('rejected_by')->nullable()->constrained('admins');
            $table->foreignId('proof_path')->nullable()->constrained('file_storages');
            $table->decimal('amount', 15, 2);
            $table->tinyInteger('status')->comment('1 = pending, 2 = paid, 3 = failed, 4 = expired');
            $table->text('rejected_reason')->nullable();
            $table->datetime('approved_at')->nullable();
            $table->datetime('rejected_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
