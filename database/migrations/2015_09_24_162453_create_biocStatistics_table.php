<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBiocStatisticsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('biocStatistics', function (Blueprint $table) {
            $table->string('month',10);
            $table->string('year',10);
            $table->bigInteger('number_of_unique_ip');
            $table->bigInteger('number_of_downloads');
            $table->primary(['month','year']);
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
        Schema::drop('biocStatistics');
    }
}
