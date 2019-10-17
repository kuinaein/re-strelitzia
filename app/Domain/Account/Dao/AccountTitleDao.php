<?php

declare(strict_types=1);

namespace App\Domain\Account\Dao;

use App\Core\StreCase;
use App\Core\StreDao;
use App\Domain\Account\Dto\AccountTitle;
use App\Domain\Account\Dto\AccountTitleType;
use App\Domain\Account\Dto\SystemAccountTitleKey;
use App\Domain\Account\Model\AccountTitleModel;
use Eloquent;

class AccountTitleDao extends StreDao
{
    protected static $dtoClass = AccountTitle::class;

    public function __construct(AccountTitleModel $repo)
    {
        parent::__construct($repo);
    }

    /**
     * @param AccountTitleModel $model
     * @return AccountTitle
     */
    protected function convertModelToDto(Eloquent $model): StreCase
    {
        return new AccountTitle(
            $model->id,
            $model->name,
            '' === $model->system_key ? null : new SystemAccountTitleKey($model->system_key),
            new AccountTitleType($model->type),
            0 === $model->parent_id ? null : $model->parent_id,
            $model->created_at,
            $model->updated_at
        );
    }

    /**
     * @param AccountTitle $dto
     * @return AccountTitleModel
     */
    protected function convertDtoToModel(StreCase $dto): Eloquent
    {
        $model = is_null($dto->id) ? new AccountTitleModel() : $this->repo()->findOrFail($dto->id);
        $model->name = $dto->name;
        $model->type = $dto->type->valueOf();
        $model->parent_id = $dto->parentId ?? 0;
        return $model;
    }

    public function findOrFailBySystemKey(SystemAccountTitleKey $key): AccountTitle
    {
        $model = $this->repo()->where('system_key', '=', $key)->firstOrFail();
        return $this->convertModelToDto($model);
    }
}
