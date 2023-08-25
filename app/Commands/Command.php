<?php

namespace App\Commands;

abstract class Command {
    public abstract function execute(): Result;

    public static function call(...$args): Result
    {
        $instance = self::create(...$args);
        return $instance->execute();
    }

    private static function create(...$args): self
    {
        return new static(...$args);
    }
}