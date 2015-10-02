<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAnalysisTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('analysis', function (Blueprint $table) {
            $table->string('analysis_id');
            $table->string('id');
            $table->string('arguments',1000);
            $table->string('ip_add',20);
            $table->string('analysis_type');
            $table->string('analysis_origin',40);
            $table->primary('analysis_id');
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
        Schema::drop('analysis');
    }
}
