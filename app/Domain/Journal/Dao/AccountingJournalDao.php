<?php

declare(strict_types=1);

namespace App\Domain\Journal\Dao;

use App\Domain\Account\Dto\AccountTitle;
use App\Domain\Account\Dto\AccountTitleType;
use App\Domain\Journal\Dto\AccountingJournal;
use App\Domain\Journal\Model\AccountingJournalModel;
use DB;
use Exception;
use Illuminate\Support\Collection;

class AccountingJournalDao
{
    /**
     * @var AccountingJournalModel
     */
    private $repo;

    public function __construct(AccountingJournalModel $repo) //, AccountTitleDao $accountDao)
    {
        $this->repo = $repo;
        // $this->accountDao = $accountDao;
    }

    private function convertModelToDto(AccountingJournalModel $model): AccountingJournal
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

    private function convertDtoToModel(AccountingJournal $dto): AccountingJournalModel
    {
        $model = new AccountingJournalModel();
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
        $model = $this->repo->where(['debit_account_id' => $debit->id, 'credit_account_id' => $credit->id])
            ->firstOrFail();
        return $this->convertModelToDto($model);
    }

    public function createOrFail(AccountingJournal $dto)
    {
        $model = $this->convertDtoToModel($dto);
        $model->save();
        $freshModel = $model->fresh();
        if (!$freshModel) {
            throw new Exception();
        }
        return $this->convertModelToDto($freshModel);
    }

    public function updateOrFail(AccountingJournal $dto)
    {
        return $this->createOrFail($dto);
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

    private function sumOneSideForTypes(array $accountTypes, string $side): Collection
    {
        return $this->repo->select([
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
