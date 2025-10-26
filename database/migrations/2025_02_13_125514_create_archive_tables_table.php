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
        Schema::create('archive_tables', function (Blueprint $table) {
            $table->ulid()->primary()->index()->unique();
            $table->string('table_original')->index()->comment(' Название таблицы от которой создавался архив');
            $table->string('table_name')->index()->comment('Название архивной таблицы');
            $table->string('start_at')->index()->comment('Дата начала архивации');
            $table->string('end_at')->index()->comment('Дата окончания архивации');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('archive_tables');
    }
};
