<?php

declare(strict_types=1);

namespace App\Domain\Journal\Service;

use App\Domain\Journal\Dao\AccountingJournalDao;
use DB;

class TrialBalanceBuildService
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
     * @param \App\Domain\Account\Dto\AccountTitleType[] $accountTypes
     * @return array<int, int>
     */
    public function build(array $accountTypes): array
    {
        return DB::transaction(function () use ($accountTypes) {
            return $this->journalDao->buildTrialBalance($accountTypes);
        });
    }
}
