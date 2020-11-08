<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersDataTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->text('fb_id')->comment('Facebook 綁定ID | Facebook Bind ID')->nullable();
            $table->text('fb_email')->comment('Facebook 信箱 | Facebook Email')->nullable();
            $table->text('google_id')->comment('Google 綁定ID | Google Bind ID')->nullable();
            $table->text('google_email')->comment('Google 信箱 | Google Email')->nullable();
            $table->text('current_role')->comment('目前角色 | Current Role')->nullable();
            $table->text('registered_at')->comment('註冊時間 | Register Time')->nullable();
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
}
