<?php declare(strict_types = 1);

namespace App\Domain\Account\Dao;

use App\Domain\Account\Dto\AccountTitle;
use App\Domain\Account\Dto\AccountTitleType;
use App\Domain\Account\Dto\SystemAccountTitleKey;
use App\Domain\Account\Model\AccountTitleModel;
use Carbon\Carbon;
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
        $dto = new AccountTitle();
        $dto->id = $model->id;
        $dto->name = $model->name;
        $dto->systemKey = '' === $model->system_key ? null : new SystemAccountTitleKey($model->system_key);
        $dto->type = new AccountTitleType($model->type);
        $dto->parentId = 0 === $model->parent_id ? null : $model->parent_id;
        $dto->createdAt = Carbon::createFromFormat(config('stre.datetime_format'), $model->created_at);
        $dto->updatedAt = Carbon::createFromFormat(config('stre.datetime_format'), $model->updated_at);
        return $dto;
    }

    private function convertDtoToModel(AccountTitle $dto): AccountTitleModel
    {
        $model = new AccountTitleModel();
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

    public function findOrFailBySystemKey(SystemAccountTitleKey $key): AccountTitle
    {
        $model = $this->repo->where('system_key', '=', $key)->firstOrFail();
        return $this->convertModelToDto($model);
    }

    public function createOrFail(AccountTitle $dto)
    {
        $model = $this->convertDtoToModel($dto);
        $model->save();
        return $this->convertModelToDto($model->fresh());
    }
}
