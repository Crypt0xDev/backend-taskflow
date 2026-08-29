<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('email')->nullable()->unique()->after('user_name');
            $table->date('birth_date')->nullable()->after('email');
        });

        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'age')) {
                $table->dropColumn('age');
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->unsignedTinyInteger('age')->nullable()->after('role');
            $table->dropUnique(['email']);
            $table->dropColumn(['email', 'birth_date']);
        });
    }
};
