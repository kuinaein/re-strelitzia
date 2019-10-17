<?php

declare(strict_types=1);

namespace App\Domain\Account\Dto;

use App\Core\StreCase;
use Illuminate\Support\Carbon;
use Exception;
use JsonSerializable;
use ReflectionClass;

/**
 * 勘定科目
 *
 * @property-read int|null $id
 * @property-read string $name
 * @property-read SystemAccountTitleKey|null $systemKey
 * @property-read AccountTitleType $type
 * @property-read int|null $parentId
 * @property-read Carbon|null $createdAt
 * @property-read Carbon|null $updatedAt
 *
 * @method AccountTitle withName(string $name)
 */
final class AccountTitle extends StreCase
{
    public function __construct(
        ?int $id,
        string $name,
        ?SystemAccountTitleKey $systemKey,
        AccountTitleType $type,
        ?int $parentId,
        ?Carbon $createdAt,
        ?Carbon $updatedAt
    ) {
        parent::__construct(func_get_args());
    }

    public static function fromRequest(array $ar)
    {
        return new AccountTitle(
            $ar['id'] ?? null,
            $ar['name'],
            null,
            new AccountTitleType($ar['type']),
            is_null($ar['parentId']) ? null : intval($ar['parentId'], 10),
            null,
            null
        );
    }
}
