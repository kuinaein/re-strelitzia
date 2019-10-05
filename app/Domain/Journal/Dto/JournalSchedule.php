<?php

declare(strict_types=1);

namespace App\Domain\Journal\Dto;

use App\Core\StreCase;
use Exception;
use Illuminate\Support\Carbon;

/**
 * 仕訳スケジュール
 *
 * @property-read int|null $id
 * @property-read int $debitAccountId 借方勘定科目ID
 * @property-read int $creditAccountId 借方勘定科目ID
 * @property-read bool $enabled 有効フラグ
 * @property-read int $postDate 仕訳日
 * @property-read string $remarks 摘要
 * @property-read int $amount 金額
 * @property-read Carbon $nextPostDate 次の仕訳日
 * @property-read Carbon|null $createdAt
 * @property-read Carbon|null $updatedAt
 */
final class JournalSchedule extends StreCase
{
    public function __construct(
        ?int $id,
        int $debitAccountId,
        int $creditAccountId,
        bool $enabled,
        int $postDate,
        string $remarks,
        int $amount,
        Carbon $nextPostDate,
        ?Carbon $createdAt,
        ?Carbon $updatedAt
    ) {
        parent::__construct();
    }

    public static function fromRequest(array $ar)
    {
        $nextPostDate = Carbon::createFromFormat(config('stre.datetime_format'), $ar['nextPostDate']);
        if (!$nextPostDate) {
            throw new Exception('次の仕訳日が不正:' . $ar['nextPostDate']);
        }
        return new JournalSchedule(
            $ar['id'] ?? null,
            $ar['debitAccountId'],
            $ar['creditAccountId'],
            $ar['enabled'],
            $ar['postDate'],
            $ar['remarks'],
            $ar['amount'],
            $nextPostDate,
            null,
            null
        );
    }
}
