<?php

use App\Modules\Access\Role\Role;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->unsignedBigInteger('role_id')->nullable()->after('role');
            $table->foreign('role_id', 'fk_users_role')
                ->references('id')->on('roles')->onDelete('restrict');
        });

        DB::table('users')->select('id', 'role')->whereNotNull('role')->orderBy('id')->get()->each(function ($user) {
            $roleId = Role::where('name', $user->role)->value('id');
            if ($roleId) {
                DB::table('users')->where('id', $user->id)->update(['role_id' => $roleId]);
            }
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('role');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->enum('role', ['admin', 'user'])->default('user')->after('role_id');
        });

        DB::table('users')->select('id', 'role_id')->whereNotNull('role_id')->orderBy('id')->get()->each(function ($user) {
            $roleName = Role::where('id', $user->role_id)->value('name');
            if ($roleName) {
                DB::table('users')->where('id', $user->id)->update(['role' => $roleName]);
            }
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign('fk_users_role');
            $table->dropColumn('role_id');
        });
    }
};
