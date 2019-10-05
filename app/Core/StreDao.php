<?php

declare(strict_types=1);

namespace App\Core;

use Eloquent;
use Exception;

abstract class StreDao
{
    /**
     * @var string
     */
    protected static $dtoClass;

    abstract protected function convertModelToDto(Eloquent $model): StreCase;
    abstract protected function convertDtoToModel(StreCase $dto): Eloquent;

    /**
     * @var Eloquent
     */
    private $repo;

    protected function __construct(Eloquent $repo)
    {
        $this->repo = $repo;
    }

    protected function repo()
    {
        return $this->repo;
    }

    public function all()
    {
        return $this->repo()->all()->map(function ($model) {
            return $this->convertModelToDto($model);
        });
    }

    public function findOrFail(int $id)
    {
        $model = $this->repo()->findOrFail($id);
        return $this->convertModelToDto($model);
    }

    public function createOrFail($dto)
    {
        return $this->updateOrFail($dto);
    }

    public function updateOrFail($dto)
    {
        $model = $this->convertDtoToModel($dto);
        $model->save();
        $freshModel = $model->fresh();
        if (!$freshModel) {
            throw new Exception();
        }
        return $this->convertModelToDto($freshModel);
    }

    public function destroy($dtoId): void
    {
        $this->repo()->destroy($dtoId);
    }
}
