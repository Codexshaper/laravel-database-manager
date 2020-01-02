<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDBMCollectionFieldsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dbm_collection_fields', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('dbm_collection_id');
            $table->string('name');
            $table->string('old_name');
            $table->string('type');
            $table->string('index')->nullable();
            $table->string('default')->nullable();
            $table->text('extra')->nullable();
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
        Schema::dropIfExists('dbm_collection_fields');
    }
}
