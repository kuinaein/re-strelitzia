<?php

declare(strict_types=1);

namespace App\Domain\Journal\Dto;

use App\Core\StreCase;
use Exception;
use Illuminate\Support\Carbon;

/**
 * 仕訳
 *
 * @property-read int|null $id
 * @property-read int $debitAccountId 借方勘定科目ID
 * @property-read int $creditAccountId 貸方勘定科目ID
 * @property-read Carbon $journalDate
 * @property-read string $remarks 摘要
 * @property-read int $amount 金額
 * @property-read Carbon|null $createdAt
 * @property-read Carbon|null $updatedAt
 */
class AccountingJournal extends StreCase
{
    public function __construct(
        ?int $id,
        int $debitAccountId,
        int $creditAccountId,
        Carbon $journalDate,
        string $remarks,
        int $amount,
        ?Carbon $createdAt,
        ?Carbon $updatedAt
    ) {
        parent::__construct();
    }

    public static function fromRequest(array $ar)
    {
        $journalDate = Carbon::createFromFormat(config('stre.datetime_format'), $ar['journalDate']);
        if (!$journalDate) {
            throw new Exception('仕訳の日付が不正:' . $ar['journalDate']);
        }
        return new AccountingJournal(
            $ar['id'] ?? null,
            $ar['debitAccountId'],
            $ar['creditAccountId'],
            $journalDate,
            $ar['remarks'],
            $ar['amount'],
            null,
            null
        );
    }
}
