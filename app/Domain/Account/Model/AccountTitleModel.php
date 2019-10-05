<?php

declare(strict_types=1);

namespace App\Domain\Account\Model;

final class AccountTitleModel extends \Eloquent
{
    protected $table = 'account_title';
    protected $fillable = ['type', 'name', 'parent_id'];
}
