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
        Schema::create('tickets', function (Blueprint $table) {
            $table->id()->index()->primary();
            $table->string('street')->comment('Улица');
            $table->string('building')->comment('Строение');
            $table->string('flat')->comment('Квартира');
            $table->string('name')->comment('ФИО абонента');
            $table->string('ls')->index()->comment('Лицевой счет');
            $table->string('phone')->index()->comment('Номер телефона абонента');
            $table->text('description')->comment('Описание заявки');
            $table->string('office')->comment('Офис');
            $table->string('attachments')->comment('Вложения')->nullable();
            $table->foreignUlid('priority_ulid')->comment('Внешний ключ на таблицу Приоритет')->constrained('priorities', 'ulid');
            $table->foreignUlid('unit_ulid')->comment('Внешний ключ на таблицу Отделы')->constrained('units', 'ulid');
            $table->foreignIdFor(User::class, 'owner_id')->comment('Создатель заявки')->constrained('users');
            $table->foreignUlid('problem_category_ulid')->comment('Внешний ключ на таблицу Категории Проблем')->constrained('problem_categories', 'ulid');
            $table->foreignUlid('ticket_status_ulid')->comment('Внешний ключ на таблицу Статусы Заявок')->constrained('ticket_statuses', 'ulid');
            $table->foreignIdFor(User::class, 'responsible_id')->nullable()->comment('Ответственный')->constrained('users');
            $table->timestamps();
            //            $table->timestamp('approved_at')->nullable();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tickets');
    }
};
