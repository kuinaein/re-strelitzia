<?php declare(strict_types = 1);

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
    passthru(base_path('vendor/bin/phpcbf') . ' --standard=' . base_path('phpcs.xml'));
    $this->info('PHP_CodeSniffer ...');
    passthru(base_path('vendor/bin/phpcs') . ' --standard=' . base_path('phpcs.xml'));
    // $this->info('PHPStan ...');
    // passthru(base_path('vendor/bin/phpstan') . ' analyze');

    // $buf = [];
    // $this->info('PHPMD ...');
    // exec(base_path('vendor/bin/phpmd ') . implode(',',[
    //     app_path(),
    //     base_path('tests'),
    //     base_path('routes'),
    //     config_path(),
    //     database_path(),
    // ]) . ' html ' . base_path('phpmd.xml'), $buf);
    // file_put_contents(base_path('phpmd-result.html'), $buf);
    // exec('xdg-open ' . base_path('phpmd-result.html'));

    // $this->info('PHP Copy/Paste Detector ...');
    // passthru(base_path('vendor/bin/phpcpd') . ' --min-tokens 30 ' . implode(' ', [
    //     app_path(),
    //     base_path('tests'),
    //     base_path('routes'),
    //     config_path(),
    // ]));

    passthru('yarn lint-fix');
})->describe('lint, 静的解析とコード整形');
