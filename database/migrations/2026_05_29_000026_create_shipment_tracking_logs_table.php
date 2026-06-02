<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('shipment_tracking_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('shipment_id')->constrained('shipments');
            $table->foreignId('updated_by')->constrained('admins');
            $table->tinyInteger('status')->comment('1 = pending, 2 = picked up, 3 = in transit, 4 = out for delivery, 5 = delivered, 6 = failed');
            $table->text('note')->nullable();
            $table->string('location', 100)->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('shipment_tracking_logs');
    }
};
