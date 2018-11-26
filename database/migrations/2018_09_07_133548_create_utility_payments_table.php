<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUtilityPaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('utility_payments', function (Blueprint $table) {
            $table->increments('id');
            $table->hashid();
            $table->integer('utility_id')->unsigned()->index();
            $table->integer('property_id')->unsigned()->index();
            $table->integer('lease_id')->unsigned()->index();
            $table->string('invoice_id')->index();
            $table->integer('admin_id')->unsigned()->index()->nullable();
            $table->integer('amount');
            $table->text('description')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('utility_id')->references('id')->on('utilities')->onDelete('cascade');
            $table->foreign('property_id')->references('id')->on('properties')->onDelete('cascade');
            $table->foreign('lease_id')->references('id')->on('leases')->onDelete('cascade');
            $table->foreign('invoice_id')->references('hash_id')->on('utility_invoices')->onDelete('cascade');
            $table->foreign('admin_id')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('utility_payments');
    }
}
