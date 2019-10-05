<?php

declare(strict_types=1);

namespace App\Domain\Account\Dto;

use App\Util\Enum;

final class AccountTitleType extends Enum
{
    public const ASSET = 'ASSET';
    public const LIABILITY = 'LIABILITY';
    public const NET_ASSET = 'NET_ASSET';
    public const REVENUE = 'REVENUE';
    public const EXPENSE = 'EXPENSE';
    public const OTHER = 'OTHER';

    /** 借方科目(左側)かどうか */
    public function isDebitSide()
    {
        switch ($this->valueOf()) {
            case self::ASSET:
            case self::EXPENSE:
                return true;
            default:
                return false;
        }
    }
}
