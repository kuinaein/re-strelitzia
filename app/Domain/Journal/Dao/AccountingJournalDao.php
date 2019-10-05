<?php

declare(strict_types=1);

namespace App\Domain\Journal\Dao;

use App\Core\StreCase;
use App\Core\StreDao;
use App\Domain\Account\Dao\AccountTitleDao;
use App\Domain\Account\Dto\AccountTitle;
use App\Domain\Account\Dto\AccountTitleType;
use App\Domain\Journal\Dto\AccountingJournal;
use App\Domain\Journal\Model\AccountingJournalModel;
use DB;
use Eloquent;
use Exception;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

final class AccountingJournalDao extends StreDao
{
    protected static $dtoClass = AccountingJournal::class;

    private $accountDao;

    public function __construct(AccountingJournalModel $repo, AccountTitleDao $accountDao)
    {
        parent::__construct($repo);
        $this->accountDao = $accountDao;
    }

    /**
     * @param AccountingJournalModel $model
     * @return AccountingJournal
     */
    protected function convertModelToDto(Eloquent $model): StreCase
    {
        return new AccountingJournal(
            $model->id,
            $model->debit_account_id,
            $model->credit_account_id,
            $model->journal_date,
            $model->remarks,
            $model->amount,
            $model->created_at,
            $model->updated_at
        );
    }

    /**
     * @param AccountingJournal $dto
     * @return AccountingJournalModel
     */
    protected function convertDtoToModel(StreCase $dto): Eloquent
    {
        /** @var AccountingJournalModel */
        $model = is_null($dto->id) ? new AccountingJournalModel() : $this->repo()->findOrFail($dto->id);
        $model->debit_account_id = $dto->debitAccountId;
        $model->credit_account_id = $dto->creditAccountId;
        $model->journal_date = $dto->journalDate;
        $model->remarks = $dto->remarks;
        $model->amount = $dto->amount;
        return $model;
    }

    public function findOrFailByAccount(AccountTitle $debit, AccountTitle $credit): AccountingJournal
    {
        /** @var AccountingJournalModel */
        $model = $this->repo()->where(['debit_account_id' => $debit->id, 'credit_account_id' => $credit->id])
            ->firstOrFail();
        return $this->convertModelToDto($model);
    }

    /**
     * @param int    $accountId
     * @param Carbon $startInclusive
     * @param Carbon $endExclusive
     * @return Collection[AccountingJournal]
     */
    public function listByAccountIdAndPeriod(int $accountId, Carbon $startInclusive, Carbon $endExclusive): Collection
    {
        return $this->listOneSide($accountId, $startInclusive, $endExclusive, 'debit')
            ->concat($this->listOneSide($accountId, $startInclusive, $endExclusive, 'credit'))
            ->map(function ($m) {
                return $this->convertModelToDto($m);
            });
    }
    public function calcBalance(int $accountId, Carbon $date): int
    {
        $a = $this->accountDao->findOrFail($accountId);
        $debitSum = $this->sumOneSideForAccount($accountId, $date, 'debit');
        $creditSum = $this->sumOneSideForAccount($accountId, $date, 'credit');
        return $a->type->isDebitSide() ? $debitSum - $creditSum : $creditSum - $debitSum;
    }

    /**
     * @param \App\Domain\Account\Dto\AccountTitleType[] $accountTypes
     * @return array<int, int> accountId => 金額
     */
    public function buildTrialBalance(array $accountTypes): array
    {
        $debitSums = $this->sumOneSideForTypes($accountTypes, 'debit');
        $creditSums = $this->sumOneSideForTypes($accountTypes, 'credit');
        $result = [];
        foreach ($debitSums as $d) {
            $type = new AccountTitleType($d->type);
            $result[(int) $d->id] = (int) ($type->isDebitSide() ? +$d->amount : -$d->amount);
        }
        foreach ($creditSums as $c) {
            $type = new AccountTitleType($c->type);
            $diff = $type->isDebitSide() ? -$c->amount : +$c->amount;
            $result[(int) $c->id] = (int) (isset($result[$c->id]) ? $result[$c->id] + $diff : $diff);
        }
        return $result;
    }

    private function listOneSide(
        int $accountId,
        Carbon $startInclusive,
        Carbon $endExclusive,
        string $side
    ): Collection {
        return $this->repo()->where([$side . '_account_id' => $accountId])
            ->where('journal_date', '>=', $startInclusive)
            ->where('journal_date', '<', $endExclusive)
            ->get();
    }
    private function sumOneSideForAccount(int $accountId, Carbon $date, string $side): int
    {
        return $this->repo()->select(\DB::raw('sum(amount) as balance'))
            ->where([$side . '_account_id' => $accountId])
            ->where('journal_date', '<', $date)
            ->get()[0]->balance ?? 0;
    }

    private function sumOneSideForTypes(array $accountTypes, string $side): Collection
    {
        return $this->repo()->select([
            'account_title.type',
            'account_title.id',
            DB::raw('sum(accounting_journal.amount) as amount')
        ])
            ->join('account_title', 'accounting_journal.' . $side . '_account_id', '=', 'account_title.id')
            ->whereIn('account_title.type', $accountTypes)
            ->groupBy(['account_title.type', 'account_title.id'])
            ->get();
    }
}
