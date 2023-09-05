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
        Schema::create('tenant_statements', function (Blueprint $table) {
            $table->id();
            $table->string('code')->nullable();
            $table->morphs('statementable');
            $table->foreignId('tenant_id');
            $table->decimal('amount');
            $table->decimal('balance');
            $table->boolean('action')->default(true);
            $table->string('description')->default('invoice');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tenant_statements');
    }
};
