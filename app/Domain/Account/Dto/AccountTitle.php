<?php
declare (strict_types = 1);

namespace App\Domain\Account\Dto;

/**
 * 勘定科目
 *
 * @property-read int $id
 * @property string $name
 * @property \App\Domain\Account\Dto\SystemAccountTitleKey|null $systemKey
 * @property \App\Domain\Account\Dto\AccountTitleType $type
 * @property int|null $parentId
 * @property-read \Carbon\Carbon $createdAt
 * @property-read \Carbon\Carbon $updatedAt
 */
class AccountTitle
{
}
