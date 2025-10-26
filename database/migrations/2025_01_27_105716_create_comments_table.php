<?php

use App\Models\User;
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
        Schema::create('comments', function (Blueprint $table) {
            $table->ulid()->index()->primary()->unique();
            $table->foreignId('ticket_id')->comment('Внешний ключ на таблицу Заявки')->constrained('tickets', 'id');
            $table->foreignIdFor(User::class)->comment('Внешний ключ на таблицу Пользователей')->constrained();
            $table->text('comment')->comment('Комментарий');
            $table->string('attachments')->nullable()->comment('Вложения');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('comments');
    }
};
