<?php declare (strict_types = 1);

namespace App\Domain\Account\Dto;

/**
 * 勘定科目
 *
 * @property-read int $id
 * @property string $name
 * @property \App\Domain\Account\Dto\SystemAccountTitleKey|null $systemKey
 * @property AccountTitleType $type
 * @property int|null $parentId
 * @property-read \Carbon\Carbon $createdAt
 * @property-read \Carbon\Carbon $updatedAt
 */
class AccountTitle
{
    public static function fromRequest(array $ar)
    {
        $self = new AccountTitle();
        $self->name = $ar['name'];
        $self->type = new AccountTitleType($ar['type']);
        $self->parentId = is_null($ar['parentId']) ? null : intval($ar['parentId'], 10);
        return $self;
    }
}
