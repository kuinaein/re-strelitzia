<?php

declare(strict_types=1);

namespace App\Domain\Journal\Model;

final class AccountingJournalModel extends \Eloquent
{
    protected $table = 'accounting_journal';
    protected $guarded = ['id', \Eloquent::UPDATED_AT, \Eloquent::CREATED_AT];
    protected $dates = ['journal_date', \Eloquent::UPDATED_AT, \Eloquent::CREATED_AT];
}
