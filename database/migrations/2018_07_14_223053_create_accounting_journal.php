<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateAccountingJournal extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        \Schema::create('accounting_journal', function (Blueprint $table): void {
            $table->increments('id');
            $table->date('journal_date');
            $table->integer('debit_account_id');
            $table->foreign('debit_account_id')->references('id')->on('account_title');
            $table->index('debit_account_id');
            $table->integer('credit_account_id');
            $table->foreign('credit_account_id')->references('id')->on('account_title');
            $table->index('credit_account_id');
            $table->string('remarks')->default('');
            $table->integer('amount');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        \Schema::dropIfExists('accounting_journal');
    }
}
