<?php

declare(strict_types=1);

namespace App\Domain\Journal\Dao;

use App\Core\StreCase;
use App\Core\StreDao;
use App\Domain\Journal\Dto\JournalSchedule;
use App\Domain\Journal\Model\JournalScheduleModel;
use Eloquent;

final class JournalScheduleDao extends StreDao
{
    protected static $dtoClass = JournalSchedule::class;

    public function __construct(JournalScheduleModel $repo)
    {
        parent::__construct($repo);
    }

    /**
     * @param JournalScheduleModel $model
     * @return JournalSchedule
     */
    protected function convertModelToDto(Eloquent $model): StreCase
    {
        return new JournalSchedule(
            $model->id,
            $model->debit_account_id,
            $model->credit_account_id,
            $model->enabled,
            $model->post_date,
            $model->remarks,
            $model->amount,
            $model->next_post_date,
            $model->created_at,
            $model->updated_at
        );
    }

    /**
     * @param JournalSchedule $dto
     * @return JournalScheduleModel
     */
    protected function convertDtoToModel(StreCase $dto): Eloquent
    {
        $model = is_null($dto->id) ? new JournalScheduleModel() : $this->repo()->findOrFail($dto->id);
        $model->debit_account_id = $dto->debitAccountId;
        $model->credit_account_id = $dto->creditAccountId;
        $model->enabled = $dto->enabled;
        $model->post_date = $dto->postDate;
        $model->remarks = $dto->remarks;
        $model->amount = $dto->amount;
        $model->next_post_date = $dto->nextPostDate;
        return $model;
    }
}
