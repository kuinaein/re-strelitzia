<?php

declare(strict_types=1);

use App\Core\StreCase;
use App\Domain\Journal\Dto\AccountingJournal;
use App\Util\ConsoleUtil;

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

Artisan::command('stre:deploy', function (): void {
    $this->call('stre:clean');
    passthru('yarn install --prod');
    passthru('yarn prod');
    $this->call('migrate');
    $this->call('optimize');
    $this->call('config:cache');
    $this->call('route:cache');
})->describe('デプロイ');

Artisan::command('stre:clean', function (): void {
    $this->call('cache:clear');
    $this->call('config:clear');
    $this->call('route:clear');
    $this->call('view:clear');
})->describe('キャッシュなどのクリア');

Artisan::command('stre:update', function (): void {
    if (!file_exists(storage_path('oauth-private.key'))) {
        $this->call('passport:install');
    }
    $this->call('migrate');
    $this->call('stre:deploy');
})->describe('アップデート');

Artisan::command('stre:install', function (): void {
    $this->call('key:generate');
    $this->call('migrate:install');
    $this->call('stre:update');
})->describe('インストール');

Artisan::command('stre:createdb', function (): void {
    $user = config('database.connections.pgsql.username');
    $pass = config('database.connections.pgsql.password');
    $db = config('database.connections.pgsql.database');
    // sudo -u postgres initdb -E UTF8 --no-locale -D '/var/lib/postgres/data'
    $psqlCmd = 'psql -U postgres -c ';
    passthru($psqlCmd . "\"CREATE USER ${user} WITH ENCRYPTED PASSWORD '${pass}'\"");
    passthru($psqlCmd . "\"CREATE DATABASE ${db} WITH OWNER ${user} ENCODING 'UTF8'" .
        " LC_COLLATE 'C' LC_CTYPE 'C' TEMPLATE template0\"");
})->describe('データベース初期化');

Artisan::command('stre:dev', function (): void {
    passthru('yarn dev');
    // ConsoleUtil::spawnInRichTerminal('pg_ctl start');
    ConsoleUtil::spawnInRichTerminal('yarn watch');
    ConsoleUtil::spawnInRichTerminal('php artisan serve');
    sleep(1);
    passthru(ConsoleUtil::getBrowserOpenCommand(config('app.url')));
})->describe('開発環境として起動');

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
    exec(ConsoleUtil::getBrowserOpenCommand(resource_path('misc/phpmd-result.html')));

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
