<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAnalysisErrorsTable extends Migration {
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('analysisErrors', function (Blueprint $table) {
			$table->increments('error_id');
			$table->string('analysis_id');
			$table->foreign('analysis_id')->references('analysis_id')->on('analysis');
			$table->string('error_string',1000);
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
		Schema::drop('analysisErrors');
	}

}
