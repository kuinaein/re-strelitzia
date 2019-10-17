<?php

declare(strict_types=1);

namespace Tests\Unit;

use App\Domain\Account\Dao\AccountTitleDao;
use App\Domain\Account\Dto\AccountTitle;
use App\Domain\Account\Dto\AccountTitleType;
use App\Domain\Account\Service\PlAccountSaveService;
use Illuminate\Foundation\Testing\WithFaker;
use Log;
use Mockery\MockInterface;
use Tests\TestCase;

final class PlAccountSaveServiceTest extends TestCase
{
    use WithFaker;

    public static function identity($v)
    {
        return $v;
    }

    /** @var Closure */
    private const IDENTITY = [self::class, 'identity'];

    public function testCreate()
    {
        $times = 5;

        $this->mock(AccountTitleDao::class, function (MockInterface $mock) use ($times) {
            $mock->shouldReceive('createOrFail')->times($times)->andReturnUsing(self::IDENTITY);
        });
        /** @var PlAccountSaveService */
        $service = app()->make(PlAccountSaveService::class);

        $a = new AccountTitle(null, 'fake', null, AccountTitleType::ASSET(), null, null, null);
        for ($i = 0; $times > $i; ++$i) {
            $card = $this->faker->creditCardType;
            $a = $a->withName($card);
            Log::debug('DTO生成ログ', ['DTO' => $a]);
            $a = $service->create($a);
            $this->assertNotNull($a, '戻り値がnull!');
            $this->assertEquals($card, $a->name, 'クレジットカード名が違う!');
        }
    }
}
