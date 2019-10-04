<?php

declare(strict_types=1);

use App\Domain\Account\Dto\AccountTitleType;
use Illuminate\Foundation\Inspiring;

/*
|--------------------------------------------------------------------------
| Console Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of your Closure based console
| commands. Each Closure is bound to a command instance allowing a
| simple approach to interacting with each command's IO methods.
|
*/

Artisan::command('stre:cs', function () {
    $this->info('PHP Code Beautifier and Fixer ...');
    passthru(base_path('vendor/bin/phpcbf') . ' --standard=' . resource_path('misc/phpcs.xml'));
    $this->info('PHP_CodeSniffer ...');
    passthru(base_path('vendor/bin/phpcs') . ' --standard=' . resource_path('misc/phpcs.xml'));
    $this->info('PHPStan ...');
    passthru(base_path('vendor/bin/phpstan') . ' analyze -c ' . resource_path('misc/phpstan.neon'));

    $buf = [];
    $this->info('PHPMD ...');
    exec(base_path('vendor/bin/phpmd ') . implode(',', [
        app_path(),
        base_path('bootstrap'),
        config_path(),
        database_path(),
        base_path('routes'),
        base_path('tests'),
    ]) . ' html ' . resource_path('misc/phpmd.xml'), $buf);
    file_put_contents(resource_path('misc/phpmd-result.html'), $buf);
    exec('start ' . resource_path('misc/phpmd-result.html'));

    $this->info('PHP Copy/Paste Detector ...');
    passthru(base_path('vendor/bin/phpcpd') . ' --min-tokens 30 ' . implode(' ', [
        app_path(),
        base_path('bootstrap'),
        config_path(),
        base_path('tests'),
        base_path('routes'),
    ]));

    passthru('yarn lint-fix');
})->describe('lint, 静的解析とコード整形');
