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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name')->comment('Имя');
            $table->string('email')->unique()->index()->comment('Почта');
            $table->string('password')->nullable()->comment('Пароль');
            $table->string('phone')->comment('Номер телефона');
            $table->boolean('is_active')->default(true)->comment('Активность');
            $table->foreignUlid('unit_ulid')->nullable()->comment('Внешний ключ на таблицу Отдел')->constrained('units', 'ulid');
            $table->string('position')->comment('Должность');
            $table->foreignId('moonshine_user_role_id')->nullable()->constrained('moonshine_user_roles')->nullOnDelete();
            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};
