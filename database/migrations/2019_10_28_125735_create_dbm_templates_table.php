<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDBMTemplatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dbm_templates', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('old_name');
            $table->string('type');
            $table->integer('length')->nullable();
            $table->string('index')->nullable();
            $table->string('default')->nullable();
            $table->boolean('notnull')->default(false);
            $table->boolean('unsigned')->default(true);
            $table->boolean('auto_increment')->default(true);
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
        Schema::dropIfExists('dbm_templates');
    }
}
