<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Adds a nullable, unique `username` column to `users`.
 *
 * Email is no longer required when registering users (many customers in
 * remote areas do not have one). Every user instead gets an auto-generated
 * unique username so they can still sign in through the "Username or Email"
 * field on the login page.
 */
class AddUsernameToUsersTable extends Migration
{
    public function up()
    {
        if (! Schema::hasTable('users')) {
            return;
        }

        Schema::table('users', function (Blueprint $t) {
            if (! Schema::hasColumn('users', 'username')) {
                $t->string('username', 100)->nullable()->after('email');
            }
        });

        // MySQL allows multiple NULLs in a unique index, so users without a
        // username yet do not violate it.
        $indexExists = collect(
            DB::select("SHOW INDEX FROM `users` WHERE Key_name = 'users_username_unique'")
        )->isNotEmpty();

        if (! $indexExists) {
            Schema::table('users', function (Blueprint $t) {
                $t->unique('username', 'users_username_unique');
            });
        }

        // Backfill a username for existing users so all of them can sign in.
        $users = DB::table('users')
            ->where(function ($q) {
                $q->whereNull('username')->orWhere('username', '');
            })
            ->orderBy('id')
            ->get();

        foreach ($users as $user) {
            DB::table('users')->where('id', $user->id)->update([
                'username' => generateUniqueUsername($user->name, $user->lastname, $user->email),
            ]);
        }
    }

    public function down()
    {
        if (! Schema::hasTable('users') || ! Schema::hasColumn('users', 'username')) {
            return;
        }

        Schema::table('users', function (Blueprint $t) {
            $t->dropUnique('users_username_unique');
            $t->dropColumn('username');
        });
    }
}
