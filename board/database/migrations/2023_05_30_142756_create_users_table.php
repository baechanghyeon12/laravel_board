<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('email')->unique();
            $table->string('password'); //60자 이상으로 설정해줘야 된다.
            $table->string('name');
            $table->timestamp('email_verified_at')->nullable(); // 이메일 인증
            $table->rememberToken(); // 로그인상태 유지 기능
            $table->timestamps(); // create_date, update_date를 자동으로 설정
            $table->softDeletes(); // deleted_date를 설정
        });
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
};
