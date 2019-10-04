<?php

declare(strict_types=1);

namespace App\Domain\Account\Dto;

use Carbon\Carbon;
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
 */
final class AccountTitle implements JsonSerializable
{
    /** @var \Illuminate\Support\Collection $bag */
    private $bag;

    public function __construct(
        ?int $id,
        string $name,
        ?SystemAccountTitleKey $systemKey,
        AccountTitleType $type,
        ?int $parentId,
        ?Carbon $createdAt,
        ?Carbon $updatedAt
    ) {
        // TODO キャッシュしないとまずそう
        $ctor = (new ReflectionClass(self::class))->getConstructor();
        if (!$ctor) {
            throw new Exception(self::class . 'のコンストラクタをリフレクションで読み取れません');
        }
        $pnames = collect($ctor->getParameters())->map(function ($p) {
            return $p->getName();
        });
        $this->bag = $pnames->combine(func_get_args());
    }

    public function __get($name)
    {
        return $this->bag[$name];
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

    public function jsonSerialize()
    {
        return $this->bag->jsonSerialize();
    }
}
