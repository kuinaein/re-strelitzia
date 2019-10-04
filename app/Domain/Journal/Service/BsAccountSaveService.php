<?php

declare(strict_types=1);

namespace App\Domain\Journal\Service;

use App\Domain\Account\Dao\AccountTitleDao;
use App\Domain\Account\Dto\AccountTitle;
use App\Domain\Account\Dto\AccountTitleType;
use App\Domain\Account\Dto\SystemAccountTitleKey;
use App\Domain\Journal\Dao\AccountingJournalDao;
use App\Domain\Journal\Dto\AccountingJournal;
use Illuminate\Support\Carbon;
use DB;
use Log;

/**
 * Accountに置くと名前空間同士の相互参照になるのでこちらに置く.
 */
class BsAccountSaveService
{
    private $accountDao;
    private $journalDao;

    public function __construct(
        AccountTitleDao $accountDao,
        AccountingJournalDao $journalDao
    ) {
        $this->accountDao = $accountDao;
        $this->journalDao = $journalDao;
    }

    /**
     * @param AccountTitle  $account
     * @param int           $openingBalance
     * @throws \Illuminate\Validation\ValidationException
     */
    public function create(AccountTitle $account, int $openingBalance): AccountTitle
    {
        $this->validate($account, $openingBalance);
        $op = $this->accountDao->findOrFailBySystemKey(
            new SystemAccountTitleKey(SystemAccountTitleKey::OPENING_BALANCE)
        );
        $a = DB::transaction(function () use ($account, $openingBalance, $op) {
            $a = $this->accountDao->createOrFail($account);
            $isAsset = AccountTitleType::ASSET === $account->type->valueOf();
            $j = new AccountingJournal(
                null,
                $isAsset ? $a->id : $op->id,
                $isAsset ? $op->id : $a->id,
                // MySQLのDATE型の最小値は1000-01-01なので一応そちらに合わせておく
                Carbon::createFromDate(1000, 1, 1),
                '',
                $openingBalance,
                null,
                null
            );
            $this->journalDao->createOrFail($j);
            return $a;
        });
        Log::notice('資産・負債科目の追加', ['新科目' => $a]);
        return $a;
    }

    /**
     * @param AccountTitle $bsAccount
     * @param int       $openingBalance
     * @throws \Illuminate\Validation\ValidationException
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function update(AccountTitle $bsAccount, int $openingBalance): AccountTitle
    {
        $op = $this->accountDao->findOrFailBySystemKey(
            new SystemAccountTitleKey(SystemAccountTitleKey::OPENING_BALANCE)
        );
        $opJournal = $bsAccount->type->valueOf() === AccountTitleType::ASSET
            ? $this->journalDao->findOrFailByAccount($bsAccount, $op)
            : $this->journalDao->findOrFailByAccount($op, $bsAccount);

        return DB::transaction(function () use ($bsAccount, $openingBalance, $opJournal) {
            $old = $this->accountDao->findOrFail($bsAccount->id);
            $this->validate($bsAccount, $openingBalance, $old);
            $opJournal->amount = $openingBalance;
            $a = $this->accountDao->updateOrFail($bsAccount);
            $this->journalDao->updateOrFail($opJournal);
            Log::notice('資産・負債科目の更新', ['科目' => $a]);
            return $a;
        });
    }

    /**
     * @param AccountTitle      $account
     * @param int               $openingBalance
     * @param null|AccountTitle $old
     */
    private function validate(AccountTitle $account, int $openingBalance, AccountTitle $old = null): void
    {
        $ar = [
            'name' => $account->name,
            'openingBalance' => $openingBalance,
        ];
        \Validator::make($ar, [
            'name' => 'required',
            'openingBalance' => 'numeric|required|min:0',
        ])
            // ->sometimes('updatedAt', 'date_equals:' . optional($old)->bsAccoount, function ($o) {
            //   return $o->id;
            // })
            ->validate();
    }
}
