<?php

declare(strict_types=1);

namespace App\Domain\Account\Dao;

use App\Domain\Account\Dto\AccountTitle;
use App\Domain\Account\Dto\AccountTitleType;
use App\Domain\Account\Dto\SystemAccountTitleKey;
use App\Domain\Account\Model\AccountTitleModel;
use Exception;
use Illuminate\Support\Collection;

class AccountTitleDao
{
    private $repo;

    public function __construct(AccountTitleModel $repo)
    {
        $this->repo = $repo;
    }

    private function convertModelToDto(AccountTitleModel $model): AccountTitle
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

    private function convertDtoToModel(AccountTitle $dto): AccountTitleModel
    {
        $model = is_null($dto->id) ? new AccountTitleModel() : $this->repo->findOrFail($dto->id);
        $model->name = $dto->name;
        $model->type = $dto->type->valueOf();
        $model->parent_id = $dto->parentId ?? 0;
        return $model;
    }

    public function all(): Collection
    {
        return $this->repo->all()->map(function ($model) {
            return $this->convertModelToDto($model);
        });
    }

    public function findOrFail(int $id): AccountTitle
    {
        $model = $this->repo->findOrFail($id);
        return $this->convertModelToDto($model);
    }

    public function findOrFailBySystemKey(SystemAccountTitleKey $key): AccountTitle
    {
        $model = $this->repo->where('system_key', '=', $key)->firstOrFail();
        return $this->convertModelToDto($model);
    }

    public function createOrFail(AccountTitle $dto)
    {
        return $this->updateOrFail($dto);
    }

    public function updateOrFail(AccountTitle $dto)
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
