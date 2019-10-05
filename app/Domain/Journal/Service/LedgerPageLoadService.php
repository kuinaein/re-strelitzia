<?php

declare(strict_types=1);

namespace App\Domain\Journal\Service;

use App\Domain\Journal\Dao\AccountingJournalDao;
use Illuminate\Support\Carbon;

class LedgerPageLoadService
{
    /**
     * @var AccountingJournalDao
     */
    private $journalDao;

    public function __construct(AccountingJournalDao $journalDao)
    {
        $this->journalDao = $journalDao;
    }

    /**
     * @param int    $accountId
     * @param Carbon $startInclusive
     * @param Carbon $endExclusive
     * @return array { beginningBlance: int, journals: AccountingJournal[] }
     */
    public function load(int $accountId, Carbon $startInclusive, Carbon $endExclusive): array
    {
        return \DB::transaction(function () use ($accountId, $startInclusive, $endExclusive) {
            $journals = $this->journalDao->listByAccountIdAndPeriod($accountId, $startInclusive, $endExclusive);
            $beginningBalance = $this->journalDao->calcBalance($accountId, $startInclusive);
            return ['beginningBalance' => $beginningBalance, 'journals' => $journals];
        });
    }
}
