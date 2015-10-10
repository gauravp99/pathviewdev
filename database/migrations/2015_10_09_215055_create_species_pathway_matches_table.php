<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSpeciesPathwayMatchesTable extends Migration {
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('speciesPathwayMatch', function (Blueprint $table) {
			$table->increments('match_id');
			$table->string('species_id',10);
			$table->string('pathway_id',10);
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
		Schema::drop('speciesPathwayMatch');
	}

}
