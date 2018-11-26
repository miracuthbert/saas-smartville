<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLeaseInvoicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lease_invoices', function (Blueprint $table) {
            $table->increments('id');
            $table->hashid();
            $table->integer('property_id')->unsigned()->index();
            $table->integer('lease_id')->unsigned()->index();
            $table->integer('user_id')->unsigned()->index()->nullable();
            $table->string('currency');
            $table->integer('amount');
            $table->date('start_at');
            $table->date('end_at');
            $table->timestamp('sent_at')->nullable();
            $table->timestamp('due_at')->nullable();
            $table->timestamp('cleared_at')->nullable();
            $table->timestamps();

            $table->foreign('property_id')->references('id')->on('properties')->onDelete('cascade');
            $table->foreign('lease_id')->references('id')->on('leases')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('lease_invoices');
    }
}
