<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'country_id')) {
                $table->unsignedBigInteger('country_id')->nullable()->index()->after('password');

                // Si en PRODUCCIÓN usas MySQL/Postgres y quieres FK:
                // if (Schema::getConnection()->getDriverName() !== 'sqlite') {
                //     $table->foreign('country_id')->references('id')->on('countries')->nullOnDelete();
                // }
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // $table->dropForeign(['country_id']); // si agregaste FK en prod
            if (Schema::hasColumn('users', 'country_id')) {
                $table->dropColumn('country_id');
            }
        });
    }
};
