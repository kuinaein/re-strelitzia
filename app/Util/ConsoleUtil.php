<?php

declare(strict_types=1);

namespace App\Util;

use Exception;

class ConsoleUtil
{
    public static function getBrowserOpenCommand(string $url)
    {
        return 'start ' . $url;
    }

    public static function spawnInRichTerminal(string $cmd)
    {
        $p = popen('start /b cmd /c conemu64 -single -run ' . $cmd, 'r');
        if (!$p) {
            throw new Exception();
        }
        pclose($p);
    }
}
