<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('archive_tickets_aggregated', function (Blueprint $table) {
            $table->string('ulid')->index()->primary()->unique();
            $table->string('priority_ulid')->nullable();
            $table->string('unit_ulid')->nullable();
            $table->unsignedBigInteger('owner_id')->nullable();
            $table->string('problem_category_ulid')->nullable();
            $table->string('company_ulid')->nullable();
            $table->string('title');
            $table->text('description');
            $table->string('ticket_status_ulid')->nullable();
            $table->unsignedBigInteger('responsible_id')->nullable();
            $table->timestamp('deadline_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('archive_tickets_aggregated');
    }
};
