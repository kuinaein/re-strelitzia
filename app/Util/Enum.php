<?php declare (strict_types = 1);

namespace App\Util;

/**
 * PHPで列挙型(enum)を作る
 * https://qiita.com/Hiraku/items/71e385b56dcaa37629fe
 *
 * author: Hiraku Nakano
 *
 * @license https://creativecommons.org/publicdomain/zero/1.0/deed.ja CC-0 1.0
 */
abstract class Enum implements \JsonSerializable
{
    private $scalar;

    public function __construct($value)
    {
        $ref = new \ReflectionObject($this);
        $consts = $ref->getConstants();

        if (!in_array($value, $consts, true)) {
            throw new \InvalidArgumentException();
        }

        $this->scalar = $value;
    }

    final public static function __callStatic($label, $args)
    {
        $class = get_called_class();
        $const = constant("${class}::${label}");
        return new $class($const);
    }

    final public function __toString()
    {
        return (string)$this->scalar;
    }

    // 元の値を取り出すメソッド。
    // メソッド名は好みのものに変更どうぞ
    final public function valueOf()
    {
        return $this->scalar;
    }

    final public function jsonSerialize()
    {
        return $this->valueOf();
    }
}
