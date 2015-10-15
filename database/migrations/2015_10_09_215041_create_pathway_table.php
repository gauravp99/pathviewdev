<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePathwaysTable extends Migration {
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('pathway', function (Blueprint $table) {
			$table->string('pathway_id',10);
			$table->string('pathway_desc',100);
			$table->timestamps();
			$table->primary('pathway_id');

		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('pathway');
	}
}
