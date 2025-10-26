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
        Schema::create('archive_comments', function (Blueprint $table) {
            $table->ulid()->index()->primary()->unique();
            $table->string('ticket_id')->nullable();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->text('comment')->nullable();
            $table->string('attachments')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('archive_comments');
    }
};
