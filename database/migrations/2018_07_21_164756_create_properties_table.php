<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePropertiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('properties', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('company_id')->unsigned()->index();
            $table->integer('category_id')->unsigned()->index()->nullable();
            $table->string('name', 250);
            $table->text('address')->nullable();
            $table->string('slug')->unique();
            $table->string('image')->nullable();
            $table->string('overview_short');
            $table->text('overview');
            $table->integer('price');
            $table->boolean('finished')->default(false);
            $table->boolean('live')->default(false);
            $table->timestamp('edited_at')->nullable();
            $table->timestamp('occupied_at')->nullable();
            $table->softDeletes();
            $table->timestamps();
            
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');
            $table->foreign('category_id')->references('id')->on('categories')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('properties');
    }
}
