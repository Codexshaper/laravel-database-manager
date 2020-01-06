<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDBMFieldsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dbm_fields', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('dbm_object_id')->unsigned();
            $table->string('name');
            $table->string('display_name');
            $table->string('type')->nullable();
            // $table->boolean('required')->default(false);
            $table->boolean('create')->default(true);
            $table->boolean('read')->default(true);
            $table->boolean('edit')->default(true);
            $table->boolean('delete')->default(true);
            $table->integer('order')->unsigned();
            $table->string('function_name')->nullable();
            $table->text('settings')->nullable();
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
        Schema::dropIfExists('dbm_fields');
    }
}
