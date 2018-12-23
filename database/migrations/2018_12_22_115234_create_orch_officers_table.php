<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrchOfficersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orch_officers', function (Blueprint $table) {
            $table->increments('id');
            $table->string('email')->unique();
            $table->string('name');
            $table->string('surname');
            $table->enum('gender', ['male', 'female'])->default('male');
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('orchestra_name')->nullable(); // For Orchestra Officer Role Only
            $table->string('role_type')->default('officer');

            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('orch_officers');
    }
}
