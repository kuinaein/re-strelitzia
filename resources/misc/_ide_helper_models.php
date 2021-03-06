<?php

// @formatter:off
/**
 * A helper file for your Eloquent Models
 * Copy the phpDocs from this file to the correct Model,
 * And remove them from this file, to prevent double declarations.
 *
 * @author Barry vd. Heuvel <barryvdh@gmail.com>
 */


namespace App\Domain\Account\Model{
/**
 * App\Domain\Account\Model\AccountTitleModel
 *
 * @property int $id
 * @property string $type
 * @property string $name
 * @property int $parent_id
 * @property string $system_key
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Domain\Account\Model\AccountTitleModel newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Domain\Account\Model\AccountTitleModel newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Domain\Account\Model\AccountTitleModel query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Domain\Account\Model\AccountTitleModel whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Domain\Account\Model\AccountTitleModel whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Domain\Account\Model\AccountTitleModel whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Domain\Account\Model\AccountTitleModel whereParentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Domain\Account\Model\AccountTitleModel whereSystemKey($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Domain\Account\Model\AccountTitleModel whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Domain\Account\Model\AccountTitleModel whereUpdatedAt($value)
 */
	final class AccountTitleModel extends \Eloquent {}
}

namespace App\Domain\Journal\Model{
/**
 * App\Domain\Journal\Model\AccountingJournalModel
 *
 * @property int $id
 * @property \Illuminate\Support\Carbon $journal_date
 * @property int $debit_account_id
 * @property int $credit_account_id
 * @property string $remarks
 * @property int $amount
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Domain\Journal\Model\AccountingJournalModel newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Domain\Journal\Model\AccountingJournalModel newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Domain\Journal\Model\AccountingJournalModel query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Domain\Journal\Model\AccountingJournalModel whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Domain\Journal\Model\AccountingJournalModel whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Domain\Journal\Model\AccountingJournalModel whereCreditAccountId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Domain\Journal\Model\AccountingJournalModel whereDebitAccountId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Domain\Journal\Model\AccountingJournalModel whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Domain\Journal\Model\AccountingJournalModel whereJournalDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Domain\Journal\Model\AccountingJournalModel whereRemarks($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Domain\Journal\Model\AccountingJournalModel whereUpdatedAt($value)
 */
	final class AccountingJournalModel extends \Eloquent {}
}

namespace App\Domain\Journal\Model{
/**
 * App\Domain\Journal\Model\JournalScheduleModel
 *
 * @property int $id
 * @property bool $enabled
 * @property int $post_date
 * @property string $remarks
 * @property int $debit_account_id
 * @property int $credit_account_id
 * @property int $amount
 * @property \Illuminate\Support\Carbon $next_post_date
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Domain\Journal\Model\JournalScheduleModel newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Domain\Journal\Model\JournalScheduleModel newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Domain\Journal\Model\JournalScheduleModel query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Domain\Journal\Model\JournalScheduleModel whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Domain\Journal\Model\JournalScheduleModel whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Domain\Journal\Model\JournalScheduleModel whereCreditAccountId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Domain\Journal\Model\JournalScheduleModel whereDebitAccountId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Domain\Journal\Model\JournalScheduleModel whereEnabled($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Domain\Journal\Model\JournalScheduleModel whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Domain\Journal\Model\JournalScheduleModel whereNextPostDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Domain\Journal\Model\JournalScheduleModel wherePostDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Domain\Journal\Model\JournalScheduleModel whereRemarks($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Domain\Journal\Model\JournalScheduleModel whereUpdatedAt($value)
 */
	final class JournalScheduleModel extends \Eloquent {}
}

namespace App{
/**
 * App\User
 *
 * @property string $name
 * @property string $email
 * @property string $password
 * @property int $id
 * @property \Illuminate\Support\Carbon|null $email_verified_at
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @property-read int|null $notifications_count
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereUpdatedAt($value)
 */
	class User extends \Eloquent {}
}

