<?php

declare(strict_types=1);

namespace App\Core;

use Exception;
use JsonSerializable;
use ReflectionClass;

abstract class StreCase implements JsonSerializable
{
    /** @var array */
    private static $propertyNameListCache = [];

    /** @var \Illuminate\Support\Collection $bag */
    private $bag;

    protected function __construct()
    {
        $className = static::class;
        $backtrace = debug_backtrace(0, 2)[1];
        if ($className !== $backtrace['class'] || '__construct' !== $backtrace['function']) {
            throw new Exception();
        }

        if (!isset(self::$propertyNameListCache[$className])) {
            $ctor = (new ReflectionClass(static::class))->getConstructor();
            if (!$ctor) {
                throw new Exception();
            }
            self::$propertyNameListCache[$className] = collect($ctor->getParameters())->map(function ($p) {
                return $p->getName();
            });
        }
        $this->bag = self::$propertyNameListCache[$className]->combine($backtrace['args']);
    }

    public function __get($name)
    {
        return $this->bag[$name];
    }

    public function jsonSerialize()
    {
        return $this->bag->jsonSerialize();
    }
}
