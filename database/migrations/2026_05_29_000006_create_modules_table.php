<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('modules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('module_group_id')->nullable()->constrained('module_groups')->cascadeOnDelete();
            $table->string('name', 100);
            $table->string('route', 100);
            $table->integer('order');
            $table->tinyInteger('is_shown')->comment('0 = hidden, 1 = shown');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('modules');
    }
};
