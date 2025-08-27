<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */// BORRAR EN CASCADA
    // public function up(): void
    // {
    //     Schema::table('users', function (Blueprint $table) {
    //         $table->foreignId('country_id')->nullable()->constrained()->onUpdate('cascade')->onDelete('cascade');
    //         $table->foreignId('state_id')->nullable()->constrained()->onUpdate('cascade')->onDelete('cascade');
    //         $table->foreignId('city_id')->nullable()->constrained()->onUpdate('cascade')->onDelete('cascade');
    //         $table->string('address')->nullable();
    //         $table->string('postal_code')->nullable();

    //     });
    // }

  public function up(): void
{
    Schema::table('users', function (Blueprint $table) {
        $table->dropForeign(['country_id']);
        $table->dropForeign(['state_id']);
        $table->dropForeign(['city_id']);
    });

    Schema::table('users', function (Blueprint $table) {
        $table->foreign('country_id')
            ->references('id')->on('countries')
            ->onUpdate('cascade')
            ->onDelete('restrict');

        $table->foreign('state_id')
            ->references('id')->on('states')
            ->onUpdate('cascade')
            ->onDelete('restrict');

        $table->foreign('city_id')
            ->references('id')->on('cities')
            ->onUpdate('cascade')
            ->onDelete('set null');
    });
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