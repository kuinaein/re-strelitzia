<?php

declare(strict_types=1);

namespace App\Domain\Account\Service;

use App\Domain\Account\Dao\AccountTitleDao;
use App\Domain\Account\Dto\AccountTitle;
use DB;
use Log;
use Validator;

class PlAccountSaveService
{
    private $dao;

    public function __construct(AccountTitleDao $accountDao)
    {
        $this->dao = $accountDao;
    }

    /**
     * @param AccountTitle $plAccount
     * @throws \Illuminate\Validation\ValidationException
     */
    public function create(AccountTitle $plAccount): AccountTitle
    {
        $this->validate($plAccount);
        $a = DB::transaction(function () use ($plAccount) {
            return $this->dao->createOrFail($plAccount);
        });
        Log::notice('収益・費用科目の追加', ['新科目' => $a]);
        return $a;
    }

    /**
     * @param AccountTitle $plAccount
     * @throws \Illuminate\Validation\ValidationException
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function update(AccountTitle $plAccount): AccountTitle
    {
        return DB::transaction(function () use ($plAccount) {
            $old = $this->dao->findOrFail($plAccount->id);
            $this->validate($plAccount, $old);
            $a = $this->dao->updateOrFail($plAccount);
            Log::notice('収益・費用科目の更新', ['科目' => $a]);
            return $a;
        });
    }

    /**
     * @param AccountTitle      $plAccount
     * @param null|AccountTitle $old
     */
    private function validate(AccountTitle $plAccount, AccountTitle $old = null): void
    {
        $ar = ['name' => $plAccount->name];
        Validator::make($ar, [
            'name' => 'required',
        ])->validate();
    }
}
