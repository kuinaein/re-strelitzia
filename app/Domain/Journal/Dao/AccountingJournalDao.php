<?php

declare(strict_types=1);

namespace App\Domain\Journal\Dao;

use App\Domain\Journal\Dto\AccountingJournal;
use App\Domain\Journal\Model\AccountingJournalModel;
use Exception;

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
}
