<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        Schema::disableForeignKeyConstraints();

        // 1) Crear tabla nueva con las FKs corregidas
        Schema::create('users_new', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email');
            $table->dateTime('email_verified_at')->nullable();
            $table->string('password');
            $table->string('remember_token')->nullable();
            $table->timestamps();

            $table->unsignedInteger('country_id')->nullable();
            $table->unsignedInteger('state_id')->nullable();
            $table->unsignedInteger('city_id')->nullable();

            $table->string('address')->nullable();
            $table->string('postal_code')->nullable();

            // 👇 Reglas de borrado nuevas
            $table->foreign('country_id')->references('id')->on('countries')
                ->onUpdate('cascade')->onDelete('restrict');

            $table->foreign('state_id')->references('id')->on('states')
                ->onUpdate('cascade')->onDelete('restrict');

            $table->foreign('city_id')->references('id')->on('cities')
                ->onUpdate('cascade')->onDelete('set null');
        });

        // 2) Copiar datos de la tabla vieja
        DB::statement("
            INSERT INTO users_new
            (id, name, email, email_verified_at, password, remember_token, created_at, updated_at,
             country_id, state_id, city_id, address, postal_code)
            SELECT
            id, name, email, email_verified_at, password, remember_token, created_at, updated_at,
            country_id, state_id, city_id, address, postal_code
            FROM users
        ");

        // 3) Reemplazar tabla
        Schema::drop('users');
        Schema::rename('users_new', 'users');

        Schema::enableForeignKeyConstraints();
    }

 public function down(): void
{
    Schema::table('users', function (Blueprint $table) {
        $table->dropForeign(['country_id']);
        $table->dropForeign(['state_id']);
        $table->dropForeign(['city_id']);
    });

    Schema::table('users', function (Blueprint $table) {
        $table->foreign('country_id')->references('id')->on('countries')->cascadeOnUpdate()->cascadeOnDelete();
        $table->foreign('state_id')->references('id')->on('states')->cascadeOnUpdate()->cascadeOnDelete();
        $table->foreign('city_id')->references('id')->on('cities')->cascadeOnUpdate()->cascadeOnDelete();
    });
}
};
