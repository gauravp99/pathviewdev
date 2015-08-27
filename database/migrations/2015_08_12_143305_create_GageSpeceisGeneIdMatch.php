<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGageSpeceisGeneIdMatch extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('GageSpeceisGeneIdMatch', function (Blueprint $table) {
			$table->string('species_id')->index();
			$table->string('geneid')->index();
			$table->timestamp('created_at');
			$table->timestamp('updated_at');
			$table->foreign('species_id')->references('species_id')->on('Species');
			$table->foreign('geneid')->references('geneid')->on('gene');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('GageSpeceisGeneIdMatch');
	}


}
