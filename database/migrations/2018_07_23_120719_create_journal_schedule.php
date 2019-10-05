<?php

declare (strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateJournalSchedule extends Migration
{
    /**
     * Run the migrations.
     */
    public function up() : void
    {
        \Schema::create('journal_schedule', function (Blueprint $table) : void {
            $table->increments('id');
            $table->boolean('enabled');
            $table->integer('post_date');
            $table->string('remarks');
            $table->integer('debit_account_id');
            $table->foreign('debit_account_id')->references('id')->on('account_title');
            $table->index('debit_account_id');
            $table->integer('credit_account_id');
            $table->foreign('credit_account_id')->references('id')->on('account_title');
            $table->index('credit_account_id');
            $table->integer('amount');
            $table->date('next_post_date');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down() : void
    {
        \Schema::dropIfExists('journal_schedule');
    }
}
