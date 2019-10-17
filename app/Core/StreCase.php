<?php

declare(strict_types=1);

namespace App\Core;

use Exception;
use JsonSerializable;
use ReflectionClass;
use Str;

abstract class StreCase implements JsonSerializable
{
    /** @var array [実装クラス名 => プロパティ名[]] */
    private static $propertyNameListCache = [];

    /** @var array [プロパティ名 => 値] */
    private $bag;

    protected function __construct(array $properties)
    {
        $className = static::class;
        // $backtrace = debug_backtrace(0, 2)[1];
        // if ($className !== $backtrace['class'] || '__construct' !== $backtrace['function']) {
        //     throw new Exception();
        // }

        if (!isset(self::$propertyNameListCache[$className])) {
            $ctor = (new ReflectionClass(static::class))->getConstructor();
            if (!$ctor) {
                throw new Exception();
            }
            self::$propertyNameListCache[$className] = array_map(function ($p) {
                return $p->getName();
            }, $ctor->getParameters());
        }
        $bag = array_combine(self::$propertyNameListCache[$className], $properties);
        if (!$bag) {
            throw new Exception(static::class . 'のインスタンス生成に失敗しました!');
        }
        $this->bag = $bag;
    }

    public function __get(string $name)
    {
        return $this->bag[$name];
    }

    public function __call(string $name, array $args)
    {
        $className = static::class;
        if (Str::startsWith($name, 'with')) {
            $fieldName = lcfirst(substr($name, 4));
            $args = array_map(function ($k) use ($fieldName, $args) {
                return $k === $fieldName ? $args[0] : $this->bag[$k];
            }, self::$propertyNameListCache[$className]);
            return (new ReflectionClass($className))->newInstanceArgs($args);
        }
        trigger_error("メソッド${className}::${name}()は存在しません", E_USER_ERROR);
        ;
    }

    public function jsonSerialize()
    {
        return $this->bag;
    }
}
