<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
          $table->increments('id');
          $table->string('name')->nullable()->default(' ');
          $table->string('surnames')->nullable()->default(' ');
          $table->string('email')->unique();
          $table->string('avatar')->nullable();
          $table->string('celular')->nullable()->default(' ');
          $table->timestamp('email_verified_at')->nullable();
          $table->string('password');
          $table->string('phone')->nullable()->default('3115000000');
          $table->string('token')->unique();
          $table->integer('status')->nullable()->default(1);
          $table->rememberToken();
          $table->timestamps();
        });

        DB::unprepared('
            CREATE TRIGGER Generador_token_usuarios BEFORE INSERT ON `users`
              FOR EACH ROW SET NEW.token = CONCAT ("user_", MD5(RAND()))');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
