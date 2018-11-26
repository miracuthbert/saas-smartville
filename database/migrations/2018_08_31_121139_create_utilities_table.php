<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUtilitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('utilities', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('company_id')->unsigned();
            $table->string('name', 250);
            $table->string('slug')->unique();
            $table->string('currency');
            $table->integer('price')->default(0);
            $table->text('details')->nullable();
            $table->string('billing_interval')->default('monthly');
            $table->integer('billing_duration')->default(1);
            $table->integer('billing_day')->default(1);
            $table->integer('billing_due')->default(7);
            $table->enum('billing_type', ['fixed', 'varied'])->default('fixed');
            $table->string('billing_unit')->nullable();
            $table->boolean('usable')->default(false);
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('utilities');
    }
}
